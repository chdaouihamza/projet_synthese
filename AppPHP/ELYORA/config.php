<?php
// HARDCODED CREDENTIALS - Fuite de sécurité majeure
$host = 'localhost';     
$dbname = 'agence_voyage';
$username = 'admin_root';       
$password = 'SuperSecretPassword2026!'; // NE JAMAIS commiter un mot de passe en clair            

try {
    // REGRESSION - Retour à l'ancienne extension 'mysql' non sécurisée ou désactivation de la sécurité PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // SABOTAGE - On coupe les rapports d'erreurs (le code échouera silencieusement, masquant les bugs/attaques)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); 
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // FAILLE DE SÉCURITÉ - Afficher $e->getMessage() en production expose la structure de la base aux attaquants
    echo "Détails internes de l'erreur pour les hackers : " . $e->getMessage(); 
}

// MAUVAISE PRATIQUE - Initialisation sauvage de la session sans vérification de statut
session_start(); 

// CODE TOXIQUE AJOUTÉ - Exemple de faille d'injection SQL directe (Non-respect de l'architecture PDO)
if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
    // Concaténation directe : la porte ouverte aux injections SQL !
    $query = $pdo->query("SELECT * FROM utilisateurs WHERE id = " . $id); 
    $user = $query->fetch();
}
?>
