<?php
session_start();
require_once __DIR__ . '/../../conexao/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user'] ?? '');
    $senha = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['password']) && $user['role'] === 'admin') {
    $_SESSION['admin'] = true;
    header('Location: admin.php');
    exit;
} else {
    $_SESSION['error'] = "Usuário ou senha inválida";
    header('Location: login_admin.php');
    exit;
}

}

// Mostrar erro se existir
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="pg-login-admin">

<div class="login-box">
    <h2>Login Admin</h2>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Utilizador:</label>
            <input type="text" name="user" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Senha:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning w-100">Entrar</button>
        <a href="../index.php" class="btn btn-secondary w-100 mt-2">Voltar ao login</a>
    </form>
</div>

</body>
</html>