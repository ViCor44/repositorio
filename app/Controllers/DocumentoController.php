<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Parque;
use App\Models\Documento;
use App\Models\DocumentoVersao;
use Core\Uploader;


class DocumentoController extends Controller {

    public function index() {

        \Core\Auth::requireRole([
            'Administrador',
            'Engenheiro',
            'Tecnico',
            'Consulta',
            'Auditor'
        ]);

        $model = new Documento();

        // filtros vindos do GET
        $filters = [
            'q'           => $_GET['q'] ?? null,
            'tag'         => $_GET['tag'] ?? null,
            'tipo'        => $_GET['tipo'] ?? null,
            'zona_id'     => $_GET['zona_id'] ?? null,
            'edificio_id' => $_GET['edificio_id'] ?? null,
            'sala_id'     => $_GET['sala_id'] ?? null
        ];

        $documentos = $model->search($filters);

        // tags
        $tagModel = new \App\Models\Tag();
        $tags = $tagModel->all();

        // zonas para filtros
        $zonaModel = new \App\Models\Zona();
        $zonas = $zonaModel->all();

        $this->view('documentos/index', [
            'title'      => 'Dashboard Documentos',
            'documentos' => $documentos,
            'tags'       => $tags,
            'zonas'      => $zonas,
            'filters'    => $filters
        ]);
    }

    public function create() {

        \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        $zonaModel = new \App\Models\Zona();

        $this->view('documentos/create', [
            'zonas' => $zonaModel->all(),
            'title' => 'Novo Documento'
        ]);
    }

    public function store() {

        \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        $db = \Core\Database::getInstance();
        $db->beginTransaction();

        try {

            $documentoModel = new Documento();

            $documentoId = $documentoModel->create([
                'zona_id' => $_POST['zona_id'] ?: null,
                'edificio_id' => $_POST['edificio_id'] ?: null,
                'sala_id' => $_POST['sala_id'] ?: null,
                'titulo' => $_POST['titulo'],
                'tipo' => $_POST['tipo'],
                'estado' => 'ativo',
                'criado_por' => $_SESSION['user']['id']
            ]);

            $uploader = new Uploader('/storage/documents');

            $fileInfo = $uploader->upload($_FILES['ficheiro']);

            $versaoModel = new DocumentoVersao();

            $versaoModel->create([
                'documento_id' => $documentoId,
                'versao' => '1.0',
                'ficheiro_path' => $fileInfo['path'],
                'formato' => $fileInfo['ext'],
                'tamanho' => $fileInfo['size'],
                'criado_por' => $_SESSION['user']['id'],
                'is_ativo' => 1
            ]);

            \Core\Logger::log('create_document','documento',$documentoId);

            $db->commit();

            $this->redirect('/documentos');

        } catch (\Exception $e) {

            $db->rollBack();
            die($e->getMessage());
        }
    }

    public function download() {

        \Core\Auth::requireRole([
            'Administrador','Engenheiro','Tecnico','Consulta','Auditor'
        ]);

        $id = $_GET['id'] ?? null;

        if (!$id) die("Documento inválido");

        $model = new Documento();
        $doc = $model->findWithActiveVersion($id);

        if (!$doc) die("Documento não encontrado");

        $path = dirname(__DIR__,2) . '/storage/documents/' . $doc['ficheiro_path'];

        if (!file_exists($path)) die("Ficheiro não existe");

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$doc['titulo'].'.'.$doc['formato'].'"');
        header('Content-Length: ' . filesize($path));

        \Core\Logger::log('download','documento',$id);

