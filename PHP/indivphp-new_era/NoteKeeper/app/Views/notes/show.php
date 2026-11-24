<div class="d-flex justify-content-between align-items-start">
    <div>
        <span class="badge text-bg-secondary"><?= Validator::e($note['type']) ?></span>
        <h1 class="h2 mt-2"><?= Validator::e($note['title']) ?></h1>
        <p class="text-secondary"><?= Validator::e($note['description']) ?></p>
    </div>
    <a class="btn btn-outline-secondary" href="/notes">Назад</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="note-content">
            <?= nl2br(Validator::e($note['content'])) ?>
        </div>
    <?php if ($note['type']==='place'): ?>
        <hr>
        <p>
            <strong>Местоположение:</strong> 
            <?= Validator::e($note['details']['location']??'') ?>
        </p>
        <p>
            <strong>Тип:</strong> 
            <?= Validator::e($note['details']['place_type']??'') ?>
        </p>
        <?php endif; ?>
        <?php if ($note['type']==='character'): ?>
            <hr>
            <p>
                <strong>Пол:</strong> <?= Validator::e($note['details']['gender']??'') ?>
            </p>
            <p>
                <strong>Возраст:</strong> <?= Validator::e((string)($note['details']['age']??'')) ?>
            </p>
            <p>
                <strong>Происхождение:</strong> <?= Validator::e($note['details']['origin']??'') ?>
            </p>
        <?php endif; ?>
        <?php if ($note['type']==='event'): ?>
            <hr>
            <p><strong>Дата:</strong> <?= Validator::e($note['details']['event_date']??'') ?></p>
            <p><strong>Место:</strong> <?= Validator::e($note['details']['location']??'') ?></p>
            <p><strong>Участники:</strong> <?= Validator::e($note['details']['participants']??'') ?></p>
        <?php endif; ?>
<div class="text-secondary small mt-4">Создано: <?= Validator::e($note['created_at']) ?> · Изменено: <?= Validator::e($note['updated_at']) ?></div>
</div></div>

<div class="mt-3 d-flex gap-2">
    <a class="btn btn-primary" href="/notes/edit?id=<?= (int)$note['id'] ?>">Редактировать</a>
<form method="post" action="/notes/delete" onsubmit="return confirm('Удалить заметку?')">
    <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
    <input type="hidden" name="id" value="<?= (int)$note['id'] ?>">
    <button class="btn btn-outline-danger">Удалить</button>
</form>
</div>
