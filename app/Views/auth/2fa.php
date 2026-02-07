<div class="login-card">

<h1>Verificação 2FA</h1>

<?php if (!empty($error)): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/2fa">

<label>Código</label>
<input type="text" name="code" pattern="[0-9]{6}" required>

<br><br>

<button class="btn">Confirmar</button>

</form>

</div>
