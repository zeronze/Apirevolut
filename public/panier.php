<?php

session_start();
include_once("fonctions-panier.php");

creationPanier();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../partials/connexion.php");
    exit();
}

// Récupérer les informations du profil depuis la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "espace_membre";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$userID = $_SESSION['user_id'];

// Préparer et exécuter la requête SQL pour récupérer les informations du profil
$stmt = $conn->prepare("SELECT bon_reduction FROM membres WHERE id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bon_reduction = $row['bon_reduction']; // Récupérer la valeur du bon de réduction
}

// Calculer le montant total du panier
$montantGlobal = MontantGlobal();
$reduction = $bon_reduction ? (($montantGlobal * $bon_reduction) / 100) : 0;

if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = null;
}

$erreur = false;

if ($action !== null) {
    if (!in_array($action, array('ajout', 'suppression', 'refresh'))) {
        $erreur = true;
    }

    // Récupération des variables en POST ou GET
    $l = (isset($_POST['l']) ? $_POST['l'] : (isset($_GET['l']) ? $_GET['l'] : null));
    $p = (isset($_POST['p']) ? $_POST['p'] : (isset($_GET['p']) ? $_GET['p'] : null));
    $q = (isset($_POST['q']) ? $_POST['q'] : (isset($_GET['q']) ? $_GET['q'] : null));

    // Suppression des espaces verticaux
    $l = preg_replace('#\v#', '', $l);
    // On vérifie que $p est un float
    $p = floatval($p);

    // On traite $q qui peut être un entier simple ou un tableau d'entiers
    if (is_array($q)) {
        $QteArticle = array();
        $i = 0;
        foreach ($q as $contenu) {
            $QteArticle[$i++] = intval($contenu);
        }
    } else {
        $q = intval($q);
    }
}

if (!$erreur) {
    switch ($action) {
        case "ajout":
            ajouterArticle($l, $q, $p);
            break;

        case "suppression":
            supprimerArticle($l);
            break;

        case "refresh":
            for ($i = 0; $i < count($QteArticle); $i++) {
                modifierQTeArticle($_SESSION['panier']['libelleProduit'][$i], round($QteArticle[$i]));
            }
            break;

        default:
            break;
    }
}
include '../templates/panier.html';
?>
