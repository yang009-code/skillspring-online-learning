<?php
/* File: admin/delete-course.php - changes a course to inactive. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('admin/courses.php');
}

$courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

if ($courseId > 0) {
    $statement = $pdo->prepare(
        "UPDATE courses SET status = 'inactive' WHERE course_id = ?"
    );
    $statement->execute(array($courseId));
    setFlash('success', 'The course was changed to inactive.');
} else {
    setFlash('error', 'The course could not be changed.');
}

redirect('admin/courses.php');
?>
