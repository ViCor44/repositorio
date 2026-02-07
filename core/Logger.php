<?php

namespace Core;

class Logger {

    public static function log(string $acao, string $entidade, int $entidadeId = null) {

        $db = Database::getInstance();

        $stmt = $db->prepare("
            INSERT INTO logs (user_id, acao, entidade, entidade_id, ip)
            VALUES (:user,:acao,:entidade,:eid,:ip)
        ");

        $stmt->execute([
            'user' => $_SESSION['user']['id'] ?? null,
            'acao' => $acao,
            'entidade' => $entidade,
            'eid' => $entidadeId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }
}
