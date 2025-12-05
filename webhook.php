<?php
include "connect.php";

$input = file_get_contents('php://input');
$data = $_POST;
if (empty($data) && $input) {
    $data = json_decode($input, true);
}

$payment_id = isset($data['payment_id']) ? intval($data['payment_id']) : null;
$order_id   = isset($data['order_id']) ? intval($data['order_id']) : null;
$status     = isset($data['status']) ? $data['status'] : null;

if (!$payment_id || !$order_id || !in_array($status, ['paid','failed','pending'])) {
    http_response_code(400);
    echo json_encode(['ok'=>false, 'msg'=>'invalid']);
    exit;
}

$stmt = $conn->prepare("UPDATE payments SET status = ?, provider_tx_id = ?, updated_at = NOW() WHERE id = ?");
$provider_tx = 'sandbox_tx_' . time();
$stmt->bind_param("ssi", $status, $provider_tx, $payment_id);
$stmt->execute();
$stmt->close();

if ($status === 'paid') {
    $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
} elseif ($status === 'failed') {
    $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
}

if (!empty($_SERVER['HTTP_REFERER'])) {
    header("Location: profile.php");
    exit;
}


http_response_code(200);
echo json_encode(['ok'=>true]);
exit;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: profile.php");
    exit;
}

?>