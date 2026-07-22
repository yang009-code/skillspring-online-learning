<?php
/* File: update-cart.php - recalculates cart prices after option changes. */
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('cart.php');
}
requireDatabase($pdo);
$accessPlans = isset($_POST['access_plan']) ? $_POST['access_plan'] : array();
$supportPlans = isset($_POST['support_plan']) ? $_POST['support_plan'] : array();

foreach (cartItems() as $courseId => $item) {
    $accessPlan = isset($accessPlans[$courseId]) ? trim($accessPlans[$courseId]) : $item['access_plan'];
    $supportPlan = isset($supportPlans[$courseId]) ? trim($supportPlans[$courseId]) : $item['support_plan'];
    $price = priceFor($pdo, $courseId, $accessPlan, $supportPlan);
    if ($price !== null) {
        $_SESSION['cart'][$courseId]['access_plan'] = $accessPlan;
        $_SESSION['cart'][$courseId]['support_plan'] = $supportPlan;
        $_SESSION['cart'][$courseId]['price'] = $price;
    }
}
setFlash('success', 'The cart was updated.');
redirect('cart.php');
?>
