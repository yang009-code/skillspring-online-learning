<?php
/* File: remove-from-cart.php - removes one course from the cart. */
require_once __DIR__ . '/includes/bootstrap.php';
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (isset($_SESSION['cart'][$courseId])) {
    unset($_SESSION['cart'][$courseId]);
    setFlash('success', 'The course was removed from the cart.');
}
redirect('cart.php');
?>
