# SMTP Setup and PHPMailer Instructions

This file explains how to configure SMTP settings and PHPMailer for the Plastoproof website.

1) Install dependencies using Composer (server must have Composer installed):

```powershell
cd "C:\laragon\www\Plastoproof product page ready\Plastoproof website"
composer require phpmailer/phpmailer
```

Optional: install `vlucas/phpdotenv` to manage environment variables from a local `.env` file (useful for development):

```powershell
composer require vlucas/phpdotenv
```

2) Configure SMTP credentials via environment variables or server configuration. Recommended mechanism:
- Use a `.env` file (and a PHP dotenv loader) or set environment variables in your web server.
- Example variables (in `.env` or env):

```
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=your.email@gmail.com
SMTP_PASS=your-app-password
SMTP_FROM=your.email@gmail.com
SMTP_FROM_NAME=Plastoproof Website
SMTP_TO=info@plastoproof.com
SMTP_TO_NAME=Plastoproof
```

3) Gmail App Passwords:
- If you use Gmail with 2FA enabled, create an App Password and use it for `SMTP_PASS` (do not use your normal account password).
  See https://support.google.com/accounts/answer/185833
  PowerShell example to temporarily set the variables for a session (do NOT put passwords in source code):

  ```powershell
  $env:SMTP_HOST = 'smtp.gmail.com'
  $env:SMTP_PORT = '587'
  $env:SMTP_SECURE = 'tls'
  $env:SMTP_USER = 'sharmakirti59822@gmail.com'  # your Gmail account
  $env:SMTP_PASS = 'your-app-password'          # use an App Password for Gmail with 2FA
  $env:SMTP_FROM = 'sharmakirti59822@gmail.com'
  $env:SMTP_FROM_NAME = 'Plastoproof Website'
  $env:SMTP_TO = 'info@plastoproof.com'
  $env:SMTP_TO_NAME = 'Plastoproof'
  ```

  Windows (permanent) environment variables:
  - Open System Properties → Advanced → Environment Variables → Add user or system variables.
  - Or use `setx` in PowerShell (note: `setx` requires re-opened shell to take effect):

  ```powershell
  setx SMTP_USER "sharmakirti59822@gmail.com"
  setx SMTP_PASS "your-app-password"
  ```

  Security Note: Immediately rotate or revoke any credential you shared publicly. Avoid pasting credentials in chat or repo.
  See https://support.google.com/accounts/answer/185833

4) What the backend does:
- `send-mail.php` validates and sanitizes inputs from `POST` (name, email, message, phone/contact, company).
- Email is formatted as HTML and sent to `SMTP_TO` via PHPMailer.
- Returns JSON: `{ success: true|false, message: '...' }`.

5) Frontend integration:
- The contact page, home contact form, and enquiry popup forms are marked with `data-send-mail="true"` and JS submits them to `send-mail.php` via `fetch`.
- On success the user sees an inline success message and form is reset.

6) Security and production notes:
- Never commit credentials to source control.
- Use server environment variables or a secrets manager in production.
- Consider input throttling and CAPTCHA to prevent spam.
