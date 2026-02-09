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

<div class="action-buttons">

    <!-- APROVAR -->
    <form method="POST" action="<?= BASE_URL ?>/admin/utilizadores/aprovar" class="approve-form">

        <input type="hidden" name="id" value="<?= $u['id'] ?>">

        <select name="role_id" required>
            <option value="">Selecionar role</option>

            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>">
                    <?= htmlspecialchars($role['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn btn-approve">Aprovar</button>

    </form>

    <!-- REJEITAR -->
    <form method="POST"
          action="<?= BASE_URL ?>/admin/utilizadores/rejeitar"
          onsubmit="return confirm('Tem a certeza que deseja rejeitar este pedido?');">

        <input type="hidden" name="id" value="<?= $u['id'] ?>">

        <button class="btn btn-danger">Rejeitar</button>

    </form>

</div>

</td>
</tr>
<?php endforeach; ?>

</table>
