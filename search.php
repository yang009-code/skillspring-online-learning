<?php
/* File: search.php - searches the course table. */
require_once __DIR__ . '/includes/bootstrap.php';
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = array();

if ($pdo && $query != '') {
    $sql = "SELECT * FROM courses WHERE status = 'active' AND
            (course_name LIKE ? OR category LIKE ? OR instructor LIKE ? OR description LIKE ?)
            ORDER BY course_name";
    $keyword = '%' . $query . '%';
    $statement = $pdo->prepare($sql);
    $statement->execute(array($keyword, $keyword, $keyword, $keyword));
    $results = $statement->fetchAll();
}

$pageTitle = 'Search';
$metaDescription = 'Search the SkillSpring course catalogue.';
require __DIR__ . '/includes/header.php';
?>

<section class="card">
    <h1>Search Courses</h1>
    <form class="search-bar" method="get">
        <input type="search" name="q" value="<?= e($query) ?>" placeholder="Title, category, instructor or keyword" required>
        <button class="btn" type="submit">Search</button>
    </form>
    <p><a href="<?= BASE_URL ?>/help/search.php">Search Help</a></p>
</section>

<?php if ($query != '') { ?>
<h2>Results for “<?= e($query) ?>”</h2>
<section class="card-grid">
    <?php foreach ($results as $course) { ?>
    <article class="card course-card">
        <img src="<?= BASE_URL ?>/<?= e($course['image']) ?>" alt="<?= e($course['course_name']) ?> course cover">
        <h3><?= e($course['course_name']) ?></h3>
        <p><?= e($course['short_description']) ?></p>
        <a class="btn" href="<?= BASE_URL ?>/course-details.php?id=<?= (int)$course['course_id'] ?>">View Course</a>
    </article>
    <?php } ?>
</section>
<?php if (!$results) { ?><p class="card">No matching courses were found.</p><?php } ?>
<?php } ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
