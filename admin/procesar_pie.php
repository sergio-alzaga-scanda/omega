<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = true;

    // Recorremos todos los campos enviados para actualizar la tabla configuracion
    foreach ($_POST as $clave => $valor) {
        $clave_esc = $conn->real_escape_string($clave);
        $valor_esc = $conn->real_escape_string($valor);
        
        // Actualizamos basándonos en la clave y asegurando que pertenezca a la sección 'contacto'
        $sql = "UPDATE configuracion SET valor = '$valor_esc' WHERE clave = '$clave_esc' AND seccion = 'contacto'";
        
        if (!$conn->query($sql)) {
            $success = false;
        }
    }

    if ($success) {
        header("Location: gestion_pie.php?status=success");
    } else {
        header("Location: gestion_pie.php?status=error");
    }
    exit();
}