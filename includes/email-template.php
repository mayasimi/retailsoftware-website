<?php
declare(strict_types=1);

function emailHtml(string $heading, string $intro, array $inquiry = []): string
{
    $details = '';
    if ($inquiry !== []) {
        $rows = [
            content('email.label_name') => $inquiry['name'],
            content('email.label_email') => $inquiry['email'],
            content('email.label_company') => $inquiry['company'] !== '' ? $inquiry['company'] : content('email.not_provided'),
            content('email.label_type') => $inquiry['type'] === 'demo' ? content('email.label_demo') : content('email.label_contact'),
            content('email.label_message') => nl2br(h($inquiry['message'])),
        ];
        foreach ($rows as $label => $value) {
            $details .= '<tr><th align="left" valign="top" style="padding:12px;border-bottom:1px solid #eee8dc;color:#4a453b">' . h($label) . '</th><td style="padding:12px;border-bottom:1px solid #eee8dc;color:#24211b">' . ($label === content('email.label_message') ? $value : h($value)) . '</td></tr>';
        }
        $details = '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:24px 0">' . $details . '</table>';
    }

    return '<!doctype html><html><body style="margin:0;padding:32px 12px;background:#f7f5f0;font-family:Arial,sans-serif;color:#24211b">'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden">'
        . '<tr><td style="padding:24px 32px;background:#191815;border-bottom:4px solid #c5a04a;color:#fff;font-size:18px;font-weight:bold">' . h(content('site.name')) . '</td></tr>'
        . '<tr><td style="padding:32px"><h1 style="margin:0 0 16px;font-size:25px;color:#191815">' . h($heading) . '</h1><p style="margin:0;line-height:1.7;color:#514c43">' . h($intro) . '</p>'
        . $details
        . '<p style="margin:24px 0 0;color:#777267;font-size:13px;line-height:1.6">' . h(content('email.footer')) . '</p></td></tr>'
        . '</table></body></html>';
}
