<h2>Pedido de Acesso</h2>

<form method="POST" action="<?= BASE_URL ?>/register">

<label>Nome</label>
<input name="nome" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Confirmar Password</label>
<input type="password" name="password_confirm" required>

<button>Enviar pedido</button>

<div class="auth-links">
    <a href="<?= BASE_URL ?>/login">Voltar ao login</a>
</div>

</form>
