<?php
$isEdit = !empty($note['id']);
$details = $details ?? [];
$val = fn($key,$default='') => $note[$key] ?? $details[$key] ?? $default;
?>

<h1 class="h3 mb-3"><?= $isEdit?'Редактирование заметки':'Создание заметки' ?></h1>
<?php require BASE_PATH.'/app/Views/partials/errors.php'; ?>

<form method="post" action="<?= $isEdit?'/notes/update':'/notes/store' ?>" class="form-card needs-validation" novalidate id="note-form">
<input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
<?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int)$note['id'] ?>"><?php endif; ?>
<div class="mb-3">
    <label class="form-label">Название *</label>
    <input class="form-control" name="title" maxlength="150" required value="<?= Validator::e($val('title')) ?>">
    <div class="invalid-feedback">Введите название.</div>
</div>

<div class="mb-3">
    <label class="form-label">Тип заметки *</label>
    <select class="form-select" name="type" id="note-type" required>
        <?php foreach (['general'=>'Общая','place'=>'Место','character'=>'Персонаж','event'=>'Событие'] as $key=>$label): ?>
            <option value="<?= $key ?>" <?= $val('type','general')===$key?'selected':'' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Краткое описание</label>
    <textarea class="form-control" name="description" maxlength="500" rows="2"><?= Validator::e($val('description')) ?></textarea>
</div>
<div class="mb-3">
    <label class="form-label">Основное содержание *</label>
    <textarea class="form-control" name="content" rows="6" required><?= Validator::e($val('content')) ?></textarea>
    <div class="invalid-feedback">Введите содержание заметки.</div>
</div>

<div class="type-fields" data-type="place">
<h2 class="h5">Информация о месте</h2>
<div class="mb-3">
    <label class="form-label">Местоположение</label>
    <input class="form-control" name="location" value="<?= Validator::e($val('location')) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Тип места</label>
    <select class="form-select" name="place_type">
        <option value="">Не выбран</option>
        <?php foreach (['Город','Здание','Регион','Природный объект','Другое'] as $v): ?>
            <option <?= $val('place_type')===$v?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
    </select>
</div>
</div>
<div class="type-fields" data-type="character">
<h2 class="h5">Информация о персонаже</h2>
<div class="mb-3">
    <label class="form-label">Пол</label>
    <select class="form-select" name="gender">
        <option value="">Не указан</option>
        <?php foreach (['Мужской','Женский','Другой'] as $v): ?>
            <option <?= $val('gender')===$v?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Возраст</label>
    <input class="form-control" type="number" min="0" max="1000" name="age" value="<?= Validator::e((string)$val('age')) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Происхождение</label>
    <input class="form-control" name="origin" value="<?= Validator::e($val('origin')) ?>">
</div>
</div>
<div class="type-fields" data-type="event">
<h2 class="h5">Информация о событии</h2>
<div class="mb-3">
    <label class="form-label">Дата события</label>
    <input class="form-control" type="date" name="event_date" value="<?= Validator::e($val('event_date')) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Место проведения</label>
    <input class="form-control" name="location" value="<?= Validator::e($val('location')) ?>">
</div>
<div class="mb-3">
    <label class="form-label">Участники</label>
    <textarea class="form-control" name="participants" rows="2"><?= Validator::e($val('participants')) ?></textarea>
</div>
</div>
<div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_favorite" id="favorite" <?= !empty($val('is_favorite'))?'checked':'' ?>><label class="form-check-label" for="favorite">Добавить в избранное</label></div>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_public" id="public" <?= !empty($val('is_public'))?'checked':'' ?>><label class="form-check-label" for="public">Сделать заметку публичной</label></div>
<button class="btn btn-primary"><?= $isEdit?'Сохранить изменения':'Создать заметку' ?></button> <a class="btn btn-outline-secondary" href="/notes">Отмена</a>
</form>
