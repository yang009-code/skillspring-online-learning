<?php
/* File: add-to-cart.php - saves one course in the session cart. */
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('courses.php');
}
requireDatabase($pdo);

$courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
$accessPlan = isset($_POST['access_plan']) ? trim($_POST['access_plan']) : '';
$supportPlan = isset($_POST['support_plan']) ? trim($_POST['support_plan']) : '';
$course = getCourse($pdo, $courseId);
$price = priceFor($pdo, $courseId, $accessPlan, $supportPlan);

if (!$course || $price === null) {
    setFlash('error', 'The course or options are not valid.');
    redirect('courses.php');
}

$_SESSION['cart'][$courseId] = array(
    'course_id' => $courseId,
    'course_name' => $course['course_name'],
    'image' => $course['image'],
    'access_plan' => $accessPlan,
    'support_plan' => $supportPlan,
    'price' => $price
);
setFlash('success', $course['course_name'] . ' was added to the cart.');
redirect('cart.php');
?>