        readfile($path);
        exit;
    }

    public function show() {

        \Core\Auth::requireRole([
            'Administrador','Engenheiro','Tecnico','Consulta','Auditor'
        ]);

        $id = $_GET['id'] ?? null;
        if (!$id) die("Documento inválido");

        $model = new \App\Models\Documento();
        $doc = $model->findWithActiveVersion($id);

        $doc = $model->findWithActiveVersion($id);

        if (!$doc) die("Documento não encontrado");

        /**
         * 🔔 MARCAR DOCUMENTO COMO VISTO
         */
        $db = \Core\Database::getInstance();

        $stmt = $db->prepare("
            INSERT INTO documento_views (user_id, documento_id, last_view_at)
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE last_view_at = NOW()
        ");

        $stmt->execute([
            $_SESSION['user']['id'],
            $id
        ]);

        $versoes = $model->versions($id);
        $comentarios = (new \App\Models\Comentario())->byDocumento($id);
        $tags = (new \App\Models\Tag())->byDocumento($id);

        $this->view('documentos/show', [
            'title' => 'Detalhe Documento',
            'doc' => $doc,
            'versoes' => $versoes,
            'comentarios' => $comentarios,
            'tags' => $tags
        ]);
    }

    public function novaVersao() {

        \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        $id = $_GET['id'] ?? null;
        if (!$id) die("Documento inválido");

        $this->view('documentos/nova_versao', [
            'documento_id' => $id,
            'title' => 'Nova Versão'
        ]);
    }

    public function storeNovaVersao() {

        \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        $id = $_POST['documento_id'];

        $db = \Core\Database::getInstance();
        $db->beginTransaction();

        try {

            // Desativar versões antigas
            $stmt = $db->prepare(
                "UPDATE documento_versoes SET is_ativo=0 WHERE documento_id=?"
            );
            $stmt->execute([$id]);

            // Upload
            $uploader = new \Core\Uploader('/storage/documents');
            $file = $uploader->upload($_FILES['ficheiro']);

            // Nova versão
            $versaoModel = new \App\Models\DocumentoVersao();

            $versaoModel->create([
                'documento_id' => $id,
                'versao' => $_POST['versao'],
                'ficheiro_path' => $file['path'],
                'formato' => $file['ext'],
                'tamanho' => $file['size'],
                'criado_por' => $_SESSION['user']['id'],
                'is_ativo' => 1
            ]);

            \Core\Logger::log('nova_versao','documento',$id);

            $db->commit();

            $this->redirect('/documentos/ver?id='.$id);

        } catch (\Exception $e) {
            $db->rollBack();
            die($e->getMessage());
        }
    }

    public function comentar() {

        \Core\Auth::requireRole([
            'Administrador','Engenheiro','Tecnico'
        ]);

        $docId = $_POST['documento_id'];
        $texto = trim($_POST['texto']);

        if (!$texto) {
            $this->redirect('/documentos/ver?id='.$docId);
        }

        $model = new \App\Models\Comentario();
        $model->create([
            'documento_id' => $docId,
            'user_id' => $_SESSION['user']['id'],
            'texto' => $texto
        ]);

        \Core\Logger::log('comentario','documento',$docId);

        $this->redirect('/documentos/ver?id='.$docId);
    }

    public function addTag() {

        \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        $docId = $_POST['documento_id'];
        $nome = trim($_POST['tag']);

        if (!$nome) {
            $this->redirect('/documentos/ver?id='.$docId);
        }

        $tagModel = new \App\Models\Tag();

        $tagId = $tagModel->findOrCreate($nome);

        $tagModel->attachToDocumento($docId,$tagId);

        \Core\Logger::log('add_tag','documento',$docId);

        $this->redirect('/documentos/ver?id='.$docId);
    }

    public function preview() {

        \Core\Auth::requireRole([
            'Administrador','Engenheiro','Tecnico','Consulta','Auditor'
        ]);

        $id = $_GET['id'] ?? null;
        if (!$id) die("Documento inválido");

        $model = new \App\Models\Documento();
        $doc = $model->findWithActiveVersion($id);

        if (!$doc) die("Documento não encontrado");

        $path = dirname(__DIR__,2) . '/storage/documents/' . $doc['ficheiro_path'];

        if (!file_exists($path)) die("Ficheiro não existe");

        $mime = mime_content_type($path);

        header("Content-Type: $mime");
        header("Content-Disposition: inline");

        readfile($path);
        exit;
    }

public function edit() {

    \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die('SEM ID');
    }

    $docModel = new \App\Models\Documento();

    $doc = $docModel->find($id);

    if (!$doc) {
        die('DOC NAO ENCONTRADO');
    }

    $zonaModel = new \App\Models\Zona();

    $this->view('documentos/edit',[
        'title'=>'Editar Documento',
        'documento'=>$doc,
        'zonas'=>$zonaModel->all()
    ]);
}


    public function update() {

        \Core\Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        $docModel = new \App\Models\Documento();

        $docModel->update($_POST);

        $this->redirect('/documentos');
    }

    public function delete() {

        \Core\Auth::requireRole(['Administrador']);

        $docModel = new \App\Models\Documento();

        $docModel->delete($_POST['id']);

        $this->redirect('/documentos');
    }

}
