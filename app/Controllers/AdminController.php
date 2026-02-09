<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;

class AdminController extends Controller {

    public function users() {

        Auth::requireRole(['Administrador']);

        $users = (new \App\Models\User())->pendingUsers();
        $roles = (new \App\Models\Role())->all();

        $this->view('admin/users', [
            'title' => 'Aprovação de Utilizadores',
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function approveUser() {

        Auth::requireRole(['Administrador']);

        if (empty($_POST['id']) || empty($_POST['role_id'])) {
            $_SESSION['flash_error'] = 'Selecione um role.';
            return $this->redirect('/admin/utilizadores');
        }

        $id = (int)$_POST['id'];
        $roleId = (int)$_POST['role_id'];
        $roleModel = new \App\Models\Role();

        if (!$roleModel->exists($roleId)) {
            $_SESSION['flash_error'] = 'Role inválido.';
            return $this->redirect('/admin/utilizadores');
        }
        (new \App\Models\User())->approve($id, $roleId);

        \Core\Logger::log('approve_user', 'user', $id);

        $_SESSION['flash_success'] = 'Utilizador aprovado com sucesso.';

        $this->redirect('/admin/utilizadores');
    }

    public function rejectUser() {

        Auth::requireRole(['Administrador']);

        if (empty($_POST['id'])) {
            $_SESSION['flash_error'] = 'Utilizador inválido.';
            return $this->redirect('/admin/utilizadores');
        }

        $id = (int)$_POST['id'];

        (new \App\Models\User())->reject($id);

        \Core\Logger::log('reject_user', 'user', $id);

        $_SESSION['flash_success'] = 'Pedido rejeitado com sucesso.';

        $this->redirect('/admin/utilizadores');
    }

    
}
