<h1>Aprovação de Utilizadores</h1>

<table>
<tr>
  <th>Nome</th>
  <th>Email</th>
  <th>Ação</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
<td><?= htmlspecialchars($u['nome']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td>
<form method="POST"
      action="<?= BASE_URL ?>/admin/utilizadores/aprovar">
<input type="hidden" name="id" value="<?= $u['id'] ?>">
<button class="btn">Aprovar</button>
</form>
</td>
</tr>
<?php endforeach; ?>

</table>
