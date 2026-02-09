<?php

namespace App\Models;

use Core\Model;

class Role extends Model {

    public function all(): array {

        $stmt = $this->db->query("
            SELECT id, nome
            FROM roles
            ORDER BY nome
        ");

        return $stmt->fetchAll();
    }

    public function exists(int $id): bool {

        $stmt = $this->db->prepare("
            SELECT id
            FROM roles
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        return (bool) $stmt->fetch();
    }
}
