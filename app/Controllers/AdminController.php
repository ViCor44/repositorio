<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;

class AdminController extends Controller {

    public function users() {

        Auth::requireRole(['Administrador']);

        $users = (new \App\Models\User())->pendingUsers();

        $this->view('admin/users',[
            'title'=>'Aprovação de Utilizadores',
            'users'=>$users
        ]);
    }

    public function approveUser() {

        Auth::requireRole(['Administrador']);

        $id = $_POST['id'];

        (new \App\Models\User())->approve($id);

        \Core\Logger::log('approve_user','user',$id);

        $this->redirect('/admin/utilizadores');
    }
}
