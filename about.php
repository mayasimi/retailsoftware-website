<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';
$activePage = 'about';
$documentTitle = pageTitle('about.title');
$documentDescription = content('about.description');
$heroEyebrow = content('about.eyebrow');
$heroTitle = content('about.heading');
$heroCopy = content('about.intro');
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-hero.php';
?>
<section class="section-pad">
    <div class="container story-grid">
        <div class="story-images">
            <img class="story-image-main" src="<?= h(content('about.image_primary')) ?>" alt="<?= h(content('about.image_primary_alt')) ?>">
        </div>
        <div class="story-copy">
            <p class="eyebrow"><?= h(content('about.story_eyebrow')) ?></p>
            <h2><?= h(content('about.story_heading')) ?></h2>
            <p><?= h(content('about.story_copy')) ?></p>
            <h3><?= h(content('about.promise_heading')) ?></h3>
            <p><?= h(content('about.promise_copy')) ?></p>
            <a class="btn btn-primary mt-3" href="features.php"><?= h(content('about.button')) ?></a>
        </div>
    </div>
</section>
<section class="section-pad soft-section">
    <div class="container text-center narrow-copy">
        <p class="eyebrow"><?= h(content('about.approach_eyebrow')) ?></p>
        <h2><?= h(content('about.approach_heading')) ?></h2>
        <p><?= h(content('about.approach_copy')) ?></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
