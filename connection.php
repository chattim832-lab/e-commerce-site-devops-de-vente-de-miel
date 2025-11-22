<?php
// connection.php
// Configuration de la connexion à la base de données

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'mielshop_db';  // ← Nouveau nom de la base

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
 
// Définir l'encodage UTF-8 pour éviter les problèmes d'accents
mysqli_set_charset($conn, "utf8");
?>