<?php
include 'connection.php';

// ============ SUPPRIMER PRODUIT (AVANT LE HEADER) ============
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    
    // Récupérer le nom de l'image avant suppression
    $result = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
    if($row = mysqli_fetch_assoc($result)){
        $image_path = 'img/' . $row['image'];
        if(file_exists($image_path) && $row['image'] != 'default.jpg'){
            unlink($image_path);
        }
    }
    
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header('Location: admin_products.php');
    exit();
}

// ============ INCLURE LE HEADER APRÈS LA LOGIQUE DE SUPPRESSION ============
include 'admin_header.php';

$message = [];

// Ajouter produit avec image
if(isset($_POST['add_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $stock = intval($_POST['stock']);
    
    // Gestion de l'upload d'image
    $image_name = 'default.jpg';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)){
            $new_name = uniqid() . '.' . $ext;
            $upload_path = 'img/' . $new_name;
            
            // Créer le dossier img s'il n'existe pas
            if(!file_exists('img')){
                mkdir('img', 0777, true);
            }
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)){
                $image_name = $new_name;
                $message[] = 'Produit ajouté avec succès!';
            } else {
                $message[] = 'Erreur lors de l\'upload de l\'image.';
            }
        } else {
            $message[] = 'Format d\'image non autorisé. Utilisez JPG, PNG ou GIF.';
        }
    }
    
    if(empty($message) || $message[0] === 'Produit ajouté avec succès!'){
        $query = "INSERT INTO products(name, price, description, image, stock) 
                  VALUES('$name', '$price', '$desc', '$image_name', $stock)";
        
        if(mysqli_query($conn, $query)){
            if(empty($message)) $message[] = 'Produit ajouté avec succès!';
        } else {
            $message[] = 'Erreur lors de l\'ajout du produit: ' . mysqli_error($conn);
        }
    }
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
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

.message {
    background: #4CAF50;
    color: white;
    padding: 1rem;
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

.form-container {
    background: #fff;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 3rem;
}

.form-container h2 {
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-container h2 i {
    color: #fcc927;
}

.form-admin {
    display: grid;
    gap: 1rem;
}

.form-admin input,
.form-admin textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-family: inherit;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-admin input:focus,
.form-admin textarea:focus {
    border-color: #fcc927;
    outline: none;
}

.form-admin textarea {
    resize: vertical;
    min-height: 120px;
}

.form-admin input[type="file"] {
    padding: 0.8rem;
    cursor: pointer;
}

.form-admin .btn {
    padding: 1rem 2rem;
    background: #fcc927;
    border: none;
    border-radius: 8px;
    color: #000;
    font-weight: bold;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.form-admin .btn:hover {
    background: #e6b800;
    transform: translateY(-2px);
}

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

.product-image {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
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
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #ddd;
}

@media(max-width: 768px) {
    .admin-container {
        padding: 0 1rem;
    }
    
    .form-container {
        padding: 1.5rem;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.8rem 0.5rem;
    }
}
</style>

<div class="admin-container">
    <h1 class="page-title">
        <i class='bx bx-package'></i>
        Gestion des produits
    </h1>
    
    <?php
    if(!empty($message)){
        foreach($message as $msg){
            $isError = strpos($msg, 'Erreur') !== false || strpos($msg, 'non autorisé') !== false;
            echo '<div class="message ' . ($isError ? 'error' : '') . '">
                    <i class="bx ' . ($isError ? 'bx-error' : 'bx-check-circle') . '"></i>
                    <span>' . htmlspecialchars($msg) . '</span>
                  </div>';
        }
    }
    ?>
    
    <div class="form-container">
        <h2>
            <i class='bx bx-plus-circle'></i>
            Ajouter un nouveau produit
        </h2>
        <form method="post" enctype="multipart/form-data" class="form-admin">
            <input type="text" name="name" placeholder="Nom du produit (ex: Miel d'Eucalyptus)" required>
            <input type="number" step="0.01" name="price" placeholder="Prix en TND" min="0" required>
            <input type="number" name="stock" placeholder="Stock disponible" min="0" required>
            <textarea name="description" placeholder="Description détaillée du produit" rows="4" required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="add_product" class="btn">
                <i class='bx bx-plus'></i>
                Ajouter le produit
            </button>
        </form>
    </div>

    <div class="table-container">
        <h2>
            <i class='bx bx-list-ul'></i>
            Liste des produits (<?= mysqli_num_rows($products) ?>)
        </h2>
        
        <?php if(mysqli_num_rows($products) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><strong>#<?= $row['id'] ?></strong></td>
                    <td>
                        <img src="img/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="product-image">
                    </td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><strong><?= number_format($row['price'], 2) ?> TND</strong></td>
                    <td>
                        <span style="color: <?= $row['stock'] > 10 ? '#4caf50' : ($row['stock'] > 0 ? '#ff9800' : '#f44336') ?>">
                            <?= $row['stock'] ?> unités
                        </span>
                    </td>
                    <td><?= htmlspecialchars(substr($row['description'], 0, 60)) ?>...</td>
                    <td>
                        <a href="admin_products.php?delete=<?= $row['id'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                            <i class='bx bx-trash'></i>
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class='bx bx-package'></i>
            <p>Aucun produit disponible. Ajoutez votre premier produit !</p>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>