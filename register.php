<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
if (logged_in()) {
    header('Location: dashboard.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (strlen($username) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        flash('error', 'Use a username of 3+ characters, a valid email, and a password of 6+ characters.');
    } else {
        try {
            db()->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)')->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
            flash('success', 'Account created. Please sign in.');
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            flash('error', 'That username or email is already registered.');
        }
    }
}
$page_title = 'Create account';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-card">
    <div>
        <p class="eyebrow">WELCOME TO HOSPITALCARE</p>
        <h1>Create your account</h1>
        <p>Register once to manage hospital records securely.</p>
    </div>
    <form method="post" class="form-grid"><label>Username<input name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"></label><label>Email<input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></label><label>Password<input type="password" name="password" minlength="6" required></label><button>Create account</button></form>
    <p>Already registered? <a href="login.php">Sign in</a>.</p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>