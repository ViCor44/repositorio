<h1>Nova Versão</h1>

<form method="POST"
      enctype="multipart/form-data"
      action="<?= BASE_URL ?>/documentos/nova-versao">

    <input type="hidden" name="documento_id"
           value="<?= $documento_id ?>">

    <label>Versão</label><br>
    <input type="text" name="versao" required><br><br>

    <label>Ficheiro</label><br>
    <input type="file" name="ficheiro" required><br><br>

    <button type="submit">Submeter nova versão</button>
</form>
