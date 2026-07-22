<?php
/* File: categories.php - groups active courses by category. */
require_once __DIR__ . '/includes/bootstrap.php';
$categories = array();
if ($pdo) {
    $categories = $pdo->query("SELECT category, COUNT(*) AS course_count FROM courses
                               WHERE status = 'active' GROUP BY category ORDER BY category")->fetchAll();
}
$pageTitle = 'Categories';
$metaDescription = 'Browse SkillSpring courses by category.';
require __DIR__ . '/includes/header.php';
?>

<h1>Course Categories</h1>
<section class="card-grid">
    <?php foreach ($categories as $category) { ?>
    <article class="card">
        <h2><?= e($category['category']) ?></h2>
        <p><?= (int)$category['course_count'] ?> available courses</p>
        <a class="btn" href="<?= BASE_URL ?>/courses.php?category=<?= urlencode($category['category']) ?>">Browse Category</a>
    </article>
    <?php } ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
