<?php
include 'connection.php';

// ============ GESTION DES ACTIONS (AVANT LE HEADER) ============

// Mettre à jour le statut d'une commande
if(isset($_POST['update_order'])){
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id=$order_id");
    header('Location: admin_placed_orders.php');
    exit();
}

// Supprimer une commande
if(isset($_GET['delete'])){
    $order_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM orders WHERE id=$order_id");
    header('Location: admin_placed_orders.php');
    exit();
}

// ============ INCLURE LE HEADER APRÈS LA LOGIQUE ============
include 'admin_header.php';

// Récupérer toutes les commandes avec infos utilisateur et produit
$orders = mysqli_query($conn, "
    SELECT o.*, 
           u.name as user_name, 
           u.email as user_email,
           p.name as product_name,
           p.image as product_image
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN products p ON o.product_id = p.id
    ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des commandes - Admin</title>
</head>
<body>

<style>
.admin-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    color: #fcc927;
    font-size: 2.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-title i {
    font-size: 2.5rem;
}

.stats-summary {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.stat-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.stat-badge i {
    font-size: 1.5rem;
}

.stat-badge.pending {
    border-left: 4px solid #ff9800;
}

.stat-badge.completed {
    border-left: 4px solid #4caf50;
}

.stat-badge.cancelled {
    border-left: 4px solid #f44336;
}

.orders-container {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.orders-container h2 {
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.orders-container h2 i {
    color: #fcc927;
}

.orders-table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

th, td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

th {
    background: #fcc927;
    color: #000;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    position: sticky;
    top: 0;
    z-index: 10;
}

tbody tr {
    transition: background 0.3s;
}

tbody tr:hover {
    background: #f9f9f9;
}

.product-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.user-name {
    font-weight: 600;
    color: #333;
}

.user-email {
    font-size: 0.85rem;
    color: #666;
}

.status-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending {
    background: #fff3e0;
    color: #ff9800;
}

.status-badge.completed {
    background: #e8f5e9;
    color: #4caf50;
}

.status-badge.cancelled {
    background: #ffebee;
    color: #f44336;
}

.status-form {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.status-form select {
    padding: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 5px;
    font-family: inherit;
    cursor: pointer;
    transition: border-color 0.3s;
}

.status-form select:focus {
    outline: none;
    border-color: #fcc927;
}

.btn-update {
    padding: 0.5rem 1rem;
    background: #2196F3;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-update:hover {
    background: #1976D2;
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.5rem 1rem;
    background: #f44336;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-delete:hover {
    background: #d32f2f;
}

.price-cell {
    font-weight: 600;
    color: #fcc927;
    font-size: 1.1rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #999;
}

.empty-state i {
    font-size: 5rem;
    margin-bottom: 1rem;
    color: #ddd;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

@media(max-width: 768px) {
    .admin-container {
        padding: 0 1rem;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .orders-container {
        padding: 1rem;
    }
    
    th, td {
        padding: 0.8rem 0.5rem;
        font-size: 0.9rem;
    }
}
</style>

<div class="admin-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class='bx bx-cart'></i>
            Gestion des commandes
        </h1>
        
        <div class="stats-summary">
            <?php
            $pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='pending'"));
            $completed = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='completed'"));
            $cancelled = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='cancelled'"));
            ?>
            <div class="stat-badge pending">
                <i class='bx bx-time'></i>
                <span><strong><?= $pending ?></strong> En attente</span>
            </div>
            <div class="stat-badge completed">
                <i class='bx bx-check-circle'></i>
                <span><strong><?= $completed ?></strong> Terminées</span>
            </div>
            <div class="stat-badge cancelled">
                <i class='bx bx-x-circle'></i>
                <span><strong><?= $cancelled ?></strong> Annulées</span>
            </div>
        </div>
    </div>

    <div class="orders-container">
        <h2>
            <i class='bx bx-list-ul'></i>
            Toutes les commandes (<?= mysqli_num_rows($orders) ?>)
        </h2>
        
        <?php if(mysqli_num_rows($orders) > 0): ?>
        <div class="orders-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Modifier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($order = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td><strong>#<?= $order['id'] ?></strong></td>
                        <td>
                            <div class="user-info">
                                <span class="user-name"><?= htmlspecialchars($order['user_name']) ?></span>
                                <span class="user-email"><?= htmlspecialchars($order['user_email']) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="product-cell">
                                <img src="img/<?= $order['product_image'] ?>" alt="" class="product-image">
                                <span><?= htmlspecialchars($order['product_name']) ?></span>
                            </div>
                        </td>
                        <td><?= $order['quantity'] ?></td>
                        <td class="price-cell"><?= number_format($order['total_price'], 2) ?> TND</td>
                        <td>
                            <span class="status-badge <?= $order['status'] ?>">
                                <?php 
                                switch($order['status']) {
                                    case 'pending': echo 'En attente'; break;
                                    case 'completed': echo 'Terminée'; break;
                                    case 'cancelled': echo 'Annulée'; break;
                                    default: echo $order['status'];
                                }
                                ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <form method="post" class="status-form">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" required>
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Terminée</option>
                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                                </select>
                                <button type="submit" name="update_order" class="btn-update">
                                    <i class='bx bx-save'></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="admin_placed_orders.php?delete=<?= $order['id'] ?>" 
                               class="btn-delete"
                               onclick="return confirm('Supprimer cette commande ?')">
                                <i class='bx bx-trash'></i>
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class='bx bx-cart-alt'></i>
            <h3>Aucune commande pour le moment</h3>
            <p>Les commandes apparaîtront ici une fois que les clients commenceront à commander.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>