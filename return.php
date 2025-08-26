<?php
session_start();
include 'db_connect.php';

// Configuration CinetPay
$api_key = "86110119365f879d1aac8e0.43181545";
$site_id = "105904329";

// Récupération de l'ID de transaction
$transaction_id = $_GET['transaction_id'] ?? '';

if (!empty($transaction_id)) {
    // Vérification du statut du paiement
    function verifierPaiement($transactionId, $api_key, $site_id) {
        $url = "https://api.cinetpay.com/v2/payment/check";
        $params = http_build_query([
            'apikey' => $api_key,
            'site_id' => $site_id,
            'transaction_id' => $transactionId
        ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?' . $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) return false;

        $data = json_decode($response, true);
        if (isset($data['data']['status'])) {
            return $data['data']['status'];
        }
        return false;
    }

    $statut = verifierPaiement($transaction_id, $api_key, $site_id);
    
    // Affichage du résultat
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Résultat du paiement</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            .success { color: green; }
            .error { color: red; }
            .pending { color: orange; }
        </style>
    </head>
    <body>';
    
    if ($statut === 'ACCEPTED') {
        echo '<h1 class="success">Paiement réussi!</h1>
              <p>Votre paiement a été traité avec succès.</p>';
    } elseif ($statut === 'REFUSED' || $statut === 'CANCELLED') {
        echo '<h1 class="error">Paiement échoué</h1>
              <p>Votre paiement a été refusé ou annulé.</p>';
    } else {
        echo '<h1 class="pending">Paiement en attente</h1>
              <p>Votre paiement est encore en cours de traitement.</p>';
    }
    
    echo '<p>ID de transaction: ' . htmlspecialchars($transaction_id) . '</p>
          <p><a href="index.php">Retour à l\'accueil</a></p>
          </body></html>';
} else {
    echo '<h1 class="error">Erreur</h1>
          <p>Aucun ID de transaction spécifié.</p>
          <p><a href="index.php">Retour à l\'accueil</a></p>';
}
?>