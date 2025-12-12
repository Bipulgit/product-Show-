<?php
/**
 * Enhanced send-mail.php using PHPMailer
 * Better error handling and Gmail compatibility
 */

// Allow CORS for development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Check if PHPMailer exists
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    // Try to load PHPMailer from vendor directory
    $vendorAutoload = __DIR__ . '/vendor/autoload.php';
    if (file_exists($vendorAutoload)) {
        require_once $vendorAutoload;
    } else {
        // Fallback to direct inclusion if available
        $phpmailerPath = __DIR__ . '/vendor/phpmailer/phpmailer/src/';
        if (file_exists($phpmailerPath . 'PHPMailer.php')) {
            require_once $phpmailerPath . 'PHPMailer.php';
            require_once $phpmailerPath . 'SMTP.php';
            require_once $phpmailerPath . 'Exception.php';
        }
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$contact = trim($_POST['contact'] ?? $_POST['phone'] ?? '');
$company = trim($_POST['company'] ?? $_POST['subject'] ?? '');

// Validate required fields
if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide name, email and message.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// Load SMTP config
$configPath = __DIR__ . '/config/smtp.php';
$smtpConfig = file_exists($configPath) ? include $configPath : [];

// Get configuration values
$host = $smtpConfig['host'] ?? 'smtp.gmail.com';
$port = $smtpConfig['port'] ?? 587;
$secure = $smtpConfig['secure'] ?? 'tls';
$username = $smtpConfig['username'] ?? 'enquiry@plastoproof.com';
$password = $smtpConfig['password'] ?? 'ynjh xnvw aabz ulja';
$from_email = $smtpConfig['from_email'] ?? $username;
$from_name = $smtpConfig['from_name'] ?? 'Plastoproof Website';
$to_email = $smtpConfig['to_email'] ?? 'enquiry@plastoproof.com';
$to_name = $smtpConfig['to_name'] ?? 'plastoproof Enquiries';
$debug = $smtpConfig['debug'] ?? true;

try {
    // Check if PHPMailer is available
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        // Use PHPMailer
        $mail = new PHPMailer(true);
        
        if ($debug) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer Debug: $str");
            };
        }
        
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $port;
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to_email, $to_name);
        $mail->addReplyTo($email, $name);
        
        // Content
        $subject = 'Website Enquiry from ' . $name;
        if ($company) {
            $subject .= ' (' . $company . ')';
        }
        
        $emailContent = "=== NEW WEBSITE ENQUIRY ===\n";
        $emailContent .= "Date: " . date('F j, Y \a\t g:i A') . "\n";
        $emailContent .= "Subject: " . $subject . "\n\n";
        $emailContent .= "Name: " . $name . "\n";
        $emailContent .= "Email: " . $email . "\n";
        if ($contact) {
            $emailContent .= "Contact: " . $contact . "\n";
        }
        if ($company) {
            $emailContent .= "Company: " . $company . "\n";
        }
        $emailContent .= "\nMessage:\n" . $message . "\n";
        $emailContent .= "=============================\n\n";
        
        $mail->Subject = $subject;
        $mail->Body = $emailContent;
        
        // Send email
        $result = $mail->send();
        
        if ($result) {
            // Save to log file for backup
            $logFile = __DIR__ . '/enquiries.log';
            file_put_contents($logFile, $emailContent, FILE_APPEND | LOCK_EX);
            
            // echo json_encode(['success' => true, 'message' => 'धन्यवाद! आपका संदेश सफलतापूर्वक भेज दिया गया है।']);
            // echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
            // echo json_encode(['success' => true, 'message' => '']);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Email could not be sent');
        }
        
    } else {
        // Fallback to mail() function if PHPMailer not available
        $subject = 'Website Enquiry from ' . $name;
        if ($company) {
            $subject .= ' (' . $company . ')';
        }
        
        $emailContent = "=== NEW WEBSITE ENQUIRY ===\n";
        $emailContent .= "Date: " . date('F j, Y \a\t g:i A') . "\n";
        $emailContent .= "Name: " . $name . "\n";
        $emailContent .= "Email: " . $email . "\n";
        if ($contact) {
            $emailContent .= "Contact: " . $contact . "\n";
        }
        if ($company) {
            $emailContent .= "Company: " . $company . "\n";
        }
        $emailContent .= "\nMessage:\n" . $message . "\n";
        $emailContent .= "=============================\n\n";
        
        $headers = "From: $from_name <$from_email>\r\n";
        $headers .= "Reply-To: $name <$email>\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $result = mail($to_email, $subject, $emailContent, $headers);
        
        if ($result) {
            // Save to log file for backup
            $logFile = __DIR__ . '/enquiries.log';
            file_put_contents($logFile, $emailContent, FILE_APPEND | LOCK_EX);
            
            // echo json_encode(['success' => true, 'message' => 'धन्यवाद! आपका संदेश सफलतापूर्वक भेज दिया गया है।']);
            // echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
            // echo json_encode(['success' => true, 'message' => '']);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Built-in mail() function failed');
        }
    }
    
} catch (Exception $e) {
    error_log("Mail Error: " . $e->getMessage());
    
    if ($debug) {
        http_response_code(502);
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to send email. Please try again later.',
            'debug' => [
                'error' => $e->getMessage(),
                'config_check' => [
                    'host' => $host,
                    'port' => $port,
                    'username' => $username,
                    'phpmailer_available' => class_exists('PHPMailer\PHPMailer\PHPMailer')
                ]
            ]
        ]);
    } else {
        http_response_code(502);
        echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again later.']);
    }
}
?>