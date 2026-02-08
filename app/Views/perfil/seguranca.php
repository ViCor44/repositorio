<style>
.qr-test {
    display: flex !important;
    gap: 30px !important;
    flex-wrap: wrap !important;
    background: rgba(255,0,0,.05);
}

.qr-test > div {
    width: 280px !important;
    border: 2px dashed red;
}
</style>


<h1>Segurança da Conta</h1>

<?php if ($_SESSION['user']['twofa_enabled']): ?>
    <div class="alert alert-success mb-4">
        <strong>✅ Autenticação em dois fatores (2FA) já está ativa na tua conta!</strong><br>
        A tua conta está agora muito mais protegida contra acessos não autorizados.
    </div>

    <div class="alert alert-info">
        <strong>Queres desativar ou reconfigurar o 2FA?</strong><br>
        Por motivos de segurança, esta ação deve ser feita com cuidado. Contacta um administrador ou adiciona um botão de reset (se implementares).
    </div>

    <!-- Opcional: botão de desativação (descomenta quando tiveres o método disable2fa) -->
    <!-- 
    <form method="POST" action="<?= BASE_URL ?>/perfil/2fa/desativar" onsubmit="return confirm('Tens a certeza que queres desativar o 2FA? A conta ficará menos segura.');">
        <button type="submit" class="btn btn-outline-danger">
            Desativar 2FA (não recomendado)
        </button>
    </form>
    -->

<?php else: ?>
    <hr class="my-4">

    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-primary">📱 Ativa a autenticação em dois fatores (2FA)</h3>

            <p class="lead mt-3">
                O 2FA adiciona uma camada extra de segurança: além da password, precisas de um código temporário gerado no teu telemóvel.
            </p>

            <div class="alert alert-warning mt-3">
                <strong>Porquê ativar?</strong> Protege a tua conta mesmo que alguém descubra a tua password.
            </div>

            <h5 class="mt-4">Passo 1 – Instala uma aplicação autenticadora</h5>
            <p>Escolhe uma destas apps gratuitas e seguras. Aponta a câmara do teu telemóvel para o QR code correspondente para abrir diretamente a loja:</p>

            <div class="qr-test">

                <!-- GOOGLE -->
                <div class="card shadow-sm text-center" style="width:280px">
                    <div class="card-body">

                        <h6 class="mb-3">Google Authenticator</h6>

                        <div class="d-flex flex-column align-items-center gap-3">

                            <div>
                                <small class="text-muted">Android</small><br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode('https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2') ?>"
                                    style="width:140px;border-radius:8px;">
                            </div>

                            <div>
                                <small class="text-muted">iPhone</small><br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode('https://apps.apple.com/app/google-authenticator/id388497605') ?>"
                                    style="width:140px;border-radius:8px;">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- MICROSOFT -->
                <div class="card shadow-sm text-center" style="width:280px">
                    <div class="card-body">

                        <h6 class="mb-3">Microsoft Authenticator</h6>

                        <div class="d-flex flex-column align-items-center gap-3">

                            <div>
                                <small class="text-muted">Android</small><br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode('https://play.google.com/store/apps/details?id=com.azure.authenticator') ?>"
                                    style="width:140px;border-radius:8px;">
                            </div>

                            <div>
                                <small class="text-muted">iPhone</small><br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode('https://apps.apple.com/app/microsoft-authenticator/id983156458') ?>"
                                    style="width:140px;border-radius:8px;">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- AUTHY -->
                <div class="card shadow-sm text-center" style="width:280px">
                    <div class="card-body">

                        <h6 class="mb-3">Authy</h6>

                        <div class="d-flex flex-column align-items-center gap-3">

                            <div>
                                <small class="text-muted">Android</small><br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode('https://play.google.com/store/apps/details?id=com.authy.authy') ?>"
                                    style="width:140px;border-radius:8px;">
                            </div>

                            <div>
                                <small class="text-muted">iPhone</small><br>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode('https://apps.apple.com/app/authy/id494120986') ?>"
                                    style="width:140px;border-radius:8px;">
                            </div>

                        </div>
                    </div>
                </div>

            </div>           

            <!-- Fallback para quem não consegue ler QR -->
            <p class="text-muted small text-center mt-4">
                Se os QR codes não funcionarem ou preferires clicar manualmente, usa os links abaixo:<br>

                <strong>Google Authenticator:</strong>
                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">
                    Android
                </a> •
                <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank">
                    iOS
                </a>

                <br>

                <strong>Microsoft Authenticator:</strong>
                <a href="https://play.google.com/store/apps/details?id=com.azure.authenticator" target="_blank">
                    Android
                </a> •
                <a href="https://apps.apple.com/app/microsoft-authenticator/id983156458" target="_blank">
                    iOS
                </a>

                <br>

                <strong>Authy:</strong>
                <a href="https://play.google.com/store/apps/details?id=com.authy.authy" target="_blank">
                    Android
                </a> •
                <a href="https://apps.apple.com/app/authy/id494120986" target="_blank">
                    iOS
                </a>
            </p>

            <h5>Passo 2 – Configura a conta</h5>
            <ol class="list-group list-group-numbered mb-4">
                <li class="list-group-item">Abre a app que instalaste</li>
                <li class="list-group-item">Escolhe <strong>Adicionar conta</strong> ou <strong>Scan QR code</strong></li>
                <li class="list-group-item">Lê o QR code abaixo (ou introduz o código manualmente)</li>
                <li class="list-group-item">Introduz o código de 6 dígitos que aparece na app</li>
            </ol>

            <?php if (!empty($qr)): ?>
                <div class="row align-items-center mt-4">
                    <div class="col-md-5 text-center">
                        <img src="<?= htmlspecialchars($qr) ?>" alt="QR Code para autenticação 2FA" class="img-fluid shadow" style="max-width: 380px; border-radius: 12px;">
                    </div>

                    <div class="col-md-7">
                        <p class="fw-bold mt-3 mt-md-0">Ou introduza manualmente (se não conseguires ler o QR):</p>
                        <code class="d-block p-3 bg-light border rounded" style="word-break: break-all; font-size: 0.95rem;">
                            <?= htmlspecialchars($secret) ?>
                        </code>

                        <form method="POST" action="<?= BASE_URL ?>/perfil/2fa/confirmar" class="mt-4">
                            <div class="mb-3">
                                <label for="code" class="form-label fw-bold">Código gerado pela aplicação</label>
                                <input type="text" name="code" id="code" class="form-control form-control-lg text-center" placeholder="123456" required pattern="\d{6}" maxlength="6" inputmode="numeric">
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                Confirmar ativação
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center mt-4">
                    <form method="POST" action="<?= BASE_URL ?>/perfil/2fa/iniciar">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="bi bi-shield-lock-fill me-2"></i> Ativar 2FA agora
                        </button>
                    </form>
                    <p class="text-muted mt-3 small">Demora menos de 1 minuto</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Dica final sempre visível -->
<div class="alert alert-secondary mt-4">
    <strong>🔒 Importante:</strong> Guarda bem o acesso ao teu telemóvel. Se perderes o dispositivo ou mudares de telemóvel, contacta um administrador para recuperar o acesso à conta.
</div>