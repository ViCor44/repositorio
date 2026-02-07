<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;

class LocalizacaoController extends Controller {

    public function index() {

        Auth::requireRole(['Administrador']);

        $zonaModel = new \App\Models\Zona();
        $edModel   = new \App\Models\Edificio();
        $salaModel = new \App\Models\Sala();

        $zonas = $zonaModel->all();

        foreach ($zonas as &$z) {

            $z['edificios'] = $edModel->byZona($z['id']);

            foreach ($z['edificios'] as &$e) {
                $e['salas'] = $salaModel->byEdificio($e['id']);
            }
        }

        $this->view('admin/localizacao',[
            'title'=>'Gestão de Localizações',
            'zonas'=>$zonas
        ]);
    }

    public function createZona() {

        \Core\Auth::requireRole(['Administrador']);

        (new \App\Models\Zona())
            ->create($_POST['nome']);

        $this->redirect('/admin/localizacao');
    }

    public function createEdificio() {

        \Core\Auth::requireRole(['Administrador']);

        (new \App\Models\Edificio())
            ->create($_POST['zona_id'],$_POST['nome']);

        $this->redirect('/admin/localizacao');
    }

    public function createSala() {

        \Core\Auth::requireRole(['Administrador']);

        (new \App\Models\Sala())
            ->create($_POST['edificio_id'],$_POST['nome']);

        $this->redirect('/admin/localizacao');
    }

    public function updateZona() {

        Auth::requireRole(['Administrador']);

        (new \App\Models\Zona())
            ->update($_POST['id'],$_POST['nome']);

        $this->redirect('/admin/localizacao');
    }

    public function deleteZona() {

        Auth::requireRole(['Administrador']);

        if (!(new \App\Models\Zona())->delete($_POST['id'])) {
            $_SESSION['flash_error'] = 'Zona tem edifícios associados.';
        }

        $this->redirect('/admin/localizacao');
    }

    public function updateEdificio() {

        Auth::requireRole(['Administrador']);

        (new \App\Models\Edificio())
            ->update($_POST['id'],$_POST['nome']);

        $this->redirect('/admin/localizacao');
    }

    public function deleteEdificio() {

        Auth::requireRole(['Administrador']);

        if (!(new \App\Models\Edificio())->delete($_POST['id'])) {
            $_SESSION['flash_error'] = 'Edifício tem salas associadas.';
        }

        $this->redirect('/admin/localizacao');
    }
    
    public function updateSala() {

        Auth::requireRole(['Administrador']);

        (new \App\Models\Sala())
            ->update($_POST['id'],$_POST['nome']);

        $this->redirect('/admin/localizacao');
    }

    public function deleteSala() {

        Auth::requireRole(['Administrador']);

        (new \App\Models\Sala())
            ->delete($_POST['id']);

        $this->redirect('/admin/localizacao');
    }

}
