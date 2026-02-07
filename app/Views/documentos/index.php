<?php if (
    empty($_SESSION['user']['twofa_enabled']) &&
    empty($_SESSION['hide_2fa_banner'])
): ?>


<div class="card" style="border-left:5px solid #f59e0b;margin-bottom:15px">

<strong>🔐 Aumente a segurança da sua conta</strong>

<p>
Ativar autenticação em dois fatores (2FA) protege o acesso mesmo que a password seja comprometida.
</p>

<a class="btn" href="<?= BASE_URL ?>/perfil/seguranca">
Ativar 2FA agora
</a>
<form method="POST"
      action="<?= BASE_URL ?>/perfil/2fa/ocultar"
      style="display:inline">

    <button class="btn secondary">
    Agora não
    </button>

</form>
</div>

<?php endif; ?>

<form method="GET" id="filtersForm" class="filters-grid">

<div class="row">
<select name="zona_id" id="zonaSelect">
<option value="">Zona</option>
<?php foreach ($zonas as $z): ?>
<option value="<?= $z['id'] ?>"
<?= (($filters['zona_id'] ?? null) == $z['id'] ? 'selected' : '') ?>>
<?= htmlspecialchars($z['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<select name="edificio_id" id="edificioSelect">
<option value="">Edifício</option>
</select>

<select name="sala_id" id="salaSelect">
<option value="">Sala</option>
</select>
</div>

<div class="row">
<input name="q"
       placeholder="Pesquisar título..."
       value="<?= htmlspecialchars($filters['q'] ?? '') ?>">

<select name="tipo">
<option value="">Tipo</option>
<!-- opções -->
</select>

<select name="tag">
<option value="">Tag</option>
<?php foreach ($tags as $t): ?>
<option value="<?= $t['id'] ?>"
<?= (($filters['tag'] ?? null) == $t['id'] ? 'selected' : '') ?>>
<?= htmlspecialchars($t['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<button class="btn">Pesquisar</button>
</div>

</form>

<hr>

<p>Bem-vindo ao repositório técnico.</p>
<div class="page-header">
    <h1>Documentos Técnicos</h1>

    <a class="btn" href="<?= BASE_URL ?>/documentos/novo">
        ➕ Novo Documento
    </a>
</div>


<table border="1" cellpadding="6" cellspacing="0">
<tr>
    <th>Título</th>
    <th>Tipo</th>
    <th>Local</th>
    <th>Estado</th>
    <th>Versão</th>
    <th>Ações</th>
</tr>

<?php if (empty($documentos)): ?>
<tr>
    <td colspan="6">Nenhum documento ainda.</td>
</tr>
<?php endif; ?>

<?php foreach ($documentos as $doc): ?>
<tr>
    <td><?= htmlspecialchars($doc['titulo']) ?></td>
    <td>
    <span class="badge tipo-<?= $doc['tipo'] ?>">
        <?= strtoupper($doc['tipo']) ?>
    </span>
    </td>
    <td>
        <?= $doc['zona'] ? ' / '.$doc['zona'] : '' ?>
        <?= $doc['edificio'] ? ' / '.$doc['edificio'] : '' ?>
        <?= $doc['sala'] ? ' / '.$doc['sala'] : '' ?>
    </td>
    <td>
    <span class="badge estado-<?= $doc['estado'] ?>">
        <?= strtoupper($doc['estado']) ?>
    </span>
    </td>
    <td><?= $doc['versao'] ?></td>
    <td class="actions">
        <a class="btn secondary"
        href="<?= BASE_URL ?>/documentos/ver?id=<?= $doc['id'] ?>">👁 Ver</a>

        <a class="btn"
        href="<?= BASE_URL ?>/documentos/download?id=<?= $doc['id'] ?>">⬇ Download</a>

        <a href="<?= BASE_URL ?>/documentos/editar?id=<?= $doc['id'] ?>"
        class="btn secondary">✏️ Editar</a>

        <form method="POST"
            action="<?= BASE_URL ?>/documentos/apagar"
            onsubmit="return confirm('Apagar documento?')"
            style="display:inline">

        <input type="hidden" name="id" value="<?= $doc['id'] ?>">

        <button class="btn danger">🗑</button>

        </form>
    </td>
</tr>


<?php endforeach; ?>

</table>
<script>

const zona   = document.getElementById('zonaSelect');
const edif   = document.getElementById('edificioSelect');
const sala   = document.getElementById('salaSelect');

async function loadEdificios(zonaId, selected=null) {

    edif.innerHTML = '<option value="">Edifício</option>';
    sala.innerHTML = '<option value="">Sala</option>';

    if (!zonaId) return;

    const res = await fetch('<?= BASE_URL ?>/api/edificios?zona_id='+zonaId);
    const data = await res.json();

    data.forEach(e=>{
        const opt = document.createElement('option');
        opt.value = e.id;
        opt.textContent = e.nome;
        if (selected == e.id) opt.selected = true;
        edif.appendChild(opt);
    });
}

async function loadSalas(edId, selected=null) {

    sala.innerHTML = '<option value="">Sala</option>';

    if (!edId) return;

    const res = await fetch('<?= BASE_URL ?>/api/salas?edificio_id='+edId);
    const data = await res.json();

    data.forEach(s=>{
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.nome;
        if (selected == s.id) opt.selected = true;
        sala.appendChild(opt);
    });
}

// listeners

zona.addEventListener('change',()=>{
    loadEdificios(zona.value);
});

edif.addEventListener('change',()=>{
    loadSalas(edif.value);
});

// reload com filtros

<?php if (!empty($filters['zona_id'])): ?>
loadEdificios(
    <?= (int)$filters['zona_id'] ?>,
    <?= (int)($filters['edificio_id'] ?? 0) ?>
);
<?php endif; ?>

<?php if (!empty($filters['edificio_id'])): ?>
loadSalas(
    <?= (int)$filters['edificio_id'] ?>,
    <?= (int)($filters['sala_id'] ?? 0) ?>
);
<?php endif; ?>

</script>

