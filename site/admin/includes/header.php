<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        <?= htmlspecialchars($tituloPagina ?? 'Admin Banda') ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
        rel="stylesheet">

        <link
    rel="stylesheet"
    href="../css/admin.css">

</head>

<body class="pg-admin">

    <div class="admin-layout">

        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <main class="admin-content">