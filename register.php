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
        // Démarrage de la session et stockage de l'ID utilisateur 
        session_start(); 
        $_SESSION['user_id'] = $conn->insert_id; 
        
        // Redirection vers la page d'upload de CV 
        header("Location: cv.html"); 
        exit(); 
    } else { 
        echo "Erreur lors de l'inscription : " . $conn->error; 
    } 
    
    $conn->close(); 
} 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Études à l'étranger</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Créer un compte</h1>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="fullname">Nom complet</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="country">Pays de résidence</label>
                <input type="text" id="country" name="country" required>
            </div>
            
            <div class="form-group">
                <label for="level">Niveau d'études</label>
                <select id="level" name="level" required>
                    <option value="">Sélectionnez votre niveau</option>
                    <option value="lycee">Lycée</option>
                    <option value="licence">Licence</option>
                    <option value="master">Master</option>
                    <option value="doctorat">Doctorat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm-password">Confirmer le mot de passe</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            
            <button type="submit" class="btn">S'inscrire</button>
        </form>
        
        <p>Déjà inscrit ? <a href="login.html">Connectez-vous ici</a></p>
    </div>
</body>
</html>