<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$userID = $_SESSION['user_id'];

// Récupérer les informations du profil depuis la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espace_membre";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Préparer et exécuter la requête SQL pour récupérer les informations du profil
$stmt = $conn->prepare("SELECT pseudo, mail, lien_de_parrainage, parrain_id, points FROM membres WHERE id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'utilisateur existe dans la base de données
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pseudo = $row['pseudo'];
    $mail = $row['mail'];
    $referralLink = $row['lien_de_parrainage']; // Ajout de cette ligne pour récupérer le lien de parrainage
    $parrainID = $row['parrain_id']; // Ajout de cette ligne pour récupérer l'ID du parrain
    $points = $row['points']; // Ajout de cette ligne pour récupérer les points de l'utilisateur

    // Vérifier si l'utilisateur a un parrain
    if ($parrainID !== null) {
        // Récupérer les informations du parrain depuis la base de données
        $stmt = $conn->prepare("SELECT pseudo FROM membres WHERE id = ?");
        $stmt->bind_param("i", $parrainID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $parrain = $row['pseudo'];
        } else {
            // Le parrain n'existe pas dans la base de données
            $parrain = "Inconnu";
        }
    } else {
        // L'utilisateur n'a pas de parrain
        $parrain = "Aucun parrain";
    }
} else {
    // Utilisateur introuvable, déconnecter et rediriger vers la page de connexion
    session_destroy();
    $stmt->close();
    $conn->close();
    header("Location: connexion.php");
    exit();
}

// Récupérer le nombre de filleuls de l'utilisateur
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM membres WHERE parrain_id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombreFilleuls = $row['count'];
} else {
    $nombreFilleuls = 0;
}

// Fermer le premier statement
$stmt->close();

// Fermer la connexion à la base de données
$conn->close();

// Générer le lien de parrainage avec l'ID de l'utilisateur
$lienParrainage = 'http://parrainage/partials/inscription.php?ref=' . $referralLink;

// Inclure le fichier du modèle de profil
include '../templates/profil.html';
?>
