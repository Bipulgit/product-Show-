<?php
/**
 * Email Test Script - Direct PHPMailer Test
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Email Test</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .box{background:white;padding:20px;margin:10px 0;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} .success{color:green;} .error{color:red;} pre{background:#f0f0f0;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
echo "</head><body>";

echo "<h1>📧 Email Configuration Test</h1>";

// Test 1: Load PHPMailer
echo "<div class='box'><h3>1. Loading PHPMailer...</h3>";
try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        echo "<span class='success'>✓ Autoloader loaded</span><br>";
    } else {
        $path = __DIR__ . '/vendor/phpmailer/phpmailer/src/';
        require_once $path . 'Exception.php';
        require_once $path . 'PHPMailer.php';
        require_once $path . 'SMTP.php';
        echo "<span class='success'>✓ Direct include loaded</span><br>";
    }
    
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        echo "<span class='success'>✓ PHPMailer class available</span>";
    }
} catch (Exception $e) {
    echo "<span class='error'>✗ Error: " . $e->getMessage() . "</span>";
}
echo "</div>";

// Test 2: Load Config
echo "<div class='box'><h3>2. Loading Configuration...</h3>";
$config = file_exists(__DIR__ . '/config/smtp.php') ? include __DIR__ . '/config/smtp.php' : [];
echo "Host: <strong>" . ($config['host'] ?? 'smtp.gmail.com') . "</strong><br>";
echo "Port: <strong>" . ($config['port'] ?? 587) . "</strong><br>";
echo "Username: <strong>" . ($config['username'] ?? 'Not set') . "</strong><br>";
echo "Password: <strong>" . (isset($config['password']) && !empty($config['password']) ? '****** (Set)' : 'Not set') . "</strong><br>";
echo "</div>";

// Test 3: Send Test Email
echo "<div class='box'><h3>3. Sending Test Email...</h3>";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    
    // Try Port 465 with SSL (better for shared hosting)
    $mail->isSMTP();
    $mail->Host = $config['host'] ?? 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'] ?? 'sharmakirti59822@gmail.com';
    $mail->Password = $config['password'] ?? 'ybpb elqt haps brrs';
    $mail->SMTPSecure = 'ssl'; // Use SSL instead of TLS
    $mail->Port = 465; // SSL port instead of 587
    $mail->CharSet = 'UTF-8';
    
    // Increased timeout for slow servers
    $mail->Timeout = 60;
    $mail->SMTPKeepAlive = true;
    
    // Debug output
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';
    
    // SSL options - relaxed for shared hosting
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            'verify_depth' => 0
        ]
    ];
    
    // Email content
    $mail->setFrom($mail->Username, 'Plastoproof Test');
    $mail->addAddress($mail->Username);
    $mail->Subject = 'Test Email - ' . date('Y-m-d H:i:s');
    $mail->Body = "This is a test email sent at " . date('Y-m-d H:i:s') . "\n\nIf you receive this, your email configuration is working correctly!\n\nSent from: " . $_SERVER['SERVER_NAME'] ?? 'Unknown Server';
    
    echo "<div style='background:#e3f2fd;padding:15px;margin:10px 0;border-radius:5px;'>";
    echo "<strong>Attempting connection with:</strong><br>";
    echo "• Port: 465 (SSL)<br>";
    echo "• Host: smtp.gmail.com<br>";
    echo "• Timeout: 60 seconds<br>";
    echo "</div>";
    
    echo "<pre style='background:#f8f9fa;padding:15px;border:1px solid #dee2e6;'>";
    
    if ($mail->send()) {
        echo "</pre>";
        echo "<h2 class='success'>✅ SUCCESS!</h2>";
        echo "<p>Email sent successfully to: <strong>" . $mail->Username . "</strong></p>";
        echo "<p>Check your inbox!</p>";
    } else {
        echo "</pre>";
        echo "<h2 class='error'>❌ FAILED</h2>";
        echo "<p>Error: " . $mail->ErrorInfo . "</p>";
    }
    
} catch (Exception $e) {
    echo "</pre>";
    echo "<h2 class='error'>❌ Exception</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    
    // Show alternative solution
    echo "<div style='background:#fff3cd;padding:15px;margin:10px 0;border-radius:5px;border:1px solid #ffc107;'>";
    echo "<h4>⚠️ Connection Failed - Trying Alternative Method...</h4>";
    echo "<p>SMTP ports might be blocked on this server. Trying PHP mail() function...</p>";
    echo "</div>";
    
    // Fallback to mail() function
    try {
        $to = $config['username'] ?? 'sharmakirti59822@gmail.com';
        $subject = 'Fallback Test Email - ' . date('Y-m-d H:i:s');
        $message = "This is a fallback test email using PHP mail() function.\n\nSent at: " . date('Y-m-d H:i:s');
        $headers = "From: noreply@" . ($_SERVER['SERVER_NAME'] ?? 'plastoproof.com') . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        if (mail($to, $subject, $message, $headers)) {
            echo "<h2 class='success'>✅ FALLBACK SUCCESS!</h2>";
            echo "<p>Email sent using PHP mail() function to: <strong>$to</strong></p>";
            echo "<p>Check your inbox!</p>";
        } else {
            echo "<h2 class='error'>❌ FALLBACK ALSO FAILED</h2>";
            echo "<p>Both SMTP and mail() function failed.</p>";
            echo "<p><strong>Solution:</strong> Contact your hosting provider to:</p>";
            echo "<ul>";
            echo "<li>Enable SMTP ports (465 or 587)</li>";
            echo "<li>Enable PHP mail() function</li>";
            echo "<li>Check if your IP is blacklisted</li>";
            echo "</ul>";
        }
    } catch (Exception $e2) {
        echo "<h2 class='error'>❌ Error in fallback</h2>";
        echo "<p>" . $e2->getMessage() . "</p>";
    }
    
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>";

// Test 4: System Info
echo "<div class='box'><h3>4. System Information</h3>";
echo "PHP Version: <strong>" . phpversion() . "</strong><br>";
echo "OpenSSL Extension: " . (extension_loaded('openssl') ? "<span class='success'>✓ Enabled</span>" : "<span class='error'>✗ Disabled</span>") . "<br>";
echo "Sockets Extension: " . (extension_loaded('sockets') ? "<span class='success'>✓ Enabled</span>" : "<span class='error'>✗ Disabled</span>") . "<br>";
echo "Current Time: <strong>" . date('Y-m-d H:i:s') . "</strong><br>";
echo "</div>";

echo "<div class='box' style='background:#fff3cd;border:1px solid #ffc107;'>";
echo "<h3>⚠️ Important Notes:</h3>";
echo "<ul>";
echo "<li>If you see 'SUCCESS' above, email is working!</li>";
echo "<li>Check inbox: sharmakirti59822@gmail.com</li>";
echo "<li>Look for test email with current timestamp</li>";
echo "<li>If failed, check the SMTP debug output above</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
?>
