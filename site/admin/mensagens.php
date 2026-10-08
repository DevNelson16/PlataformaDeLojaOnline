<?php

$tituloPagina = 'Mensagens';

require_once __DIR__ . '/includes/auth.php';


/*
|--------------------------------------------------------------------------
| Marcar mensagem como lida
|--------------------------------------------------------------------------
*/

if (isset($_GET['mark_read'])) {

    $id = (int) $_GET['mark_read'];

    if ($id > 0) {

        $stmt = $pdo->prepare("
            UPDATE contact_messages
            SET status = 'lida'
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }

    header('Location: mensagens.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Eliminar mensagem
|--------------------------------------------------------------------------
*/

if (isset($_GET['delete_msg'])) {

    $id = (int) $_GET['delete_msg'];

    if ($id > 0) {

        $stmt = $pdo->prepare("
            DELETE FROM contact_messages
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }

    header('Location: mensagens.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Buscar mensagens
|--------------------------------------------------------------------------
*/

$messages = $pdo->query("
    SELECT *
    FROM contact_messages
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">

    <div class="mb-4">

        <h2 class="section-title mb-1">
            <i class="bi bi-envelope"></i>
            Mensagens
        </h2>

        <p class="text-muted mb-0">
            Consultar as mensagens enviadas através do site.
        </p>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Mensagem</th>
                            <th>Estado</th>
                            <th>Data</th>
                            <th>Ações</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (empty($messages)): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-4"
                            >
                                Não existem mensagens.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($messages as $message): ?>

                            <tr>

                                <td>
                                    #<?= (int) $message['id'] ?>
                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $message['nome'] ?? '—'
                                    ) ?>

                                    <?= htmlspecialchars(
                                        $message['apelido'] ?? ''
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $message['email'] ?? '—'
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $message['mensagem'] ?? '—'
                                    ) ?>

                                </td>


                                <td>

                                    <?php if (
                                        isset($message['status']) &&
                                        $message['status'] === 'lida'
                                    ): ?>

                                        <span class="badge bg-success">
                                            Lida
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-warning text-dark">
                                            Não lida
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $message['created_at'] ?? '—'
                                    ) ?>

                                </td>


                                <td>

                                    <?php if (
                                        isset($message['status']) &&
                                        $message['status'] !== 'lida'
                                    ): ?>

                                        <a
                                            href="mensagens.php?mark_read=<?= (int) $message['id'] ?>"
                                            class="btn btn-sm btn-success mb-1"
                                        >

                                            <i class="bi bi-check2"></i>

                                            Marcar lida

                                        </a>

                                    <?php endif; ?>


                                    <a
                                        href="mensagens.php?delete_msg=<?= (int) $message['id'] ?>"
                                        class="btn btn-sm btn-danger mb-1"
                                        onclick="return confirm('Tem a certeza que pretende eliminar esta mensagem?');"
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