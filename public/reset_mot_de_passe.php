<?php
// Vérifier si le jeton de réinitialisation du mot de passe est valide
if (isset($_GET['token'])) {
    $resetToken = $_GET['token'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "espace_membre";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM membres WHERE reset_token = ?");
    $stmt->bind_param("s", $resetToken);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Afficher le formulaire de réinitialisation du mot de passe
        // Dans ce formulaire, l'utilisateur peut saisir un nouveau mot de passe et le confirmer
    } else {
        // Jeton de réinitialisation du mot de passe invalide, afficher un message d'erreur
        $errorMessage = "Le lien de réinitialisation du mot de passe est invalide ou a expiré.";
    }

    $stmt->close();
    $conn->close();
}

include '../templates/reset_mot_de_passe.html';
?>

