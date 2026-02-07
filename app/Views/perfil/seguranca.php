<h1>Segurança da Conta</h1>

<?php if (!$_SESSION['user']['twofa_enabled'] && !empty($qr)): ?>

<hr>

<div class="card">

<h3>📱 Configure a autenticação em dois fatores</h3>

<p>
Para usar 2FA, precisa de uma aplicação autenticadora no telemóvel.
Estas apps geram códigos temporários que mudam a cada 30 segundos.
</p>

<p><strong>Recomendamos:</strong></p>

<ul>
<li>Google Authenticator</li>
<li>Microsoft Authenticator</li>
<li>Authy</li>
</ul>

<p>
Depois de instalar a aplicação:
</p>

<ol>
<li>Abra a app autenticadora</li>
<li>Escolha <strong>Adicionar conta</strong> ou <strong>Scan QR code</strong></li>
<li>Leia o QR code abaixo</li>
<li>Introduza o código gerado para confirmar</li>
</ol>

<hr>

<div style="display:flex;gap:30px;align-items:center">

<div>
<img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=<?= urlencode($qr) ?>">
</div>

<div>

<p><strong>Ou introduza manualmente:</strong></p>

<code style="word-break:break-all;display:block">
<?= htmlspecialchars($secret) ?>
</code>

<br>

<form method="POST" action="<?= BASE_URL ?>/perfil/2fa/confirmar">

<label>Código gerado pela aplicação</label>
<input name="code" placeholder="123456" required>

<br><br>

<button class="btn">Confirmar ativação</button>

</form>

</div>

</div>

<div style="margin-top:15px;font-size:13px;color:#555">

<p>
🔒 <strong>Dica:</strong> Guarde o acesso ao seu telemóvel em segurança.
Se perder o dispositivo, terá de contactar um administrador para recuperar a conta.
</p>

</div>

</div>

<?php endif; ?>

