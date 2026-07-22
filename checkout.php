<?php
/* File: checkout.php - practice checkout without real payment information. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);
$items = cartItems();
$user = currentUser();
$errorText = '';

if (!$items) {
    setFlash('error', 'Add a course before checkout.');
    redirect('courses.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    $allowedMethods = array('Credit Card Demo', 'PayPal Demo', 'Student Account Demo');

    if ($fullName == '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($paymentMethod, $allowedMethods)) {
        $errorText = 'Complete the form and choose a demo payment method.';
    } else {
        $total = 0;
        foreach ($items as $item) {
            $total += priceFor($pdo, $item['course_id'], $item['access_plan'], $item['support_plan']);
        }

        try {
            $pdo->beginTransaction();
            $sql = "INSERT INTO orders (user_id, customer_name, customer_email, payment_method, total_amount, order_status)
                    VALUES (?, ?, ?, ?, ?, 'Completed')";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($user['user_id'], $fullName, $email, $paymentMethod, $total));
            $orderId = (int)$pdo->lastInsertId();

            $itemSql = "INSERT INTO order_items (order_id, course_id, access_plan, support_plan, unit_price)
                        VALUES (?, ?, ?, ?, ?)";
            $itemStatement = $pdo->prepare($itemSql);

            foreach ($items as $item) {
                $verifiedPrice = priceFor($pdo, $item['course_id'], $item['access_plan'], $item['support_plan']);
                $itemStatement->execute(array($orderId, $item['course_id'], $item['access_plan'], $item['support_plan'], $verifiedPrice));

                $expiryDate = null;
                if ($item['access_plan'] == '30-Day Access') {
                    $expiryDate = date('Y-m-d', strtotime('+30 days'));
                } elseif ($item['access_plan'] == '90-Day Access') {
                    $expiryDate = date('Y-m-d', strtotime('+90 days'));
                }

                /* Reuse an old enrollment if the user bought the same course before. */
                $check = $pdo->prepare('SELECT enrollment_id FROM enrollments WHERE user_id = ? AND course_id = ?');
                $check->execute(array($user['user_id'], $item['course_id']));
                $oldEnrollment = $check->fetch();

                if ($oldEnrollment) {
                    $sql = "UPDATE enrollments SET order_id = ?, access_plan = ?, enrollment_date = CURDATE(), expiry_date = ?
                            WHERE enrollment_id = ?";
                    $enroll = $pdo->prepare($sql);
                    $enroll->execute(array($orderId, $item['access_plan'], $expiryDate, $oldEnrollment['enrollment_id']));
                } else {
                    $sql = "INSERT INTO enrollments (user_id, course_id, order_id, access_plan, enrollment_date, expiry_date)
                            VALUES (?, ?, ?, ?, CURDATE(), ?)";
                    $enroll = $pdo->prepare($sql);
                    $enroll->execute(array($user['user_id'], $item['course_id'], $orderId, $item['access_plan'], $expiryDate));
                }
            }

            $pdo->commit();
            unset($_SESSION['cart']);
            $_SESSION['last_order_id'] = $orderId;
            redirect('purchase-success.php');
        } catch (Exception $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errorText = 'The order could not be saved.';
        }
    }
}

$total = 0;
foreach ($items as $item) {
    $total += (float)$item['price'];
}
$pageTitle = 'Checkout';
$metaDescription = 'Complete a SkillSpring demonstration checkout.';
require __DIR__ . '/includes/header.php';
?>
<section class="two-column">
    <article class="card">
        <h1>Checkout</h1>
        <p class="flash warning">This is only a demo. Do not enter a real card number or banking password.</p>
        <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
        <form class="styled-form" method="post">
            <label for="full_name">Customer Name</label>
            <input id="full_name" name="full_name" type="text" value="<?= e($user['full_name']) ?>" required>
            <label for="email">Customer Email</label>
            <input id="email" name="email" type="email" value="<?= e($user['email']) ?>" required>
            <label for="payment_method">Demo Payment Method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">Choose one</option>
                <option>Credit Card Demo</option>
                <option>PayPal Demo</option>
                <option>Student Account Demo</option>
            </select>
            <button class="btn" type="submit">Complete Purchase</button>
        </form>
    </article>
    <aside class="card">
        <h2>Order Summary</h2>
        <ul><?php foreach ($items as $item) { ?><li><?= e($item['course_name']) ?> — <?= money($item['price']) ?></li><?php } ?></ul>
        <p class="price">Total: <?= money($total) ?></p>
        <a href="<?= BASE_URL ?>/help/purchase.php">Checkout Help</a>
    </aside>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
