<?php
// Inclusion du fichier de connexion
require_once 'db_connect.php'; // Chemin correct

// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Récupération de l'ID utilisateur
$user_id = $_SESSION['user_id'];

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification du type de document (CV ou bulletin)
    $type_document = isset($_POST['document_type']) ? $_POST['document_type'] : 'cv';
    
    // Vérification si un fichier a été uploadé
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        // Définition des extensions autorisées
        $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        
        // Récupération de l'extension du fichier
        $file_info = pathinfo($_FILES['document']['name']);
        $extension = strtolower($file_info['extension']);
        
        // Vérification de l'extension
        if (in_array($extension, $allowed_extensions)) {
            // Génération d'un nom de fichier unique
            $new_filename = uniqid() . '.' . $extension;
            
            // Définition du chemin de destination
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $destination = $upload_dir . $new_filename;
            
            // Déplacement du fichier vers le dossier de destination
            if (move_uploaded_file($_FILES['document']['tmp_name'], $destination)) {
                // Enregistrement des informations dans la base de données
                $sql = "INSERT INTO documents (utilisateur_id, type_document, nom_fichier, chemin_fichier) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $user_id, $type_document, $_FILES['document']['name'], $destination);
                
                if ($stmt->execute()) {
                    // Redirection vers la page de recommandations
                    header("Location: recommendations.php");
                    exit();
                } else {
                    echo "Erreur lors de l'enregistrement du document : " . $conn->error;
                }
            } else {
                echo "Erreur lors de l'upload du fichier.";
            }
        } else {
            echo "Extension de fichier non autorisée.";
        }
    } else {
        echo "Erreur lors de l'upload du fichier.";
    }
}
?>