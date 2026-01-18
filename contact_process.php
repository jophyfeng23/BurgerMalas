<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $recaptcha_secret = '6Ld0SQcsAAAAALgzmXjS1rY_M62KGrBIz6PmRdNG'; // <--- PUT SECRET KEY HERE
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$recaptcha_response);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        die("Recaptcha verification failed. Please try again.");
    }

    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? 'No Subject'));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'burgermalas25@gmail.com';
        $mail->Password = ''; // App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('burgermalas25@gmail.com', 'Burger Malas Website');
        $mail->addAddress('burgermalas25@gmail.com', 'Burger Malas Team');

        if (!empty($email) && !empty($name)) {
            $mail->addReplyTo($email, $name);
        }

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <strong>Name:</strong> {$name}<br>
            <strong>Email:</strong> {$email}<br><br>
            <strong>Message:</strong><br>" . nl2br($message);

        $mail->send();
        echo "Thank you! Your message has been sent. We will try get back to you within 24 hours.";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}



