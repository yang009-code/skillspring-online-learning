<?php
/* File: my-courses.php - lists courses that the current user can open. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);

$user = currentUser();

$sql = "SELECT e.*, c.course_name, c.short_description, c.image,
               COUNT(DISTINCT l.lesson_id) AS lesson_count,
               COUNT(DISTINCT p.progress_id) AS completed_count
        FROM enrollments e
        INNER JOIN courses c ON c.course_id = e.course_id
        INNER JOIN orders o ON o.order_id = e.order_id
        LEFT JOIN lessons l ON l.course_id = c.course_id
        LEFT JOIN progress p
          ON p.lesson_id = l.lesson_id
         AND p.user_id = e.user_id
         AND p.completed = 1
        WHERE e.user_id = ?
          AND o.order_status = 'Completed'
          AND (e.expiry_date IS NULL OR e.expiry_date >= CURDATE())
        GROUP BY e.enrollment_id, c.course_id
        ORDER BY e.enrollment_date DESC";

$statement = $pdo->prepare($sql);
$statement->execute(array($user['user_id']));
$enrollments = $statement->fetchAll();

$pageTitle = 'My Courses';
$metaDescription = 'Open enrolled courses and view progress.';
require __DIR__ . '/includes/header.php';
?>
<h1>My Courses</h1>
<p><a href="<?= BASE_URL ?>/help/learning.php">Learning Help</a></p>

<section class="card-grid">
<?php foreach ($enrollments as $enrollment) { ?>
    <?php
    $lessonCount = (int)$enrollment['lesson_count'];
    $completedCount = (int)$enrollment['completed_count'];

    if ($lessonCount < 1) {
        $lessonCount = 1;
    }

    $progressPercent = round(($completedCount / $lessonCount) * 100);

    if ($progressPercent > 100) {
        $progressPercent = 100;
    }
    ?>

    <article class="card course-card">
        <img src="<?= BASE_URL ?>/<?= e($enrollment['image']) ?>"
             alt="<?= e($enrollment['course_name']) ?> course cover">

        <h2><?= e($enrollment['course_name']) ?></h2>
        <p><?= e($enrollment['short_description']) ?></p>
        <p><strong>Access:</strong> <?= e($enrollment['access_plan']) ?></p>
        <p>
            <strong>Expiry:</strong>
            <?= $enrollment['expiry_date'] ? e($enrollment['expiry_date']) : 'No expiry' ?>
        </p>

        <div class="progress-track" aria-label="Course progress">
            <div class="progress-bar" style="width: <?= $progressPercent ?>%"></div>
        </div>
        <p><?= $progressPercent ?>% complete</p>

        <div class="button-row">
            <a class="btn" href="<?= BASE_URL ?>/learn.php?id=<?= (int)$enrollment['course_id'] ?>">
                Open Lessons
            </a>
            <a class="btn secondary" href="<?= BASE_URL ?>/review.php?id=<?= (int)$enrollment['course_id'] ?>">
                Review
            </a>
        </div>
    </article>
<?php } ?>
</section>

<?php if (!$enrollments) { ?>
<section class="card">
    <p>You do not have an active course at this time.</p>
    <a class="btn" href="<?= BASE_URL ?>/courses.php">Browse Courses</a>
</section>
<?php } ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
