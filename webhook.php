<?php
include "connect.php";

$input = file_get_contents("php://input");
$data  = json_decode($input, true);

if (!$data || !isset($data['order_id']) || !isset($data['transaction_status'])) {
    http_response_code(400);
    exit;
}

$midtrans_order_id = $data['order_id'];
$tx_status = $data['transaction_status'];
$tx_id = $data['transaction_id'] ?? null;

if (in_array($tx_status, ['settlement', 'capture'])) {
    $order_status = 'paid';
} elseif (in_array($tx_status, ['deny', 'expire', 'cancel'])) {
    $order_status = 'failed';
} else {
    $order_status = 'pending';
}

$stmt = $conn->prepare("
    UPDATE orders 
    SET status = ? 
    WHERE midtrans_order_id = ?
");
$stmt->bind_param("ss", $order_status, $midtrans_order_id);
$stmt->execute();
$stmt->close();     

$stmt = $conn->prepare("
    INSERT INTO payments (order_id, status, provider_tx_id)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE
        status = VALUES(status),
        provider_tx_id = VALUES(provider_tx_id),
        updated_at = NOW()
");
$stmt->bind_param("sss", $midtrans_order_id, $order_status, $tx_id);
$stmt->execute();
$stmt->close();

http_response_code(200);
echo json_encode(['ok' => true]);
