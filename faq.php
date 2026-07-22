<?php
/* File: faq.php - common questions about the website. */
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'FAQ';
$metaDescription = 'Common questions about SkillSpring.';
require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1>Frequently Asked Questions</h1>
    <h2>Is the payment real?</h2><p>No. Checkout is only a class project demonstration.</p>
    <h2>How do I open a course?</h2><p>Register, log in, add a course to the cart and finish the demo checkout.</p>
    <h2>Can I change the course options?</h2><p>Yes. The options can be changed in the cart before checkout.</p>
    <h2>How is progress saved?</h2><p>The Mark Complete button saves the lesson in the progress table.</p>
    <h2>Who can change courses?</h2><p>Only an administrator can use the course management pages.</p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
