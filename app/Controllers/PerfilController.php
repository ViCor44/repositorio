<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;

class PerfilController extends Controller {

    public function seguranca() {

        Auth::require();

        $this->view('perfil/seguranca',[
            'title'=>'Segurança da Conta'
        ]);
    }

    public function iniciar2fa() {

        $secret = \Core\TwoFactor::generateSecret();

        $_SESSION['2fa_setup'] = $secret;

        $uri = \Core\TwoFactor::provisioningUri(
            $_SESSION['user']['nome'],
            $secret
        );

        $this->view('perfil/seguranca',[
            'qr'=>$uri,
            'secret'=>$secret,
            'title'=>'Ativar 2FA'
        ]);
    }

    public function confirmar2fa() {

        $secret = $_SESSION['2fa_setup'];

        if (\Core\TwoFactor::verify($secret,$_POST['code'])) {

            (new \App\Models\User())
                ->enable2fa($_SESSION['user']['id'],$secret);

            unset($_SESSION['2fa_setup']);

            $_SESSION['user']['twofa_enabled'] = 1;

            $this->redirect('/perfil/seguranca');
        }

        return $this->view('perfil/seguranca',[
            'error'=>'Código inválido',
            'title'=>'Ativar 2FA'
        ]);
    }

    public function hide2faBanner() {

        $_SESSION['hide_2fa_banner'] = true;

        $this->redirect('/documentos');
    }

}
