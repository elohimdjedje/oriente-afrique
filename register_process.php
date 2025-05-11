<?php
// Inclusion du fichier de connexion
require_once 'db_connect.php'; // Chemin correct

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom_complet = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $pays = $conn->real_escape_string($_POST['country']);
    $niveau = $conn->real_escape_string($_POST['level']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    
    // Vérification que les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }
    
    // Hachage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Vérification si l'email existe déjà
    $check_email = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Cette adresse email est déjà utilisée.";
        exit();
    }
    
    // Insertion de l'utilisateur dans la base de données
    $sql = "INSERT INTO utilisateurs (nom_complet, email, mot_de_passe, pays_residence, niveau_etudes) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nom_complet, $email, $hashed_password, $pays, $niveau);
    
    if ($stmt->execute()) {
        session_start();
        $_SESSION['user_id'] = $conn->insert_id;
        header("Location: cv.html");
        exit();
    } else {
        echo "Erreur lors de l'inscription : " . $conn->error;
    }
    $conn->close();
}
?>