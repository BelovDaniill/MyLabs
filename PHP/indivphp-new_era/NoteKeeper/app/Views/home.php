<section class="p-5 mb-4 bg-light rounded-3">
<h1 class="display-5 fw-bold">Ваши заметки находятся в одном месте</h1>
<p class="col-md-8 fs-5">Создавайте записи о местах, персонажах, событиях и любых идеях. Организуйте информацию и быстро находите нужное.</p>
<?php if (Auth::check()): ?><a class="btn btn-primary btn-lg" href="/notes">Перейти к заметкам</a><?php else: ?><a class="btn btn-primary btn-lg" href="/register">Начать работу</a><?php endif; ?>
</section>
<div class="row g-3 mb-4">
<div class="col-md-6"><div class="card stat-card"><div class="card-body"><div class="text-secondary">Пользователей</div><div class="display-6"><?= (int)$stats['users'] ?></div></div></div></div>
<div class="col-md-6"><div class="card stat-card"><div class="card-body"><div class="text-secondary">Всего заметок</div><div class="display-6"><?= (int)$stats['notes'] ?></div></div></div></div>
</div>
<h2 class="h4 mb-3">Последние публичные заметки</h2>
<?php if (!$latest): ?><p class="text-secondary">Публичных заметок пока нет.</p><?php else: ?>
<div class="row g-3"><?php foreach ($latest as $note): ?><div class="col-md-4"><div class="card h-100"><div class="card-body"><span class="badge text-bg-secondary"><?= Validator::e($note['type']) ?></span><h3 class="h5 mt-2"><?= Validator::e($note['title']) ?></h3><p><?= Validator::e($note['description']) ?></p><small class="text-secondary">Автор: <?= Validator::e($note['username']) ?></small></div></div></div><?php endforeach; ?></div>
<?php endif; ?>
