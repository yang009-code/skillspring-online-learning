<?php
/* File: admin/courses.php - lists course records for the administrator. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);

$courses = $pdo->query(
    'SELECT * FROM courses ORDER BY course_id DESC'
)->fetchAll();

$pageTitle = 'Manage Courses';
$metaDescription = 'Add, edit or deactivate courses.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card">
    <div class="heading-row">
        <h1>Manage Courses</h1>
        <a class="btn" href="<?= BASE_URL ?>/admin/add-course.php">Add Course</a>
    </div>

    <div class="table-wrap">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $course) { ?>
                <tr>
                    <td><?= (int)$course['course_id'] ?></td>
                    <td><?= e($course['course_name']) ?></td>
                    <td><?= e($course['category']) ?></td>
                    <td><?= money($course['base_price']) ?></td>
                    <td><?= e($course['status']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/edit-course.php?id=<?= (int)$course['course_id'] ?>">
                            Edit
                        </a>

                        <?php if ($course['status'] == 'active') { ?>
                            <form class="inline-form" action="<?= BASE_URL ?>/admin/delete-course.php" method="post"
                                  onsubmit="return confirm('Change this course to inactive?');">
                                <input type="hidden" name="course_id" value="<?= (int)$course['course_id'] ?>">
                                <button class="btn secondary" type="submit">Deactivate</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
