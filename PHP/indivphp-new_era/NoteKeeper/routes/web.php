<?php
$auth = new AuthController();
$notes = new NoteController();
$search = new SearchController();
$admin = new AdminController();

$router->get('/', function () {
    $pdo=Database::connection();
    $stats=[
        'users'=>(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
        'notes'=>(int)$pdo->query('SELECT COUNT(*) FROM notes')->fetchColumn()
    ];
    render('home',['title'=>'NoteKeeper','stats'=>$stats,'latest'=>Note::publicLatest()]);
});
$router->get('/register', [$auth,'registerForm']);
$router->post('/register', [$auth,'register']);
$router->get('/login', [$auth,'loginForm']);
$router->post('/login', [$auth,'login']);
$router->post('/logout', [$auth,'logout']);
$router->get('/notes', [$notes,'index']);
$router->get('/notes/create', [$notes,'createForm']);
$router->post('/notes/store', [$notes,'store']);
$router->get('/notes/show', [$notes,'show']);
$router->get('/notes/edit', [$notes,'editForm']);
$router->post('/notes/update', [$notes,'update']);
$router->post('/notes/delete', [$notes,'delete']);
$router->get('/search', [$search,'index']);
$router->get('/admin', [$admin,'index']);
$router->post('/admin/create', [$admin,'createAdmin']);
$router->post('/admin/user/update', [$admin,'updateUser']);
$router->post('/admin/user/delete', [$admin,'deleteUser']);
$router->post('/admin/note/delete', [$admin,'deleteNote']);
