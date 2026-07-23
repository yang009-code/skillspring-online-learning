<?php
/* File: review.php - saves one rating and comment for an active course enrollment. */
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
requireDatabase($pdo);

$user = currentUser();
$courseId = isset($_GET['id'])
    ? (int)$_GET['id']
    : (isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0);

$sql = "SELECT c.course_id, c.course_name
        FROM enrollments e
        INNER JOIN courses c ON c.course_id = e.course_id
        INNER JOIN orders o ON o.order_id = e.order_id
        WHERE e.user_id = ?
          AND e.course_id = ?
          AND o.order_status = 'Completed'
          AND (e.expiry_date IS NULL OR e.expiry_date >= CURDATE())
        LIMIT 1";

$statement = $pdo->prepare($sql);
$statement->execute(array($user['user_id'], $courseId));
$course = $statement->fetch();

if (!$course) {
    setFlash('error', 'Only a user with active course access can write a review.');
    redirect('my-courses.php');
}

$errorText = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if ($rating < 1 || $rating > 5 || $comment == '') {
        $errorText = 'Choose a rating from 1 to 5 and write a comment.';
    } else {
        $check = $pdo->prepare(
            'SELECT review_id FROM reviews WHERE user_id = ? AND course_id = ?'
        );
        $check->execute(array($user['user_id'], $courseId));
        $oldReview = $check->fetch();

        if ($oldReview) {
            $statement = $pdo->prepare(
                'UPDATE reviews SET rating = ?, comment = ?, created_at = CURRENT_TIMESTAMP WHERE review_id = ?'
            );
            $statement->execute(array($rating, $comment, $oldReview['review_id']));
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO reviews (user_id, course_id, rating, comment) VALUES (?, ?, ?, ?)'
            );
            $statement->execute(array($user['user_id'], $courseId, $rating, $comment));
        }

        setFlash('success', 'Your review was saved.');
        redirect('my-courses.php');
    }
}

$pageTitle = 'Review ' . $course['course_name'];
$metaDescription = 'Rate and review a SkillSpring course.';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow-card">
    <h1>Review <?= e($course['course_name']) ?></h1>

    <?php if ($errorText != '') { ?>
        <p class="flash error"><?= e($errorText) ?></p>
    <?php } ?>

    <form class="styled-form" method="post">
        <input type="hidden" name="course_id" value="<?= $courseId ?>">

        <label for="rating">Rating</label>
        <select id="rating" name="rating" required>
            <option value="">Choose 1 to 5</option>
            <option value="1">1 - Needs Improvement</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Very Good</option>
            <option value="5">5 - Excellent</option>
        </select>

        <label for="comment">Comment</label>
        <textarea id="comment" name="comment" rows="7" required></textarea>

        <button class="btn" type="submit">Save Review</button>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
