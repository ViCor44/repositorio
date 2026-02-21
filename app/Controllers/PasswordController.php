<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PasswordController extends Controller {

    public function forgot() {
        $this->view('auth/forgot', ['title' => 'Recuperar Password']);
    }

    public function send() {

        $email = trim($_POST['email']);

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {

            $token = bin2hex(random_bytes(32));
            $hash  = hash('sha256', $token);
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $resetModel = new PasswordReset();
            $resetModel->create($user['id'], $hash, $expires);

            $host = 'http://191.188.126.13'; // IP fixo interno

            $link = $host . BASE_URL . "/password/reset?token=$token";
;

            $mail = new PHPMailer(true);

            try {

                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'slide.rocketchat@gmail.com';
                $mail->Password   = 'jbbo gsys gvmq bise';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('slide.rocketchat@gmail.com', 'Securo');
                $mail->addAddress($user['email']);

                $mail->isHTML(true);
                $mail->Subject = 'Reset da sua password - Securo';

                $mail->Body = "
                    <div style='font-family:Arial,sans-serif;font-size:14px;color:#333'>
                        
                        <h2 style='color:#1f2937'>Securo - Recuperação de Password</h2>

                        <p>Recebemos um pedido para redefinir a sua password.</p>

                        <p>
                            Clique no botão abaixo para continuar:
                        </p>

                        <p style='margin:25px 0'>
                            <a href='$link' style='
                                display:inline-block;
                                padding:12px 20px;
                                background:#2563eb;
                                color:white;
                                text-decoration:none;
                                border-radius:6px;
                                font-weight:bold;
                            '>
                                Redefinir Password
                            </a>
                        </p>

                        <hr style='margin:25px 0'>

                        <p style='font-size:13px;color:#555'>
                            🔒 Este é um sistema interno da empresa.<br>
                            O link apenas funciona quando estiver ligado à rede corporativa.
                        </p>

                        <p style='font-size:13px;color:#555'>
                            ⏳ O link expira em 1 hora.
                        </p>

                        <p style='font-size:13px;color:#555'>
                            Se não solicitou esta alteração, pode ignorar este email.
                        </p>

                        <p style='margin-top:30px;font-size:12px;color:#888'>
                            Securo - Sistema de Gestão Documental
                        </p>

                    </div>
                ";

                $mail->send();

            } catch (Exception $e) {
                error_log("Erro ao enviar email: " . $mail->ErrorInfo);
            }
        }

        $this->view('auth/forgot_sent', [
            'title' => 'Verifique o seu email'
        ]);
    }

    public function reset() {

        $token = $_GET['token'] ?? null;

        if (!$token) die("Token inválido");

        $hash = hash('sha256', $token);

        $resetModel = new PasswordReset();
        $record = $resetModel->findValid($hash);

        if (!$record) die("Link inválido ou expirado");

        $this->view('auth/reset', [
            'title' => 'Nova Password',
            'token' => $token
        ]);
    }

    public function update() {

        $token = $_POST['token'];
        $password = $_POST['password'];

        $hash = hash('sha256', $token);

        $resetModel = new PasswordReset();
        $record = $resetModel->findValid($hash);

        if (!$record) die("Token inválido");

        $userModel = new User();
        $userModel->updatePassword(
            $record['user_id'],
            password_hash($password, PASSWORD_DEFAULT)
        );

        $resetModel->markUsed($record['id']);

        $this->redirect('/login');
    }
}
