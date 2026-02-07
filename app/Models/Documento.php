<?php

namespace App\Models;

use Core\Model;
use PDO;

class Documento extends Model {

    /**
     * Lista todos os documentos com localização
     */
    public function all() {

        $sql = "
            SELECT d.*, 
                   p.nome AS parque,
                   z.nome AS zona,
                   e.nome AS edificio,
                   s.nome AS sala
            FROM documentos d
            JOIN parques p ON p.id = d.parque_id
            LEFT JOIN zonas z ON z.id = d.zona_id
            LEFT JOIN edificios e ON e.id = d.edificio_id
            LEFT JOIN salas s ON s.id = d.sala_id
            ORDER BY d.criado_em DESC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria documento base (sem versão)
     */
    public function create(array $data) {

        $stmt = $this->db->prepare("
            INSERT INTO documentos
            (sala_id,titulo,tipo,estado,criado_por)
            VALUES
            (:sala,:titulo,:tipo,:estado,:user)
        ");

        $stmt->execute([
            'sala'   => $data['sala_id'],
            'titulo' => $data['titulo'],
            'tipo'   => $data['tipo'],
            'estado' => $data['estado'],
            'user'   => $data['criado_por'],
        ]);

        return $this->db->lastInsertId();
    }

    public function allWithActiveVersion() {

        $sql = "
            SELECT d.*, 
                dv.versao,
                dv.ficheiro_path,
                dv.formato,
                p.nome AS parque,
                z.nome AS zona,
                e.nome AS edificio,
                s.nome AS sala
            FROM documentos d
            JOIN documento_versoes dv 
                ON dv.documento_id = d.id AND dv.is_ativo = 1
            JOIN parques p ON p.id = d.parque_id
            LEFT JOIN zonas z ON z.id = d.zona_id
            LEFT JOIN edificios e ON e.id = d.edificio_id
            LEFT JOIN salas s ON s.id = d.sala_id
            ORDER BY d.criado_em DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function findWithActiveVersion($id) {

        $stmt = $this->db->prepare("
            SELECT d.*, dv.*
            FROM documentos d
            JOIN documento_versoes dv
            ON dv.documento_id = d.id
            AND dv.is_ativo = 1
            WHERE d.id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function versions($id) {

        $stmt = $this->db->prepare("
            SELECT v.*, u.nome
            FROM documento_versoes v
            JOIN users u ON u.id = v.criado_por
            WHERE v.documento_id = ?
            ORDER BY v.criado_em DESC
        ");

        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    public function search(array $filters) {

        $sql = "
            SELECT d.*,
                s.nome AS sala,
                e.nome AS edificio,
                z.nome AS zona,
                v.versao AS versao
            FROM documentos d
            JOIN salas s ON s.id=d.sala_id
            JOIN edificios e ON e.id=s.edificio_id
            JOIN zonas z ON z.id=e.zona_id
            LEFT JOIN documento_versoes v 
                ON v.documento_id = d.id AND v.is_ativo = 1
            WHERE 1=1
            ";

        $params = [];

        if (!empty($filters['zona_id'])) {
            $sql .= " AND z.id = :zona";
            $params['zona'] = $filters['zona_id'];
        }

        if (!empty($filters['edificio_id'])) {
            $sql .= " AND e.id = :edificio";
            $params['edificio'] = $filters['edificio_id'];
        }

        if (!empty($filters['sala_id'])) {
            $sql .= " AND s.id = :sala";
            $params['sala'] = $filters['sala_id'];
        }

        if (!empty($filters['q'])) {
            $sql .= " AND d.titulo LIKE :q";
            $params['q'] = '%'.$filters['q'].'%';
        }

        if (!empty($filters['tipo'])) {
            $sql .= " AND d.tipo = :tipo";
            $params['tipo'] = $filters['tipo'];
        }

        if (!empty($filters['tag'])) {
            $sql .= "
                AND EXISTS (
                    SELECT 1
                    FROM documento_tags dt
                    WHERE dt.documento_id = d.id
                    AND dt.tag_id = :tag
                )
            ";
            $params['tag'] = $filters['tag'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function filterByLocal($zona,$edificio,$sala) {

        $sql = "
        SELECT d.*
        FROM documentos d
        LEFT JOIN salas s ON s.id = d.sala_id
        LEFT JOIN edificios e ON e.id = s.edificio_id
        LEFT JOIN zonas z ON z.id = e.zona_id
        WHERE 1=1
        ";

        $params = [];

        if ($zona) {
            $sql .= " AND z.id = ?";
            $params[] = $zona;
        }

        if ($edificio) {
            $sql .= " AND e.id = ?";
            $params[] = $edificio;
        }

        if ($sala) {
            $sql .= " AND s.id = ?";
            $params[] = $sala;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }


    public function find(int $id) {

        $stmt = $this->db->prepare("
            SELECT d.*,
                s.id AS sala_id,
                e.id AS edificio_id,
                z.id AS zona_id
            FROM documentos d
            LEFT JOIN salas s ON s.id=d.sala_id
            LEFT JOIN edificios e ON e.id=s.edificio_id
            LEFT JOIN zonas z ON z.id=e.zona_id
            WHERE d.id=?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function update(array $data) {

        $stmt = $this->db->prepare("
            UPDATE documentos
            SET sala_id=:sala,
                titulo=:titulo,
                tipo=:tipo
            WHERE id=:id
        ");

        $stmt->execute([
            'sala'=>$data['sala_id'],
            'titulo'=>$data['titulo'],
            'tipo'=>$data['tipo'],
            'id'=>$data['id']
        ]);
    }

    public function delete(int $id) {

        $stmt = $this->db->prepare(
            "DELETE FROM documentos WHERE id=?"
        );

        $stmt->execute([$id]);
    }


}
