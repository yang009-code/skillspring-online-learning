<?php
/* File: admin/users.php - changes user roles and account status. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    if (($role == 'user' || $role == 'admin') && ($status == 'active' || $status == 'disabled')) {
        $statement = $pdo->prepare('UPDATE users SET role = ?, status = ? WHERE user_id = ?');
        $statement->execute(array($role, $status, $userId));
        setFlash('success', 'The user account was updated.');
        redirect('admin/users.php');
    }
}
$users = $pdo->query('SELECT user_id, full_name, email, username, role, status, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Manage Users';
$metaDescription = 'Manage SkillSpring users.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card">
    <h1>Manage Users</h1>
    <div class="table-wrap">
        <table class="styled-table">
            <thead><tr><th>User</th><th>Email</th><th>Created</th><th>Role and Status</th></tr></thead>
            <tbody>
            <?php foreach ($users as $account) { ?>
                <tr>
                    <td><?= e($account['full_name']) ?><br><small><?= e($account['username']) ?></small></td>
                    <td><?= e($account['email']) ?></td>
                    <td><?= e($account['created_at']) ?></td>
                    <td>
                        <form class="inline-form" method="post">
                            <input type="hidden" name="user_id" value="<?= (int)$account['user_id'] ?>">
                            <select name="role"><option<?= selected('user', $account['role']) ?>>user</option><option<?= selected('admin', $account['role']) ?>>admin</option></select>
                            <select name="status"><option<?= selected('active', $account['status']) ?>>active</option><option<?= selected('disabled', $account['status']) ?>>disabled</option></select>
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
