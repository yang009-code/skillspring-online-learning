<?php
    /* File: index.php - context help page. */
    require_once dirname(__DIR__) . '/includes/bootstrap.php';
    $pageTitle = 'Help Pages';
    $metaDescription = 'Help page for help pages.';
    require dirname(__DIR__) . '/includes/header.php';
    ?>
    <section class="card narrow-card">
        <h1>Help Pages</h1>
        <p>Choose the part of the website that you need help with.</p>
<ul><li><a href="register.php">Registering an account</a></li><li><a href="login.php">Logging in</a></li><li><a href="search.php">Searching for a course</a></li><li><a href="purchase.php">Course options and checkout</a></li><li><a href="learning.php">Opening enrolled courses</a></li><li><a href="progress.php">Saving lesson progress</a></li><li><a href="account.php">Editing an account</a></li></ul>
        <a class="btn secondary" href="<?= BASE_URL ?>/help/index.php">Help Home</a>
    </section>
    <?php require dirname(__DIR__) . '/includes/footer.php'; ?>
