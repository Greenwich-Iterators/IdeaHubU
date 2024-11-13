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
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cPassword = $_POST['cPassword'];




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

        $mail->setFrom($email, $firstName);
        // Gmail address which you used as SMTP server
        $mail->addAddress('celestusplatinum@gmail.com');
        // Email address where you want to receive emails ( you can use any of your gmail address including the gmail address which you used as SMTP server )
        $mail->addReplyTo('unideahub', 'Uni Idea Hub-inquiries desk');

        $mail->isHTML(true);
        $mail->Subject = 'Uni Idea Hub > New Member registration';
        $mail->Body =     "You have received an online registration with the following details:<br>
		<p style=font-size:1em; font-family:Open Sans;>Full Names : $firstName </p><br>
						Full Names: $firstName $lastName<br><br>
						Email: $email ";


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
