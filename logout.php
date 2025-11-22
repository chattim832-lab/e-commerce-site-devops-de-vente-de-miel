<?php
// logout.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Détruire toutes les sessions
$_SESSION = [];
session_unset();
session_destroy();

// Redirection vers la page de connexion
header('Location: login.php');
exit();
