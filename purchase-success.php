<?php
/* File: purchase-success.php - confirms that a demo order was saved. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
$orderId = 0;
if (isset($_SESSION['last_order_id'])) {
    $orderId = (int)$_SESSION['last_order_id'];
    unset($_SESSION['last_order_id']);
}
$pageTitle = 'Purchase Complete';
$metaDescription = 'SkillSpring purchase confirmation.';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>Purchase Complete</h1>
    <p>Your demonstration order was saved.</p>
    <?php if ($orderId > 0) { ?><p><strong>Order Number:</strong> <?= $orderId ?></p><?php } ?>
    <div class="button-row">
        <a class="btn" href="<?= BASE_URL ?>/my-courses.php">Open My Courses</a>
        <a class="btn secondary" href="<?= BASE_URL ?>/order-history.php">Order History</a>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
