<?php
// create_admin.php - Script pour créer/réparer le compte admin
include 'connection.php';

// ===== CONFIGUREZ VOS IDENTIFIANTS ICI =====
$admin_name = "Admin";
$admin_email = "admin@mielshop.com";
$admin_password = "admin123";  // ← Changez ce mot de passe si vous voulez
// ============================================

echo "<h2>🔧 Réparation du compte Admin</h2>";

// Hasher le mot de passe correctement
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Vérifier si l'admin existe déjà
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$admin_email'");

if (mysqli_num_rows($check) > 0) {
    // L'admin existe → Mettre à jour le mot de passe
    $query = "UPDATE users SET 
              name='$admin_name',
              password='$hashed_password', 
              user_type='admin' 
              WHERE email='$admin_email'";
    
    if (mysqli_query($conn, $query)) {
        echo "<p style='color:green;font-size:18px;'>✅ <strong>Mot de passe admin mis à jour avec succès!</strong></p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur: " . mysqli_error($conn) . "</p>";
    }
} else {
    // L'admin n'existe pas → Le créer
    $query = "INSERT INTO users (name, email, password, user_type) 
              VALUES ('$admin_name', '$admin_email', '$hashed_password', 'admin')";
    
    if (mysqli_query($conn, $query)) {
        echo "<p style='color:green;font-size:18px;'>✅ <strong>Compte admin créé avec succès!</strong></p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur: " . mysqli_error($conn) . "</p>";
    }
}

echo "<div style='background:#f0f0f0; padding:20px; border-radius:10px; margin:20px 0;'>";
echo "<h3>🔑 Vos identifiants de connexion :</h3>";
echo "<p><strong>Email:</strong> <code style='background:#fff; padding:5px;'>$admin_email</code></p>";
echo "<p><strong>Mot de passe:</strong> <code style='background:#fff; padding:5px;'>$admin_password</code></p>";
echo "</div>";

echo "<p><a href='login.php' style='background:#fcc927; padding:10px 20px; text-decoration:none; color:#000; border-radius:5px; font-weight:bold;'>➡️ Aller à la page de connexion</a></p>";

echo "<p style='color:red; margin-top:30px;'><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier après utilisation pour des raisons de sécurité!</p>";

// Afficher le hash généré (pour debug)
echo "<hr>";
echo "<p style='color:#666; font-size:12px;'>Hash généré: <code>$hashed_password</code></p>";
?>