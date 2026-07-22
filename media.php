<?php
/* File: media.php - displays the three course preview videos. */
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Course Videos';
$metaDescription = 'Watch three short course preview videos.';
require __DIR__ . '/includes/header.php';
?>
<h1>Course Preview Videos</h1>
<p>The three MP4 files are stored in the local media folder.</p>
<section class="media-grid">
    <article class="card"><h2>Online Learning</h2><video controls preload="metadata"><source src="<?= BASE_URL ?>/media/preview1.mp4" type="video/mp4">Your browser does not support MP4 video.</video></article>
    <article class="card"><h2>Programming</h2><video controls preload="metadata"><source src="<?= BASE_URL ?>/media/preview2.mp4" type="video/mp4">Your browser does not support MP4 video.</video></article>
    <article class="card"><h2>Career Skills</h2><video controls preload="metadata"><source src="<?= BASE_URL ?>/media/preview3.mp4" type="video/mp4">Your browser does not support MP4 video.</video></article>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
