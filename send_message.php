<?php
session_start();
include 'connection.php';

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$message')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Votre message a été envoyé avec succès!";
    } else {
        $_SESSION['error_message'] = "Erreur lors de l'envoi du message.";
    }
    
    header('Location: contact.php');
    exit();
} else {
    header('Location: contact.php');
    exit();
}
?>