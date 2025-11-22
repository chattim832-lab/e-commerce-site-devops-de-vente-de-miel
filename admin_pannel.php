<?php
include 'connection.php';
include 'admin_header.php';

// Récupérer les statistiques
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type='user'"))['count'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$total_messages = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM messages"))['count'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='pending'"))['count'];

// Calculer le revenu total
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM orders WHERE status='completed'"))['total'] ?? 0;
?>

<style>
/* ===== CONTAINER ===== */
.admin-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

/* ===== BANNER ===== */
.dashboard-banner {
    background: linear-gradient(135deg, #fcc927 0%, #f9a825 100%);
    color: #fff;
    text-align: center;
    padding: 3rem 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    margin-bottom: 3rem;
}

.dashboard-banner h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.dashboard-banner p {
    font-size: 1.2rem;
    opacity: 0.95;
}

/* ===== STATISTICS CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stat-card .icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.stat-card .icon.users {
    background: #e3f2fd;
    color: #2196F3;
}

.stat-card .icon.products {
    background: #f3e5f5;
    color: #9c27b0;
}

.stat-card .icon.orders {
    background: #fff3e0;
    color: #ff9800;
}

.stat-card .icon.messages {
    background: #e8f5e9;
    color: #4caf50;
}

.stat-card .icon.revenue {
    background: #fce4ec;
    color: #e91e63;
}

.stat-card .icon.pending {
    background: #ffebee;
    color: #f44336;
}

.stat-card .info h3 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 0.3rem;
}

.stat-card .info p {
    color: #666;
    font-size: 0.95rem;
}

/* ===== QUICK ACTIONS ===== */
.quick-actions {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 3rem;
}

.quick-actions h2 {
    color: #fcc927;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 2rem 1rem;
    background: #f9f9f9;
    border-radius: 10px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.action-btn:hover {
    background: #fcc927;
    color: #000;
    border-color: #e6b800;
    transform: translateY(-3px);
}

.action-btn i {
    font-size: 2.5rem;
}

.action-btn span {
    font-weight: 600;
    font-size: 1rem;
}

/* ===== RECENT ACTIVITY ===== */
.recent-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.activity-card {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.activity-card h3 {
    color: #fcc927;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.activity-item {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    transition: background 0.3s;
}

.activity-item:hover {
    background: #f9f9f9;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item p {
    margin: 0.3rem 0;
    color: #666;
    font-size: 0.9rem;
}

.activity-item .time {
    color: #999;
    font-size: 0.8rem;
}

/* ===== RESPONSIVE ===== */
@media(max-width: 768px) {
    .dashboard-banner h1 {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .recent-section {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-container">
    <!-- Banner -->
    <div class="dashboard-banner">
        <h1>🍯 Tableau de bord Admin</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['admin_name']); ?> !</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon users">
                <i class='bx bx-user'></i>
            </div>
            <div class="info">
                <h3><?= $total_users ?></h3>
                <p>Utilisateurs</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon products">
                <i class='bx bx-package'></i>
            </div>
            <div class="info">
                <h3><?= $total_products ?></h3>
                <p>Produits</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon orders">
                <i class='bx bx-cart'></i>
            </div>
            <div class="info">
                <h3><?= $total_orders ?></h3>
                <p>Commandes</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon messages">
                <i class='bx bx-message-dots'></i>
            </div>
            <div class="info">
                <h3><?= $total_messages ?></h3>
                <p>Messages</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon revenue">
                <i class='bx bx-money'></i>
            </div>
            <div class="info">
                <h3><?= number_format($revenue, 2) ?> TND</h3>
                <p>Revenu total</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon pending">
                <i class='bx bx-time'></i>
            </div>
            <div class="info">
                <h3><?= $pending_orders ?></h3>
                <p>Commandes en attente</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2><i class='bx bx-bolt'></i> Actions rapides</h2>
        <div class="actions-grid">
            <a href="admin_products.php" class="action-btn">
                <i class='bx bx-plus-circle'></i>
                <span>Ajouter un produit</span>
            </a>
            <a href="admin_users.php" class="action-btn">
                <i class='bx bx-user-check'></i>
                <span>Gérer utilisateurs</span>
            </a>
            <a href="admin_placed_orders.php" class="action-btn">
                <i class='bx bx-list-ul'></i>
                <span>Voir commandes</span>
            </a>
            <a href="admin_messages.php" class="action-btn">
                <i class='bx bx-envelope'></i>
                <span>Lire messages</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-section">
        <div class="activity-card">
            <h3><i class='bx bx-trending-up'></i> Commandes récentes</h3>
            <?php
            $recent_orders = mysqli_query($conn, "SELECT o.*, u.name as user_name, p.name as product_name 
                                                   FROM orders o 
                                                   JOIN users u ON o.user_id = u.id 
                                                   JOIN products p ON o.product_id = p.id 
                                                   ORDER BY o.created_at DESC LIMIT 5");
            if (mysqli_num_rows($recent_orders) > 0) {
                while($order = mysqli_fetch_assoc($recent_orders)):
            ?>
                <div class="activity-item">
                    <p><strong><?= htmlspecialchars($order['user_name']) ?></strong> a commandé <strong><?= htmlspecialchars($order['product_name']) ?></strong></p>
                    <p>Montant: <strong><?= number_format($order['total_price'], 2) ?> TND</strong></p>
                    <p class="time"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                </div>
            <?php 
                endwhile;
            } else {
                echo '<p style="text-align:center;color:#999;">Aucune commande récente</p>';
            }
            ?>
        </div>

        <div class="activity-card">
            <h3><i class='bx bx-message'></i> Messages récents</h3>
            <?php
            $recent_messages = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
            if (mysqli_num_rows($recent_messages) > 0) {
                while($msg = mysqli_fetch_assoc($recent_messages)):
            ?>
                <div class="activity-item">
                    <p><strong><?= htmlspecialchars($msg['name']) ?></strong></p>
                    <p><?= htmlspecialchars(substr($msg['message'], 0, 60)) ?>...</p>
                    <p class="time"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></p>
                </div>
            <?php 
                endwhile;
            } else {
                echo '<p style="text-align:center;color:#999;">Aucun message récent</p>';
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>