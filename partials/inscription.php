<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Rediriger vers le fichier de déconnexion pour effectuer la déconnexion
    header("Location: ../partials/deconnexion.php");
    exit();
}

// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espace_membre";

// Placez le code de génération de lien de parrainage ici
function generateReferralLink() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $referralLink = '';
    $length = 10; // Longueur du lien de parrainage

    for ($i = 0; $i < $length; $i++) {
        $referralLink .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $referralLink;
}

// Vérifier si tous les champs sont remplis
if (empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['mail2']) || empty($_POST['mdp']) || empty($_POST['mdp2'])) {
    $errorMessage = "Veuillez remplir tous les champs du formulaire.";
    include '../templates/inscription.html';
    exit();
}

// Récupérer les données du formulaire
$pseudo = $_POST['pseudo'];
$mail = $_POST['mail'];
$mail2 = $_POST['mail2'];
$mdp = $_POST['mdp'];
$mdp2 = $_POST['mdp2'];

// Vérifier si les adresses email correspondent
if ($mail !== $mail2) {
    $errorMessage = "Les adresses email ne correspondent pas.";
    include '../templates/inscription.html';
    exit();
}

// Vérifier si les mots de passe correspondent
if ($mdp !== $mdp2) {
    $errorMessage = "Les mots de passe ne correspondent pas.";
    include '../templates/inscription.html';
    exit();
}

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $errorMessage = "Erreur de connexion à la base de données : " . $conn->connect_error;
    include '../templates/inscription.html';
    exit();
}

// Vérifier si l'adresse e-mail existe déjà dans la base de données
$stmt = $conn->prepare("SELECT id FROM membres WHERE mail = ?");
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // L'adresse e-mail existe déjà, afficher un message d'erreur
    $errorMessage = "L'adresse e-mail est déjà utilisée par un autre utilisateur.";
    include '../templates/inscription.html';
    exit();
}

$stmt->close();

// Hash du mot de passe
$hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

// Générer le lien de parrainage
$referralLink = generateReferralLink();

// Récupérer l'ID du parrain depuis la base de données
$parrainID = null;
if (!empty($_POST['referral'])) {
    $referral = $_POST['referral'];

    $stmt = $conn->prepare("SELECT id FROM membres WHERE lien_de_parrainage = ?");
    $stmt->bind_param("s", $referral);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $parrainID = $row['id'];
    } else {
        $errorMessage = "Le code de parrainage n'est pas valide. Veuillez réessayer.";
        include '../templates/inscription.html';
        exit();
    }

    $stmt->close();
}

// Insérer l'utilisateur dans la table membres avec le lien de parrainage et l'ID du parrain
$stmt = $conn->prepare("INSERT INTO membres (pseudo, mail, motdepasse, lien_de_parrainage, parrain_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $pseudo, $mail, $hashedPassword, $referralLink, $parrainID);

if ($stmt->execute()) {
    $successMessage = "L'inscription a été effectuée avec succès !<br>pseudo : " . $pseudo . "<br>mail : " . $mail . "<br>Lien de parrainage : " . $referralLink . "<br>";

    // Attribuer 10 points au parrain
    if ($parrainID) {
        $stmt = $conn->prepare("UPDATE membres SET points = points + 10 WHERE id = ?");
        $stmt->bind_param("i", $parrainID);
        $stmt->execute();
        $stmt->close();
    }

    include '../templates/inscription.html';
} else {
    $errorMessage = "Erreur lors de l'inscription : " . $stmt->error;
    include '../templates/inscription.html';
}

$stmt->close();

// Fermer la connexion à la base de données
$conn->close();
?>
