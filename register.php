<?php
/* File: register.php - creates a new user account. */
require_once __DIR__ . '/includes/bootstrap.php';
if (isLoggedIn()) {
    redirect('profile.php');
}
$errorText = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    requireDatabase($pdo);
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($fullName == '' || $username == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorText = 'Enter a name, username and valid email.';
    } elseif (strlen($password) < 6) {
        $errorText = 'The password must have at least six characters.';
    } elseif ($password != $confirm) {
        $errorText = 'The two passwords do not match.';
    } else {
        /* First check if this account already exists. */
        $statement = $pdo->prepare('SELECT user_id FROM users WHERE username = ? OR email = ?');
        $statement->execute(array($username, $email));
        if ($statement->fetch()) {
            $errorText = 'That username or email is already used.';
        } else {
            $sql = "INSERT INTO users (full_name, email, username, password, role, status)
                    VALUES (?, ?, ?, ?, 'user', 'active')";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($fullName, $email, $username, password_hash($password, PASSWORD_DEFAULT)));
            setFlash('success', 'Registration is complete. Please log in.');
            redirect('login.php');
        }
    }
}

$pageTitle = 'Register';
$metaDescription = 'Create a SkillSpring account.';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>Create an Account</h1>
    <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
    <form id="registerForm" class="styled-form" method="post">
        <label for="full_name">Full Name</label>
        <input id="full_name" name="full_name" type="text" value="<?= isset($_POST['full_name']) ? e($_POST['full_name']) : '' ?>" required>
        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" value="<?= isset($_POST['email']) ? e($_POST['email']) : '' ?>" required>
        <label for="username">Username</label>
        <input id="username" name="username" type="text" value="<?= isset($_POST['username']) ? e($_POST['username']) : '' ?>" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" minlength="6" required>
        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" name="confirm_password" type="password" minlength="6" required>
        <button class="btn" type="submit">Register</button>
    </form>
    <p><a href="<?= BASE_URL ?>/help/register.php">Registration Help</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
