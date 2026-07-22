<?php
/* File: login.php - checks a username and password. */
require_once __DIR__ . '/includes/bootstrap.php';
if (isLoggedIn()) {
    redirect('profile.php');
}
$errorText = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    requireDatabase($pdo);
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $statement = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $statement->execute(array($username));
    $user = $statement->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $errorText = 'The username or password is incorrect.';
    } elseif ($user['status'] != 'active') {
        $errorText = 'This account has been disabled.';
    } else {
        $_SESSION['user'] = array(
            'user_id' => (int)$user['user_id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $user['role']
        );
        setFlash('success', 'Welcome back, ' . $user['full_name'] . '.');
        if ($user['role'] == 'admin') {
            redirect('admin/dashboard.php');
        }
        redirect('profile.php');
    }
}

$pageTitle = 'Login';
$metaDescription = 'Log in to SkillSpring.';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>Login</h1>
    <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
    <form class="styled-form" method="post">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>
        <button class="btn" type="submit">Login</button>
    </form>
    <p><a href="<?= BASE_URL ?>/help/login.php">Login Help</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
