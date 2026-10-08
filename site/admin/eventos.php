<?php

$tituloPagina = 'Eventos';

require_once __DIR__ . '/includes/auth.php';

$events = $pdo->query("
    SELECT id, title, description, city, venue, event_date, price, image, created_at
    FROM events
    ORDER BY event_date ASC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                <i class="bi bi-calendar-event"></i>
                Eventos
            </h2>

            <p class="text-muted mb-0">
                Gerir os eventos da banda.
            </p>
        </div>

        <a href="add_event.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Adicionar evento
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Evento</th>
                            <th>Cidade</th>
                            <th>Local</th>
                            <th>Data</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($events)): ?>

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                Não existem eventos.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($events as $event): ?>

                            <tr>

                                <td>
                                    #<?= (int) $event['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($event['title']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($event['city']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($event['venue']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($event['event_date']) ?>
                                </td>

                                <td>
                                    <?= number_format(
                                        (float) $event['price'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?> €
                                </td>

                                <td>

                                    <a
                                        href="edit_event.php?id=<?= (int) $event['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Editar
                                    </a>

                                    <a
                                        href="delete_event.php?id=<?= (int) $event['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem a certeza que pretende eliminar este evento?');"
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