<?php
/* File: admin/messages.php - reads customer messages and saves replies. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messageId = isset($_POST['message_id']) ? (int)$_POST['message_id'] : 0;
    $response = isset($_POST['admin_response']) ? trim($_POST['admin_response']) : '';
    if ($messageId > 0 && $response != '') {
        $statement = $pdo->prepare("UPDATE messages SET admin_response = ?, status = 'Answered', responded_at = NOW() WHERE message_id = ?");
        $statement->execute(array($response, $messageId));
        setFlash('success', 'The response was saved.');
        redirect('admin/messages.php');
    }
}
$messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Messages';
$metaDescription = 'Read and answer customer messages.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<h1>Customer Messages</h1>
<?php foreach ($messages as $message) { ?>
<article class="card section-title">
    <h2><?= e($message['subject']) ?></h2>
    <p><strong>From:</strong> <?= e($message['full_name']) ?> (<?= e($message['email']) ?>)<br>
       <strong>Status:</strong> <?= e($message['status']) ?><br>
       <strong>Created:</strong> <?= e($message['created_at']) ?></p>
    <p><?= nl2br(e($message['message'])) ?></p>
    <form class="styled-form" method="post">
        <input type="hidden" name="message_id" value="<?= (int)$message['message_id'] ?>">
        <label for="response<?= (int)$message['message_id'] ?>">Administrator Response</label>
        <textarea id="response<?= (int)$message['message_id'] ?>" name="admin_response" rows="5" required><?= e($message['admin_response']) ?></textarea>
        <button class="btn" type="submit">Save Response</button>
    </form>
</article>
<?php } ?>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
