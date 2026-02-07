<h2>Login</h2>

<p style="margin-bottom:20px;color:#666">
Aceda ao sistema de documentação técnica.
</p>

<form method="POST">

<label>Email</label>
<input type="email" name="email" required>

<label>Password</label>
<input type="password" name="password" required>

<button>Entrar</button>

<div class="auth-links">
    <p>
        Não tem conta?
        <a href="<?= BASE_URL ?>/register">Pedir acesso</a>
    </p>

    <a href="<?= BASE_URL ?>/password/reset">Recuperar password</a>
</div>

<?php if(!empty($error)): ?>
<p style="color:red;margin-top:15px"><?= $error ?></p>
<?php endif; ?>
</form>
