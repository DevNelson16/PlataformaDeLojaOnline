<?php

$tituloPagina = 'Vendas';

require_once __DIR__ . '/includes/auth.php';

$sales = $pdo->query("
    SELECT
        sales.*,
        customers.name AS customer_name,
        tickets.id AS ticket_id,
        tickets.price AS ticket_price,
        tickets.status AS ticket_status,
        events.title AS event_title,
        events.event_date,
        events.venue
    FROM sales
    LEFT JOIN customers ON sales.customer_id = customers.id
    LEFT JOIN tickets ON sales.ticket_id = tickets.id
    LEFT JOIN events ON tickets.event_id = events.id
    ORDER BY sales.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="mb-4">

        <h2 class="section-title mb-1">
            <i class="bi bi-cash-stack"></i>
            Vendas
        </h2>

        <p class="text-muted mb-0">
            Consultar as vendas realizadas.
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
                            <th>Evento</th>
                            <th>Bilhete</th>
                            <th>Preço</th>
                            <th>Total</th>
                            <th>Data</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($sales)): ?>

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                Não existem vendas.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($sales as $sale): ?>

                            <tr>

                                <td>
                                    #<?= (int) $sale['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $sale['customer_name'] ?? '—'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $sale['event_title'] ?? '—'
                                    ) ?>
                                </td>

                                <td>
                                    <?php if ($sale['ticket_id'] !== null): ?>

                                        #<?= (int) $sale['ticket_id'] ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($sale['ticket_price'] !== null): ?>

                                        <?= number_format(
                                            (float) $sale['ticket_price'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?> €

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (isset($sale['total'])): ?>

                                        <strong>
                                            <?= number_format(
                                                (float) $sale['total'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?> €
                                        </strong>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($sale['created_at'])) {
                                        echo htmlspecialchars($sale['created_at']);
                                    } elseif (isset($sale['sale_date'])) {
                                        echo htmlspecialchars($sale['sale_date']);
                                    } else {
                                        echo '—';
                                    }
                                    ?>
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