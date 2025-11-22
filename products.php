<?php
session_start();
include 'connection.php';
include 'header.php';

$products = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Produits - MielShop</title>
</head>
<body>

<section class="products">
    <div class="title">
        <h1>Nos Produits</h1>
        <p>Découvrez notre sélection de miels naturels</p>
    </div>
    
    <div class="product-grid">
    <?php 
    if (mysqli_num_rows($products) > 0) {
        while($row = mysqli_fetch_assoc($products)): 
    ?>
        <div class="product-card">
            <div class="product-image">
                <?php 
                $image_path = 'img/' . $row['image'];
                if (file_exists($image_path)) {
                    echo '<img src="'.$image_path.'" alt="'.htmlspecialchars($row['name']).'">';
                } else {
                    echo '<img src="img/default.jpg" alt="Image par défaut">';
                }
                ?>
            </div>
            <div class="product-info">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p class="description"><?= htmlspecialchars($row['description']) ?></p>
                <div class="product-footer">
                    <p class="price"><?= number_format($row['price'], 2) ?> TND</p>
                    <p class="stock">Stock: <?= $row['stock'] ?></p>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="cart.php?add=<?= $row['id'] ?>" class="btn">
                        <i class='bx bx-cart'></i> Ajouter au panier
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn">
                        Connectez-vous pour commander
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php 
        endwhile;
    } else {
        echo '<p style="text-align:center;width:100%;">Aucun produit disponible pour le moment.</p>';
    }
    ?>
    </div>
</section>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}

.products {
    padding: 8rem 2rem 4rem;
    background: #f5f5f5;
}

.products .title {
    text-align: center;
    margin-bottom: 3rem;
}

.products .title h1 {
    font-size: 2.5rem;
    color: #fcc927;
    margin-bottom: 0.5rem;
}

.products .title p {
    font-size: 1.1rem;
    color: #666;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.product-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 250px;
    overflow: hidden;
    background: #f0f0f0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 1.5rem;
}

.product-info h3 {
    font-size: 1.3rem;
    color: #333;
    margin-bottom: 0.8rem;
}

.product-info .description {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.product-info .price {
    font-size: 1.4rem;
    font-weight: bold;
    color: #fcc927;
}

.product-info .stock {
    font-size: 0.9rem;
    color: #888;
}

.product-card .btn {
    display: block;
    width: 100%;
    padding: 0.8rem;
    background: #fcc927;
    color: #000;
    text-align: center;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}

.product-card .btn:hover {
    background: #e6b800;
}

.product-card .btn i {
    margin-right: 0.5rem;
}

@media(max-width: 768px) {
    .products {
        padding: 6rem 1rem 2rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
}
</style>

</body>
</html>