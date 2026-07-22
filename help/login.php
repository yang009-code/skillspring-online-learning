<?php
/* File: login.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Log In';
$metaDescription = 'Help page for how to log in.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Log In</h1>
    <ol><li>Open Login from the menu.</li><li>Enter the username and password used during registration.</li><li>Press Login.</li><li>If the account is disabled, use Contact.</li><li>Use Logout on a shared computer.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
