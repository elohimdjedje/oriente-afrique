<?php
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Inclusion du fichier de connexion
require_once 'db_connect.php'; // Chemin correct

// Récupération de l'ID utilisateur
$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur
$sql = "SELECT * FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommandations d'écoles</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .recommendations-container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(0,0,0,0.5);
            border-radius: 10px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            padding: 32px 24px;
        }
        .school-card {
            background: rgba(27, 37, 127, 0.2);
            border: 1px solid #1b257f;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .match-percentage {
            background: #1b257f;
            color: white;
            border-radius: 20px;
            padding: 5px 10px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="recommendations-container">
        <h1>Écoles recommandées pour vous</h1>
        <p>Basé sur votre profil et vos documents, voici les établissements qui correspondent le mieux à votre parcours.</p>
        
        <div class="school-card">
            <span class="match-percentage">95% de correspondance</span>
            <h2>École Supérieure d'Ingénieurs de Paris</h2>
            <p>Paris, France</p>
            <p>Domaine: Informatique et Réseaux</p>
            <button class="btn btn-primary">Voir les détails</button>
        </div>
        
        <div class="school-card">
            <span class="match-percentage">87% de correspondance</span>
            <h2>Université de Lyon</h2>
            <p>Lyon, France</p>
            <p>Domaine: Sciences de l'Ingénieur</p>
            <button class="btn btn-primary">Voir les détails</button>
        </div>
        
        <div class="school-card">
            <span class="match-percentage">82% de correspondance</span>
            <h2>EDHEC Business School</h2>
            <p>Lille, France</p>
            <p>Domaine: Management International</p>
            <button class="btn btn-primary">Voir les détails</button>
        </div>
    </div>
</body>
</html>