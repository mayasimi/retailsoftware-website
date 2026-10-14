<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/email-template.php';

function redirectWithError(string $destination, string $message): void
{
    $_SESSION['form_error'] = $message;
    header('Location: ' . $destination);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

if (!validateCsrfToken()) {
    redirectWithError('contact.php', 'Your form session expired. Please try again.');
}

$type = $_POST['inquiry_type'] ?? '';
$returnPage = $type === 'demo' ? 'demo.php' : 'contact.php';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$company = $_POST['company'] ?? '';
$message = $_POST['message'] ?? '';

if (!is_string($type) || !is_string($name) || !is_string($email) || !is_string($company) || !is_string($message)) {
    redirectWithError($returnPage, 'Please check the form fields and try again.');
}

$name = trim($name);
$email = trim($email);
$company = trim($company);
$message = trim($message);

if (!in_array($type, ['contact', 'demo'], true)
    || $name === ''
    || strlen($name) > 120
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($email) > 254
    || strlen($company) > 160
    || $message === ''
    || strlen($message) > 5000
) {
    redirectWithError($returnPage, 'Please check the form fields and try again.');
}

$inquiry = [
    'type' => $type,
    'name' => $name,
    'email' => $email,
    'company' => $company,
    'message' => $message,
];

try {
    $statement = database()->prepare(
        'INSERT INTO inquiries (inquiry_type, sender_name, sender_email, company, message) VALUES (:type, :name, :email, :company, :message)'
    );
    $statement->execute([
        ':type' => $type,
        ':name' => $name,
        ':email' => $email,
        ':company' => $company,
        ':message' => $message,
    ]);
} catch (PDOException $exception) {
    error_log('Flexi Retail inquiry could not be saved: ' . $exception->getMessage());
    redirectWithError($returnPage, 'We could not save your message just now. Please try again shortly.');
}

$mailerAvailable = is_file(__DIR__ . '/vendor/autoload.php');
$mailErrors = [];

if ($mailerAvailable) {
    require_once __DIR__ . '/vendor/autoload.php';

    $recipients = [
        ['email' => $email, 'name' => 'Customer', 'kind' => 'sender'],
        ['email' => 'mail@flexisoftware.ng', 'name' => 'Flexi Retail administration', 'kind' => 'admin'],
        ['email' => 'info@goodworthspartners.com', 'name' => 'Goodworth Partners administration', 'kind' => 'admin'],
    ];
    $adminSubjectKey = $type === 'demo' ? 'email.admin_subject_demo' : 'email.admin_subject_contact';

    foreach ($recipients as $recipientInfo) {
        $recipient = $recipientInfo['email'];
        try {
            $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
            $mailer->isSMTP();
            $mailer->Host = getenv('SMTP_HOST') ?: '';
            $mailer->SMTPAuth = true;
            $mailer->Username = getenv('SMTP_USERNAME') ?: '';
            $mailer->Password = getenv('SMTP_PASSWORD') ?: '';
            $mailer->Port = (int) (getenv('SMTP_PORT') ?: '587');
            $mailer->Timeout = 15;
            $encryption = strtolower(getenv('SMTP_ENCRYPTION') ?: 'tls');
            if ($encryption === 'ssl') {
                $mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                throw new RuntimeException('SMTP_ENCRYPTION must be tls or ssl.');
            }

            $fromAddress = getenv('SMTP_FROM_EMAIL') ?: $mailer->Username;
            if (!filter_var($fromAddress, FILTER_VALIDATE_EMAIL) || $mailer->Host === '' || $mailer->Username === '' || $mailer->Password === '') {
                throw new RuntimeException('SMTP_HOST, SMTP_USERNAME, SMTP_PASSWORD, and a valid SMTP_FROM_EMAIL must be configured.');
            }
            $mailer->CharSet = 'UTF-8';
            $mailer->setFrom($fromAddress, content('site.name'));
            $mailer->addAddress($recipient, $recipientInfo['name']);
            $mailer->addReplyTo($email, $name);
            $mailer->isHTML(true);

            if ($recipientInfo['kind'] === 'sender') {
                $mailer->Subject = content('email.sender_subject');
                $mailer->Body = emailHtml(content('email.sender_heading'), content('email.sender_copy'));
                $mailer->AltBody = content('email.sender_heading') . "\n\n" . content('email.sender_copy');
            } else {
                $mailer->Subject = content($adminSubjectKey);
                $mailer->Body = emailHtml(content('email.admin_heading'), content('email.admin_copy'), $inquiry);
                $mailer->AltBody = content('email.admin_heading') . "\n\n"
                    . content('email.label_name') . ': ' . $name . "\n"
                    . content('email.label_email') . ': ' . $email . "\n"
                    . content('email.label_company') . ': ' . ($company !== '' ? $company : content('email.not_provided')) . "\n"
                    . content('email.label_type') . ': ' . ($type === 'demo' ? content('email.label_demo') : content('email.label_contact')) . "\n\n"
                    . content('email.label_message') . ":\n" . $message;
            }

            $mailer->send();
        } catch (Throwable $exception) {
            $mailErrors[] = $recipient;
            error_log('Flexi Retail email notification to ' . $recipient . ' failed: ' . $exception->getMessage());
        }
    }
} else {
    $mailErrors[] = 'all recipients';
    error_log('Flexi Retail email notifications were not sent because Composer dependencies are missing.');
}

$status = $mailErrors === [] ? 'sent=1' : 'mail_failed=1';
header('Location: ' . $returnPage . '?' . $status);
exit;
