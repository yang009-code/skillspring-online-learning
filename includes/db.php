<?php
/* File: db.php - makes the PDO database connection. */
$pdo = null;
$dbError = '';

try {
    if (DB_NAME == 'YOUR_DATABASE_NAME') {
        throw new Exception('Database settings have not been entered yet.');
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $error) {
    $dbError = $error->getMessage();
}
?>
