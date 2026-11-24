<?php
/** Renders a view inside the shared layout. */
function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require BASE_PATH . '/app/Views/layouts/header.php';
    require BASE_PATH . '/app/Views/' . $view . '.php';
    require BASE_PATH . '/app/Views/layouts/footer.php';
}

/** Stores a one-time flash message. */
function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) { $_SESSION['_flash'][$key] = $message; return null; }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

/** Redirects to a local application path. */
function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}
