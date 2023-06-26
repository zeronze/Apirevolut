<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: ../public/profil.php");
    exit();
}

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configuration de la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "espace_membre";

    // Récupérer les données du formulaire
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $errorMessage = "Erreur de connexion à la base de données : " . $conn->connect_error;
        include '../templates/connexion.html';
        exit();
    }

    // Vérifier les identifiants dans la base de données
    $stmt = $conn->prepare("SELECT id, motdepasse FROM membres WHERE pseudo = ?");
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($mdp, $user['motdepasse'])) {
        // Authentification réussie, enregistrer l'ID de l'utilisateur en session
        $_SESSION['user_id'] = $user['id'];

        // Fermer la connexion à la base de données
        $stmt->close();
        $conn->close();

        // Rediriger vers la page de profil
        header("Location: ../public/profil.php");
        exit();
    } else {
        // Identifiants invalides, afficher un message d'erreur
        $errorMessage = "Pseudo ou mot de passe incorrect.";
    }

    // Fermer la connexion à la base de données
    $stmt->close();
    $conn->close();
}

include '../templates/connexion.html';
?>
