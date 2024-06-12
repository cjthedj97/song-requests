<?php
require 'send_mail.php';

// Define constants for Turnstile and SMTP
define('SECRET_KEY', '1x0000000000000000000000000000000AA');
define('SMTP_HOST', 'Enter_SMTP_Server_Here');
define('SMTP_PORT', 587);
define('SMTP_USER', 'Enter_Username_Here');
define('SMTP_PASS', 'Enter_Password_Here');
define('FROM_EMAIL', 'example_email@example.com');
define('TO_EMAIL', 'example_email@example.com');

function logError($message) {
    error_log($message, 3, 'error.log'); // Log errors to error.log file
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['cf-turnstile-response'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    $data = [
        'secret' => SECRET_KEY,
        'response' => $token,
        'remoteip' => $ip
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        logError('Error verifying Turnstile: Unable to contact verification URL.');
        include 'capcha_error.html';
        exit;
    }

    $outcome = json_decode($result);

    if ($outcome && $outcome->success) {
        $requesterName = $_POST['fullName'];
        $artistName = $_POST['artist'];
        $songTitle = $_POST['song'];
        $notes = $_POST['message'];

        $emailMessage = "
        Requester Name: $requesterName
        Artist: $artistName
        Song Title: $songTitle
        Notes: $notes
        ";

        if (sendMail($emailMessage)) {
            include 'success.html';
        } else {
            // To Disable Error logging for PHP Mailer comment out the line Below
            logError('Error sending email: PHPMailer error.');
            include 'error.html';
        }
    } else {
	// To Disable Error Logging For Turnstile Comment out the next three lines
        $errorMessage = isset($outcome->{'error-codes'}) ? implode(', ', $outcome->{'error-codes'}) : 'Unknown error during Turnstile verification.';
        logError("Turnstile response: " . json_encode($outcome)); // Log the full response
        logError("Turnstile verification failed: $errorMessage");
        include 'capcha_error.html';
    }
} else {
    include 'form.html';
}
?>
