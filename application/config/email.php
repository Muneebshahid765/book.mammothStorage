<?php
defined('BASEPATH') or exit('No direct script access allowed');

// You can set these via server environment variables OR directly in this file on production.
$env = function ($key, $default = '') {
    $v = getenv($key);
    if ($v !== false && $v !== '') {
        return $v;
    }
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
        return $_SERVER[$key];
    }
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
        return $_ENV[$key];
    }
    return $default;
};

$config['smtp_host'] = $env('SMTP_HOST', '');
$config['smtp_user'] = $env('SMTP_USER', '');
$config['smtp_pass'] = $env('SMTP_PASS', '');
$config['smtp_port'] = (int) $env('SMTP_PORT', 587);
$config['smtp_crypto'] = $env('SMTP_CRYPTO', 'tls');

$useSmtp = ($config['smtp_user'] !== '' && $config['smtp_pass'] !== '' && $config['smtp_host'] !== '');
$config['protocol'] = $useSmtp ? 'smtp' : 'mail';

// Abandoned lead emails (support inbox). Override via env OR change here for production.
$config['abandoned_lead_to'] = $env('ABANDONED_LEAD_TO', 'hello@mammothstorage.com.au');
$config['abandoned_lead_from'] = $env('ABANDONED_LEAD_FROM', 'no-reply@mammothstorage.com.au');

// Optional: receive a copy for testing (comma-separated emails supported)

$config['abandoned_lead_bcc'] = $env('ABANDONED_LEAD_BCC', '');

$config['abandoned_cron_key'] = $env('ABANDONED_CRON_KEY', '');

$config['mailtype'] = 'text';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
