<h1><?= htmlspecialchars($doc['titulo']) ?></h1>

<p><strong>Tipo:</strong> <?= strtoupper($doc['tipo']) ?></p>
<p><strong>Estado:</strong> <?= $doc['estado'] ?></p>
<p><strong>Versão ativa:</strong> <?= $doc['versao'] ?></p>

<p>
    <a href="<?= BASE_URL ?>/documentos/download?id=<?= $doc['id'] ?>">⬇ Download</a>
</p>

<hr>

<h3>Preview</h3>

<div class="card">

<?php
$previewUrl = BASE_URL . '/documentos/preview?id=' . $doc['id'];

$ext = strtolower($doc['formato']);

if ($ext === 'pdf'):
?>

    <iframe src="<?= $previewUrl ?>"
            width="100%"
            height="600"
            style="border:none"></iframe>

<?php elseif (in_array($ext, ['png','jpg','jpeg','gif'])): ?>

    <img src="<?= $previewUrl ?>"
         style="max-width:100%">

<?php else: ?>

    <p>Preview indisponível para este formato.</p>

<?php endif; ?>

</div>

<hr>

<h3>Histórico de Versões</h3>

<table border="1" cellpadding="6">
<tr>
    <th>Versão</th>
    <th>Data</th>
    <th>Utilizador</th>
    <th>Ativa</th>
</tr>

<?php foreach ($versoes as $v): ?>
<tr>
    <td><?= $v['versao'] ?></td>
    <td><?= $v['criado_em'] ?></td>
    <td><?= $v['nome'] ?></td>
    <td><?= $v['is_ativo'] ? '✔' : '' ?></td>
</tr>
<?php endforeach; ?>
</table>

<p>
    <a href="<?= BASE_URL ?>/documentos/nova-versao?id=<?= $doc['id'] ?>">
        ➕ Nova Versão
    </a>
</p>

<p><strong>Tags:</strong>
<?php if (empty($tags)): ?>
    nenhuma
<?php else: ?>
    <?php foreach ($tags as $tg): ?>
        <span><?= htmlspecialchars($tg['nome']) ?></span>
    <?php endforeach; ?>
<?php endif; ?>
</p>

<hr>

<h3>Comentários Técnicos</h3>

<?php if (empty($comentarios)): ?>
<p>Sem comentários.</p>
<?php endif; ?>

<?php foreach ($comentarios as $c): ?>
<p>
    <strong><?= htmlspecialchars($c['nome']) ?></strong>
    (<?= $c['criado_em'] ?>)<br>
    <?= nl2br(htmlspecialchars($c['texto'])) ?>
</p>
<hr>
<?php endforeach; ?>

<?php if (in_array($_SESSION['user']['role'], ['Administrador','Engenheiro','Tecnico'])): ?>
<form method="POST" action="<?= BASE_URL ?>/documentos/comentar">
    <input type="hidden" name="documento_id" value="<?= $doc['id'] ?>">
    <textarea name="texto" rows="4" cols="60" required></textarea><br>
    <button type="submit">Adicionar Comentário</button>
</form>
<?php endif; ?>

<?php if (in_array($_SESSION['user']['role'], ['Administrador','Engenheiro','Tecnico'])): ?>

<form method="POST" action="<?= BASE_URL ?>/documentos/tags">

    <input type="hidden" name="documento_id" value="<?= $doc['id'] ?>">

    <input type="text" name="tag" placeholder="Nova tag">

    <button type="submit">Adicionar Tag</button>

</form>

<?php endif; ?>