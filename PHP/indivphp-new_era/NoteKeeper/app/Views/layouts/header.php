<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= Validator::e($title ?? 'NoteKeeper') ?> — NoteKeeper</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand fw-bold" href="/">NoteKeeper</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="nav">
<ul class="navbar-nav me-auto">
<li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
<?php if (Auth::check()): ?>
<li class="nav-item"><a class="nav-link" href="/notes">Мои заметки</a></li>
<li class="nav-item"><a class="nav-link" href="/search">Поиск</a></li>
<?php if (Auth::user()['role']==='admin'): ?><li class="nav-item"><a class="nav-link" href="/admin">Администрирование</a></li><?php endif; ?>
<?php endif; ?>
</ul>
<div class="d-flex gap-2 align-items-center">
<?php if (Auth::check()): ?>
<span class="text-light small"><?= Validator::e(Auth::user()['username']) ?></span>
<form method="post" action="/logout"><input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>"><button class="btn btn-outline-light btn-sm">Выйти</button></form>
<?php else: ?>
<a class="btn btn-outline-light btn-sm" href="/login">Войти</a><a class="btn btn-primary btn-sm" href="/register">Регистрация</a>
<?php endif; ?>
</div>
</div></div></nav>
<main class="container py-4">
<?php foreach (['success','error'] as $kind): if ($message=flash($kind)): ?>
<div class="alert alert-<?= $kind==='success'?'success':'danger' ?>"><?= Validator::e($message) ?></div>
<?php endif; endforeach; ?>
