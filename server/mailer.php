<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function sendMail($to, $name, $subject, $message)
{
    $from = "support@brighpartinvestment.com";
    $namefrom = $name;

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'server143.web-hosting.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $from;
        $mail->Password   = 'Samuel252.'; // safer

        // Port 465 = SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($from, $namefrom);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log($mail->ErrorInfo);

        return false;
    }
}