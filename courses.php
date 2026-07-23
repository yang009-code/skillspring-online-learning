<?php
/* File: courses.php - lists active courses from MySQL. */
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Courses';
$metaDescription = 'Browse all SkillSpring online courses.';
$courses = array();
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

if ($pdo) {
    if ($category != '') {
        $sql = "SELECT *
                FROM courses
                WHERE status = 'active' AND category = ?
                ORDER BY course_name";

        $statement = $pdo->prepare($sql);
        $statement->execute(array($category));
        $courses = $statement->fetchAll();
    } else {
        $sql = "SELECT *
                FROM courses
                WHERE status = 'active'
                ORDER BY course_name";

        $courses = $pdo->query($sql)->fetchAll();
    }
}

require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1>Course Catalogue</h1>

    <p>
        There are <?= count($courses) ?> active courses in this section.
        Each course has an access plan and a support plan.
    </p>

    <form class="search-bar" action="<?= BASE_URL ?>/search.php" method="get">
        <input type="search" name="q"
               placeholder="Search by title, category, instructor or keyword" required>
        <button class="btn" type="submit">Search</button>
    </form>

    <p><a href="<?= BASE_URL ?>/help/search.php">Help with searching</a></p>
</section>

<section class="card-grid section-title">
    <?php foreach ($courses as $course) { ?>
    <article class="card course-card">
        <img src="<?= BASE_URL ?>/<?= e($course['image']) ?>"
             alt="<?= e($course['course_name']) ?> course cover">

        <p><span class="badge"><?= e($course['category']) ?></span></p>
        <h2><?= e($course['course_name']) ?></h2>
        <p><?= e($course['short_description']) ?></p>
        <p><strong>Instructor:</strong> <?= e($course['instructor']) ?></p>
        <p class="price">From <?= money($course['base_price']) ?></p>

        <a class="btn" href="<?= BASE_URL ?>/course-details.php?id=<?= (int)$course['course_id'] ?>">
            Details and Options
        </a>
    </article>
    <?php } ?>
</section>

<?php if (!$courses) { ?>
<section class="card">
    <p>No courses were found.</p>
</section>
<?php } ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
