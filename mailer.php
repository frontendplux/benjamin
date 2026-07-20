<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/vendor/autoload.php';

function sendMail($to, $name, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'server143.web-hosting.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'support@brighpartinvestment.com';
        $mail->Password = 'Samuel252.';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('support@brighpartinvestment.com', 'Brightpartinvestment');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();

    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
        return false;
    }
}