<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use Core\Auth;

class AuthController extends Controller {

    public function index() {

        Auth::requireRole(['Administrador','Engenheiro','Tecnico']);

        echo "Área protegida!";
    }

    public function show() {
       $this->view('auth/login', [
            'title' => 'Login'
        ], 'layouts/auth');
    }

public function login() {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    // 👉 Primeiro: utilizador existe?
    if (!$user) {
        return $this->view('auth/login', [
            'error' => 'Credenciais inválidas',
            'title' => 'Login'
        ], 'layouts/auth');
    }

    // 👉 Depois: estado da conta
    if ($user['status'] === 'pendente') {
        return $this->view('auth/login',[
            'error'=>'Conta ainda não aprovada por administrador',
            'title'=>'Login'
        ],'layouts/auth');
    }

    if ($user['status'] === 'rejeitado') {
        return $this->view('auth/login',[
            'error'=>'Pedido de acesso rejeitado. Contacte o administrador.',
            'title'=>'Login'
        ],'layouts/auth');
    }

    // 👉 Password correta?
    if (!password_verify($password, $user['password_hash'])) {
        return $this->view('auth/login', [
            'error' => 'Credenciais inválidas',
            'title' => 'Login'
        ], 'layouts/auth');
    }

    // 👉 2FA
    if ($user['twofa_enabled']) {

        $_SESSION['2fa_pending'] = $user['id'];

        return $this->view('auth/2fa', [
            'title' => 'Verificação 2FA'
        ], 'layouts/auth');
    }

    // 👉 Login OK
    $_SESSION['user'] = [
        'id' => $user['id'],
        'nome' => $user['nome'],
        'role' => $user['role_nome'],
        'twofa_enabled' => (int)$user['twofa_enabled']
    ];

    $this->redirect('/documentos');
}

    public function logout() {
        session_destroy();
        \Core\Logger::log('logout','user',$_SESSION['user']['id']);
        $this->redirect('/login');
    }

    public function verify2fa()
    {
        if (!isset($_SESSION['2fa_pending'])) {
            $this->redirect('/login');
        }

        $userId = $_SESSION['2fa_pending'];
        $userModel = new User();
        $user = $userModel->findById($userId);

        $code = trim($_POST['code'] ?? '');

        if (\Core\TwoFactor::verify($user['twofa_secret'], $code)) {
            unset($_SESSION['2fa_pending']);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'role' => $user['role_nome'],
                'twofa_enabled' => 1
            ];

            $this->redirect('/documentos');
        }

        return $this->view('auth/2fa', [
            'error' => 'Código inválido. Verifica a hora do telemóvel e tenta novamente.',
            'title' => 'Verificação 2FA'
        ], 'layouts/auth');
    }
    
    public function registerForm() {

        $this->view('auth/register',[
            'title'=>'Pedido de Acesso'
        ],'layouts/auth');
    }

    public function register() {

        $userModel = new User();

        if (empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['password'])) {
            return $this->view('auth/register',[
                'error'=>'Preenche todos os campos',
                'title'=>'Pedido de Acesso'
            ],'layouts/auth');
        }

        if ($_POST['password'] !== $_POST['password_confirm']) {
            return $this->view('auth/register',[
                'error'=>'As passwords não coincidem',
                'title'=>'Pedido de Acesso'
            ],'layouts/auth');
        }

        if ($userModel->findByEmail($_POST['email'])) {
            return $this->view('auth/register',[
                'error'=>'Email já registado',
                'title'=>'Pedido de Acesso'
            ],'layouts/auth');
        }

        $userModel->createPending([
            'nome'=>$_POST['nome'],
            'email'=>$_POST['email'],
            'password'=>$_POST['password']
        ]);

        $_SESSION['flash_success'] = 'Pedido enviado com sucesso! Aguarda aprovação de um administrador.';

        $this->redirect('/login');
    }

}
