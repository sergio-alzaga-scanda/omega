<?php
session_start();
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM clientes WHERE id = $id");
}
header("Location: gestion_clientes.php?status=success");