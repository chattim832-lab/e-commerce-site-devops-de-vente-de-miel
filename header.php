<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <div class="header-container">
        <a href="index.php" class="logo">🍯 MielShop</a>
        
        <nav class="navbar" id="navbar">
            <a href="index.php">Accueil</a>
            <a href="products.php">Produits</a>
            <a href="contact.php">Contact</a>
            <a href="cart.php">
                <i class='bx bx-cart'></i> 
                Panier 
                <span class="cart-count">(<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</span>
            </a>
            
            <?php if(isset($_SESSION['user_name'])): ?>
                <div class="user-menu">
                    <span class="user-name">
                        <i class='bx bx-user-circle'></i>
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </span>
                    <a href="logout.php" class="logout-btn">
                        <i class='bx bx-log-out'></i> Déconnexion
                    </a>
                </div>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    <i class='bx bx-log-in'></i> Login
                </a>
                <a href="register.php" class="register-btn">S'inscrire</a>
            <?php endif; ?>
        </nav>
        
        <div class="menu-toggle" id="menu-toggle">
            <i class='bx bx-menu'></i>
        </div>
    </div>
</header>

<style>
/* Header styles */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #fff;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    z-index: 1000;
    padding: 0;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
}

.header .logo {
    font-size: 1.8rem;
    font-weight: bold;
    color: #fcc927;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.header .logo:hover {
    transform: scale(1.05);
}

.navbar {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.navbar a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.navbar a:hover {
    color: #fcc927;
}

.navbar .cart-count {
    background: #fcc927;
    color: #000;
    padding: 0.2rem 0.5rem;
    border-radius: 50%;
    font-size: 0.85rem;
    font-weight: bold;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-name {
    color: #333;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.user-name i {
    font-size: 1.5rem;
    color: #fcc927;
}

.logout-btn {
    padding: 0.5rem 1rem;
    background: #f44336;
    color: #fff !important;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.logout-btn:hover {
    background: #d32f2f;
    color: #fff !important;
}

.login-btn {
    padding: 0.5rem 1.2rem;
    background: #fcc927;
    color: #000 !important;
    border-radius: 5px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.login-btn:hover {
    background: #e6b800;
    color: #000 !important;
}

.register-btn {
    padding: 0.5rem 1.2rem;
    background: #000;
    color: #fff !important;
    border-radius: 5px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.register-btn:hover {
    background: #333;
    color: #fff !important;
}

.menu-toggle {
    display: none;
    font-size: 2rem;
    color: #333;
    cursor: pointer;
}

/* Responsive */
@media(max-width: 968px) {
    .navbar {
        position: fixed;
        top: 80px;
        left: -100%;
        width: 100%;
        background: #fff;
        flex-direction: column;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: left 0.3s ease;
        gap: 1.5rem;
    }
    
    .navbar.active {
        left: 0;
    }
    
    .navbar a {
        width: 100%;
        padding: 0.8rem;
        border-bottom: 1px solid #eee;
    }
    
    .user-menu {
        flex-direction: column;
        width: 100%;
    }
    
    .menu-toggle {
        display: block;
    }
}
</style>

<script>
// Menu mobile toggle
const menuToggle = document.getElementById('menu-toggle');
const navbar = document.getElementById('navbar');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });
}

// Fermer le menu quand on clique sur un lien
const navLinks = document.querySelectorAll('.navbar a');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navbar.classList.remove('active');
    });
});
</script>

</body>
</html>