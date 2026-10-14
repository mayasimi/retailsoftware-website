<?php
declare(strict_types=1);

$activePage = $activePage ?? '';
$documentTitle = $documentTitle ?? pageTitle('site.name');
$documentDescription = $documentDescription ?? content('site.description');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($documentDescription) ?>">
    <title><?= $documentTitle ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/flexi.css" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark container">
            <a class="navbar-brand" href="index.php" aria-label="<?= h(content('site.name')) ?> home">
                <img class="brand-logo" src="<?= h(content('site.logo')) ?>" alt="<?= h(content('site.name')) ?>">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavigation" aria-controls="siteNavigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="siteNavigation">
                <div class="navbar-nav ms-auto align-items-lg-center">
                    <a class="nav-item nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="index.php"><?= h(content('nav.home')) ?></a>
                    <a class="nav-item nav-link <?= $activePage === 'about' ? 'active' : '' ?>" href="about.php"><?= h(content('nav.about')) ?></a>
                    <a class="nav-item nav-link <?= $activePage === 'features' ? 'active' : '' ?>" href="features.php"><?= h(content('nav.features')) ?></a>
                    <a class="nav-item nav-link <?= $activePage === 'contact' ? 'active' : '' ?>" href="contact.php"><?= h(content('nav.contact')) ?></a>
                    <a class="btn btn-primary nav-cta" href="demo.php"><?= h(content('nav.demo')) ?></a>
                </div>
            </div>
        </nav>
    </header>
    <main>
