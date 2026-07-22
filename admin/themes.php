<?php
/* File: admin/themes.php - changes the template used by the website. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
$themes = array('light', 'dark', 'blue');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $theme = isset($_POST['theme']) ? $_POST['theme'] : '';
    if (in_array($theme, $themes)) {
        $check = $pdo->query("SELECT setting_name FROM site_settings WHERE setting_name = 'active_theme'");
        if ($check->fetch()) {
            $statement = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_name = 'active_theme'");
        } else {
            $statement = $pdo->prepare("INSERT INTO site_settings (setting_name, setting_value) VALUES ('active_theme', ?)");
        }
        $statement->execute(array($theme));
        setcookie('site_theme', $theme, time() + 60 * 60 * 24 * 30, BASE_URL . '/');
        setFlash('success', 'The website template was changed to ' . $theme . '.');
        redirect('admin/themes.php');
    }
}
$currentTheme = getActiveTheme($pdo);
$pageTitle = 'Website Templates';
$metaDescription = 'Change the SkillSpring website template.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card narrow-card">
    <h1>Website Templates</h1>
    <p>The current template is <strong><?= e($currentTheme) ?></strong>.</p>
    <form class="styled-form" method="post">
        <label><input type="radio" name="theme" value="light"<?= checked('light', $currentTheme) ?>> Light Template - normal three-column layout</label>
        <label><input type="radio" name="theme" value="dark"<?= checked('dark', $currentTheme) ?>> Dark Template - two-column layout and left menu</label>
        <label><input type="radio" name="theme" value="blue"<?= checked('blue', $currentTheme) ?>> Blue Template - compact four-column layout</label>
        <button class="btn" type="submit">Apply Template</button>
    </form>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
