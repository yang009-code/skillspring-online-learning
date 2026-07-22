<?php
/* File: learning-path.php - HTML image map with three clickable areas. */
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Learning Path';
$metaDescription = 'Use the image map to open course categories.';
require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1>Interactive Learning Path</h1>
    <p>Click one of the three areas. On a small screen, the image can scroll sideways.</p>
    <div class="image-map-wrapper">
        <img class="learning-map-image" src="<?= BASE_URL ?>/images/learning-path.png"
             alt="Web development, programming and career skills learning areas"
             usemap="#learningMap" width="1000" height="420">
        <map name="learningMap">
            <area shape="rect" coords="40,65,300,355"
                  href="<?= BASE_URL ?>/courses.php?category=Web+Development"
                  alt="Open Web Development courses" title="Web Development">
            <area shape="rect" coords="370,65,630,355"
                  href="<?= BASE_URL ?>/courses.php?category=Programming"
                  alt="Open Programming courses" title="Programming">
            <area shape="rect" coords="700,65,960,355"
                  href="<?= BASE_URL ?>/courses.php?category=Career+Skills"
                  alt="Open Career Skills courses" title="Career Skills">
        </map>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
