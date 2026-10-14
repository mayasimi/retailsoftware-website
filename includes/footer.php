    </main>
    <footer class="site-footer">
        <div class="container footer-main">
            <div class="row g-4">
                <div class="col-lg-5">
                    <a href="index.php" class="footer-name"><?= h(content('site.name')) ?></a>
                    <p class="footer-summary"><?= h(content('footer.summary')) ?></p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h2><?= h(content('footer.links_title')) ?></h2>
                    <a href="about.php"><?= h(content('nav.about')) ?></a>
                    <a href="features.php"><?= h(content('nav.features')) ?></a>
                    <a href="contact.php"><?= h(content('nav.contact')) ?></a>
                    <a href="demo.php"><?= h(content('nav.demo')) ?></a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h2><?= h(content('footer.contact_title')) ?></h2>
                    <p><?= h(content('site.location')) ?></p>
                    <a href="mailto:<?= h(content('site.public_email')) ?>"><?= h(content('site.public_email')) ?></a>
                    <a href="tel:<?= h(content('site.phone_link')) ?>"><?= h(content('site.phone_display')) ?></a>
                    <a href="<?= h(content('site.website_url')) ?>"><?= h(content('site.website_display')) ?></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container d-flex flex-wrap justify-content-center text-center">
                <span>&copy; <?= date('Y') ?> <?= h(content('site.name')) ?>. <?= h(content('footer.rights')) ?></span>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/site.js"></script>
</body>
</html>
