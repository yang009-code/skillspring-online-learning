<?php
/* File: progress.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Save Progress';
$metaDescription = 'Help page for how to save progress.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Save Progress</h1>
    <ol><li>Open an enrolled course.</li><li>Review one of its lessons.</li><li>Press Mark Complete.</li><li>The progress table saves the lesson.</li><li>Return to My Courses to see the percentage.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
