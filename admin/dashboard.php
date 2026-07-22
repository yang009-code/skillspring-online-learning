<?php
/* File: admin/dashboard.php - main administrator page. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
$courseCount = $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
$userCount = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$orderCount = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$newMessageCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE status = 'New'")->fetchColumn();
$pageTitle = 'Admin Dashboard';
$metaDescription = 'SkillSpring administrator dashboard.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<h1>Administrator Dashboard</h1>
<section class="card-grid admin-summary">
    <article class="card"><h2><?= (int)$courseCount ?></h2><p>Course Records</p></article>
    <article class="card"><h2><?= (int)$userCount ?></h2><p>User Accounts</p></article>
    <article class="card"><h2><?= (int)$orderCount ?></h2><p>Orders</p></article>
    <article class="card"><h2><?= (int)$newMessageCount ?></h2><p>New Messages</p></article>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
