<?php
session_start();
include 'connection.php';

$message = [];

if (isset($_POST['submit-btn'])) {
    $email = mysqli_real_escape_string($conn, trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
    $pass  = $_POST['password'] ?? '';

    if (empty($email) || empty($pass)) {
        $message[] = "Tous les champs sont requis.";
    } else {
        $sql = "SELECT id, name, email, password, user_type FROM users WHERE email = '$email' LIMIT 1";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $hash = $row['password'];

            if (password_verify($pass, $hash)) {
                // mot de passe correct
                if ($row['user_type'] === 'admin') {
                    $_SESSION['admin_id']    = $row['id'];
                    $_SESSION['admin_name']  = $row['name'];
                    $_SESSION['admin_email'] = $row['email'];
                    header('Location: admin_pannel.php');
                    exit();
                } else {
                    $_SESSION['user_id']    = $row['id'];
                    $_SESSION['user_name']  = $row['name'];
                    $_SESSION['user_email'] = $row['email'];
                    header('Location: index.php');
                    exit();
                }
            } else {
                $message[] = "Email ou mot de passe incorrect.";
            }
        } else {
            $message[] = "Email ou mot de passe incorrect.";
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
    <title>Connexion - MielShop</title>
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
            max-width: 450px;
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

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #fcc927;
            box-shadow: 0 0 0 3px rgba(252, 201, 39, 0.1);
        }

        .forgot-password {
            text-align: right;
            margin-top: -0.5rem;
        }

        .forgot-password a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .forgot-password a:hover {
            color: #fcc927;
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

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: #999;
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        @media(max-width: 600px) {
            .form-container {
                padding: 2rem 1.5rem;
            }

            .form-container h1 {
                font-size: 1.8rem;
            }

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
            echo '
            <div class="message">
                <span>'.htmlspecialchars($msg).'</span>
                <i class="bx bx-x" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
    ?>

    <form method="post" novalidate>
        <h1>🍯 Connexion</h1>
        <p class="subtitle">Bienvenue sur MielShop !</p>

        <div class="input-box">
            <i class='bx bx-envelope'></i>
            <input type="email" name="email" placeholder="Votre adresse email" required>
        </div>

        <div class="input-box">
            <i class='bx bx-lock-alt'></i>
            <input type="password" name="password" placeholder="Votre mot de passe" required>
        </div>

        <button type="submit" name="submit-btn" class="btn">
            Se connecter
        </button>

        <div class="divider">OU</div>

        <div class="form-footer">
            Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </div>
    </form>
</div>

</body>
</html>