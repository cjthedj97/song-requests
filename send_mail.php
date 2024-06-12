<?php
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($emailMessage) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        //Recipients
        $mail->setFrom(FROM_EMAIL, 'Music Request');
        $mail->addAddress(TO_EMAIL);

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Music Request';
        $mail->Body    = $emailMessage;

        $mail->send();
        return true;
    } catch (Exception $e) {
        logError('PHPMailer error: ' . $mail->ErrorInfo);
        return false;
    }
}
?>
