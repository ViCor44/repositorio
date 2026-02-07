<?php

namespace App\Models;

use Core\Model;
use PDO;

class Tag extends Model {

    public function all() {

        return $this->db
            ->query("SELECT * FROM tags ORDER BY nome")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOrCreate(string $nome) {

        $stmt = $this->db->prepare(
            "SELECT id FROM tags WHERE nome=?"
        );
        $stmt->execute([$nome]);

        $tag = $stmt->fetch();

        if ($tag) return $tag['id'];

        $stmt = $this->db->prepare(
            "INSERT INTO tags (nome) VALUES (?)"
        );
        $stmt->execute([$nome]);

        return $this->db->lastInsertId();
    }

    public function attachToDocumento(int $docId, int $tagId) {

        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO documento_tags (documento_id,tag_id)
             VALUES (?,?)"
        );

        $stmt->execute([$docId,$tagId]);
    }

    public function byDocumento(int $id) {

        $stmt = $this->db->prepare(
            "SELECT t.*
            FROM tags t
            JOIN documento_tags dt ON dt.tag_id = t.id
            WHERE dt.documento_id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }
}
