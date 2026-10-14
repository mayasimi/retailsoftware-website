<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';
$activePage = '';
$documentTitle = pageTitle('demo.title');
$documentDescription = content('demo.description');
$heroEyebrow = content('demo.eyebrow');
$heroTitle = content('demo.heading');
$heroCopy = content('demo.intro');
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-hero.php';
$formError = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
?>
<section class="section-pad">
    <div class="container demo-grid">
        <div class="demo-copy">
            <p class="eyebrow"><?= h(content('demo.details_eyebrow')) ?></p>
            <h2><?= h(content('demo.details_heading')) ?></h2>
            <p><?= h(content('demo.details_copy')) ?></p>
        </div>
        <div class="form-panel">
            <h2><?= h(content('demo.form_heading')) ?></h2>
            <p><?= h(content('demo.form_copy')) ?></p>
            <?php if ($formError !== ''): ?><div class="alert alert-warning" role="alert"><?= h($formError) ?></div><?php endif; ?>
            <?php if (isset($_GET['mail_failed'])): ?><div class="alert alert-warning" role="status"><?= h(content('demo.mail_error_message')) ?></div><?php elseif (isset($_GET['sent'])): ?><div class="alert alert-success" role="status"><?= h(content('demo.success_message')) ?></div><?php endif; ?>
            <form action="submit-inquiry.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                <input type="hidden" name="inquiry_type" value="demo">
                <div class="mb-3"><label for="name" class="form-label"><?= h(content('form.name')) ?></label><input class="form-control" id="name" name="name" maxlength="120" required autocomplete="name"></div>
                <div class="mb-3"><label for="email" class="form-label"><?= h(content('form.email')) ?></label><input class="form-control" id="email" name="email" type="email" maxlength="254" required autocomplete="email"></div>
                <div class="mb-3"><label for="company" class="form-label"><?= h(content('form.company')) ?></label><input class="form-control" id="company" name="company" maxlength="160" autocomplete="organization"></div>
                <div class="mb-3"><label for="message" class="form-label"><?= h(content('form.message')) ?></label><textarea class="form-control" id="message" name="message" rows="4" maxlength="5000" required></textarea></div>
                <button class="btn btn-primary w-100" type="submit"><?= h(content('demo.submit_button')) ?></button>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
