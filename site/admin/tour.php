<?php

$tituloPagina = 'Tour';

require_once __DIR__ . '/includes/auth.php';

$tour_dates = $pdo->query("
    SELECT *
    FROM tour_dates
    ORDER BY tour_date ASC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                <i class="bi bi-music-note"></i>
                Tour
            </h2>

            <p class="text-muted mb-0">
                Gerir as datas e locais da tour da banda.
            </p>
        </div>

    </div>


    <!-- Adicionar data -->

    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <strong>
                <i class="bi bi-plus-lg"></i>
                Adicionar data da tour
            </strong>
        </div>

        <div class="card-body">

            <form action="add_tour.php" method="POST">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label for="tour_date" class="form-label">
                            Data
                        </label>

                        <input
                            type="date"
                            name="tour_date"
                            id="tour_date"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label for="city" class="form-label">
                            Cidade
                        </label>

                        <input
                            type="text"
                            name="city"
                            id="city"
                            class="form-control"
                            placeholder="Ex: Lisboa"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label for="venue" class="form-label">
                            Local
                        </label>

                        <input
                            type="text"
                            name="venue"
                            id="venue"
                            class="form-control"
                            placeholder="Ex: Altice Arena"
                            required
                        >

                    </div>

                </div>


                <div class="mt-3">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Adicionar
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- Datas da tour -->

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Cidade</th>
                            <th>Local</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($tour_dates)): ?>

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Não existem datas de tour.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($tour_dates as $tour): ?>

                            <tr>

                                <td>
                                    #<?= (int) $tour['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($tour['tour_date']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($tour['city']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($tour['venue']) ?>
                                </td>

                                <td>

                                    <a
                                        href="edit_tour.php?id=<?= (int) $tour['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Editar
                                    </a>

                                    <a
                                        href="delete_tour.php?id=<?= (int) $tour['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem a certeza que pretende eliminar esta data da tour?');"
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