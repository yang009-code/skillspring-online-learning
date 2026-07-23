<?php
/* File: learn.php - shows lessons only to a user with active course access. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);

$user = currentUser();
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* The order must still be completed and the access date must be valid. */
$sql = "SELECT c.*
        FROM enrollments e
        INNER JOIN courses c ON c.course_id = e.course_id
        INNER JOIN orders o ON o.order_id = e.order_id
        WHERE e.user_id = ?
          AND e.course_id = ?
          AND o.order_status = 'Completed'
          AND (e.expiry_date IS NULL OR e.expiry_date >= CURDATE())
        LIMIT 1";

$statement = $pdo->prepare($sql);
$statement->execute(array($user['user_id'], $courseId));
$course = $statement->fetch();

if (!$course) {
    setFlash('error', 'This course is not available. The order may be cancelled or the access time may have ended.');
    redirect('my-courses.php');
}

$sql = "SELECT l.*, p.completed
        FROM lessons l
        LEFT JOIN progress p
          ON p.lesson_id = l.lesson_id
         AND p.user_id = ?
        WHERE l.course_id = ?
        ORDER BY l.lesson_order";

$statement = $pdo->prepare($sql);
$statement->execute(array($user['user_id'], $courseId));
$lessons = $statement->fetchAll();

$pageTitle = 'Learn: ' . $course['course_name'];
$metaDescription = 'Open course lessons and save progress.';
require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1><?= e($course['course_name']) ?></h1>
    <p><?= e($course['short_description']) ?></p>
    <p><a href="<?= BASE_URL ?>/help/progress.php">Progress Help</a></p>
</section>

<?php foreach ($lessons as $lesson) { ?>
<article class="card section-title">
    <h2><?= (int)$lesson['lesson_order'] ?>. <?= e($lesson['lesson_title']) ?></h2>
    <p><?= e($lesson['lesson_description']) ?></p>

    <video controls preload="metadata" width="640">
        <source src="<?= BASE_URL ?>/<?= e($lesson['video_file']) ?>" type="video/mp4">
        Your browser does not support MP4 video.
    </video>

    <form class="inline-form" action="<?= BASE_URL ?>/progress.php" method="post">
        <input type="hidden" name="course_id" value="<?= $courseId ?>">
        <input type="hidden" name="lesson_id" value="<?= (int)$lesson['lesson_id'] ?>">
        <button class="btn <?= $lesson['completed'] ? 'secondary' : '' ?>" type="submit">
            <?= $lesson['completed'] ? 'Completed - Mark Again' : 'Mark Complete' ?>
        </button>
    </form>
</article>
<?php } ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
