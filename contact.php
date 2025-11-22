<?php 
session_start();
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Contact - MielShop</title>
</head>
<body>

<section class="contact-form">
    <h2>Contactez-nous</h2>
    
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    
    <form method="post" action="send_message.php">
        <input type="text" name="name" placeholder="Votre nom" required>
        <input type="email" name="email" placeholder="Votre email" required>
        <textarea name="message" rows="6" placeholder="Votre message" required></textarea>
        <button type="submit" class="btn">Envoyer</button>
    </form>
</section>

<style>
.contact-form {
    width: 50%;
    margin: 8rem auto 4rem;
    text-align: center;
    padding: 2rem;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.contact-form h2 {
    margin-bottom: 2rem;
    color: #fcc927;
    font-size: 2rem;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-family: inherit;
}

.contact-form textarea {
    resize: vertical;
    min-height: 150px;
}

.contact-form .btn {
    width: 100%;
    padding: 1rem;
    background: #fcc927;
    border: none;
    border-radius: 10px;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.contact-form .btn:hover {
    background: #e6b800;
}

.success-message {
    background: #4CAF50;
    color: white;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
}

.error-message {
    background: #f44336;
    color: white;
    padding: 1rem;
    border-radius: 5px;
    margin-bottom: 1rem;
}

@media(max-width: 768px) {
    .contact-form {
        width: 90%;
    }
}
</style>

</body>
</html>