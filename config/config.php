<?php
// config.php

$host = 'localhost';
$dbname = 'e_mairie_taabo';
$user = 'root';
$pass = ''; // Mets ton mot de passe ici si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>