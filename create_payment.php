<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$name    = $_POST['name'] ?? null;
$phone   = $_POST['phone'] ?? null;
$address = $_POST['address'] ?? null;

if (!$name || !$phone || !$address) {
    die("Data tidak lengkap.");
}

$stmt = $conn->prepare("
    SELECT cart_items.quantity, products.id AS product_id, products.price
    FROM cart_items 
    JOIN products ON cart_items.product_id = products.id
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result();

$total = 0;
while ($row = $data->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
}

$stmt = $conn->prepare("
    INSERT INTO orders 
    (user_id, total_price, customer_name, customer_phone, customer_address, status)
    VALUES (?, ?, ?, ?, ?, 'pending')
");
$stmt->bind_param("iisss", $user_id, $total, $name, $phone, $address);
$stmt->execute();

$order_db_id = $stmt->insert_id; 
$stmt->close();

$midtrans_order_id = "RJ-" . date("YmdHis") . "-" . $order_db_id;

$stmt = $conn->prepare("
    UPDATE orders 
    SET midtrans_order_id = ? 
    WHERE id = ?
");
$stmt->bind_param("si", $midtrans_order_id, $order_db_id);
$stmt->execute();
$stmt->close();

$data->data_seek(0);
foreach ($data as $item) {
    $stmt = $conn->prepare("
        INSERT INTO order_items 
        (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iiii",
        $order_db_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    );
    $stmt->execute();
    $stmt->close();
}

$server_key = getenv("MIDTRANS_SERVER_KEY");
$app_url    = getenv("APP_URL");

$payload = [
    "transaction_details" => [
        "order_id" => $midtrans_order_id, 
        "gross_amount" => $total
    ],
    "customer_details" => [
        "first_name" => $name,
        "phone" => $phone
    ],
    "callbacks" => [
        "finish" => $app_url . "/user/profile.php"
    ]
];

$ch = curl_init("https://app.sandbox.midtrans.com/snap/v1/transactions");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Accept: application/json",
    "Authorization: Basic " . base64_encode($server_key . ":")
]);

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

if (isset($response["redirect_url"])) {
    header("Location: " . $response["redirect_url"]);
    exit;
} else {
    echo "Gagal membuat pembayaran:<br>";
    var_dump($response);
}