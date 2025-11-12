<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Method Not Allowed");
}

// Google reCAPTCHA secret key
$recaptchaSecret = "6Ld0SQcsAAAAALgzmXjS1rY_M62KGrBIz6PmRdNG"; 

// Verify reCAPTCHA
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
    die("reCAPTCHA verification failed. Please try again.");
}

// Sanitize inputs
$name = htmlspecialchars($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($_POST['subject'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

// Email recipient
$to = "burgermalas25@gmail.com";

// Prepare email headers
$boundary = md5(time());
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

// Email body
$body = "--{$boundary}\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message\n\r\n";

// Handle attachment
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
    $fileTmp = $_FILES['attachment']['tmp_name'];
    $fileName = $_FILES['attachment']['name'];
    $fileData = chunk_split(base64_encode(file_get_contents($fileTmp)));
    $fileType = mime_content_type($fileTmp);

    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: {$fileType}; name=\"{$fileName}\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $fileData . "\r\n";
}

// End boundary
$body .= "--{$boundary}--";

// Send email
if (mail($to, "New Contact Form Submission", $body, $headers)) {
    echo "Thank you! Your message has been sent. We will try to get back to you within 24 hours.";
} else {
    echo "Sorry, something went wrong. Please try again.";
}
?>
