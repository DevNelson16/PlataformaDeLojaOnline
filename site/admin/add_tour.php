<?php

session_start();

require_once __DIR__ . '/../../conexao/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login_admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tour_date = $_POST['tour_date'] ?? '';
    $city = trim($_POST['city'] ?? '');
    $venue = trim($_POST['venue'] ?? '');

    if (empty($tour_date) || empty($city) || empty($venue)) {
        die('Preencha todos os campos.');
    }

    try {

        $stmt = $pdo->prepare("
            INSERT INTO tour_dates
            (
                tour_date,
                city,
                venue
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $tour_date,
            $city,
            $venue
        ]);

        header('Location: tour.php');
        exit;

    } catch (PDOException $e) {

        die(
            'Erro ao adicionar data da tour: ' .
            htmlspecialchars($e->getMessage())
        );
    }
}

header('Location: tour.php');
exit;