<?php

session_start();

require_once __DIR__ . '/../../conexao/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login_admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $venue = trim($_POST['venue'] ?? '');
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $capacity = (int) ($_POST['capacity'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);

    if (
        empty($title) ||
        empty($city) ||
        empty($venue) ||
        empty($date) ||
        empty($time) ||
        $capacity <= 0
    ) {
        die('Preencha todos os campos obrigatórios.');
    }

    // Junta a data e a hora
    $event_date = $date . ' ' . $time . ':00';

    // Upload da imagem
    $imageName = null;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {

        $uploadDir = __DIR__ . '/../img/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = strtolower(
            pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)
        );

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowedExtensions)) {
            die('Formato de imagem não permitido.');
        }

        $imageName = uniqid('evento_', true) . '.' . $extension;

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadDir . $imageName
        );
    }

    try {

        /*
        |--------------------------------------------------------------------------
        | 1. Criar o evento
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            INSERT INTO events
            (
                title,
                description,
                city,
                venue,
                event_date,
                price,
                image
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $title,
            $description,
            $city,
            $venue,
            $event_date,
            $price,
            $imageName
        ]);

        $event_id = $pdo->lastInsertId();


        /*
        |--------------------------------------------------------------------------
        | 2. Criar os bilhetes
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= $capacity; $i++) {

            /*
             * O número do bilhete inclui o ID do evento.
             *
             * Exemplo:
             * EV1-GA-001
             * EV1-GA-002
             * EV2-GA-001
             * EV2-GA-002
             *
             * Assim nunca teremos dois ticket_number iguais.
             */

            $ticket_number =
                "EV" .
                $event_id .
                "-GA-" .
                str_pad($i, 3, "0", STR_PAD_LEFT);

            $stmt = $pdo->prepare("
                INSERT INTO tickets
                (
                    event_id,
                    ticket_number
                )
                VALUES (?, ?)
            ");

            $stmt->execute([
                $event_id,
                $ticket_number
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Voltar para a página de eventos
        |--------------------------------------------------------------------------
        */

        header('Location: eventos.php');
        exit;

    } catch (PDOException $e) {

        die(
            'Erro ao criar o evento: ' .
            htmlspecialchars($e->getMessage())
        );
    }
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Adicionar Evento</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Adicionar Evento
        </h2>

        <a
            href="eventos.php"
            class="btn btn-secondary"
        >
            Voltar
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <form
                action="add_event.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <div class="mb-3">

                    <label class="form-label">
                        Nome do evento
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Descrição
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                    ></textarea>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Cidade
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Local
                        </label>

                        <input
                            type="text"
                            name="venue"
                            class="form-control"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Data
                        </label>

                        <input
                            type="date"
                            name="date"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Hora
                        </label>

                        <input
                            type="time"
                            name="time"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Capacidade
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            class="form-control"
                            min="1"
                            required
                        >

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Preço do bilhete (€)
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="0"
                        required
                    >

                </div>


                <div class="mb-4">

                    <label class="form-label">
                        Imagem do evento
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Criar Evento
                </button>

                <a
                    href="eventos.php"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

</body>

</html>