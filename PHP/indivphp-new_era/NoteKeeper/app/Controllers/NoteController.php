<?php
declare(strict_types=1);

/** Handles note CRUD operations. */
class NoteController
{
    /** Displays the current user's notes. */
    public function index(): void
    {
        Auth::requireLogin();
        $type=(string)($_GET['type']??'');
        render('notes/index',['title'=>'Мои заметки','notes'=>Note::forUser((int)Auth::user()['id'],$type),'type'=>$type]);
    }

    /** Displays a note creation form. */
    public function createForm(): void
    {
        Auth::requireLogin();
        render('notes/form',['title'=>'Новая заметка','note'=>null,'details'=>[]]);
    }

    /** Validates and saves a new note. */
    public function store(): void
    {
        Auth::requireLogin(); Auth::verifyCsrf();
        [$data,$errors]=Validator::note($_POST);
        if ($errors) { render('notes/form',['title'=>'Новая заметка','note'=>$_POST,'details'=>$_POST,'errors'=>$errors]); return; }
        Note::create((int)Auth::user()['id'],$data);
        flash('success','Заметка создана.');
        redirect('/notes');
    }

    /** Displays an existing note. */
    public function show(): void
    {
        Auth::requireLogin();
        $note=Note::find((int)($_GET['id']??0));
        if (!$note || (!Note::belongsTo((int)$note['id'],(int)Auth::user()['id']) && Auth::user()['role']!=='admin')) {
            http_response_code(404); render('errors/404',['title'=>'Не найдено']); return;
        }
        render('notes/show',['title'=>$note['title'],'note'=>$note]);
    }

    /** Displays the edit form after checking ownership. */
    public function editForm(): void
    {
        Auth::requireLogin();
        $note=Note::find((int)($_GET['id']??0));
        if (!$note || (!Note::belongsTo((int)$note['id'],(int)Auth::user()['id']) && Auth::user()['role']!=='admin')) {
            http_response_code(404); render('errors/404',['title'=>'Не найдено']); return;
        }
        render('notes/form',['title'=>'Редактирование заметки','note'=>$note,'details'=>$note['details']]);
    }

    /** Updates an existing note after checking ownership. */
    public function update(): void
    {
        Auth::requireLogin(); Auth::verifyCsrf();
        $id=(int)($_POST['id']??0);
        $note=Note::find($id);
        if (!$note || (!Note::belongsTo($id,(int)Auth::user()['id']) && Auth::user()['role']!=='admin')) { http_response_code(404); exit('Заметка не найдена.'); }
        [$data,$errors]=Validator::note($_POST);
        if ($errors) { render('notes/form',['title'=>'Редактирование заметки','note'=>$_POST,'details'=>$_POST,'errors'=>$errors]); return; }
        Note::update($id,$data);
        flash('success','Изменения сохранены.');
        redirect('/notes');
    }

    /** Deletes a note after checking ownership. */
    public function delete(): void
    {
        Auth::requireLogin(); Auth::verifyCsrf();
        $id=(int)($_POST['id']??0);
        if (!Note::belongsTo($id,(int)Auth::user()['id']) && Auth::user()['role']!=='admin') { http_response_code(403); exit('Нет прав на удаление.'); }
        Note::delete($id);
        flash('success','Заметка удалена.');
        redirect('/notes');
    }
}
