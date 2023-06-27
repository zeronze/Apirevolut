<?php
// Récupérez les données du paiement envoyées depuis votre frontend
$currency = $_POST['currency'];
$totalAmount = $_POST['totalAmount'];

// Effectuez des validations et des traitements supplémentaires si nécessaire

// Appelez l'API Revolut pour créer une commande
$apiUrl = 'https://sandbox-business.revolut.com/api/1.0/orders'; // Endpoint de l'API Revolut (sandbox pour les tests)
$apiKey = 'pk_PPKUmQFkrfwN3fIajhzBLLJMG8BG0RCZgNiuybEBbt5hlxFp'; // Clé d'API du marchand

$data = array(
  'amount' => $totalAmount,
  'currency' => $currency,
  // Ajoutez d'autres paramètres nécessaires à votre demande de commande
);

$headers = array(
  'Content-Type: application/json',
  'Authorization: Bearer ' . $apiKey,
);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if ($response === false) {
  // Gestion des erreurs de requête curl
  $error = curl_error($ch);
  // Traitez l'erreur selon vos besoins
  die('Erreur Curl : ' . $error);
}

curl_close($ch);

// Analysez la réponse de l'API Revolut
$order = json_decode($response, true);

// Vérifiez si la création de la commande a réussi
if (isset($order['public_id'])) {
  // La commande a été créée avec succès
  $publicId = $order['public_id'];

  // Retournez le résultat au frontend
  $responseData = array(
    'publicId' => $publicId
  );

  echo json_encode($responseData);
} else {
  // La création de la commande a échoué
  // Traitez l'erreur selon vos besoins
  die('Erreur lors de la création de la commande Revolut.');
}
?>
