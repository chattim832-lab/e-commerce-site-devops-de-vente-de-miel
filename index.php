<?php 
session_start();
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Accueil - MielShop</title>
</head>
<body>

<section class="home-banner">
    <div class="banner-content">
        <h1>🍯 Bienvenue sur MielShop</h1>
        <p>Découvrez notre miel naturel et frais, directement de la ruche.</p>
        <a href="products.php" class="btn">Voir les produits</a>
    </div>
</section>

<section class="features">
    <div class="feature">
        <i class='bx bx-leaf' style="font-size:3rem;color:#fcc927;"></i>
        <h3>Qualité 100% Naturelle</h3>
        <p>Miel pur et artisanal, sans additifs</p>
    </div>
    <div class="feature">
        <i class='bx bx-package' style="font-size:3rem;color:#fcc927;"></i>
        <h3>Livraison rapide</h3>
        <p>Recevez votre commande à domicile</p>
    </div>
    <div class="feature">
        <i class='bx bx-support' style="font-size:3rem;color:#fcc927;"></i>
        <h3>Service client</h3>
        <p>Support disponible 7/7</p>
    </div>
</section>

<section class="about-section">
    <div class="about-container">
        <h2>Pourquoi choisir MielShop ?</h2>
        <p>Nous sommes une entreprise tunisienne spécialisée dans la production et la vente de miel naturel. 
        Notre miel est récolté avec soin par des apiculteurs locaux passionnés.</p>
        <div class="stats">
            <div class="stat">
                <h3>100+</h3>
                <p>Clients satisfaits</p>
            </div>
            <div class="stat">
                <h3>10+</h3>
                <p>Années d'expérience</p>
            </div>
            <div class="stat">
                <h3>6</h3>
                <p>Variétés de miel</p>
            </div>
        </div>
    </div>
</section>

<style>
/* Reset et styles de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', Arial, sans-serif;
    background: #f5f5f5;
    color: #333;
    padding-top: 80px;
}

/* Banner principal */
.home-banner {
    background: linear-gradient(135deg, #fcc927 0%, #f9a825 100%);
    min-height: 70vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.home-banner::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    top: -100px;
    right: -100px;
}

.home-banner::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    bottom: -150px;
    left: -150px;
}

.banner-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
}

.banner-content h1 {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    animation: fadeInDown 1s ease;
}

.banner-content p {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    animation: fadeInUp 1s ease 0.3s both;
}

.banner-content .btn {
    display: inline-block;
    padding: 1.2rem 3rem;
    background: #fff;
    color: #fcc927;
    font-weight: bold;
    font-size: 1.1rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    animation: fadeInUp 1s ease 0.6s both;
}

.banner-content .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    background: #000;
    color: #fcc927;
}

/* Section features */
.features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: -50px auto 4rem;
    padding: 0 2rem;
    position: relative;
    z-index: 3;
}

.feature {
    background: #fff;
    text-align: center;
    padding: 3rem 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.feature i {
    margin-bottom: 1rem;
}

.feature h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 1rem;
}

.feature p {
    color: #666;
    line-height: 1.6;
}

/* Section à propos */
.about-section {
    background: #fff;
    padding: 5rem 2rem;
    margin: 4rem 0;
}

.about-container {
    max-width: 1000px;
    margin: 0 auto;
    text-align: center;
}

.about-container h2 {
    font-size: 2.5rem;
    color: #fcc927;
    margin-bottom: 1.5rem;
}

.about-container > p {
    font-size: 1.2rem;
    color: #666;
    line-height: 1.8;
    margin-bottom: 3rem;
}

.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.stat {
    padding: 2rem;
    background: #f9f9f9;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.stat:hover {
    transform: scale(1.05);
}

.stat h3 {
    font-size: 3rem;
    color: #fcc927;
    margin-bottom: 0.5rem;
}

.stat p {
    color: #666;
    font-size: 1.1rem;
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media(max-width: 768px) {
    body {
        padding-top: 60px;
    }
    
    .banner-content h1 {
        font-size: 2rem;
    }
    
    .banner-content p {
        font-size: 1.1rem;
    }
    
    .features {
        margin-top: 2rem;
        grid-template-columns: 1fr;
    }
    
    .about-container h2 {
        font-size: 2rem;
    }
    
    .stats {
        grid-template-columns: 1fr;
    }
}
</style>

</body>
</html>