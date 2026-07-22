<?php
/* File: header.php - shared top part of the PHP pages. */
if (!isset($pageTitle)) {
    $pageTitle = SITE_NAME;
}
if (!isset($metaDescription)) {
    $metaDescription = 'Online courses and learning tools from SkillSpring.';
}

$theme = getActiveTheme($pdo);
$flash = getFlash();

/* The static HTML pages read this cookie to use the same template. */
setcookie('site_theme', $theme, time() + 60 * 60 * 24 * 30, BASE_URL . '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="online courses, programming, web development, career skills">
    <meta name="author" content="SkillSpring Project">
    <title><?= e($pageTitle) ?> | <?= e(SITE_NAME) ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/images/favicon.png">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/theme-<?= e($theme) ?>.css">
    <script src="<?= BASE_URL ?>/js/main.js" defer></script>
</head>
<body>
<header class="site-header">
    <div class="container header-row">
        <a class="brand" href="<?= BASE_URL ?>/index.php">
            <img src="<?= BASE_URL ?>/images/logo.png" alt="SkillSpring logo">
        </a>
        <button id="menuButton" class="menu-button" type="button"
                aria-expanded="false" aria-controls="mainNavigation">Menu</button>
        <nav id="mainNavigation" class="main-nav" aria-label="Main navigation">
            <a href="<?= BASE_URL ?>/index.php">Home</a>
            <a href="<?= BASE_URL ?>/courses.php">Courses</a>
            <a href="<?= BASE_URL ?>/categories.php">Categories</a>
            <a href="<?= BASE_URL ?>/learning-path.php">Learning Path</a>
            <a href="<?= BASE_URL ?>/media.php">Media</a>
            <a href="<?= BASE_URL ?>/about.php">About</a>
            <a href="<?= BASE_URL ?>/contact.php">Contact</a>
            <a href="<?= BASE_URL ?>/cart.php">Cart (<?= cartCount() ?>)</a>
            <?php if (isLoggedIn()) { ?>
                <a href="<?= BASE_URL ?>/my-courses.php">My Courses</a>
                <a href="<?= BASE_URL ?>/profile.php">Profile</a>
                <?php if (isAdmin()) { ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a>
                <?php } ?>
                <a href="<?= BASE_URL ?>/logout.php">Logout</a>
            <?php } else { ?>
                <a href="<?= BASE_URL ?>/login.php">Login</a>
                <a href="<?= BASE_URL ?>/register.php">Register</a>
            <?php } ?>
        </nav>
    </div>
</header>

<?php if ($flash) { ?>
<div class="container"><p class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></p></div>
<?php } ?>

<?php if (!$pdo) { ?>
<div class="container">
    <p class="flash warning">Database setup is not finished. Check <code>includes/config.php</code>.</p>
</div>
<?php } ?>

<main class="container">
