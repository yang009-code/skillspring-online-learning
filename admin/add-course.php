<?php
/* File: admin/add-course.php - adds one course and its normal options. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';
requireAdmin();
requireDatabase($pdo);
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

    if ($courseName == '' || $category == '' || $instructor == '' || $basePrice <= 0 || $shortDescription == '' || $description == '') {
        $errorText = 'Complete all required fields and enter a positive price.';
    } else {
        try {
            $pdo->beginTransaction();
            $sql = "INSERT INTO courses (course_name, category, instructor, base_price, difficulty,
                    short_description, description, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($courseName, $category, $instructor, $basePrice, $difficulty,
                $shortDescription, $description, $image == '' ? 'images/courses/course01.png' : $image));
            $courseId = (int)$pdo->lastInsertId();

            $optionSql = 'INSERT INTO course_options (course_id, option_type, option_name, extra_price) VALUES (?, ?, ?, ?)';
            $optionStatement = $pdo->prepare($optionSql);
            $options = array(
                array('access', '30-Day Access', 0), array('access', '90-Day Access', 15),
                array('access', 'Lifetime Access', 35), array('support', 'Self-Study', 0),
                array('support', 'Email Support', 10), array('support', 'Instructor Support', 25)
            );
            foreach ($options as $option) {
                $optionStatement->execute(array($courseId, $option[0], $option[1], $option[2]));
            }
            $pdo->commit();
            setFlash('success', 'The course was added.');
            redirect('admin/courses.php');
        } catch (Exception $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errorText = 'The course could not be added.';
        }
    }
}
$pageTitle = 'Add Course';
$metaDescription = 'Add a SkillSpring course.';
require dirname(__DIR__) . '/includes/header.php';
require __DIR__ . '/_nav.php';
?>
<section class="card">
    <h1>Add Course</h1>
    <?php if ($errorText != '') { ?><p class="flash error"><?= e($errorText) ?></p><?php } ?>
    <form class="styled-form" method="post">
        <label for="course_name">Course Name</label><input id="course_name" name="course_name" required>
        <label for="category">Category</label><input id="category" name="category" required>
        <label for="instructor">Instructor</label><input id="instructor" name="instructor" required>
        <label for="base_price">Base Price</label><input id="base_price" name="base_price" type="number" step="0.01" min="0.01" required>
        <label for="difficulty">Difficulty</label><select id="difficulty" name="difficulty"><option>Beginner</option><option>Intermediate</option><option>Advanced</option></select>
        <label for="image">Image Path</label><input id="image" name="image" placeholder="images/courses/course01.png">
        <label for="short_description">Short Description</label><textarea id="short_description" name="short_description" rows="3" required></textarea>
        <label for="description">Full Description</label><textarea id="description" name="description" rows="8" required></textarea>
        <button class="btn" type="submit">Add Course</button>
    </form>
</section>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
