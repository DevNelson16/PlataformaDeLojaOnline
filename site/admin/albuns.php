<?php

$tituloPagina = 'Álbuns';

require_once __DIR__ . '/includes/auth.php';

$albums = $pdo->query("
    SELECT *
    FROM albums
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                <i class="bi bi-disc"></i>
                Álbuns
            </h2>

            <p class="text-muted mb-0">
                Gerir os álbuns da banda.
            </p>
        </div>

        <a href="add_album.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Adicionar álbum
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Capa</th>
                            <th>Título</th>
                            <th>Data de criação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($albums)): ?>

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Não existem álbuns.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($albums as $album): ?>

                            <tr>

                                <td>
                                    #<?= (int) $album['id'] ?>
                                </td>

                                <td>

                                    <?php if (!empty($album['image'])): ?>

                                        <img
                                            src="../img/<?= htmlspecialchars($album['image']) ?>"
                                            alt="<?= htmlspecialchars($album['title']) ?>"
                                            width="70"
                                            height="70"
                                            class="rounded"
                                            style="object-fit: cover;"
                                        >

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Sem imagem
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($album['title']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $album['created_at'] ?? '—'
                                    ) ?>
                                </td>

                                <td>

                                    <a
                                        href="edit_album.php?id=<?= (int) $album['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Editar
                                    </a>

                                    <a
                                        href="delete_album.php?id=<?= (int) $album['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem a certeza que pretende eliminar este álbum?');"
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