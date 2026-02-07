<h2>Pedido de Acesso</h2>

<form method="POST">

<label>Nome</label>
<input name="nome" required>

<label>Email</label>
<input type="email" required>

<label>Password</label>
<input type="password" required>

<label>Confirmar Password</label>
<input type="password" required>

<button>Enviar pedido</button>

<div class="auth-links">
    <a href="<?= BASE_URL ?>/login">Voltar ao login</a>
</div>

</form>
