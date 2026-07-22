<?php
/* File: register.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Register';
$metaDescription = 'Help page for how to register.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Register</h1>
    <ol><li>Open Register from the top menu.</li><li>Enter your name, email and a username.</li><li>Make a password with at least six characters.</li><li>Type the password again.</li><li>Press Register, then use Login.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
