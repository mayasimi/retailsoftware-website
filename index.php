<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';
$activePage = 'home';
$documentTitle = pageTitle('home.title');
$documentDescription = content('home.description');
require __DIR__ . '/includes/header.php';
?>
<section class="home-hero">
    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="7000">
        <div class="carousel-inner">
            <?php foreach (['first', 'second'] as $slide): ?>
                <div class="carousel-item <?= $slide === 'first' ? 'active' : '' ?>">
                    <img class="home-carousel-image" src="<?= h(content('home.hero_image_' . $slide)) ?>" alt="<?= h(content('home.hero_image_alt_' . $slide)) ?>">
                    <div class="carousel-caption">
                        <div class="home-hero-copy">
                            <p class="eyebrow"><?= h(content('home.eyebrow_' . $slide)) ?></p>
                            <h1><?= h(content('home.heading_' . $slide)) ?></h1>
                            <p class="hero-lead"><?= h(content('home.intro_' . $slide)) ?></p>
                            <div class="d-flex flex-wrap justify-content-center gap-3">
                                <a class="btn btn-primary btn-lg" href="demo.php"><?= h(content('home.primary_cta')) ?></a>
                                <a class="btn btn-outline-light btn-lg" href="features.php"><?= h(content('home.secondary_cta')) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev" aria-label="<?= h(content('home.previous_slide')) ?>">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next" aria-label="<?= h(content('home.next_slide')) ?>">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="<?= h(content('home.slide_label_first')) ?>"></button>
            <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="1" aria-label="<?= h(content('home.slide_label_second')) ?>"></button>
        </div>
    </div>
</section>
<section class="home-facts">
    <div class="container">
        <div class="row g-3">
            <?php foreach (['web', 'stock', 'local'] as $fact): ?>
                <div class="col-md-4">
                    <article class="fact-card">
                        <div class="fact-icon"><i class="<?= h(content('home.fact_icon_' . $fact)) ?>" aria-hidden="true"></i></div>
                        <div><h2><?= h(content('home.fact_title_' . $fact)) ?></h2><p><?= h(content('home.fact_copy_' . $fact)) ?></p></div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section-pad home-intro">
    <div class="container story-grid home-about-grid">
        <div class="home-hero-copy">
            <div class="section-title position-relative pb-3 mb-4">
                <p class="eyebrow"><?= h(content('home.value_eyebrow')) ?></p>
                <h2><?= h(content('home.value_heading')) ?></h2>
            </div>
            <p class="hero-lead"><?= h(content('home.value_copy')) ?></p>
            <div class="home-proof-list">
                <?php foreach (['first', 'second', 'third'] as $proof): ?>
                    <p><i class="fas fa-check-circle" aria-hidden="true"></i><?= h(content('home.proof_' . $proof)) ?></p>
                <?php endforeach; ?>
            </div>
            <a class="btn btn-primary mt-3" href="about.php"><?= h(content('home.about_button')) ?></a>
        </div>
        <img class="home-about-image" src="<?= h(content('home.about_image')) ?>" alt="<?= h(content('home.about_image_alt')) ?>">
    </div>
</section>
<section class="section-pad feature-preview">
    <div class="container">
        <div class="section-title position-relative pb-3 mb-5 text-center">
            <p class="eyebrow"><?= h(content('home.features_eyebrow')) ?></p>
            <h2><?= h(content('home.features_heading')) ?></h2>
        </div>
        <div class="row g-4">
            <?php foreach (array_filter(array_map('trim', explode(',', content('features.home_modules')))) as $feature): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="service-item text-center">
                        <div class="service-icon mx-auto"><i class="<?= h(content('features.icon_' . $feature)) ?>" aria-hidden="true"></i></div>
                        <h3><?= h(content('features.title_' . $feature)) ?></h3>
                        <p><?= h(content('features.copy_' . $feature)) ?></p>
                        <a class="btn btn-primary btn-square rounded-circle" href="features.php" aria-label="<?= h(content('home.feature_details_label')) ?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a class="btn btn-primary py-3 px-5" href="features.php"><?= h(content('home.features_link')) ?></a>
        </div>
    </div>
</section>
<section class="cta-band">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
        <div><p class="eyebrow"><?= h(content('home.cta_eyebrow')) ?></p><h2><?= h(content('home.cta_heading')) ?></h2></div>
        <a class="btn btn-light btn-lg" href="demo.php"><?= h(content('home.cta_button')) ?></a>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
