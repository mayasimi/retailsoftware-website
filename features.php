<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';
$activePage = 'features';
$documentTitle = pageTitle('features.title');
$documentDescription = content('features.description');
$heroEyebrow = content('features.eyebrow');
$heroTitle = content('features.heading');
$heroCopy = content('features.intro');
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-hero.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <?php foreach (array_filter(array_map('trim', explode(',', content('features.modules')))) as $feature): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="product-card">
                        <?php $featureImage = content('features.image_' . $feature); if ($featureImage !== ''): ?>
                            <img src="<?= h($featureImage) ?>" alt="<?= h(content('features.image_alt_' . $feature)) ?>">
                        <?php endif; ?>
                        <div class="product-card-copy">
                            <div class="feature-icon"><i class="<?= h(content('features.icon_' . $feature)) ?>" aria-hidden="true"></i></div>
                            <h2><?= h(content('features.title_' . $feature)) ?></h2>
                            <p><?= h(content('features.copy_' . $feature)) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="cta-band">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
        <div><p class="eyebrow"><?= h(content('features.cta_eyebrow')) ?></p><h2><?= h(content('features.cta_heading')) ?></h2></div>
        <div class="d-flex flex-wrap gap-3">
            <a class="btn btn-light btn-lg" href="demo.php"><?= h(content('features.cta_button')) ?></a>
            <a class="btn btn-outline-light btn-lg" href="<?= h(content('features.brochure_file')) ?>" download><?= h(content('features.brochure_label')) ?></a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
