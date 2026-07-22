<?php
/* File: admin.php - context help page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$pageTitle = 'Administrator Help';
$metaDescription = 'Help page for administrator help.';
require dirname(__DIR__) . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>Administrator Help</h1>
    <ol><li>Log in with an administrator account.</li><li>Use Courses to add, edit or deactivate a course.</li><li>Use Users to change a role or disable an account.</li><li>Use Orders to change order status.</li><li>Use Messages to save a reply.</li><li>Use Report to view the graph.</li><li>Use Themes to change the website template.</li><li>Use Monitor to check project services.</li></ol>
    <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
