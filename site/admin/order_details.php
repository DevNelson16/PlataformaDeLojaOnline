<?php
session_start();
require_once __DIR__ . '/../../conexao/db.php';

$orderId = $_GET['id'] ?? 0;

if ($orderId == 0) {
    echo "Pedido não encontrado.";
    exit;
}

// Consultar o pedido
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    echo "Pedido não encontrado.";
    exit;
}

// Consultar os itens do pedido
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.price, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Pedido #<?= $orderId ?></title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="pg-order-details">
    <h1>Detalhes do Pedido #<?= $orderId ?></h1>
    <p><strong>Total:</strong> <?= number_format($order['total'], 2, ',', '.') ?> €</p>
    <p><strong>Data:</strong> <?= $order['created_at'] ?></p>

    <h2>Itens do Pedido</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="50"> <?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2, ',', '.') ?> €</td>
                <td><?= $item['qty'] ?></td>
                <td><?= number_format($item['price'] * $item['qty'], 2, ',', '.') ?> €</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="admin.php">Voltar ao painel</a></p>
</body>
</html>
