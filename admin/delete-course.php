<?php
/* File: admin/delete-course.php - makes a course inactive instead of deleting old order data. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$statement = $pdo->prepare("UPDATE courses SET status = 'inactive' WHERE course_id = ?");
$statement->execute(array($courseId));
setFlash('success', 'The course was changed to inactive.');
redirect('admin/courses.php');
?>
