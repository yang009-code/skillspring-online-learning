<?php
/* File: cart.php - shows courses stored in the session cart. */
require_once __DIR__ . '/includes/bootstrap.php';
$items = cartItems();
$total = 0;
$pageTitle = 'Cart';
$metaDescription = 'Review selected courses and options.';
require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1>Shopping Cart</h1>
    <?php if (!$items) { ?>
        <p>Your cart is empty.</p>
        <a class="btn" href="<?= BASE_URL ?>/courses.php">Browse Courses</a>
    <?php } else { ?>
        <form method="post" action="<?= BASE_URL ?>/update-cart.php">
            <div class="table-wrap">
                <table class="styled-table">
                    <thead><tr><th>Course</th><th>Access Plan</th><th>Support Plan</th><th>Price</th><th>Remove</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $courseId => $item) { ?>
                        <?php
                        $total += (float)$item['price'];
                        $accessOptions = $pdo ? getOptions($pdo, $courseId, 'access') : array();
                        $supportOptions = $pdo ? getOptions($pdo, $courseId, 'support') : array();
                        ?>
                        <tr>
                            <td><?= e($item['course_name']) ?></td>
                            <td>
                                <select name="access_plan[<?= (int)$courseId ?>]">
                                    <?php foreach ($accessOptions as $option) { ?>
                                    <option value="<?= e($option['option_name']) ?>"<?= selected($option['option_name'], $item['access_plan']) ?>><?= e($option['option_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select name="support_plan[<?= (int)$courseId ?>]">
                                    <?php foreach ($supportOptions as $option) { ?>
                                    <option value="<?= e($option['option_name']) ?>"<?= selected($option['option_name'], $item['support_plan']) ?>><?= e($option['option_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><?= money($item['price']) ?></td>
                            <td><a class="btn danger" href="<?= BASE_URL ?>/remove-from-cart.php?id=<?= (int)$courseId ?>">Remove</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <p class="price">Cart Total: <?= money($total) ?></p>
            <div class="button-row">
                <button class="btn secondary" type="submit">Update Options</button>
                <a class="btn" href="<?= BASE_URL ?>/checkout.php">Checkout</a>
            </div>
        </form>
        <p><a href="<?= BASE_URL ?>/help/purchase.php">Purchase Help</a></p>
    <?php } ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
