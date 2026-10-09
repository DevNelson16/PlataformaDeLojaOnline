<?php

$tituloPagina = 'Bilhetes';

require_once __DIR__ . '/includes/auth.php';

$tickets = $pdo->query("
    SELECT
        tickets.*,
        events.title AS event_title,
        events.event_date,
        events.venue,
        events.price AS event_price,
        CASE
            WHEN tickets.ticket_number LIKE '%-GA-%' THEN 'GA'
            WHEN tickets.ticket_number LIKE '%-VIP-%' THEN 'VIP'
            WHEN tickets.ticket_number LIKE '%-FS-%' THEN 'FS'
            ELSE 'Geral'
        END AS type
    FROM tickets
    LEFT JOIN events ON tickets.event_id = events.id
    ORDER BY tickets.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="section-title mb-1">
            <i class="bi bi-ticket-perforated"></i>
            Bilhetes
        </h2>

        <p class="text-muted mb-0">
            Consultar e gerir os bilhetes dos eventos.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Evento</th>
                            <th>Data</th>
                            <th>Local</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (empty($tickets)): ?>

                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    Não existem bilhetes.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($tickets as $ticket): ?>

                                <tr>

                                    <td>
                                        #<?= (int) $ticket['id'] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $ticket['event_title'] ?? '—'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $ticket['event_date'] ?? '—'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $ticket['venue'] ?? '—'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $ticket['type'] ?? '—'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= number_format(
                                            (float) (
                                                $ticket['event_price']
                                                ?? $ticket['price']
                                            ),
                                            2,
                                            ',',
                                            '.'
                                        ) ?> €
                                    </td>

                                    <td>

                                        <?php if ($ticket['status'] === 'Vendido'): ?>

                                            <span class="badge bg-success">
                                                Vendido
                                            </span>

                                        <?php elseif ($ticket['status'] === 'Disponível'): ?>

                                            <span class="badge bg-primary">
                                                Disponível
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars(
                                                    $ticket['status'] ?? '—'
                                                ) ?>
                                            </span>

                                        <?php endif; ?>

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