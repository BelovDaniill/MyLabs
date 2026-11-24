<div class="form-card"><h1 class="h3 mb-3">Вход</h1>
<?php require BASE_PATH.'/app/Views/partials/errors.php'; ?>
<form method="post" action="/login" class="needs-validation" novalidate>
<input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
<div class="mb-3"><label class="form-label">Пароль</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-primary">Войти</button>
</form></div>
