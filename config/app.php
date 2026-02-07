<?php

// ===============================
// BASE URL DO PROJETO
// ===============================
// Ajusta se mudares de pasta
// Ex: /repositorio/public
define('BASE_URL', '/repositorio/public');

// ===============================
// AUTOLOAD SIMPLES
// ===============================

spl_autoload_register(function ($class) {

    // Pasta raiz do projeto
    $baseDir = dirname(__DIR__);

    // Converte namespace em caminho
    $class = str_replace('\\', '/', $class);

    $file = $baseDir . '/' . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
