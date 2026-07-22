<?php
/* File: admin/reports.php - sends enrollment data to the JavaScript canvas chart. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
$rows = $pdo->query('SELECT c.category, COUNT(e.enrollment_id) AS enrollment_count FROM courses c LEFT JOIN enrollments e ON e.course_id = c.course_id GROUP BY c.category ORDER BY c.category')->fetchAll();
$labels = array();
$values = array();
foreach ($rows as $row) {
    $labels[] = $row['category'];
    $values[] = (int)$row['enrollment_count'];
}
$pageTitle = 'Enrollment Report';
$metaDescription = 'Enrollment graph by course category.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card chart-wrap">
    <h1>Enrollments by Category</h1>
    <p>The graph uses an HTML canvas and the external JavaScript file.</p>
    <canvas id="salesChart" width="1000" height="520"
            data-labels='<?= e(json_encode($labels)) ?>'
            data-values='<?= e(json_encode($values)) ?>'>Your browser does not support canvas.</canvas>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
