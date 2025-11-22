<?php
session_start();
include 'connection.php';

// ============ VÉRIFICATION CONNEXION ============
if(!isset($_SESSION['user_id'])){ 
    header('Location: login.php'); 
    exit; 
}

// ============ INITIALISER LE PANIER ============
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// ============ AJOUTER AU PANIER ============
if(isset($_GET['add'])){
    $id = intval($_GET['add']);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: cart.php');
    exit;
}

// ============ SUPPRIMER DU PANIER ============
if(isset($_GET['remove'])){
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php');
    exit;
}

// ============ PASSER COMMANDE ============
if(isset($_POST['checkout'])){
    $user_id = $_SESSION['user_id'];
    foreach($_SESSION['cart'] as $product_id => $qty){
        $res = mysqli_query($conn, "SELECT price FROM products WHERE id=$product_id");
        if($row = mysqli_fetch_assoc($res)){
            $price = $row['price'];
            $total = $price * $qty;
            mysqli_query($conn, "INSERT INTO orders(user_id, product_id, quantity, total_price, status) VALUES($user_id, $product_id, $qty, $total, 'pending')");
        }
    }
    $_SESSION['cart'] = [];
    $success_message = "Commande passée avec succès !";
}

// ============ INCLURE LE HEADER APRÈS LA LOGIQUE ============
include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Mon Panier - MielShop</title>
</head>
<body>

<section class="cart-section">
    <div class="cart-container">
        <h1><i class='bx bx-cart'></i> Mon Panier</h1>
        
        <?php if(isset($success_message)): ?>
            <div class="success-message">
                <i class='bx bx-check-circle'></i>
                <?= $success_message ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($_SESSION['cart'])): ?>
            <div class="cart-table-wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $total_price = 0;
                    foreach($_SESSION['cart'] as $id => $qty):
                        $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
                        if($product = mysqli_fetch_assoc($res)):
                            $subtotal = $product['price'] * $qty;
                            $total_price += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="img/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <span><?= htmlspecialchars($product['name']) ?></span>
                                </div>
                            </td>
                            <td class="price"><?= number_format($product['price'], 2) ?> TND</td>
                            <td>
                                <div class="quantity-box">
                                    <span class="qty-number"><?= $qty ?></span>
                                </div>
                            </td>
                            <td class="subtotal"><?= number_format($subtotal, 2) ?> TND</td>
                            <td>
                                <a href="cart.php?remove=<?= $id ?>" class="remove-btn" onclick="return confirm('Supprimer ce produit du panier ?')">
                                    <i class='bx bx-trash'></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total :</strong></td>
                            <td colspan="2" class="total-price"><strong><?= number_format($total_price, 2) ?> TND</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="cart-actions">
                <a href="products.php" class="continue-shopping">
                    <i class='bx bx-arrow-back'></i> Continuer mes achats
                </a>
                <form method="post" style="display:inline;">
                    <button type="submit" name="checkout" class="checkout-btn">
                        <i class='bx bx-check-circle'></i> Passer commande
                    </button>
                </form>
            </div>
            
        <?php else: ?>
            <div class="empty-cart">
                <i class='bx bx-cart-alt'></i>
                <h2>Votre panier est vide</h2>
                <p>Découvrez nos délicieux miels naturels</p>
                <a href="products.php" class="btn">
                    <i class='bx bx-shopping-bag'></i> Voir les produits
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', Arial, sans-serif;
    background: #f5f5f5;
    padding-top: 80px;
}

.cart-section {
    min-height: calc(100vh - 80px);
    padding: 3rem 2rem;
}

.cart-container {
    max-width: 1200px;
    margin: 0 auto;
}

.cart-container h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.cart-container h1 i {
    color: #fcc927;
    font-size: 3rem;
}

.success-message {
    background: #4CAF50;
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideDown 0.5s ease;
}

.success-message i {
    font-size: 2rem;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cart-table-wrapper {
    background: #fff;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow-x: auto;
    margin-bottom: 2rem;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table thead tr {
    background: #fcc927;
    color: #000;
}

.cart-table th,
.cart-table td {
    padding: 1.5rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.cart-table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.cart-table tbody tr {
    transition: background 0.3s ease;
}

.cart-table tbody tr:hover {
    background: #f9f9f9;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-info img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product-info span {
    font-weight: 500;
}

.price,
.subtotal {
    font-weight: 600;
    color: #fcc927;
}

.quantity-box {
    display: inline-flex;
    align-items: center;
    background: #f5f5f5;
    border-radius: 8px;
    padding: 0.5rem 1rem;
}

.qty-number {
    font-weight: 600;
    font-size: 1.1rem;
}

.remove-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    background: #f44336;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.3s ease;
    font-size: 0.9rem;
}

.remove-btn:hover {
    background: #d32f2f;
}

.cart-table tfoot tr {
    background: #f9f9f9;
}

.cart-table tfoot td {
    font-size: 1.2rem;
    padding: 1.5rem;
    border: none;
}

.total-price {
    color: #fcc927;
    font-size: 1.5rem !important;
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.continue-shopping {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background: #fff;
    color: #333;
    text-decoration: none;
    border-radius: 10px;
    border: 2px solid #fcc927;
    font-weight: 600;
    transition: all 0.3s ease;
}

.continue-shopping:hover {
    background: #fcc927;
    color: #000;
}

.checkout-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2.5rem;
    background: #fcc927;
    color: #000;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(252, 201, 39, 0.3);
}

.checkout-btn:hover {
    background: #e6b800;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(252, 201, 39, 0.4);
}

.empty-cart {
    text-align: center;
    padding: 5rem 2rem;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.empty-cart i {
    font-size: 8rem;
    color: #ddd;
    margin-bottom: 2rem;
}

.empty-cart h2 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 1rem;
}

.empty-cart p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.empty-cart .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2.5rem;
    background: #fcc927;
    color: #000;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.empty-cart .btn:hover {
    background: #e6b800;
    transform: translateY(-2px);
}

@media(max-width: 768px) {
    body {
        padding-top: 60px;
    }
    
    .cart-section {
        padding: 2rem 1rem;
    }
    
    .cart-container h1 {
        font-size: 1.8rem;
    }
    
    .cart-table-wrapper {
        padding: 1rem;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 1rem 0.5rem;
        font-size: 0.9rem;
    }
    
    .product-info {
        flex-direction: column;
        text-align: center;
    }
    
    .cart-actions {
        flex-direction: column;
    }
    
    .continue-shopping,
    .checkout-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

</body>
</html>