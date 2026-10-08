<?php

$tituloPagina = 'Produtos';

require_once __DIR__ . '/includes/auth.php';

$products = $pdo->query("
    SELECT *
    FROM products
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                <i class="bi bi-shop"></i>
                Produtos
            </h2>

            <p class="text-muted mb-0">
                Gerir os produtos da loja.
            </p>
        </div>

        <a href="add_product.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Adicionar produto
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($products)): ?>

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Não existem produtos.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($products as $product): ?>

                            <tr>

                                <td>
                                    <?= (int) $product['id'] ?>
                                </td>

                                <td>
                                    <?php if (!empty($product['image'])): ?>

                                        <img
                                            src="../<?= htmlspecialchars($product['image']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>"
                                            width="60"
                                            height="60"
                                            style="object-fit: cover;"
                                            class="rounded"
                                        >

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Sem imagem
                                        </span>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['name']) ?>
                                </td>

                                <td>
                                    <?= number_format(
                                        (float) $product['price'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?> €
                                </td>

                                <td>

                                    <a
                                        href="edit_product.php?id=<?= (int) $product['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Editar
                                    </a>

                                    <a
                                        href="delete_product.php?id=<?= (int) $product['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem a certeza que pretende eliminar este produto?');"
                                    >
                                        <i class="bi bi-trash"></i>
                                        Eliminar
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>