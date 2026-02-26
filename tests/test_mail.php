<?php
/**
 * Test Email Sending
 */

require_once __DIR__ . '/includes/mail_helper.php';

$to = 'randythegreat000@gmail.com'; // Sending to self for test
$subject = 'Dynabio Email Test';
$body = '<h1>Success!</h1><p>This is a test email from your Dynabio project using PHPMailer.</p>';

echo "Attempting to send test email to $to...<br>\n";

$result = sendEmail($to, $subject, $body);

if ($result['success']) {
    echo "\n<b style='color: green;'>Test email sent successfully!</b>";
} else {
    echo "\n<b style='color: red;'>Failed to send test email.</b><br>\n";
    echo "Error: " . $result['error'];
}
?>