<?php
// Inclusion du fichier de connexion
require_once 'db_connect.php'; // Chemin correct

// Démarrage de la session
session_start();

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // Requête préparée pour éviter les injections SQL
    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Vérification du mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie, stockage de l'ID utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            
            // Redirection vers la page de recommandations
            header("Location: recommendations.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cette adresse email.";
    }
    
    $stmt->close();
    $conn->close();
}
?>