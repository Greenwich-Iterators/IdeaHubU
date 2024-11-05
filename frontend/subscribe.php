<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/vendor/autoload.php';
require_once 'phpmailer/vendor/phpmailer/phpmailer/Exception.php';
require_once 'phpmailer/vendor/phpmailer/phpmailer/PHPMailer.php';
require_once 'phpmailer/vendor/phpmailer/phpmailer/SMTP.php';
// include 'config.php';

$mail = new PHPMailer();




if (isset($_POST['subscribe-submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['newsletter-email']);

    if (!empty($email)) {
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

            $mail->setFrom($email);
            // Gmail address which you used as SMTP server
            $mail->addAddress('celestusplatinum@gmail.com');
            // Email address where you want to receive emails ( you can use any of your gmail address including the gmail address which you used as SMTP server )
            $mail->addReplyTo('Uni Idea Hub-inquiries desk');

            $mail->isHTML(true);
            $mail->Subject = 'Uni Idea Hub > New Member registration';
            $mail->Body =     "Dear Admin, <br>
            You have received a request for Newsletter updates from:<br>
		
        Email: $email <br> today at " . date("h:i:sa") . "<br>";

            $mail->send();


            $message[]  = '
                 <span>Your message has been sent!<br> Thank you for contacting the Amaka Gym Team.</span>
                </div>';
        } catch (Exception $e) {
            $message[] = 'Whoops!! Looks like we experienced a problem!.';
        }
    } else {
        $message[] = 'Whoops!! Looks like you did not provide your email address. <br> 
    Please try again.';
    }
}
