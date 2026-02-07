<?php

namespace App\Models;

use Core\Model;

class Comentario extends Model {

    public function create(array $data) {

        $stmt = $this->db->prepare("
            INSERT INTO comentarios
            (documento_id,user_id,texto)
            VALUES
            (:doc,:user,:texto)
        ");

        $stmt->execute([
            'doc' => $data['documento_id'],
            'user' => $data['user_id'],
            'texto' => $data['texto'],
        ]);
    }

    public function byDocumento($id) {

        $stmt = $this->db->prepare("
            SELECT c.*, u.nome
            FROM comentarios c
            JOIN users u ON u.id = c.user_id
            WHERE documento_id = ?
            ORDER BY criado_em DESC
        ");

        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }
}
