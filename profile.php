<?php
/* File: profile.php - shows the current account. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
$user = currentUser();
$pageTitle = 'Profile';
$metaDescription = 'View a SkillSpring profile.';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>User Profile</h1>
    <p><strong>Full Name:</strong> <?= e($user['full_name']) ?></p>
    <p><strong>Email:</strong> <?= e($user['email']) ?></p>
    <p><strong>Username:</strong> <?= e($user['username']) ?></p>
    <p><strong>Role:</strong> <?= e(ucfirst($user['role'])) ?></p>
    <div class="button-row">
        <a class="btn" href="<?= BASE_URL ?>/edit-profile.php">Edit Profile</a>
        <a class="btn secondary" href="<?= BASE_URL ?>/my-courses.php">My Courses</a>
        <a class="btn secondary" href="<?= BASE_URL ?>/order-history.php">Order History</a>
    </div>
    <p><a href="<?= BASE_URL ?>/help/account.php">Account Help</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
