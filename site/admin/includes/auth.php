<?php

session_start();

require_once __DIR__ . '/../../../conexao/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../login_admin.php');
    exit;
}