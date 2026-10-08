<?php

$tituloPagina = 'Dashboard';

require_once __DIR__ . '/includes/auth.php';

$totalEvents = $pdo
    ->query("
        SELECT COUNT(*)
        FROM events
    ")
    ->fetchColumn();

$totalTickets = $pdo
    ->query("
        SELECT COUNT(*)
        FROM tickets
    ")
    ->fetchColumn();

$soldTickets = $pdo
    ->query("
        SELECT COUNT(*)
        FROM tickets
        WHERE status = 'Vendido'
    ")
    ->fetchColumn();

$availableTickets = $pdo
    ->query("
        SELECT COUNT(*)
        FROM tickets
        WHERE status = 'Disponível'
    ")
    ->fetchColumn();

$totalCustomers = $pdo
    ->query("
        SELECT COUNT(*)
        FROM customers
    ")
    ->fetchColumn();

$totalRevenue = $pdo
    ->query("
        SELECT COALESCE(SUM(total), 0)
        FROM sales
    ")
    ->fetchColumn();

require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">

    <h2 class="section-title mb-4">

        <i class="bi bi-speedometer2"></i>

        Dashboard

    </h2>

    <div class="row g-4">

        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-calendar-event"></i>

                <h3>
                    <?= (int) $totalEvents ?>
                </h3>

                <p>
                    Eventos
                </p>

            </div>

        </div>


        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-ticket-perforated"></i>

                <h3>
                    <?= (int) $totalTickets ?>
                </h3>

                <p>
                    Total Bilhetes
                </p>

            </div>

        </div>


        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-ticket-detailed"></i>

                <h3>
                    <?= (int) $soldTickets ?>
                </h3>

                <p>
                    Bilhetes Vendidos
                </p>

            </div>

        </div>


        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-ticket"></i>

                <h3>
                    <?= (int) $availableTickets ?>
                </h3>

                <p>
                    Bilhetes Disponíveis
                </p>

            </div>

        </div>


        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-people"></i>

                <h3>
                    <?= (int) $totalCustomers ?>
                </h3>

                <p>
                    Clientes
                </p>

            </div>

        </div>


        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-cash"></i>

                <h3>

                    <?= number_format(
                        (float) $totalRevenue,
                        2,
                        ',',
                        '.'
                    ) ?>

                    €

                </h3>

                <p>
                    Receita
                </p>

            </div>

        </div>

    </div>

</div>

<?php

require_once __DIR__ . '/includes/footer.php';