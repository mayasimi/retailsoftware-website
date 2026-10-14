<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/content.php';

$database = database();
$error = '';
$notice = '';

if (isset($_GET['logout'])) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validateCsrfToken()) {
        http_response_code(403);
        exit('Invalid logout request.');
    }
    $_SESSION = [];
    session_regenerate_id(true);
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!validateCsrfToken()) {
        $error = 'Your session expired. Please refresh and try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $statement = $database->prepare('SELECT id, password_hash FROM admin_users WHERE username = :username LIMIT 1');
        $statement->execute([':username' => $username]);
        $admin = $statement->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $admin['id'];
            header('Location: index.php');
            exit;
        }
        $error = 'The username or password was not accepted.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_content']) && isset($_SESSION['admin_id'])) {
    if (!validateCsrfToken()) {
        $error = 'Your session expired. Please refresh and try again.';
    } else {
        $submittedContent = $_POST['content'] ?? [];
        if (!is_array($submittedContent)) {
            $error = 'The submitted content was not valid.';
        } else {
            $currentImages = [];
            $imageStatement = $database->prepare(
                'SELECT content_key, content_value FROM site_content WHERE content_key IN (' .
                implode(',', array_fill(0, count(CONTENT_IMAGE_KEYS), '?')) . ')'
            );
            $imageStatement->execute(CONTENT_IMAGE_KEYS);
            foreach ($imageStatement as $row) {
                $currentImages[$row['content_key']] = $row['content_value'];
            }

            $allImages = $currentImages;
            foreach (CONTENT_IMAGE_KEYS as $imageKey) {
                if (isset($submittedContent[$imageKey]) && is_string($submittedContent[$imageKey])) {
                    $allImages[$imageKey] = trim($submittedContent[$imageKey]);
                }
            }
            $imageValues = array_values(array_filter($allImages, static function (string $path): bool {
                return $path !== '';
            }));
            $imagesAreUnique = count($imageValues) === count(array_unique($imageValues));
            $imagesAreValid = true;
            foreach ($allImages as $imagePath) {
                if ($imagePath === '') {
                    continue;
                }
                $fullPath = realpath(__DIR__ . '/../' . $imagePath);
                $imageRoot = realpath(__DIR__ . '/../assets/images');
                if ($imageRoot === false || $fullPath === false || strpos($fullPath, $imageRoot . DIRECTORY_SEPARATOR) !== 0) {
                    $imagesAreValid = false;
                    break;
                }
            }

            $invalidModuleList = false;
            $existingKeys = $database->query('SELECT content_key FROM site_content')->fetchAll(PDO::FETCH_COLUMN);
            foreach (['features.modules', 'features.home_modules'] as $listKey) {
                if (!isset($submittedContent[$listKey]) || !is_string($submittedContent[$listKey])) {
                    continue;
                }
                $moduleNames = array_filter(array_map('trim', explode(',', $submittedContent[$listKey])));
                if (count($moduleNames) !== count(array_unique($moduleNames))) {
                    $invalidModuleList = true;
                    break;
                }
                foreach ($moduleNames as $moduleName) {
                    if (!preg_match('/^[a-z]+$/D', $moduleName)
                        || !in_array('features.title_' . $moduleName, $existingKeys, true)
                        || !in_array('features.copy_' . $moduleName, $existingKeys, true)
                        || !in_array('features.icon_' . $moduleName, $existingKeys, true)
                        || !in_array('features.image_' . $moduleName, $existingKeys, true)
                    ) {
                        $invalidModuleList = true;
                        break 2;
                    }
                }
            }

            if (isset($submittedContent['site.website_url'])
                && (!is_string($submittedContent['site.website_url'])
                    || !in_array(parse_url($submittedContent['site.website_url'], PHP_URL_SCHEME), ['http', 'https'], true)
                    || !filter_var($submittedContent['site.website_url'], FILTER_VALIDATE_URL))
            ) {
                $error = 'Enter a valid website URL beginning with http:// or https://.';
            } elseif (isset($submittedContent['site.phone_link'])
                && (!is_string($submittedContent['site.phone_link']) || !preg_match('/^\+?[0-9 ]+$/D', $submittedContent['site.phone_link']))
            ) {
                $error = 'Enter a phone number using digits, spaces and an optional leading plus sign.';
            } elseif ($invalidModuleList) {
                $error = 'The feature module list contains an invalid or repeated module.';
            } elseif (!$imagesAreUnique) {
                $error = 'Each page must use a different image.';
            } elseif (!$imagesAreValid) {
                $error = 'Choose an existing image from the site image folder.';
            } else {
                $database->beginTransaction();
                try {
                    $update = $database->prepare('UPDATE site_content SET content_value = :value WHERE content_key = :key');
                    foreach ($submittedContent as $key => $value) {
                        if (!is_string($key) || !in_array($key, $existingKeys, true) || !is_string($value) || strlen($value) > 10000) {
                            throw new InvalidArgumentException('One or more content fields are invalid.');
                        }
                        $update->execute([':value' => $value, ':key' => $key]);
                    }
                    $database->commit();
                    $notice = 'Site content updated.';
                } catch (Throwable $exception) {
                    $database->rollBack();
                    error_log('Flexi Retail admin content update failed: ' . $exception->getMessage());
                    $error = 'Content could not be saved. Please review the fields and try again.';
                }
            }
        }
    }
}

