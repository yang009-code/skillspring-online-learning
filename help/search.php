<?php
/* File: search.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'How to Search';
$metaDescription = 'Help page for how to search.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>How to Search</h1>
    <ol><li>Open Courses or Search.</li><li>Type a title, category, instructor or keyword.</li><li>Press Search.</li><li>Open a result to see the course.</li><li>Categories can also be used to browse.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
