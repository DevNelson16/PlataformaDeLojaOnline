<?php
session_start();
require_once __DIR__ . '/../../conexao/db.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}

// Verifica se recebeu o ID do pedido
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = (int) $_GET['id'];

// Buscar pedido
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    die("Pedido não encontrado!");
}

// Buscar itens do pedido
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image 
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
    <title>Detalhes do Pedido #<?= $order['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="pg-detalhes">

<div class="container my-5">
    <div class="card">
        <div class="pedido-header">
            <h3><i class="bi bi-receipt"></i> Detalhes do Pedido #<?= $order['id'] ?></h3>
        </div>
        <div class="card-body">
            <div class="pedido-info">
                <p><strong>Data:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
                <p><strong>Total:</strong> <span class="pedido-total"><?= number_format($order['total'], 2, ',', '.') ?> €</span></p>
            </div>

            <h5 class="mb-3"><i class="bi bi-box-seam"></i> Itens do Pedido</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($item['image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= $item['qty'] ?></td>
                                <td><?= number_format($item['price'], 2, ',', '.') ?> €</td>
                                <td><strong><?= number_format($item['qty'] * $item['price'], 2, ',', '.') ?> €</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="admin.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
                <a href="imprimir.php?id=<?= $order['id'] ?>" class="btn btn-banda">
                    <i class="bi bi-printer"></i> Imprimir Pedido
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
