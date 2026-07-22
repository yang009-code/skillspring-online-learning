<?php
/* File: functions.php - small functions used on several pages. */
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect($page)
{
    header('Location: ' . BASE_URL . '/' . ltrim($page, '/'));
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function isAdmin()
{
    if (!isLoggedIn()) {
        return false;
    }
    return $_SESSION['user']['role'] == 'admin';
}

function currentUser()
{
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    return null;
}

function setFlash($type, $message)
{
    $_SESSION['flash'] = array('type' => $type, 'message' => $message);
}

function getFlash()
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function requireDatabase($pdo)
{
    if (!$pdo) {
        setFlash('error', 'The database is not connected. Check includes/config.php.');
        redirect('index.php');
    }
}

function getActiveTheme($pdo)
{
    $theme = 'light';
    if ($pdo) {
        try {
            $sql = "SELECT setting_value FROM site_settings WHERE setting_name = 'active_theme'";
            $row = $pdo->query($sql)->fetch();
            if ($row) {
                $saved = $row['setting_value'];
                if ($saved == 'light' || $saved == 'dark' || $saved == 'blue') {
                    $theme = $saved;
                }
            }
        } catch (Exception $error) {
            $theme = 'light';
        }
    }
    return $theme;
}

function cartItems()
{
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    return array();
}

function cartCount()
{
    return count(cartItems());
}

function getCourse($pdo, $courseId)
{
    if (!$pdo) {
        return null;
    }
    $statement = $pdo->prepare('SELECT * FROM courses WHERE course_id = ? LIMIT 1');
    $statement->execute(array($courseId));
    return $statement->fetch();
}

function getOptions($pdo, $courseId, $optionType)
{
    $sql = 'SELECT * FROM course_options WHERE course_id = ? AND option_type = ? ORDER BY extra_price, option_name';
    $statement = $pdo->prepare($sql);
    $statement->execute(array($courseId, $optionType));
    return $statement->fetchAll();
}

function priceFor($pdo, $courseId, $accessPlan, $supportPlan)
{
    $course = getCourse($pdo, $courseId);
    if (!$course) {
        return null;
    }

    $sql = "SELECT SUM(extra_price) AS extra_total FROM course_options
            WHERE course_id = ? AND
            ((option_type = 'access' AND option_name = ?)
            OR (option_type = 'support' AND option_name = ?))";
    $statement = $pdo->prepare($sql);
    $statement->execute(array($courseId, $accessPlan, $supportPlan));
    $result = $statement->fetch();

    $extra = 0;
    if ($result && $result['extra_total'] !== null) {
        $extra = (float)$result['extra_total'];
    }
    return (float)$course['base_price'] + $extra;
}

function money($value)
{
    return '$' . number_format((float)$value, 2);
}

function selected($value, $currentValue)
{
    return $value == $currentValue ? ' selected' : '';
}

function checked($value, $currentValue)
{
    return $value == $currentValue ? ' checked' : '';
}
?>
