<?php

$tituloPagina = 'Pedidos';

require_once __DIR__ . '/includes/auth.php';

$orders = $pdo->query("
    SELECT *
    FROM orders
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="section-title mb-1">
            <i class="bi bi-cart-check"></i>
            Pedidos
        </h2>

        <p class="text-muted mb-0">
            Consultar os pedidos realizados pelos clientes.
        </p>
    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($orders)): ?>

                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Não existem pedidos.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($orders as $order): ?>

                            <tr>

                                <td>
                                    #<?= (int) $order['id'] ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($order['customer_name'])) {
                                        echo htmlspecialchars($order['customer_name']);
                                    } elseif (isset($order['name'])) {
                                        echo htmlspecialchars($order['name']);
                                    } elseif (isset($order['customer_id'])) {
                                        echo 'Cliente #' . (int) $order['customer_id'];
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($order['total'])):
                                    ?>
                                        <?= number_format(
                                            (float) $order['total'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?> €
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($order['status'])):
                                    ?>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($order['created_at'])) {
                                        echo htmlspecialchars($order['created_at']);
                                    } elseif (isset($order['order_date'])) {
                                        echo htmlspecialchars($order['order_date']);
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </td>

                                <td>

                                    <a
                                        href="detalhes.php?id=<?= (int) $order['id'] ?>"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="bi bi-eye"></i>
                                        Ver detalhes
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