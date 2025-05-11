<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";  // À changer en production
$password = "";      // À changer en production
$dbname = "etude_etranger";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Configuration de l'encodage UTF-8
$conn->set_charset("utf8mb4");
?>