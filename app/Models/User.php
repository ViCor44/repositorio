<?php
namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {

    public function findByEmail(string $email) {
        $stmt = $this->db->prepare(
            "SELECT u.*, r.nome AS role_nome
             FROM users u
             JOIN roles r ON r.id = u.role_id
             WHERE email = :email
             AND ativo = 1"
        );

        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function approve(int $id) {

        $stmt = $this->db->prepare("
            UPDATE users SET status='ativo' WHERE id=?
        ");

        $stmt->execute([$id]);
    }

    public function enable2fa(int $id, string $secret) {

        $stmt = $this->db->prepare("
            UPDATE users
            SET twofa_enabled = 1,
                twofa_secret = ?
            WHERE id = ?
        ");

        $stmt->execute([$secret, $id]);
    }

    public function countPending(): int {

        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE status='pendente'"
        );

        return (int)$stmt->fetchColumn();
    }

}
