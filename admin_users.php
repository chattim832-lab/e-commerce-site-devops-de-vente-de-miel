<?php
include 'connection.php';
include 'admin_header.php';

$message = '';

// Supprimer utilisateur avec sécurité
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    
    // Vérifier que ce n'est pas l'admin connecté
    if($id === $_SESSION['admin_id']){
        $message = "Vous ne pouvez pas supprimer votre propre compte !";
    } else {
        // Vérifier que l'utilisateur existe
        $check = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
        if(mysqli_num_rows($check) > 0){
            mysqli_query($conn, "DELETE FROM users WHERE id=$id");
            $message = "Utilisateur supprimé avec succès !";
        } else {
            $message = "Utilisateur introuvable.";
        }
    }
}

// Changer le type d'utilisateur (user <-> admin)
if(isset($_GET['toggle_type'])){
    $id = intval($_GET['toggle_type']);
    
    if($id !== $_SESSION['admin_id']){
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_type FROM users WHERE id=$id"));
        $new_type = ($user['user_type'] === 'admin') ? 'user' : 'admin';
        mysqli_query($conn, "UPDATE users SET user_type='$new_type' WHERE id=$id");
        $message = "Type d'utilisateur modifié avec succès !";
    }
}

// Récupérer tous les utilisateurs
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");

// Statistiques
$total_users = mysqli_num_rows($users);
$admins_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type='admin'"))['count'];
$regular_users = $total_users - $admins_count;
?>

<style>
.admin-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.page-title {
    color: #fcc927;
    font-size: 2.2rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Message */
.message {
    background: #4CAF50;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    animation: slideIn 0.3s ease;
}

.message.error {
    background: #f44336;
}

.message i {
    font-size: 1.5rem;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-card .icon {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-card .icon.total { background: #e3f2fd; color: #2196F3; }
.stat-card .icon.admins { background: #fff3e0; color: #ff9800; }
.stat-card .icon.users { background: #e8f5e9; color: #4caf50; }

.stat-card .info h3 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 0.2rem;
}

.stat-card .info p {
    color: #666;
    font-size: 0.9rem;
}

/* Table Container */
.table-container {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.table-container h2 {
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-container h2 i {
    color: #fcc927;
}

table {
    width: 100%;
    border-collapse: collapse;
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
}

tbody tr {
    transition: background 0.3s;
}

tbody tr:hover {
    background: #f9f9f9;
}

.user-type-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.user-type-badge.admin {
    background: #fff3e0;
    color: #ff9800;
}

.user-type-badge.user {
    background: #e3f2fd;
    color: #2196F3;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-toggle {
    background: #2196F3;
    color: #fff;
}

.btn-toggle:hover {
    background: #1976D2;
}

.btn-delete {
    background: #f44336;
    color: #fff;
}

.btn-delete:hover {
    background: #d32f2f;
}

.btn-action.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.current-user-row {
    background: #fff3e0 !important;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #999;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #ddd;
}

@media(max-width: 768px) {
    .admin-container {
        padding: 0 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    table {
        font-size: 0.85rem;
    }
    
    th, td {
        padding: 0.8rem 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="admin-container">
    <h1 class="page-title">
        <i class='bx bx-user'></i>
        Gestion des utilisateurs
    </h1>
    
    <?php if(!empty($message)): ?>
        <div class="message <?= strpos($message, 'pas') !== false ? 'error' : '' ?>">
            <i class='bx <?= strpos($message, 'pas') !== false ? 'bx-error' : 'bx-check-circle' ?>'></i>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon total">
                <i class='bx bx-user'></i>
            </div>
            <div class="info">
                <h3><?= $total_users ?></h3>
                <p>Total utilisateurs</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon admins">
                <i class='bx bx-shield'></i>
            </div>
            <div class="info">
                <h3><?= $admins_count ?></h3>
                <p>Administrateurs</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon users">
                <i class='bx bx-user-check'></i>
            </div>
            <div class="info">
                <h3><?= $regular_users ?></h3>
                <p>Utilisateurs réguliers</p>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <h2>
            <i class='bx bx-list-ul'></i>
            Liste des utilisateurs (<?= $total_users ?>)
        </h2>
        
        <?php if($total_users > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            mysqli_data_seek($users, 0); // Reset le pointeur
            while($user = mysqli_fetch_assoc($users)): 
                $is_current_user = ($user['id'] == $_SESSION['admin_id']);
            ?>
                <tr <?= $is_current_user ? 'class="current-user-row"' : '' ?>>
                    <td><strong>#<?= $user['id'] ?></strong></td>
                    <td>
                        <?= htmlspecialchars($user['name']) ?>
                        <?php if($is_current_user): ?>
                            <span style="color:#ff9800;font-weight:bold;"> (Vous)</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="user-type-badge <?= $user['user_type'] ?>">
                            <?= $user['user_type'] === 'admin' ? 'Administrateur' : 'Utilisateur' ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <?php if(!$is_current_user): ?>
                                <a href="admin_users.php?toggle_type=<?= $user['id'] ?>" 
                                   class="btn-action btn-toggle"
                                   onclick="return confirm('Changer le type de cet utilisateur ?')">
                                    <i class='bx bx-transfer'></i>
                                    <?= $user['user_type'] === 'admin' ? 'Rétrograder' : 'Promouvoir' ?>
                                </a>
                                
                                <a href="admin_users.php?delete=<?= $user['id'] ?>" 
                                   class="btn-action btn-delete"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    <i class='bx bx-trash'></i>
                                    Supprimer
                                </a>
                            <?php else: ?>
                                <span class="btn-action disabled">
                                    <i class='bx bx-lock'></i>
                                    Compte actuel
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class='bx bx-user'></i>
            <p>Aucun utilisateur trouvé.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>