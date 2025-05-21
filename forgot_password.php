<?php
// forgot_password.php

// Include configuration file for database connection
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 1800; // Token expires in 30 minutes

        // Insert token into database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $email, $token, $expires);
        $stmt->execute();

        // Send email with reset link
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $subject = "Reset Your Password";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo '<p class="alert alert-success">Tautan reset password telah dikirim ke email Anda.</p>';
        } else {
            echo '<p class="alert alert-danger">Gagal mengirim email. Coba lagi nanti.</p>';
        }
    } else {
        echo '<p class="alert alert-danger">Email tidak ditemukan.</p>';
    }
}
?>
