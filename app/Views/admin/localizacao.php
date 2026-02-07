<h1>Gestão de Localizações</h1>

<?php if (!empty($_SESSION['flash_error'])): ?>
<div class="card" style="border-left:5px solid #dc2626">
<?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
</div>
<?php endif; ?>

<!-- ========================= -->
<!-- CRIAR NOVA ZONA -->
<!-- ========================= -->

<div class="card">

<h3>➕ Criar nova zona</h3>

<form method="POST" action="<?= BASE_URL ?>/admin/zonas/criar">

<input name="nome"
       placeholder="Nome da zona"
       required>

<button class="btn">Criar Zona</button>

</form>

</div>

<hr>

<!-- ========================= -->
<!-- LISTAGEM HIERÁRQUICA -->
<!-- ========================= -->

<?php foreach ($zonas as $zona): ?>

<div class="card">

<!-- ZONA EDITAR/APAGAR -->

<div style="display:flex;gap:10px;align-items:center">

<form method="POST"
      action="<?= BASE_URL ?>/admin/zonas/editar"
      style="flex:1;display:flex;gap:8px">

<input type="hidden" name="id" value="<?= $zona['id'] ?>">

<input name="nome"
       value="<?= htmlspecialchars($zona['nome']) ?>">

<button class="btn">💾</button>
</form>

<form method="POST"
      action="<?= BASE_URL ?>/admin/zonas/apagar"
      onsubmit="return confirm('Apagar zona?')">

<input type="hidden" name="id" value="<?= $zona['id'] ?>">

<button class="btn secondary">🗑</button>

</form>

</div>

<!-- ADICIONAR EDIFÍCIO -->

<form method="POST"
      action="<?= BASE_URL ?>/admin/edificios/criar"
      style="margin:12px 0">

<input type="hidden"
       name="zona_id"
       value="<?= $zona['id'] ?>">

<input name="nome"
       placeholder="Nome do edifício"
       required>

<button class="btn secondary">
➕ Edifício
</button>

</form>

<!-- EDIFÍCIOS -->

<?php foreach ($zona['edificios'] as $ed): ?>

<div style="
margin-left:25px;
padding-left:15px;
border-left:3px solid #e5e7eb;
">

<!-- EDIFÍCIO EDITAR/APAGAR -->

<div style="display:flex;gap:8px;align-items:center">

<form method="POST"
      action="<?= BASE_URL ?>/admin/edificios/editar"
      style="flex:1;display:flex;gap:6px">

<input type="hidden" name="id" value="<?= $ed['id'] ?>">

<input name="nome"
       value="<?= htmlspecialchars($ed['nome']) ?>">

<button class="btn secondary">💾</button>
</form>

<form method="POST"
      action="<?= BASE_URL ?>/admin/edificios/apagar"
      onsubmit="return confirm('Apagar edifício?')">

<input type="hidden" name="id" value="<?= $ed['id'] ?>">

<button class="btn secondary">🗑</button>

</form>

</div>

<!-- ADICIONAR SALA -->

<form method="POST"
      action="<?= BASE_URL ?>/admin/salas/criar"
      style="margin:8px 0">

<input type="hidden"
       name="edificio_id"
       value="<?= $ed['id'] ?>">

<input name="nome"
       placeholder="Nome da sala"
       required>

<button class="btn secondary">
➕ Sala
</button>

</form>

<ul>

<?php foreach ($ed['salas'] as $sala): ?>

<li style="display:flex;gap:6px;align-items:center">

<form method="POST"
      action="<?= BASE_URL ?>/admin/salas/editar"
      style="flex:1;display:flex;gap:6px">

<input type="hidden" name="id" value="<?= $sala['id'] ?>">

<input name="nome"
       value="<?= htmlspecialchars($sala['nome']) ?>">

<button class="btn secondary">💾</button>

</form>

<form method="POST"
      action="<?= BASE_URL ?>/admin/salas/apagar"
      onsubmit="return confirm('Apagar sala?')">

<input type="hidden" name="id" value="<?= $sala['id'] ?>">

<button class="btn secondary">🗑</button>

</form>

</li>

<?php endforeach; ?>

</ul>

</div>

<?php endforeach; ?>

</div>

<?php endforeach; ?>
