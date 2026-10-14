<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';
$activePage = 'contact';
$documentTitle = pageTitle('contact.title');
$documentDescription = content('contact.description');
$heroEyebrow = content('contact.eyebrow');
$heroTitle = content('contact.heading');
$heroCopy = content('contact.intro');
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-hero.php';
$formError = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
?>
<section class="section-pad">
    <div class="container contact-grid">
        <div class="contact-copy">
            <p class="eyebrow"><?= h(content('contact.details_eyebrow')) ?></p>
            <h2><?= h(content('contact.details_heading')) ?></h2>
            <p><?= h(content('contact.details_copy')) ?></p>
            <div class="contact-detail"><span class="feature-icon"><i class="fas fa-envelope" aria-hidden="true"></i></span><div><strong><?= h(content('contact.email_label')) ?></strong><a href="mailto:<?= h(content('site.public_email')) ?>"><?= h(content('site.public_email')) ?></a></div></div>
            <div class="contact-detail"><span class="feature-icon"><i class="fas fa-phone-alt" aria-hidden="true"></i></span><div><strong><?= h(content('contact.phone_label')) ?></strong><a href="tel:<?= h(content('site.phone_link')) ?>"><?= h(content('site.phone_display')) ?></a></div></div>
            <div class="contact-detail"><span class="feature-icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><div><strong><?= h(content('contact.location_label')) ?></strong><span><?= h(content('site.location')) ?></span></div></div>
            <div class="contact-detail"><span class="feature-icon"><i class="fas fa-globe" aria-hidden="true"></i></span><div><strong><?= h(content('contact.website_label')) ?></strong><a href="<?= h(content('site.website_url')) ?>"><?= h(content('site.website_display')) ?></a></div></div>
        </div>
        <div class="form-panel">
            <h2><?= h(content('contact.form_heading')) ?></h2>
            <p><?= h(content('contact.form_copy')) ?></p>
            <?php if ($formError !== ''): ?><div class="alert alert-warning" role="alert"><?= h($formError) ?></div><?php endif; ?>
            <?php if (isset($_GET['mail_failed'])): ?><div class="alert alert-warning" role="status"><?= h(content('contact.mail_error_message')) ?></div><?php elseif (isset($_GET['sent'])): ?><div class="alert alert-success" role="status"><?= h(content('contact.success_message')) ?></div><?php endif; ?>
            <form action="submit-inquiry.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                <input type="hidden" name="inquiry_type" value="contact">
                <div class="mb-3"><label for="name" class="form-label"><?= h(content('form.name')) ?></label><input class="form-control" id="name" name="name" maxlength="120" required autocomplete="name"></div>
                <div class="mb-3"><label for="email" class="form-label"><?= h(content('form.email')) ?></label><input class="form-control" id="email" name="email" type="email" maxlength="254" required autocomplete="email"></div>
                <div class="mb-3"><label for="message" class="form-label"><?= h(content('form.message')) ?></label><textarea class="form-control" id="message" name="message" rows="5" maxlength="5000" required></textarea></div>
                <button class="btn btn-primary w-100" type="submit"><?= h(content('contact.submit_button')) ?></button>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
