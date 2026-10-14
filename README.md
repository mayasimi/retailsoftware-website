# Flexi Retail Software website

This is a PHP/MySQL multi-page site based on the supplied Startup template. Its home page follows the template’s image-led carousel, overlapping highlights, split about section and hoverable icon service cards, adapted to the Flexi brochure and gold/black/white palette. Public page copy, navigation labels, contact details, notification copy, and image paths are stored in the `site_content` table. The template attribution remains in the footer in accordance with the included template license.

## Requirements

- PHP 7.4 or later with PDO MySQL
- MySQL 5.7 or later
- Composer
- An SMTP account for PHPMailer notifications

## Setup

1. Create the database and seed the website content by importing `database/schema.sql` into MySQL with UTF-8 enabled (for the MySQL CLI, use `--default-character-set=utf8mb4`).
2. Set `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` for the PHP process. Defaults are `127.0.0.1`, `3306`, `flexi_retail`, `root`, and an empty password.
3. From this directory run `composer install`.
4. Configure `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_ENCRYPTION` (`tls` or `ssl`), and `SMTP_FROM_EMAIL`. The from-address must be authorized by the SMTP provider. A submitted message is saved to MySQL first, then confirmation/notification messages are sent separately to the sender, `mail@flexisoftware.ng`, and `info@goodworthspartners.com`.
5. Create the first administrator by setting `ADMIN_USERNAME` and `ADMIN_INITIAL_PASSWORD` (at least 12 characters) in the process environment, then run `php scripts/create-admin.php`. The script stores only a password hash. Remove `ADMIN_INITIAL_PASSWORD` from the environment after setup.
6. Serve this directory with PHP’s web server for local testing: `php -S 127.0.0.1:8000`. Open `http://127.0.0.1:8000/`; the administrator sign-in is at `/admin/`.

The Features page includes a download of the supplied product brochure. The admin editor updates seeded site copy and can review the 50 most recent contact/demo inquiries. Image paths can be changed there, but each page image must remain a distinct existing file under `assets/images/`.
