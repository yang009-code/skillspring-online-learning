<?php
/* File: index.php - public home page. */
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Home';
$metaDescription = 'Browse SkillSpring courses in programming, web development, databases and career skills.';

$featuredCourses = array();
if ($pdo) {
    $sql = "SELECT * FROM courses WHERE status = 'active' ORDER BY course_id LIMIT 6";
    $featuredCourses = $pdo->query($sql)->fetchAll();
}
require __DIR__ . '/includes/header.php';
?>

<!-- Main introduction -->
<section class="hero">
    <div>
        <span class="badge">PHP and MySQL Project</span>
        <h1>Learn practical skills at your own pace.</h1>
        <p>
            SkillSpring is a small online course website. Visitors can look at courses.
            Registered users can choose course options, use the demo checkout, open lessons
            and save progress.
        </p>
        <div class="button-row">
            <a class="btn" href="<?= BASE_URL ?>/courses.php">Browse Courses</a>
            <a class="btn secondary" href="<?= BASE_URL ?>/register.php">Create Account</a>
        </div>
    </div>
    <img class="hero-image" src="<?= BASE_URL ?>/images/courses/course03.png"
         alt="Responsive web design course cover">
</section>

<h2 class="section-title">Featured Courses</h2>

<?php if ($featuredCourses) { ?>
<section class="card-grid">
    <?php foreach ($featuredCourses as $course) { ?>
    <article class="card course-card">
        <img src="<?= BASE_URL ?>/<?= e($course['image']) ?>"
             alt="<?= e($course['course_name']) ?> course cover">
        <p><span class="badge"><?= e($course['category']) ?></span></p>
        <h3><?= e($course['course_name']) ?></h3>
        <p><?= e($course['short_description']) ?></p>
        <p class="price">From <?= money($course['base_price']) ?></p>
        <a class="btn" href="<?= BASE_URL ?>/course-details.php?id=<?= (int)$course['course_id'] ?>">View Course</a>
    </article>
    <?php } ?>
</section>
<?php } else { ?>
<section class="card">
    <h2>Database setup needed</h2>
    <p>The page is working, but the course data cannot be loaded yet.</p>
</section>
<?php } ?>

<section class="two-column section-title">
    <article class="card">
        <h2>Public Area</h2>
        <p>Visitors can browse, search, view details, watch previews and use the contact form.</p>
    </article>
    <article class="card">
        <h2>Private Area</h2>
        <p>Logged-in users can buy demo courses, open lessons, save progress and write reviews.</p>
    </article>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
