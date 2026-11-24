<?php
declare(strict_types=1);

/** Provides administrator-only user and note management. */
class AdminController
{
    /** Displays administrator dashboard. */
    public function index(): void
    {
        Auth::requireAdmin();
        render('admin/index',['title'=>'Администрирование','users'=>User::all(),'notes'=>Note::all()]);
    }

    /** Creates a new administrator account. */
    public function createAdmin(): void
    {
        Auth::requireAdmin(); Auth::verifyCsrf();
        $username=trim((string)($_POST['username']??''));
        $email=trim((string)($_POST['email']??''));
        $password=(string)($_POST['password']??'');
        $errors=[];
        if (mb_strlen($username)<3 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8) $errors[]='Проверьте имя (от 3 символов), email и пароль (от 8 символов).';
        if (User::findByEmail($email)) $errors[]='Пользователь с таким email уже существует.';
        if ($errors) { render('admin/index',['title'=>'Администрирование','users'=>User::all(),'notes'=>Note::all(),'errors'=>$errors]); return; }
        User::create($username,$email,$password,'admin');
        flash('success','Администратор создан.');
        redirect('/admin');
    }

    /** Updates a user's name, email, role and optional password. */
    public function updateUser(): void
    {
        Auth::requireAdmin(); Auth::verifyCsrf();
        $id=(int)($_POST['id']??0);
        $username=trim((string)($_POST['username']??''));
        $email=trim((string)($_POST['email']??''));
        $role=(string)($_POST['role']??'user');
        $password=(string)($_POST['password']??'');
        if ($id===(int)Auth::user()['id'] && $role!=='admin') { flash('error','Нельзя снять собственную роль администратора.'); redirect('/admin'); }
        if ($username==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || !in_array($role,['user','admin'],true) || ($password!=='' && strlen($password)<8)) {
            flash('error','Некорректные данные пользователя.'); redirect('/admin');
        }
        try { User::update($id,$username,$email,$role,$password); flash('success','Пользователь обновлён.'); }
        catch (PDOException $e) { flash('error','Не удалось обновить пользователя: email может быть занят.'); }
        redirect('/admin');
    }

    /** Deletes a user except the currently logged-in administrator. */
    public function deleteUser(): void
    {
        Auth::requireAdmin(); Auth::verifyCsrf();
        $id=(int)($_POST['id']??0);
        if ($id===(int)Auth::user()['id']) { flash('error','Нельзя удалить собственную учётную запись.'); redirect('/admin'); }
        User::delete($id);
        flash('success','Пользователь и его заметки удалены.');
        redirect('/admin');
    }

    /** Deletes any note as an administrator. */
    public function deleteNote(): void
    {
        Auth::requireAdmin(); Auth::verifyCsrf();
        Note::delete((int)($_POST['id']??0));
        flash('success','Заметка удалена.');
        redirect('/admin');
    }
}
