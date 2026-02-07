<?php

namespace Core;

class Uploader {

    protected string $basePath;

    public function __construct(string $basePath) {
        $this->basePath = dirname(__DIR__) . $basePath;
    }

    public function upload(array $file): array {

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Erro no upload");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $name = bin2hex(random_bytes(16)) . '.' . $ext;

        $dest = $this->basePath . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \Exception("Falha ao mover ficheiro");
        }

        return [
            'path' => $name,
            'ext' => $ext,
            'size' => $file['size']
        ];
    }
}
