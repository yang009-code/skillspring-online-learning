<?php
/* File: contact.php - saves a visitor message in MySQL. */
require_once __DIR__ . '/includes/bootstrap.php';
$messageText = '';
$errorText = '';
$user = currentUser();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    requireDatabase($pdo);
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($fullName == '' || $subject == '' || $message == '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorText = 'Please complete the form and enter a valid email.';
    } else {
        $userId = $user ? $user['user_id'] : null;
        $sql = "INSERT INTO messages (user_id, full_name, email, subject, message, status)
                VALUES (?, ?, ?, ?, ?, 'New')";
        $statement = $pdo->prepare($sql);
        $statement->execute(array($userId, $fullName, $email, $subject, $message));
        $messageText = 'Your message was saved for the administrator.';
    }
}

$pageTitle = 'Contact';
$metaDescription = 'Contact SkillSpring support.';
require __DIR__ . '/includes/header.php';
?>
<section class="two-column">
    <article class="card">
        <h1>Contact SkillSpring</h1>
        <p>Use this form for a course, order or account question.</p>
        <?php if ($messageText != '') { ?><p class="flash success"><?= e($messageText) ?></p><?php } ?>
        <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
        <form class="styled-form" method="post">
            <label for="full_name">Full Name</label>
            <input id="full_name" name="full_name" type="text" value="<?= $user ? e($user['full_name']) : '' ?>" required>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= $user ? e($user['email']) : '' ?>" required>
            <label for="subject">Subject</label>
            <input id="subject" name="subject" type="text" maxlength="150" required>
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="7" required></textarea>
            <button class="btn" type="submit">Send Message</button>
        </form>
    </article>
    <aside class="card">
        <h2>Need Help?</h2>
        <p>The help pages explain the main parts of the site.</p>
        <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Open Help</a>
    </aside>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
