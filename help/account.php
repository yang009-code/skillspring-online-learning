<?php
/* File: account.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Manage an Account';
$metaDescription = 'Help page for how to manage an account.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Manage an Account</h1>
    <ol><li>Log in and open Profile.</li><li>Press Edit Profile to change the name or email.</li><li>Open Order History for past demo purchases.</li><li>Open My Courses for lessons and reviews.</li><li>Use Contact if the account is disabled.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
