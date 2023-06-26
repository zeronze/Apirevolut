<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/Exception.php';

// Fonction pour générer un jeton de réinitialisation du mot de passe
function generateResetToken() {
    $length = 32;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';

    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $token;
}

// Vérifier si le formulaire de réinitialisation du mot de passe a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'adresse e-mail soumise dans le formulaire
    $mail = $_POST['mail'];

    // Vérifier si l'e-mail existe dans la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "espace_membre";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM membres WHERE mail = ?");
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Générer un jeton de réinitialisation du mot de passe
        $resetToken = generateResetToken();

        // Enregistrer le jeton de réinitialisation du mot de passe dans la base de données
        $stmt = $conn->prepare("UPDATE membres SET reset_token = ? WHERE mail = ?");
        $stmt->bind_param("ss", $resetToken, $mail);
        $stmt->execute();

        // Configuration des paramètres SMTP avec PHPMailer
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '4e2997b906cf07';
        $phpmailer->Password = '26461530a168dc';

        // Configuration des informations de l'expéditeur et du destinataire
        $phpmailer->setFrom('votresite@exemple.com', 'Votre site');
        $phpmailer->addAddress($mail);

        // Configuration du contenu de l'e-mail
        $phpmailer->Subject = 'Réinitialisation du mot de passe';
        $phpmailer->Body = "Bonjour,\n\nVous avez demandé une réinitialisation de votre mot de passe. Cliquez sur le lien suivant pour réinitialiser votre mot de passe :\n\n" . $resetLink . "\n\nSi vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail.\n\nCordialement,\nVotre site";

        // Envoi de l'e-mail
        if ($phpmailer->send()) {
            // Afficher un message indiquant à l'utilisateur de vérifier sa boîte de réception
            $message = "Un e-mail contenant des instructions de réinitialisation du mot de passe a été envoyé à votre adresse e-mail.";
        } else {
            // Échec de l'envoi de l'e-mail, afficher un message d'erreur
            $errorMessage = "Une erreur s'est produite lors de l'envoi de l'e-mail de réinitialisation du mot de passe. Veuillez réessayer ultérieurement.";
        }

        // Fermeture de la connexion à la base de données
        $stmt->close();
        $conn->close();
    } else {
        // L'adresse e-mail n'existe pas dans la base de données, afficher un message d'erreur
        $errorMessage = "L'adresse e-mail fournie n'est associée à aucun compte.";
    }
}

include '../templates/mot_de_passe_oublie.html';
?>
