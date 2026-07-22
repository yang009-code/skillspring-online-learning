<?php
/* File: order-history.php - lists orders made by the current user. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);
$user = currentUser();
$statement = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC');
$statement->execute(array($user['user_id']));
$orders = $statement->fetchAll();
$pageTitle = 'Order History';
$metaDescription = 'View previous SkillSpring orders.';
require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1>Order History</h1>
    <div class="table-wrap">
        <table class="styled-table">
            <thead><tr><th>Order</th><th>Date</th><th>Payment Method</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $order) { ?>
                <tr><td>#<?= (int)$order['order_id'] ?></td><td><?= e($order['order_date']) ?></td><td><?= e($order['payment_method']) ?></td><td><?= e($order['order_status']) ?></td><td><?= money($order['total_amount']) ?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if (!$orders) { ?><p>No orders have been placed yet.</p><?php } ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
