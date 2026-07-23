<?php
/* File: progress.php - saves one completed lesson. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('my-courses.php');
}

$user = currentUser();
$courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
$lessonId = isset($_POST['lesson_id']) ? (int)$_POST['lesson_id'] : 0;

/* Make sure the lesson belongs to an active enrollment. */
$sql = "SELECT l.lesson_id
        FROM lessons l
        INNER JOIN enrollments e
          ON e.course_id = l.course_id
         AND e.user_id = ?
        INNER JOIN orders o ON o.order_id = e.order_id
        WHERE l.lesson_id = ?
          AND l.course_id = ?
          AND o.order_status = 'Completed'
          AND (e.expiry_date IS NULL OR e.expiry_date >= CURDATE())";

$statement = $pdo->prepare($sql);
$statement->execute(array($user['user_id'], $lessonId, $courseId));

if ($statement->fetch()) {
    $check = $pdo->prepare(
        'SELECT progress_id FROM progress WHERE user_id = ? AND lesson_id = ?'
    );
    $check->execute(array($user['user_id'], $lessonId));
    $oldProgress = $check->fetch();

    if ($oldProgress) {
        $statement = $pdo->prepare(
            'UPDATE progress SET completed = 1, completed_at = NOW() WHERE progress_id = ?'
        );
        $statement->execute(array($oldProgress['progress_id']));
    } else {
        $statement = $pdo->prepare(
            'INSERT INTO progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, NOW())'
        );
        $statement->execute(array($user['user_id'], $lessonId));
    }

    setFlash('success', 'Lesson progress was saved.');
} else {
    setFlash('error', 'The lesson could not be updated because the course access is not active.');
    redirect('my-courses.php');
}

redirect('learn.php?id=' . $courseId);
?>
