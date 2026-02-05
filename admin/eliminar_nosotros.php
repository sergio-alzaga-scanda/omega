<?php
session_start();
require_once '../config/db.php';

// Verificación de seguridad
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Validamos que existan los parámetros necesarios
if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = (int)$_GET['id'];
    $tipo = $_GET['tipo']; // 'contadores' o 'puntos'

    if ($tipo === 'contadores') {
        // Eliminamos de la tabla contadores
        $sql = "DELETE FROM contadores WHERE id = $id";
    } elseif ($tipo === 'puntos') {
        // Eliminamos de la tabla porque_primicia_puntos
        $sql = "DELETE FROM porque_primicia_puntos WHERE id = $id";
    }

    if ($conn->query($sql)) {
        // Éxito: Regresamos con el parámetro para el SweetAlert
        header("Location: gestion_nosotros.php?status=success");
    } else {
        // Error: Regresamos con señal de fallo
        header("Location: gestion_nosotros.php?status=error");
    }
} else {
    // Si se accede sin parámetros, volvemos a la gestión
    header("Location: gestion_nosotros.php");
}
exit();