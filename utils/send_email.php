<?php
function sendEmail($to, $subject, $message, $attachmentPath = null) {
    $from = "your_email@gmail.com";
    $fromName = "AgriCycle";

    $headers = "From: $fromName <$from>\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Use SMTP to send via Gmail
    $smtpServer = "smtp.gmail.com";
    $port = 587;
    $username = "your_email@gmail.com";
    $password = "your_app_password"; // generate from Gmail -> App Passwords

    $smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, 10);
    if (!$smtpConnect) {
        echo "Connection failed: $errstr ($errno)";
        return false;
    }

    // Simple SMTP dialogue
    fputs($smtpConnect, "EHLO $smtpServer\r\n");
    fputs($smtpConnect, "AUTH LOGIN\r\n");
    fputs($smtpConnect, base64_encode($username) . "\r\n");
    fputs($smtpConnect, base64_encode($password) . "\r\n");
    fputs($smtpConnect, "MAIL FROM: <$from>\r\n");
    fputs($smtpConnect, "RCPT TO: <$to>\r\n");
    fputs($smtpConnect, "DATA\r\n");

    $emailContent = "Subject: $subject\r\n$headers\r\n\r\n$message\r\n.\r\n";
    fputs($smtpConnect, $emailContent);
    fputs($smtpConnect, "QUIT\r\n");

    fclose($smtpConnect);
    return true;
}

?>