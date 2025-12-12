<?php
// config/smtp.php
// Returns SMTP configuration reading from environment variables.
// Do NOT add real credentials to this file in production. Use environment variables.

return [
    'host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'port' => getenv('SMTP_PORT') ?: 587,
    'secure' => getenv('SMTP_SECURE') ?: 'tls',
    'username' => getenv('SMTP_USER') ?: 'enquiry@plastoproof.com',
    'password' => getenv('SMTP_PASS') ?: 'ynjh xnvw aabz ulja',
    'from_email' => getenv('SMTP_FROM') ?: (getenv('SMTP_USER') ?: 'enquiry@plastoproof.com'),
    'from_name' => getenv('SMTP_FROM_NAME') ?: 'Plastoproof Website',
    // Default recipient: change via env var SMTP_TO in production if desired
    'to_email' => getenv('SMTP_TO') ?: 'enquiry@plastoproof.com',
    'to_name' => getenv('SMTP_TO_NAME') ?: 'Plastoproof Enquiries',
    // Debug mode: set SMTP_DEBUG=1 to return verbose mailer errors to client (dev only)
    'debug' => getenv('SMTP_DEBUG') ? true : true, // Enable debug temporarily
];
