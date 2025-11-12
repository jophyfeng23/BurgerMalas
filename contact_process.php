<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $fileName = $_FILES['attachment']['name'];
        $tmpName = $_FILES['attachment']['tmp_name'];
        $uploadDir = 'uploads/';
        move_uploaded_file($tmpName, $uploadDir . $fileName);
    }

    // Process form 
    $to = "burgermalas25@gmail.com";
    $subject = "New Contact Form Submission";
    $headers = "From: $email" . "\r\n" . "Reply-To: $email";
    $body = "Name: $name\nEmail: $email\nMessage: $message\n";
    
    if (mail($to, $subject, $body, $headers)) {
        echo "Thank you! Your message has been sent. We will try get back to you within 24 hours.";
    } else {
        echo "Sorry, something went wrong. Please try again.";
    }
}
?>
