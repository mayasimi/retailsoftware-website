<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$username = getenv('ADMIN_USERNAME');
$password = getenv('ADMIN_INITIAL_PASSWORD');
if (!is_string($username) || $username === '' || !is_string($password) || strlen($password) < 12) {
    fwrite(STDERR, "Set ADMIN_USERNAME and ADMIN_INITIAL_PASSWORD (at least 12 characters) before running this script.\n");
    exit(1);
}

$statement = database()->prepare('INSERT INTO admin_users (username, password_hash) VALUES (:username, :hash)');
$statement->execute([
    ':username' => $username,
    ':hash' => password_hash($password, PASSWORD_DEFAULT),
]);
fwrite(STDOUT, "Admin account created.\n");
