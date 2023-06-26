<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises dans le formulaire
    $pseudo = $_POST['pseudo'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    // Valider les données soumises (ajoutez vos propres validations si nécessaire)

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Mettre à jour les informations du profil dans la base de données
    $servername = "localhost";
    $username = "root";
    $dbpassword = ""; // Modifier le mot de passe de la base de données si nécessaire
    $dbname = "espace_membre";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE membres SET pseudo = ?, mail = ?, motdepasse = ? WHERE id = ?");
    $stmt->bind_param("sssi", $pseudo, $mail, $hashedPassword, $userID);
    $stmt->execute();

    // Rediriger vers la page de profil après la mise à jour du profil
    header("Location: profil.php");
    exit();
} else {
    // Afficher le formulaire de modification du profil
    include '../templates/edit_profil.html';
}
?>
