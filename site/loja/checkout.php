<?php

session_start();

require '../../conexao/db.php';

// Obter os produtos do carrinho
$cart = $_SESSION['cart'] ?? [];

// Se o carrinho estiver vazio, voltar ao carrinho
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

// Calcular o total da encomenda
$total = 0;

foreach ($cart as $it) {
    $total += $it['price'] * $it['qty'];
}

// Identificar o cliente autenticado
$customerId = null;

if (isset($_SESSION['user_id'])) {

    $stmt = $pdo->prepare("
        SELECT c.id
        FROM users_banda u
        INNER JOIN customers c
            ON LOWER(TRIM(u.email)) = LOWER(TRIM(c.email))
        WHERE u.id = ?
        LIMIT 1
    ");

    $stmt->execute([$_SESSION['user_id']]);

    $customerId = $stmt->fetchColumn();

    if ($customerId === false) {
        $customerId = null;
    }
}

// Registar a encomenda associada ao cliente
$stmt = $pdo->prepare("
    INSERT INTO orders (total, customer_id)
    VALUES (?, ?)
");

$stmt->execute([$total, $customerId]);

$orderId = $pdo->lastInsertId();

// Registar os produtos da encomenda
$stm = $pdo->prepare("
    INSERT INTO order_items (order_id, product_id, qty, price)
    VALUES (?, ?, ?, ?)
");

foreach ($cart as $it) {
    $stm->execute([
        $orderId,
        $it['id'],
        $it['qty'],
        $it['price']
    ]);
}

// Limpar o carrinho após a compra
unset($_SESSION['cart']);

?>

<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Obrigado</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="pg-checkout">

    <h1>Compra finalizada</h1>

    <p>
        Encomenda Nº <?= (int) $orderId ?> concluída.
        Total:
        <strong>
            <?= number_format($total, 2, ',', '.') ?> €
        </strong>
    </p>

    <p>
        <a class="button" href="loja_online.php">
            Voltar à loja
        </a>
    </p>

    <h1>Obrigado! Volte sempre!</h1>

    <footer class="bg-dark text-white text-center py-3">
        &copy; 2025 Criado por Nelson Geovetty. Todos os direitos reservados.
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/scripts.js"></script>

</body>

</html>