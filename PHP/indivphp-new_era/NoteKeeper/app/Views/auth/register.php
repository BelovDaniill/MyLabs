<div class="form-card">
    <h1 class="h3 mb-3">Регистрация</h1>
    <?php require BASE_PATH.'/app/Views/partials/errors.php'; ?>
    <form method="post" action="/register" class="needs-validation" novalidate>
    <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
    <div class="mb-3">
        <label class="form-label">Имя пользователя</label>
        <input class="form-control" name="username" minlength="3" maxlength="40" required>
        <div class="invalid-feedback">Введите имя от 3 до 40 символов.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" required>
        <div class="invalid-feedback">Введите корректный email.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Пароль</label>
        <input class="form-control" type="password" name="password" minlength="8" required>
        <div class="invalid-feedback">Минимум 8 символов.</div>
    </div>
    <button class="btn btn-primary">Зарегистрироваться</button>
</form></div>
