<?php
/* File: edit-profile.php - changes the current user's name and email. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);
$user = currentUser();
$errorText = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    if ($fullName == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorText = 'Enter a name and a valid email.';
    } else {
        $statement = $pdo->prepare('SELECT user_id FROM users WHERE email = ? AND user_id <> ?');
        $statement->execute(array($email, $user['user_id']));
        if ($statement->fetch()) {
            $errorText = 'That email address is already used.';
        } else {
            $statement = $pdo->prepare('UPDATE users SET full_name = ?, email = ? WHERE user_id = ?');
            $statement->execute(array($fullName, $email, $user['user_id']));
            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['email'] = $email;
            setFlash('success', 'Your profile was updated.');
            redirect('profile.php');
        }
    }
}

$pageTitle = 'Edit Profile';
$metaDescription = 'Update a SkillSpring profile.';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>Edit Profile</h1>
    <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
    <form class="styled-form" method="post">
        <label for="full_name">Full Name</label>
        <input id="full_name" name="full_name" type="text" value="<?= e($user['full_name']) ?>" required>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= e($user['email']) ?>" required>
        <div class="button-row">
            <button class="btn" type="submit">Save Changes</button>
            <a class="btn secondary" href="<?= BASE_URL ?>/profile.php">Cancel</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
