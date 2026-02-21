<?php
namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {

    public function findByEmail($email) {

        $stmt = $this->db->prepare("
            SELECT u.*, r.nome AS role_nome
            FROM users u
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE u.email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        return $stmt->fetch();
    }
    public function updatePassword($id, $hash) {

        $stmt = $this->db->prepare("
            UPDATE users SET password_hash = ? WHERE id = ?
        ");

        $stmt->execute([$hash, $id]);
    }

    public function findById(int $id) {

        $stmt = $this->db->prepare("
            SELECT u.*, r.nome AS role_nome
            FROM users u
            JOIN roles r ON r.id = u.role_id
            WHERE u.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function createPending(array $data) {

        $stmt = $this->db->prepare("
            INSERT INTO users
            (nome,email,password_hash,status,role_id)
            VALUES
            (?,?,?, 'pendente', 4)
        ");

        $stmt->execute([
            $data['nome'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function pendingUsers() {

        return $this->db
            ->query("SELECT * FROM users WHERE status='pendente'")
            ->fetchAll();
    }

    public function approve($id, $roleId) {

        $stmt = $this->db->prepare("
            UPDATE users
            SET status = 'ativo',
                role_id = :role_id
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'role_id' => $roleId
        ]);
    }

    public function enable2fa(int $userId, string $secret): bool
    {
        $secret = trim($secret);

        // Regex ajustado: permite até 128 caracteres + padding
        if (!preg_match('/^[A-Z2-7]{8,128}(={0,8})?$/', strtoupper($secret))) {
            throw new \Exception("Secret 2FA inválido (não é Base32 válido). Valor recebido: " . substr($secret, 0, 50) . "... (len=" . strlen($secret) . ")");
        }

        // Grava exatamente como veio
        $stmt = $this->db->prepare("
            UPDATE users 
            SET twofa_enabled = 1, twofa_secret = :secret 
            WHERE id = :id
        ");
        $stmt->execute([':secret' => $secret, ':id' => $userId]);

        return $stmt->rowCount() > 0;
    }

    public function disable2fa(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET twofa_enabled = 0, twofa_secret = NULL 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    public function countPending(): int {

        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE status='pendente'"
        );

        return (int)$stmt->fetchColumn();
    }

    public function reject(int $id) {

        $stmt = $this->db->prepare("
            UPDATE users
            SET status = 'rejeitado'
            WHERE id = :id
            AND status = 'pendente'
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }


}
