<h1>Novo Documento</h1>

<form method="POST"
      action="<?= BASE_URL ?>/documentos"
      enctype="multipart/form-data">

    <label>Título</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Tipo</label><br>
    <select name="tipo" required>
        <option value="eletrico">Elétrico</option>
        <option value="rede">Rede</option>
        <option value="fibra">Fibra</option>
        <option value="hvac">HVAC</option>
        <option value="cctv">CCTV</option>
        <option value="plc">PLC</option>
        <option value="outro">Outro</option>
    </select><br><br>

    <label>Zona</label>
    <select name="zona_id" id="zonaSelect">
    <option value="">Selecione zona</option>
    <?php foreach ($zonas as $z): ?>
    <option value="<?= $z['id'] ?>">
    <?= htmlspecialchars($z['nome']) ?>
    </option>
    <?php endforeach; ?>
    </select>

    <label>Edifício</label>
    <select name="edificio_id" id="edificioSelect">
    <option value="">Selecione edifício</option>
    </select>

    <label>Sala</label>
    <select name="sala_id" id="salaSelect" required>
    <option value="">Selecione sala</option>
    </select>


    <!-- Depois fazemos zona/edificio/sala dinâmicos -->

    <label>Ficheiro</label><br>
    <input type="file" name="ficheiro" required><br><br>

    <button type="submit">Guardar Documento</button>
</form>
<script>

const zona   = document.getElementById('zonaSelect');
const edif   = document.getElementById('edificioSelect');
const sala   = document.getElementById('salaSelect');

zona.addEventListener('change',()=>{
    fetch('<?= BASE_URL ?>/api/edificios?zona_id='+zona.value)
      .then(r=>r.json())
      .then(data=>{
        edif.innerHTML='<option value="">Selecione edifício</option>';
        sala.innerHTML='<option value="">Selecione sala</option>';
        data.forEach(e=>{
            const opt=document.createElement('option');
            opt.value=e.id;
            opt.textContent=e.nome;
            edif.appendChild(opt);
        });
      });
});

edif.addEventListener('change',()=>{
    fetch('<?= BASE_URL ?>/api/salas?edificio_id='+edif.value)
      .then(r=>r.json())
      .then(data=>{
        sala.innerHTML='<option value="">Selecione sala</option>';
        data.forEach(s=>{
            const opt=document.createElement('option');
            opt.value=s.id;
            opt.textContent=s.nome;
            sala.appendChild(opt);
        });
      });
});
</script>
