<?php

$tituloPagina = 'Dashboard';

require_once __DIR__ . '/includes/auth.php';

// ========================================
// ÚLTIMA VISITA AO DASHBOARD
// ========================================

// Guardar a data apenas se ainda não existir.
// Assim, a atualização automática não altera a referência.
if (!isset($_SESSION['ultima_visita_dashboard'])) {
    $_SESSION['ultima_visita_dashboard'] = date('Y-m-d H:i:s');
}

$ultimaVisita = $_SESSION['ultima_visita_dashboard'];

// Identificar a atualização automática
$atualizacaoAutomatica = isset($_GET['auto_refresh'])
    && $_GET['auto_refresh'] === '1';

// ========================================
// CONTADORES DE NOVIDADES
// ========================================

$novosPedidos = 0;
$novosEventos = 0;
$novosClientes = 0;
$novasVendas = 0;

// Novos pedidos
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM orders
    WHERE created_at > ?
");

$stmt->execute([$ultimaVisita]);
$novosPedidos = (int) $stmt->fetchColumn();

// Novos eventos
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM events
    WHERE created_at > ?
");

$stmt->execute([$ultimaVisita]);
$novosEventos = (int) $stmt->fetchColumn();

// Novos clientes
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM customers
    WHERE created_at > ?
");

$stmt->execute([$ultimaVisita]);
$novosClientes = (int) $stmt->fetchColumn();

// Novas vendas
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM sales
    WHERE sale_date > ?
");

$stmt->execute([$ultimaVisita]);
$novasVendas = (int) $stmt->fetchColumn();

// ========================================
// CONTADORES GERAIS
// ========================================

// Total de pedidos
$totalOrders = $pdo->query("
    SELECT COUNT(*)
    FROM orders
")->fetchColumn();

// Total de eventos
$totalEvents = $pdo->query("
    SELECT COUNT(*)
    FROM events
")->fetchColumn();

// Total de bilhetes
$totalTickets = $pdo->query("
    SELECT COUNT(*)
    FROM tickets
")->fetchColumn();

// Bilhetes vendidos
$soldTickets = $pdo->query("
    SELECT COUNT(*)
    FROM tickets
    WHERE status = 'Vendido'
")->fetchColumn();

// Bilhetes disponíveis
$availableTickets = $pdo->query("
    SELECT COUNT(*)
    FROM tickets
    WHERE status = 'Disponível'
")->fetchColumn();

// Total de clientes
$totalCustomers = $pdo->query("
    SELECT COUNT(*)
    FROM customers
")->fetchColumn();

// Receita total
$totalRevenue = $pdo->query("
    SELECT COALESCE(SUM(total), 0)
    FROM sales
")->fetchColumn();

// ========================================
// CABEÇALHO
// ========================================

require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">

    <!-- Título do dashboard -->
    <div class="mb-4">

        <h2 class="section-title mb-1">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </h2>

        <p class="text-muted mb-0">
            Visão geral do painel administrativo.
        </p>

    </div>

    <div class="row g-4">

        <!-- PEDIDOS -->
        <div class="col-md-4 col-lg-3">

            <a
                href="orders.php"
                class="text-decoration-none text-reset">

                <div class="card-dashboard position-relative">

                    <?php if ($novosPedidos > 0): ?>

                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                            +<?= $novosPedidos ?> novo(s)
                        </span>

                    <?php endif; ?>

                    <i class="bi bi-cart-check"></i>

                    <h3>
                        <?= (int) $totalOrders ?>
                    </h3>

                    <p>Pedidos</p>

                </div>

            </a>

        </div>

        <!-- EVENTOS -->
        <div class="col-md-4 col-lg-3">

            <a
                href="eventos.php"
                class="text-decoration-none text-reset">

                <div class="card-dashboard position-relative">

                    <?php if ($novosEventos > 0): ?>

                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                            +<?= $novosEventos ?> novo(s)
                        </span>

                    <?php endif; ?>

                    <i class="bi bi-calendar-event"></i>

                    <h3>
                        <?= (int) $totalEvents ?>
                    </h3>

                    <p>Eventos</p>

                </div>

            </a>

        </div>

        <!-- TOTAL DE BILHETES -->
        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-ticket-perforated"></i>

                <h3>
                    <?= (int) $totalTickets ?>
                </h3>

                <p>Total de Bilhetes</p>

            </div>

        </div>

        <!-- BILHETES VENDIDOS -->
        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-ticket-detailed"></i>

                <h3>
                    <?= (int) $soldTickets ?>
                </h3>

                <p>Bilhetes Vendidos</p>

            </div>

        </div>

        <!-- BILHETES DISPONÍVEIS -->
        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-ticket"></i>

                <h3>
                    <?= (int) $availableTickets ?>
                </h3>

                <p>Bilhetes Disponíveis</p>

            </div>

        </div>

        <!-- CLIENTES -->
        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard position-relative">

                <?php if ($novosClientes > 0): ?>

                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                        +<?= $novosClientes ?> novo(s)
                    </span>

                <?php endif; ?>

                <i class="bi bi-people"></i>

                <h3>
                    <?= (int) $totalCustomers ?>
                </h3>

                <p>Clientes</p>

            </div>

        </div>

        <!-- RECEITA TOTAL -->
        <div class="col-md-4 col-lg-3">

            <div class="card-dashboard">

                <i class="bi bi-cash-stack"></i>

                <h3>
                    <?= number_format(
                        (float) $totalRevenue,
                        2,
                        ',',
                        '.'
                    ) ?> €
                </h3>

                <p>Receita Total</p>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>