<?php
/* File: admin/edit-course.php - changes one course record. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0);
$course = getCourse($pdo, $courseId);
if (!$course) {
    setFlash('error', 'The course was not found.');
    redirect('admin/courses.php');
}
$errorText = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseName = isset($_POST['course_name']) ? trim($_POST['course_name']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $instructor = isset($_POST['instructor']) ? trim($_POST['instructor']) : '';
    $basePrice = isset($_POST['base_price']) ? (float)$_POST['base_price'] : 0;
    $difficulty = isset($_POST['difficulty']) ? trim($_POST['difficulty']) : '';
    $shortDescription = isset($_POST['short_description']) ? trim($_POST['short_description']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $image = isset($_POST['image']) ? trim($_POST['image']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';

    if ($courseName == '' || $category == '' || $basePrice <= 0) {
        $errorText = 'Course name, category and a positive price are required.';
    } else {
        $sql = "UPDATE courses SET course_name = ?, category = ?, instructor = ?, base_price = ?,
                difficulty = ?, short_description = ?, description = ?, image = ?, status = ?
                WHERE course_id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute(array($courseName, $category, $instructor, $basePrice, $difficulty,
            $shortDescription, $description, $image, $status, $courseId));
        setFlash('success', 'The course was updated.');
        redirect('admin/courses.php');
    }
}
$pageTitle = 'Edit Course';
$metaDescription = 'Edit a SkillSpring course.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card">
    <h1>Edit Course</h1>
    <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
    <form class="styled-form" method="post">
        <input type="hidden" name="course_id" value="<?= $courseId ?>">
        <label for="course_name">Course Name</label><input id="course_name" name="course_name" value="<?= e($course['course_name']) ?>" required>
        <label for="category">Category</label><input id="category" name="category" value="<?= e($course['category']) ?>" required>
        <label for="instructor">Instructor</label><input id="instructor" name="instructor" value="<?= e($course['instructor']) ?>" required>
        <label for="base_price">Base Price</label><input id="base_price" name="base_price" type="number" step="0.01" value="<?= e($course['base_price']) ?>" required>
        <label for="difficulty">Difficulty</label>
        <select id="difficulty" name="difficulty">
            <option<?= selected('Beginner', $course['difficulty']) ?>>Beginner</option>
            <option<?= selected('Intermediate', $course['difficulty']) ?>>Intermediate</option>
            <option<?= selected('Advanced', $course['difficulty']) ?>>Advanced</option>
        </select>
        <label for="image">Image Path</label><input id="image" name="image" value="<?= e($course['image']) ?>">
        <label for="short_description">Short Description</label><textarea id="short_description" name="short_description" rows="3"><?= e($course['short_description']) ?></textarea>
        <label for="description">Full Description</label><textarea id="description" name="description" rows="8"><?= e($course['description']) ?></textarea>
        <label for="status">Status</label>
        <select id="status" name="status"><option<?= selected('active', $course['status']) ?>>active</option><option<?= selected('inactive', $course['status']) ?>>inactive</option></select>
        <button class="btn" type="submit">Save Changes</button>
    </form>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
