<?php
session_start();
require_once __DIR__ . '/../../conexao/db.php';

// Verifica se é admin
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}

// Verifica se recebeu ID
if (!isset($_GET['id'])) {
    die("Pedido inválido!");
}

$id = (int) $_GET['id'];

// Buscar pedido
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    die("Pedido não encontrado!");
}

// Buscar itens
$stmt = $pdo->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Pedido #<?= $order['id'] ?></title>
        <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="pg-imprimir">

<div class="header">
    <h1>Banda Favorita</h1>
    <p>Comprovativo de Compra</p>
</div>

<div class="pedido-info">
    <p><strong>Pedido Nº:</strong> <?= $order['id'] ?></p>
    <p><strong>Data:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
    <p><strong>Total:</strong> <?= number_format($order['total'], 2, ',', '.') ?> €</p>
</div>

<h3>Itens do Pedido</h3>
<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td><?= number_format($item['price'], 2, ',', '.') ?> €</td>
                <td><?= number_format($item['qty'] * $item['price'], 2, ',', '.') ?> €</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p class="total">TOTAL: <?= number_format($order['total'], 2, ',', '.') ?> €</p>

<div class="footer">
    <p>Obrigado pela sua compra! 🎶</p>
    <p>Banda Favorita - <?= date("Y") ?></p>
</div>

<div class="no-print" style="text-align:center; margin-top:20px;">
    <button onclick="window.print()">🖨️ Imprimir</button>
    <a href="detalhes.php?id=<?= $order['id'] ?>">⬅️ Voltar</a>
</div>

</body>
</html>
