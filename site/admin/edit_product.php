<?php
session_start();
require_once __DIR__ . '/../../conexao/db.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}

// Verifica se recebeu o ID do produto
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = (int) $_GET['id'];

// Busca o produto
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produto não encontrado!");
}

// Atualizar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $image = $_POST['image'] ?? '';

    $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, image=? WHERE id=?");
    $stmt->execute([$name, $price, $image, $id]);

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Produto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="pg-edit-product">

<div class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-4 text-center">Editar Produto</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nome:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Preço (€):</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem (URL):</label>
                <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($product['image'] ?? '') ?>">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning w-50 me-2">Salvar Alterações</button>
                <a href="admin.php" class="btn btn-secondary w-50 ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
