<?php
/* File: admin/monitor.php - checks the database and important project files. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
$root = dirname(__DIR__);
$checks = array(
    array('PHP Runtime', true, PHP_VERSION),
    array('MySQL Connection', (bool)$pdo, $pdo ? 'Connected' : 'Not connected'),
    array('Session', session_status() == PHP_SESSION_ACTIVE, 'Session is started'),
    array('Course Images', is_dir($root . '/images/courses'), 'images/courses'),
    array('Preview Video 1', is_file($root . '/media/preview1.mp4'), 'media/preview1.mp4'),
    array('Preview Video 2', is_file($root . '/media/preview2.mp4'), 'media/preview2.mp4'),
    array('Preview Video 3', is_file($root . '/media/preview3.mp4'), 'media/preview3.mp4'),
    array('Main CSS', is_file($root . '/css/style.css'), 'css/style.css'),
    array('JavaScript', is_file($root . '/js/main.js'), 'js/main.js'),
    array('Help Pages', is_file($root . '/help/index.php'), 'help/index.php')
);
$pageTitle = 'System Monitor';
$metaDescription = 'Check the main SkillSpring services.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card">
    <h1>Website Status Monitor</h1>
    <div class="table-wrap">
        <table class="styled-table">
            <thead><tr><th>Feature</th><th>Status</th><th>Details</th></tr></thead>
            <tbody>
            <?php foreach ($checks as $check) { ?>
                <tr><td><?= e($check[0]) ?></td>
                    <td class="<?= $check[1] ? 'status-online' : 'status-offline' ?>"><?= $check[1] ? 'Online / Working' : 'Offline / Missing' ?></td>
                    <td><?= e($check[2]) ?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
