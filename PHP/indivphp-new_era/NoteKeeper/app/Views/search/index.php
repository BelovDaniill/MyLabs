<h1 class="h3 mb-3">Поиск заметок</h1>
<form method="get" action="/search" class="card card-body mb-4">
<div class="row g-3"><div class="col-md-5"><label class="form-label">Название или текст</label><input class="form-control" name="q" value="<?= Validator::e($q) ?>" placeholder="Введите поисковый запрос"></div>
<div class="col-md-3"><label class="form-label">Тип</label><select class="form-select" name="type"><option value="">Все типы</option><?php foreach (['general'=>'Общая','place'=>'Место','character'=>'Персонаж','event'=>'Событие'] as $k=>$v): ?><option value="<?= $k ?>" <?= $type===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
<div class="col-md-3"><label class="form-label">Дата создания</label><input class="form-control" type="date" name="date" value="<?= Validator::e($date) ?>"></div>
<div class="col-md-1 d-flex align-items-end"><button class="btn btn-primary w-100">Найти</button></div></div>
</form>
<?php if (!$notes): ?><p class="text-secondary">Ничего не найдено.</p><?php else: ?><div class="list-group"><?php foreach ($notes as $note): ?><a class="list-group-item list-group-item-action" href="/notes/show?id=<?= (int)$note['id'] ?>"><strong><?= Validator::e($note['title']) ?></strong><span class="badge text-bg-secondary ms-2"><?= Validator::e($note['type']) ?></span><div class="small text-secondary"><?= Validator::e($note['description']) ?></div></a><?php endforeach; ?></div><?php endif; ?>
