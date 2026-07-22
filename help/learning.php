<?php
/* File: learning.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Open Lessons';
$metaDescription = 'Help page for how to open lessons.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Open Lessons</h1>
    <ol><li>Finish the demo checkout.</li><li>Open My Courses.</li><li>Press Open Lessons.</li><li>Play the course video.</li><li>Use Mark Complete after a lesson.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
