<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title><?= $title ?? 'Login' ?></title>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">

</head>
<body class="auth-body">

<div class="auth-container">

    <div class="auth-brand">
        <div class="brand-box">
            <h1>Repositorio</h1>
            <p>Plataforma Técnica Central</p>
        </div>

        <div class="brand-text">
            <h2>Bem-vindo ao Repositório Técnico</h2>
            <p>
                Centralize documentação, plantas, manuais técnicos
                e procedimentos operacionais num único local seguro.
            </p>
        </div>
    </div>


    <div class="auth-card">
        <?= $content ?>
    </div>

</div>

</body>
</html>
