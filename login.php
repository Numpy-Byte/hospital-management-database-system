<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
if (logged_in()) {
    header('Location: dashboard.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';
    $q = db()->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
    $q->execute([$identity, $identity]);
    $user = $q->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    }
    flash('error', 'Incorrect username/email or password.');
}
$page_title = 'Sign in';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-card">
    <div>
        <p class="eyebrow">HOSPITAL MANAGEMENT SYSTEM</p>
        <h1>Sign in</h1>
        <p>Access the patient and hospital record system.</p>
    </div>
    <form method="post" class="form-grid"><label>Username or email<input name="identity" required autofocus></label><label>Password<input type="password" name="password" required></label><button>Sign in</button></form>
    <p>New here? <a href="register.php">Create an account</a>.</p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>