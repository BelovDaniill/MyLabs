<?php
declare(strict_types=1);

/** Handles registration, login and logout. */
class AuthController
{
    /** Displays the registration form. */
    public function registerForm(): void { render('auth/register', ['title'=>'Регистрация']); }

    /** Validates and creates a new user account. */
    public function register(): void
    {
        Auth::verifyCsrf();
        $username=trim((string)($_POST['username']??''));
        $email=trim((string)($_POST['email']??''));
        $password=(string)($_POST['password']??'');
        $errors=[];
        if (mb_strlen($username)<3 || mb_strlen($username)>40) $errors[]='Имя пользователя должно содержать от 3 до 40 символов.';
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Укажите корректный email.';
        if (strlen($password)<8) $errors[]='Пароль должен содержать минимум 8 символов.';
        if (User::findByEmail($email)) $errors[]='Этот email уже зарегистрирован.';
        if ($errors) { render('auth/register',['title'=>'Регистрация','errors'=>$errors]); return; }
        try {
            User::create($username,$email,$password);
            flash('success','Аккаунт создан. Теперь войдите в систему.');
            redirect('/login');
        } catch (PDOException $e) {
            render('auth/register',['title'=>'Регистрация','errors'=>['Не удалось создать аккаунт. Проверьте введённые данные.']]);
        }
    }

    /** Displays the login form. */
    public function loginForm(): void { render('auth/login',['title'=>'Вход']); }

    /** Authenticates a user and starts a session. */
    public function login(): void
    {
        Auth::verifyCsrf();
        $email=trim((string)($_POST['email']??''));
        $password=(string)($_POST['password']??'');
        $user=User::findByEmail($email);
        if (!$user || !password_verify($password,$user['password'])) {
            render('auth/login',['title'=>'Вход','errors'=>['Неверный email или пароль.']]);
            return;
        }
        Auth::login($user);
        redirect('/notes');
    }

    /** Logs out the current user. */
    public function logout(): void
    {
        Auth::verifyCsrf();
        Auth::logout();
        redirect('/');
    }
}
