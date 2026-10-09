<?php

session_start();

require_once __DIR__ . '/../../conexao/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido.'
    ]);

    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$qty = max(1, (int) ($_POST['qty'] ?? 1));

$stmt = $pdo->prepare(
    'SELECT id, name, price FROM products WHERE id = ?'
);

$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'Produto não encontrado.'
    ]);

    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'qty' => $qty
    ];
}

echo json_encode([
    'success' => true,
    'cart_count' => count($_SESSION['cart']),
    'message' => 'Produto adicionado ao carrinho.'
]);

exit;
