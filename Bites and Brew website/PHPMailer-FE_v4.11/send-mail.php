<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';      // Your Gmail
        $mail->Password   = 'your-app-password';         // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Bites & Brew Website');
        $mail->addAddress('your-email@gmail.com');       // Receiving email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Message from Bites & Brew Website';
        $mail->Body    = "<strong>Name:</strong> $name <br>
                          <strong>Email:</strong> $email <br>
                          <strong>Message:</strong> $message";

        $mail->send();
        echo "<p class='success'>Message sent successfully!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }
}
?>
