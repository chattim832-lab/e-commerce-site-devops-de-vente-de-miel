<?php
include 'connection.php';

// ============ SUPPRIMER MESSAGE (AVANT LE HEADER) ============
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM messages WHERE id=$id");
    header('Location: admin_messages.php');
    exit();
}

// ============ INCLURE LE HEADER APRÈS LA LOGIQUE DE SUPPRESSION ============
include 'admin_header.php';

$messages = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="admin_style.css">
    <title>Messages - Admin</title>
</head>
<body>

<section class="admin-content">
    <h1><i class='bx bx-message-dots'></i> Messages Clients</h1>
    
    <?php if (mysqli_num_rows($messages) > 0): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($messages)): ?>
                <tr>
                    <td><strong>#<?= $row['id'] ?></strong></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars(substr($row['message'], 0, 50)) ?>...</td>
                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    <td>
                        <a href="admin_messages.php?delete=<?= $row['id'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Supprimer ce message ?')">
                           <i class='bx bx-trash'></i> Supprimer
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="empty-state">
            <i class='bx bx-message-x'></i>
            <p>Aucun message pour le moment.</p>
        </div>
    <?php endif; ?>
</section>

<style>
.admin-content {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.admin-content h1 {
    color: #fcc927;
    font-size: 2.2rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.admin-content h1 i {
    font-size: 2.5rem;
}

.table-container {
    background: #fff;
    border-radius: 15px;
    overflow-x: auto;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    padding: 2rem;
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

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    background: #f44336;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-delete:hover {
    background: #d32f2f;
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #999;
}

.empty-state i {
    font-size: 5rem;
    margin-bottom: 1rem;
    color: #ddd;
}

.empty-state p {
    font-size: 1.2rem;
}

@media(max-width: 768px) {
    .admin-content {
        padding: 0 1rem;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.8rem 0.5rem;
    }
}
</style>

</body>
</html>