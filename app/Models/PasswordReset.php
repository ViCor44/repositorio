<?php

namespace App\Models;

use Core\Model;
use PDO;

class PasswordReset extends Model {

    public function create($userId, $tokenHash, $expiresAt) {

        $stmt = $this->db->prepare("
            INSERT INTO password_resets 
            (user_id, token_hash, expires_at)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$userId, $tokenHash, $expiresAt]);
    }

    public function findValid($tokenHash) {

        $stmt = $this->db->prepare("
            SELECT *
            FROM password_resets
            WHERE token_hash = ?
            AND expires_at > NOW()
            AND used = 0
            LIMIT 1
        ");

        $stmt->execute([$tokenHash]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markUsed($id) {

        $stmt = $this->db->prepare("
            UPDATE password_resets
            SET used = 1
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }
}
