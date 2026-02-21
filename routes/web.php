<?php
$router->get('/', 'DocumentoController@index');

$router->get('/login', 'AuthController@show');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

$router->get('/documentos', 'DocumentoController@index');
$router->get('/documentos/upload', 'DocumentoController@create');
$router->post('/documentos/upload', 'DocumentoController@store');
$router->get('/documentos/novo', 'DocumentoController@create');
$router->post('/documentos', 'DocumentoController@store');
$router->get('/documentos/download', 'DocumentoController@download');
$router->get('/documentos/ver', 'DocumentoController@show');
$router->get('/documentos/nova-versao', 'DocumentoController@novaVersao');
$router->post('/documentos/nova-versao', 'DocumentoController@storeNovaVersao');
$router->post('/documentos/comentar', 'DocumentoController@comentar');
$router->post('/documentos/tags', 'DocumentoController@addTag');
$router->get('/documentos/preview', 'DocumentoController@preview');

$router->get('/admin/users', 'AdminController@index');
$router->get('/admin/utilizadores','AdminController@users');
$router->post('/admin/utilizadores/aprovar','AdminController@approveUser');
$router->get('/admin/localizacao','LocalizacaoController@index');
$router->post('/admin/zonas/criar','LocalizacaoController@createZona');
$router->post('/admin/edificios/criar','LocalizacaoController@createEdificio');
$router->post('/admin/salas/criar','LocalizacaoController@createSala');
$router->post('/admin/zonas/editar','LocalizacaoController@updateZona');
$router->post('/admin/zonas/apagar','LocalizacaoController@deleteZona');

$router->post('/admin/edificios/editar','LocalizacaoController@updateEdificio');
$router->post('/admin/edificios/apagar','LocalizacaoController@deleteEdificio');

$router->post('/admin/salas/editar','LocalizacaoController@updateSala');
$router->post('/admin/salas/apagar','LocalizacaoController@deleteSala');


$router->post('/2fa','AuthController@verify2fa');
$router->post('/perfil/2fa/ocultar','PerfilController@hide2faBanner');


$router->get('/registo','AuthController@registerForm');
$router->post('/registo','AuthController@register');

$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');


$router->get('/perfil/seguranca','PerfilController@seguranca');
$router->post('/perfil/2fa/iniciar','PerfilController@iniciar2fa');
$router->post('/perfil/2fa/confirmar','PerfilController@confirmar2fa');

$router->get('/api/edificios','ApiController@edificiosPorZona');
$router->get('/api/salas','ApiController@salasPorEdificio');

$router->get('/documentos/editar', 'DocumentoController@edit');
$router->post('/documentos/editar', 'DocumentoController@update');

$router->post('/documentos/apagar','DocumentoController@delete');

$router->post('/admin/utilizadores/rejeitar', 'AdminController@rejectUser');

$router->get('/password/forgot', 'PasswordController@forgot');
$router->post('/password/send', 'PasswordController@send');
$router->get('/password/reset', 'PasswordController@reset');
$router->post('/password/update', 'PasswordController@update');


