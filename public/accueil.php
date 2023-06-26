<?php
session_start();

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

// Récupérer tous les produits de la table "Products"
$sql = "SELECT * FROM Products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

include '../templates/accueil.html';
?>