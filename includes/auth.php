<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function logged_in(): bool
{
    return isset($_SESSION['user_id']);
}
function require_login(): void
{
    if (!logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [$type, $message];
}
function show_flash(): void
{
    if (!empty($_SESSION['flash'])) {
        [$type, $message] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="notice ' . htmlspecialchars($type) . '">' . htmlspecialchars($message) . '</div>';
    }
}
