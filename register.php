<?php
include 'connection.php';

$message = [];

if (isset($_POST['submit-btn'])) {
    // Sécurisation
    $name = mysqli_real_escape_string($conn, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Vérifier si email existe
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'") or die('query failed');

    if (mysqli_num_rows($check_email) > 0) {
        $message[] = "Cet email existe déjà !";
    } else {
        if ($password !== $cpassword) {
            $message[] = "Les mots de passe ne correspondent pas !";
        } else {
            // Hash sécurisé avec password_hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO users (name, email, password, user_type) VALUES('$name', '$email', '$hashed_password', 'user')") or die('query failed');
            $message[] = "Inscription réussie !";
            header('location: login.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Inscription - MielShop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fcc927 0%, #f9a825 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            overflow-x: hidden;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 3rem 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container h1 {
            font-size: 2.2rem;
            color: #333;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .form-container .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .message {
            background: #f44336;
            color: white;
            padding: 1rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: shake 0.3s ease;
        }

        .message.success {
            background: #4CAF50;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .message i {
            font-size: 1.3rem;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .message i:hover {
            transform: scale(1.2);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .input-box {
            position: relative;
        }

        .input-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.2rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #fcc927;
            box-shadow: 0 0 0 3px rgba(252, 201, 39, 0.1);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #fcc927 0%, #f9a825 100%);
            color: #000;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(252, 201, 39, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.95rem;
        }

        .form-footer a {
            color: #fcc927;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #e6b800;
            text-decoration: underline;
        }

        @media(max-width: 600px) {
            .form-container {
                padding: 2rem 1.5rem;
            }

            .form-container h1 {
                font-size: 1.8rem;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"] {
                padding: 0.9rem 0.9rem 0.9rem 2.8rem;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            $isSuccess = strpos($msg, 'réussie') !== false;
            echo '
            <div class="message '.($isSuccess ? 'success' : '').'">
                <span>'.htmlspecialchars($msg).'</span>
                <i class="bx bx-x" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
    ?>

    <form method="post">
        <h1>🍯 Créer un compte</h1>
        <p class="subtitle">Rejoignez MielShop pour découvrir nos miels naturels</p>

        <div class="input-box">
            <i class='bx bx-user'></i>
            <input type="text" name="name" placeholder="Votre nom complet" required>
        </div>

        <div class="input-box">
            <i class='bx bx-envelope'></i>
            <input type="email" name="email" placeholder="Votre adresse email" required>
        </div>

        <div class="input-box">
            <i class='bx bx-lock-alt'></i>
            <input type="password" name="password" placeholder="Mot de passe (min. 6 caractères)" required minlength="6">
        </div>

        <div class="input-box">
            <i class='bx bx-lock-alt'></i>
            <input type="password" name="cpassword" placeholder="Confirmer le mot de passe" required minlength="6">
        </div>

        <button type="submit" name="submit-btn" class="btn">
            S'inscrire maintenant
        </button>

        <div class="form-footer">
            Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>
        </div>
    </form>
</div>

</body>
</html>