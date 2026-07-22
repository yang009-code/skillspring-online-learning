<?php
/* File: about.php - explains the idea behind the website. */
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'About';
$metaDescription = 'Read about the SkillSpring online course project.';
require __DIR__ . '/includes/header.php';
?>

<section class="card">
    <h1>About SkillSpring</h1>
    <p>
        SkillSpring is a made-up online course website for this class project. The site has short courses
        about web development, programming, databases, study skills and career skills. A visitor can look
        through the catalogue. A registered user can choose an access plan and a support plan, add courses
        to a cart and complete a practice checkout. The purchase is not real. After checkout, the user can
        open lessons, mark progress and leave a review.
    </p>
    <p>
        The administrator has a separate area for course records, users, orders, messages, reports, themes
        and the website monitor. The main reason for this project is to practise HTML, CSS, JavaScript, PHP,
        sessions and MySQL together in one website.
    </p>
    <h2>Main Goals</h2>
    <ul>
        <li>Use a database instead of putting all course data directly in HTML.</li>
        <li>Have public pages and pages that need a login.</li>
        <li>Let the administrator add and change website data.</li>
        <li>Make the pages work on a desktop and a phone.</li>
    </ul>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
