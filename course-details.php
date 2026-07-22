<?php
/* File: course-details.php - shows one course and its two option groups. */
require_once __DIR__ . '/includes/bootstrap.php';
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = getCourse($pdo, $courseId);

if (!$course || $course['status'] != 'active') {
    setFlash('error', 'The course was not found.');
    redirect('courses.php');
}

$accessOptions = getOptions($pdo, $courseId, 'access');
$supportOptions = getOptions($pdo, $courseId, 'support');
$pageTitle = $course['course_name'];
$metaDescription = $course['short_description'];
require __DIR__ . '/includes/header.php';
?>

<section class="sidebar-layout">
    <article class="card">
        <img src="<?= BASE_URL ?>/<?= e($course['image']) ?>" alt="<?= e($course['course_name']) ?> course cover">
        <p><span class="badge"><?= e($course['category']) ?></span></p>
        <h1><?= e($course['course_name']) ?></h1>
        <p><strong>Instructor:</strong> <?= e($course['instructor']) ?></p>
        <p><strong>Difficulty:</strong> <?= e($course['difficulty']) ?></p>
        <p><?= nl2br(e($course['description'])) ?></p>
    </article>

    <aside class="card">
        <h2>Choose Course Options</h2>
        <form id="courseOptionForm" class="styled-form" data-base-price="<?= e($course['base_price']) ?>"
              action="<?= BASE_URL ?>/add-to-cart.php" method="post">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <label for="access_plan">Access Plan</label>
            <select id="access_plan" name="access_plan" required>
                <?php foreach ($accessOptions as $option) { ?>
                <option value="<?= e($option['option_name']) ?>" data-extra-price="<?= e($option['extra_price']) ?>">
                    <?= e($option['option_name']) ?> (+<?= money($option['extra_price']) ?>)
                </option>
                <?php } ?>
            </select>

            <label for="support_plan">Support Plan</label>
            <select id="support_plan" name="support_plan" required>
                <?php foreach ($supportOptions as $option) { ?>
                <option value="<?= e($option['option_name']) ?>" data-extra-price="<?= e($option['extra_price']) ?>">
                    <?= e($option['option_name']) ?> (+<?= money($option['extra_price']) ?>)
                </option>
                <?php } ?>
            </select>

            <p class="price">Total: <span id="calculatedPrice"><?= money($course['base_price']) ?></span></p>
            <button class="btn" type="submit">Add to Cart</button>
        </form>
        <p><a href="<?= BASE_URL ?>/help/purchase.php">Help with purchasing</a></p>
    </aside>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
