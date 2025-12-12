<?php
/**
 * Contact Form Email Handler
 * Plastoproof Website - Production Version
 */

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$contact = trim($_POST['contact'] ?? $_POST['phone'] ?? '');
$company = trim($_POST['company'] ?? $_POST['subject'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Load PHPMailer
try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    } else {
        $path = __DIR__ . '/vendor/phpmailer/src/';
        require_once $path . 'Exception.php';
        require_once $path . 'PHPMailer.php';
        require_once $path . 'SMTP.php';
    }
} catch (Exception $e) {
    error_log("PHPMailer load error: " . $e->getMessage());
}

// Load SMTP config
$config = file_exists(__DIR__ . '/config/smtp.php') ? include __DIR__ . '/config/smtp.php' : [];

$smtpHost = $config['host'] ?? 'smtp.gmail.com';
$smtpPort = $config['port'] ?? 587;
$smtpUser = $config['username'] ?? 'sharmakirti59822@gmail.com';
$smtpPass = $config['password'] ?? 'ybpb elqt haps brrs';
$fromEmail = $config['from_email'] ?? $smtpUser;
$fromName = $config['from_name'] ?? 'Plastoproof Website';
$toEmail = $config['to_email'] ?? 'sharmakirti59822@gmail.com';

// Prepare email
$subject = 'Website Enquiry from ' . $name;
if (!empty($company)) $subject .= ' (' . $company . ')';

$body = "=== NEW WEBSITE ENQUIRY ===\n\n";
$body .= "Date: " . date('F j, Y g:i A') . "\n";
$body .= "Name: " . $name . "\n";
$body .= "Email: " . $email . "\n";
if (!empty($contact)) $body .= "Contact: " . $contact . "\n";
if (!empty($company)) $body .= "Company: " . $company . "\n";
$body .= "\nMessage:\n" . $message . "\n";
$body .= "\n=============================\n";

// Try PHPMailer first
if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = 'ssl'; // SSL for better compatibility
        $mail->Port = 465; // SSL port
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 60;
        $mail->SMTPKeepAlive = true;
        
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'verify_depth' => 0
            ]
        ];
        
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        $mail->addReplyTo($email, $name);
        
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        if ($mail->send()) {
            echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
            exit;
        }
    } catch (Exception $e) {
        error_log("PHPMailer error: " . $e->getMessage());
        // Fall through to mail() function
    }
}

// Fallback to mail()
$headers = "From: $fromName <$fromEmail>\r\n";
$headers .= "Reply-To: $name <$email>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($toEmail, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unable to send email. Please try again later.']);
}
?>
