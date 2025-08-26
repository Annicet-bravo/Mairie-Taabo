<?php
// create_user.php
require 'config/config.php'; // ou simplement 'config.php' selon l'emplacement

$email = 'agent@taabo.ci';
$mot_de_passe = password_hash('motdepasse123', PASSWORD_DEFAULT); // mot de passe sécurisé
$nom = 'Agent Exemple';

$stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'agent')");
$stmt->execute([$nom, $email, $mot_de_passe]);

echo "✅ Agent créé avec succès.";
?>