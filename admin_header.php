<?php
// admin_header.php - Header unifié pour toutes les pages admin
if (session_status() === PHP_SESSION_NONE) session_start();

// Si l'admin n'est pas connecté -> redirection
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #333;
            padding-top: 80px;
        }

        /* ===== HEADER ADMIN ===== */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            padding: 0;
        }

        .admin-header .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .admin-header .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fcc927;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-header .logo i {
            font-size: 2rem;
        }

        .admin-header .navbar {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .admin-header .navbar a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-header .navbar a:hover,
        .admin-header .navbar a.active {
            color: #fcc927;
        }

        .admin-header .navbar a i {
            font-size: 1.2rem;
        }

        .admin-header .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-header .user-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .admin-header .user-name i {
            font-size: 1.8rem;
            color: #fcc927;
        }

        .admin-header .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.5rem;
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
        }

        .admin-header .logout-btn:hover {
            background: #d32f2f;
        }

        .admin-header .menu-toggle {
            display: none;
            font-size: 2rem;
            color: #333;
            cursor: pointer;
        }

        /* ===== RESPONSIVE ===== */
        @media(max-width: 968px) {
            body {
                padding-top: 70px;
            }
            
            .admin-header .navbar {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                background: #fff;
                flex-direction: column;
                padding: 2rem;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                transition: left 0.3s ease;
                gap: 1.5rem;
            }
            
            .admin-header .navbar.active {
                left: 0;
            }
            
            .admin-header .navbar a {
                width: 100%;
                padding: 0.8rem;
                border-bottom: 1px solid #eee;
            }
            
            .admin-header .user-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .admin-header .menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

<header class="admin-header">
    <div class="header-container">
        <a href="admin_pannel.php" class="logo">
            <i class='bx bxs-dashboard'></i>
            AdminPanel
        </a>
        
        <nav class="navbar" id="adminNavbar">
            <a href="admin_pannel.php" data-page="admin_pannel.php">
                <i class='bx bx-home'></i> Dashboard
            </a>
            <a href="admin_products.php" data-page="admin_products.php">
                <i class='bx bx-package'></i> Produits
            </a>
            <a href="admin_placed_orders.php" data-page="admin_placed_orders.php">
                <i class='bx bx-cart'></i> Commandes
            </a>
            <a href="admin_users.php" data-page="admin_users.php">
                <i class='bx bx-user'></i> Utilisateurs
            </a>
            <a href="admin_messages.php" data-page="admin_messages.php">
                <i class='bx bx-message-dots'></i> Messages
            </a>
        </nav>
        
        <div class="user-info">
            <span class="user-name">
                <i class='bx bxs-user-circle'></i>
                <?= htmlspecialchars($_SESSION['admin_name']); ?>
            </span>
            <a href="logout.php" class="logout-btn">
                <i class='bx bx-log-out'></i> Déconnexion
            </a>
        </div>
        
        <div class="menu-toggle" id="menuToggle">
            <i class='bx bx-menu'></i>
        </div>
    </div>
</header>

<script>
// Menu mobile toggle
const menuToggle = document.getElementById('menuToggle');
const adminNavbar = document.getElementById('adminNavbar');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        adminNavbar.classList.toggle('active');
    });
}

// Highlight active page - VERSION CORRIGÉE
const currentPage = window.location.pathname.split('/').pop();
const navLinks = document.querySelectorAll('.navbar a');

navLinks.forEach(link => {
    const linkPage = link.getAttribute('data-page');
    
    // Comparer avec le nom de fichier actuel
    if (linkPage === currentPage) {
        link.classList.add('active');
    }
});

// Fermer le menu mobile après clic sur un lien
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 968) {
            adminNavbar.classList.remove('active');
        }
    });
});
</script>