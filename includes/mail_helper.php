<?php
/**
 * Mail Helper
 * 
 * Manually loads PHPMailer and provides a simple function to send emails.
 * Uses credentials from config.php.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

/**
 * Send an email using Gmail SMTP
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email content (HTML supported)
 * @param string $altBody Plain text alternative
 * @return array [success => bool, error => string]
 */
function sendEmail($to, $subject, $body, $altBody = '')
{
    $mail = new PHPMailer(true);

    try {
        // Server settings — DEBUG_SERVER temporarily enabled to diagnose localhost issues
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function ($str, $level) {
            error_log("PHPMailer[$level]: $str");
        };
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;
        $mail->CharSet = 'UTF-8';

        /**
         * Bypass SSL verification
         * 
         * IMPORTANT: This is a workaround for local XAMPP environments with SSL certificate issues.
         * You may want to remove this block once the site is live if the server has proper certificates.
         */
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);

        // Prevent replies directly to the system inbox (skip on localhost)
        $host = parse_url(ACTUAL_WEB_URL, PHP_URL_HOST);
        if ($host && $host !== 'localhost' && $host !== '127.0.0.1') {
            $mail->addReplyTo('noreply@' . $host, 'No Reply');
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);

        $mail->send();
        return ['success' => true, 'error' => ''];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $mail->ErrorInfo];
    }
}
?>