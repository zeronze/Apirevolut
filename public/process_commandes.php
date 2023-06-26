<?php
session_start();
include 'fonctions-panier.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../partials/connexion.php");
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

// Récupérer les valeurs du formulaire de commande
$adresse = $_POST['delivery_address'];
$livraison = $_POST['delivery_method'];
$code_reduction = $_POST['optional_code'];
$moyen_paiement = $_POST['payment_method'];
$date_commande = $_POST['order_date'];

// Récupérer les informations du panier
$libelleProduit = $_SESSION['panier']['libelleProduit'];
$qteProduit = $_SESSION['panier']['qteProduit'];
$prixProduit = $_SESSION['panier']['prixProduit'];

// Convertir les tableaux du panier en chaînes de texte
$produits = implode(', ', $libelleProduit);
$quantites = implode(', ', $qteProduit);
$prixTotal = MontantGlobal();

$sql = "INSERT INTO commandes (adresse, livraison, code_reduction, moyen_paiement, date_commande, user_id, produits, quantites, prix_total) 
        VALUES ('$adresse', '$livraison', '$code_reduction', '$moyen_paiement', '$date_commande', '$userID', '$produits', '$quantites', '$prixTotal')";

// Exécution de la requête
if ($conn->query($sql) === true) {
    // La commande a été enregistrée avec succès
    echo "La commande a été enregistrée avec succès.";
} else {
    // Une erreur s'est produite lors de l'enregistrement de la commande
    echo "Une erreur s'est produite lors de l'enregistrement de la commande : " . $conn->error;
}

// Fermer la connexion à la base de données
$conn->close();
