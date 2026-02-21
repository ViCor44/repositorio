<h2>Definir nova password</h2>

<form method="POST" action="<?= BASE_URL ?>/password/update">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="password" required placeholder="Nova password">
    <button type="submit">Atualizar</button>
</form>
