<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;
use Core\TwoFactor;

class PerfilController extends Controller {

    public function seguranca() {
        Auth::require();

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user']['id']);

        // Atualiza sessão com valor real da BD
        $_SESSION['user']['twofa_enabled'] = (int)$user['twofa_enabled'];

        $this->view('perfil/seguranca', [
            'title' => 'Segurança da Conta',
            'user'  => $user
        ]);
    }
    // iniciar2fa
    public function iniciar2fa()
    {
        Auth::require();

        $user = (new User())->findById($_SESSION['user']['id']);
        $twoFactor = new \Core\TwoFactor();

        $secret = $twoFactor->generateSecret();
        $_SESSION['2fa_temp_secret'] = $secret;  // nome mais claro

        $qr = $twoFactor->getQrCodeDataUri($user['email'], $secret);  // ou getQrCodeUrl

        $this->view('perfil/seguranca', [
            'title'  => 'Ativar 2FA',
            'secret' => $secret,
            'qr'     => $qr,
            'user'   => $user
        ]);
    }

    // confirmar2fa
    public function confirmar2fa()
    {
        Auth::require();

        $secret = $_SESSION['2fa_temp_secret'] ?? null;
        if (!$secret) {
            $this->redirect('/perfil/seguranca?error=sessao_expirada');
        }

        $code = trim($_POST['code'] ?? '');

        if (\Core\TwoFactor::verify($secret, $code)) {
            (new User())->enable2fa($_SESSION['user']['id'], $secret);

            unset($_SESSION['2fa_temp_secret']);
            $_SESSION['user']['twofa_enabled'] = 1;

            $this->redirect('/perfil/seguranca?success=2fa_ativado');
        }

        $this->view('perfil/seguranca', [
            'error' => 'Código inválido. Tenta novamente.',
            'user'  => $_SESSION['user']
        ]);
    }

    public function hide2faBanner() {

        $_SESSION['hide_2fa_banner'] = true;

        $this->redirect('/documentos');
    }

}
