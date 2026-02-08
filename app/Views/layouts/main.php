<?php

$pendingCount = 0;

if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'Administrador') {

    $pendingCount = (new \App\Models\User())->countPending();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Repositório Técnico' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <img 
                src="<?= BASE_URL ?>/assets/img/securo-logo.png" 
                alt="Securo"
                class="sidebar-logo"
            >
        </div>

        <hr>

        <small><?= $_SESSION['user']['nome'] ?></small><br>

        <hr>

        <?php if (!empty($_SESSION['user'])): ?>
            <a href="<?= BASE_URL ?>/documentos">📂 Documentos</a>
        <?php endif; ?>
        <?php if ($_SESSION['user']['role'] === 'Administrador'): ?>

        <a href="<?= BASE_URL ?>/admin/localizacao">📍 Gestão de Localizações</a>

        <?php endif; ?>

        <hr>

        <?php if (!empty($_SESSION['user'])): ?>            

            <?php if (!$_SESSION['user']['twofa_enabled']): ?>

            <div style="margin-top:15px;padding:10px;background:#1f2933;border-radius:8px">
            <small>🔐 Proteja a conta</small><br>
            <a href="<?= BASE_URL ?>/perfil/seguranca">Ativar 2FA</a>
            </div>

            <?php endif; ?>

            <?php if ($pendingCount > 0): ?>

                <hr>

                <a href="<?= BASE_URL ?>/admin/utilizadores">
                👤 Aprovar Utilizadores
                <span style="
                background:#ef4444;
                color:white;
                padding:2px 6px;
                border-radius:999px;
                font-size:11px;
                margin-left:6px;
                ">
                <?= $pendingCount ?>
                </span>
                </a>

                <?php endif; ?>

            <a href="<?= BASE_URL ?>/logout">Logout</a>

        <?php else: ?>

            <a href="<?= BASE_URL ?>/login">Login</a>

        <?php endif; ?>
        
    </aside>

    <div class="main">

        <div class="topbar">
            <strong><?= $title ?? '' ?></strong>
        </div>

        <div class="content">
            <?= $content ?>
        </div>

    </div>

</div>

</body>
</html>
