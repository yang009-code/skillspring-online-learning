<?php
/* File: admin/orders.php - lists orders and changes order status. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
$allowedStatuses = array('Pending', 'Completed', 'Cancelled', 'Refunded');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = isset($_POST['order_status']) ? $_POST['order_status'] : '';
    if (in_array($status, $allowedStatuses)) {
        $statement = $pdo->prepare('UPDATE orders SET order_status = ? WHERE order_id = ?');
        $statement->execute(array($status, $orderId));
        setFlash('success', 'The order status was updated.');
        redirect('admin/orders.php');
    }
}
$orders = $pdo->query('SELECT o.*, u.username FROM orders o INNER JOIN users u ON u.user_id = o.user_id ORDER BY o.order_date DESC')->fetchAll();
$pageTitle = 'Manage Orders';
$metaDescription = 'Review SkillSpring orders.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card">
    <h1>Manage Orders</h1>
    <div class="table-wrap">
        <table class="styled-table">
            <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <td>#<?= (int)$order['order_id'] ?></td>
                    <td><?= e($order['customer_name']) ?><br><?= e($order['customer_email']) ?></td>
                    <td><?= e($order['order_date']) ?></td>
                    <td><?= money($order['total_amount']) ?></td>
                    <td>
                        <form class="inline-form" method="post">
                            <input type="hidden" name="order_id" value="<?= (int)$order['order_id'] ?>">
                            <select name="order_status">
                                <?php foreach ($allowedStatuses as $status) { ?>
                                <option value="<?= e($status) ?>"<?= selected($status, $order['order_status']) ?>><?= e($status) ?></option>
                                <?php } ?>
                            </select>
                            <button class="btn secondary" type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