$isAdmin = isset($_SESSION['admin_id']);
$groups = [];
$inquiries = [];
if ($isAdmin) {
    $groups = $database->query('SELECT content_key, content_group, content_label, content_value FROM site_content ORDER BY content_group, content_key')->fetchAll();
    $inquiries = $database->query('SELECT id, inquiry_type, sender_name, sender_email, company, message, created_at FROM inquiries ORDER BY created_at DESC LIMIT 50')->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h(content('admin.title')) ?> | <?= h(content('site.name')) ?></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/flexi.css" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-topbar"><a href="../index.php"><?= h(content('site.name')) ?></a><span><?= h(content('admin.title')) ?></span></div>
    <?php if (!$isAdmin): ?>
        <main class="admin-login">
            <h1><?= h(content('admin.login_heading')) ?></h1>
            <p><?= h(content('admin.login_copy')) ?></p>
            <?php if ($error !== ''): ?><div class="alert alert-warning" role="alert"><?= h($error) ?></div><?php endif; ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                <div class="mb-3"><label class="form-label" for="username"><?= h(content('admin.username_label')) ?></label><input class="form-control" id="username" name="username" autocomplete="username" required></div>
                <div class="mb-3"><label class="form-label" for="password"><?= h(content('admin.password_label')) ?></label><input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required></div>
                <button class="btn btn-primary w-100" type="submit" name="login" value="1"><?= h(content('admin.login_button')) ?></button>
            </form>
            <a class="admin-back-link" href="../index.php"><?= h(content('admin.back_link')) ?></a>
        </main>
    <?php else: ?>
        <main class="admin-main container-fluid">
            <div class="admin-heading-row">
                <div><p class="eyebrow"><?= h(content('admin.dashboard_eyebrow')) ?></p><h1><?= h(content('admin.dashboard_heading')) ?></h1></div>
                <form method="post" action="?logout=1"><input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>"><button class="btn btn-outline-dark" type="submit"><?= h(content('admin.logout_button')) ?></button></form>
            </div>
            <?php if ($error !== ''): ?><div class="alert alert-warning" role="alert"><?= h($error) ?></div><?php endif; ?>
            <?php if ($notice !== ''): ?><div class="alert alert-success" role="status"><?= h($notice) ?></div><?php endif; ?>
            <section class="admin-panel">
                <h2><?= h(content('admin.content_heading')) ?></h2>
                <p><?= h(content('admin.content_copy')) ?></p>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                    <?php $currentGroup = ''; foreach ($groups as $item): ?>
                        <?php if ($currentGroup !== $item['content_group']): $currentGroup = $item['content_group']; ?>
                            <?php if ($currentGroup !== ''): ?></div><?php endif; ?>
                            <div class="admin-content-group"><h3><?= h($currentGroup) ?></h3>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label" for="content-<?= h($item['content_key']) ?>"><?= h($item['content_label']) ?></label>
                            <textarea class="form-control" id="content-<?= h($item['content_key']) ?>" name="content[<?= h($item['content_key']) ?>]" rows="<?= in_array($item['content_key'], CONTENT_IMAGE_KEYS, true) ? '1' : '2' ?>"><?= h($item['content_value']) ?></textarea>
                            <small class="text-muted"><?= h($item['content_key']) ?></small>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($groups !== []): ?></div><?php endif; ?>
                    <button class="btn btn-primary" type="submit" name="save_content" value="1"><?= h(content('admin.save_button')) ?></button>
                </form>
            </section>
            <section class="admin-panel">
                <h2><?= h(content('admin.inquiries_heading')) ?></h2>
                <p><?= h(content('admin.inquiries_copy')) ?></p>
                <?php if ($inquiries === []): ?><p><?= h(content('admin.no_inquiries')) ?></p><?php else: ?>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <article class="inquiry-card">
                            <div class="d-flex flex-wrap justify-content-between gap-2"><strong><?= h($inquiry['sender_name']) ?> &mdash; <?= h($inquiry['inquiry_type']) ?></strong><time><?= h($inquiry['created_at']) ?></time></div>
                            <a href="mailto:<?= h($inquiry['sender_email']) ?>"><?= h($inquiry['sender_email']) ?></a>
                            <?php if ($inquiry['company'] !== ''): ?><p><?= h($inquiry['company']) ?></p><?php endif; ?>
                            <p><?= nl2br(h($inquiry['message'])) ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    <?php endif; ?>
</body>
</html>
