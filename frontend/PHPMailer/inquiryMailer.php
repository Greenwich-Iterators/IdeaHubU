<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/vendor/autoload.php';
require_once 'phpmailer/vendor/phpmailer/phpmailer/Exception.php';
require_once 'phpmailer/vendor/phpmailer/phpmailer/PHPMailer.php';
require_once 'phpmailer/vendor/phpmailer/phpmailer/SMTP.php';
include 'config.php';



$mail = new PHPMailer();
$alert = '';

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];




    try {
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        // Gmail address which you want to use as SMTP server
        $mail->Password = '';
        // Gmail address Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = '2525';

        $mail->setFrom($email, $name);
        // Gmail address which you used as SMTP server
        $mail->addAddress('celestusplatinum@gmail.com');
        // Email address where you want to receive emails ( you can use any of your gmail address including the gmail address which you used as SMTP server )
        $mail->addReplyTo('Uni Idea Hub-inquiries desk');

        $mail->isHTML(true);
        $mail->Subject = 'Uni Idea Hub > New Member registration';
        $mail->Body =     "You have received an inquiry email with the following details:<br>
		<p style=font-size:1em; font-family:Open Sans;>Full Names : $name </p><br>
        Email: $email <br><br>
        Phone: $phone <br><br>
        Subject: $subject <br><br>
        Message :<br><br> $message";

        $mail->send();
        $alert = '<div class="alert-success">
                 <span>Your message has been Sent!<br> Thank you for contacting Uni Idea Hub Team.</span>
                </div>';
    } catch (Exception $e) {
        $alert = '<div class="alert-error">
                <span>' . $e->getMessage() . '</span>
              </div>';
    }
}
