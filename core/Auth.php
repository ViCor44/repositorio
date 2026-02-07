<?php
namespace Core;

use App\Models\User;

class Auth {

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool {
        return isset($_SESSION['user']);
    }

    public static function requireRole($roles) {
        $roles = (array)$roles;

        if (!self::check() || !in_array($_SESSION['user']['role'], $roles)) {
            http_response_code(403);
            die("Acesso negado");
        }
    }

    public static function require() {

        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

}
