<h1 class="h3 mb-4">Администрирование</h1>
<?php require BASE_PATH.'/app/Views/partials/errors.php'; ?>
<section class="card card-body mb-4">
    <h2 class="h5">Создать администратора</h2>
    <form method="post" action="/admin/create" class="row g-2">
        <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
        <div class="col-md-3"><input class="form-control" name="username" placeholder="Имя пользователя" minlength="3"></div>
        <div class="col-md-3"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
        <div class="col-md-3"><input class="form-control" type="password" name="password" placeholder="Пароль (от 8 символов)" required minlength="8"></div>
        <div class="col-md-3"><button class="btn btn-primary w-100">Создать</button></div>
    </form>
</section>
<section class="mb-5">
    <h2 class="h5">Пользователи</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Новый пароль</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <form method="post" action="/admin/user/update">
                            <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
                            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                            <td><?= (int)$u['id'] ?></td>
                            <td><input class="form-control form-control-sm" name="username" value="<?= Validator::e($u['username']) ?>"></td>
                            <td><input class="form-control form-control-sm" type="email" name="email" value="<?= Validator::e($u['email']) ?>"></td>
                            <td>
                                <select class="form-select form-select-sm" name="role">
                                    <option value="user" <?= $u['role']==='user'?'selected':'' ?>>user</option>
                                    <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option>
                                </select>
                            </td>
                            <td><input class="form-control form-control-sm" type="password" name="password" placeholder="Без изменений"></td>
                            <td class="d-flex gap-1"><button class="btn btn-sm btn-primary">Сохранить</button></form><form method="post" action="/admin/user/delete" onsubmit="return confirm('Удалить пользователя вместе с заметками?')"><input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="btn btn-sm btn-outline-danger" <?= (int)$u['id']===(int)Auth::user()['id']?'disabled':'' ?>>Удалить</button></form></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<section><h2 class="h5">Все заметки</h2><div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>Название</th><th>Тип</th><th>Автор</th><th></th></tr></thead><tbody><?php foreach ($notes as $n): ?><tr><td><?= (int)$n['id'] ?></td><td><?= Validator::e($n['title']) ?></td><td><?= Validator::e($n['type']) ?></td><td><?= Validator::e($n['username']) ?></td><td class="d-flex gap-2"><a class="btn btn-sm btn-outline-secondary" href="/notes/edit?id=<?= (int)$n['id'] ?>">Изменить</a><form method="post" action="/admin/note/delete" onsubmit="return confirm('Удалить заметку?')"><input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>"><input type="hidden" name="id" value="<?= (int)$n['id'] ?>"><button class="btn btn-sm btn-outline-danger">Удалить</button></form></td></tr><?php endforeach; ?></tbody></table></div></section>
