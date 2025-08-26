<?php
include 'db_connect.php';

// Configuration CinetPay
$api_key = "86110119365f879d1aac8e0.43181545";
$site_id = "105904329";
$secret_key = "32485060688f6b130340a1.20223593";

// Récupération des données de notification
$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['cpm_trans_id'])) {
    $transaction_id = $data['cpm_trans_id'];
    $status = $data['cpm_result'];
    
    // Vérification de la signature
    $received_sign = $data['signature'];
    $to_sign = $data['cpm_amount'] . $data['cpm_currency'] . $data['cpm_trans_id'] . $data['cpm_site_id'];
    $calculated_sign = hash_hmac('sha256', $to_sign, $secret_key);
    
    if ($received_sign === $calculated_sign) {
        // Signature valide, mettre à jour la base de données
        if ($status == '00') {
            // Paiement réussi
            $stmt = $conn->prepare("UPDATE demandes_paiement SET statut = 'payé', date_paiement = NOW() WHERE cinetpay_transaction_id = ?");
            $stmt->bind_param('s', $transaction_id);
            $stmt->execute();
        } else {
            // Paiement échoué
            $stmt = $conn->prepare("UPDATE demandes_paiement SET statut = 'annulé' WHERE cinetpay_transaction_id = ?");
            $stmt->bind_param('s', $transaction_id);
            $stmt->execute();
        }
        http_response_code(200);
        echo "OK";
    } else {
        // Signature invalide
        http_response_code(400);
        echo "Signature invalide";
    }
} else {
    http_response_code(400);
    echo "Données invalides";
}
?>