<?php
/* File: purchase.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Purchase a Course';
$metaDescription = 'Help page for how to purchase a course.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Purchase a Course</h1>
    <ol><li>Open a course detail page.</li><li>Choose an access plan and support plan.</li><li>Add the course to the cart.</li><li>Change an option in the cart if needed.</li><li>Log in and use the demo checkout.</li><li>Do not enter real banking information.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
