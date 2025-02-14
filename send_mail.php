<?php
$to = "23co26@aiktc.ac.in"; // Replace with the recipient's email address
$subject = "Test Email from Localhost";
$message = "Hello! This is a test email sent from PHP on localhost.";
$headers = "From: "23co26@aiktc.ac.in"; // Replace with your email address

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Failed to send email.";
}
?>
