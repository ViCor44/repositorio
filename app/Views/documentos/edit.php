<h1>Editar Documento</h1>

<form method="POST"
      action="<?= BASE_URL ?>/documentos/editar"
      enctype="multipart/form-data"
      class="card">

<input type="hidden" name="id" value="<?= $documento['id'] ?>">

<label>Título</label>
<input name="titulo"
       value="<?= htmlspecialchars($documento['titulo']) ?>"
       required>

<label>Tipo</label>
<select name="tipo">
<option value="ELETRICO" <?= $documento['tipo']=='ELETRICO'?'selected':'' ?>>Elétrico</option>
<option value="REDE" <?= $documento['tipo']=='REDE'?'selected':'' ?>>Rede</option>
<option value="PLC" <?= $documento['tipo']=='PLC'?'selected':'' ?>>PLC</option>
<option value="TI" <?= $documento['tipo']=='TI'?'selected':'' ?>>TI</option>
</select>

<hr>

<h3>Localização</h3>

<label>Zona</label>
<select id="zonaSelect">
<option value="">Selecione zona</option>
<?php foreach ($zonas as $z): ?>
<option value="<?= $z['id'] ?>"
<?= ($documento['zona_id']==$z['id']?'selected':'') ?>>
<?= htmlspecialchars($z['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<label>Edifício</label>
<select id="edificioSelect">
<option value="">Selecione edifício</option>
</select>

<label>Sala</label>
<select name="sala_id" id="salaSelect" required>
<option value="">Selecione sala</option>
</select>

<hr>

<button class="btn">Guardar alterações</button>

<a href="<?= BASE_URL ?>/documentos" class="btn secondary">
Cancelar
</a>

</form>

<script>

const zona   = document.getElementById('zonaSelect');
const edif   = document.getElementById('edificioSelect');
const sala   = document.getElementById('salaSelect');

async function loadEdificios(zonaId, selected=null) {

    edif.innerHTML = '<option value="">Selecione edifício</option>';
    sala.innerHTML = '<option value="">Selecione sala</option>';

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

    sala.innerHTML = '<option value="">Selecione sala</option>';

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

// preload

loadEdificios(
    <?= (int)$documento['zona_id'] ?>,
    <?= (int)$documento['edificio_id'] ?>
);

loadSalas(
    <?= (int)$documento['edificio_id'] ?>,
    <?= (int)$documento['sala_id'] ?>
);

</script>
