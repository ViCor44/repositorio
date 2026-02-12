<?php

namespace Core;

class AuthLogger {

    public static function log(string $type, array $data = []) {

        $dir = __DIR__ . '/../storage/logs';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir . '/auth.log';

        $time = date('Y-m-d H:i:s');

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $parts = [];

        foreach ($data as $k => $v) {
            $parts[] = "$k=$v";
        }

        $line = "[$time] $type ip=$ip " . implode(' ', $parts) . PHP_EOL;

        file_put_contents($file, $line, FILE_APPEND);
    }
}
