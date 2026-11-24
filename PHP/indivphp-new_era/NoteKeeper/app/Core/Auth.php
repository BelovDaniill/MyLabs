<?php
declare(strict_types=1);

/** Authentication, authorization and CSRF helpers. */
class Auth
{
    /** Returns the currently authenticated user or null. */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /** Returns whether a user is authenticated. */
    public static function check(): bool
    {
        return self::user() !== null;
    }

    /** Requires authentication and redirects guests to the login page. */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('error', 'Сначала войдите в систему.');
            redirect('/login');
        }
    }

    /** Requires the administrator role. */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if ((self::user()['role'] ?? '') !== 'admin') {
            http_response_code(403);
            render('errors/403', ['title' => 'Доступ запрещён']);
            exit;
        }
    }

    /** Regenerates the session identifier after authentication. */
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }

    /** Clears the authenticated session. */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    /** Returns a CSRF token, creating one if necessary. */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    /** Validates the submitted CSRF token and stops the request if invalid. */
    public static function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!is_string($token) || !hash_equals(self::csrfToken(), $token)) {
            http_response_code(419);
            exit('Недействительный токен безопасности. Обновите страницу и попробуйте снова.');
        }
    }
}
