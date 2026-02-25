<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitización de datos
    $nombre   = $conn->real_escape_string($_POST['nombre']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $servicio = $conn->real_escape_string($_POST['servicio']);
    $correo   = $conn->real_escape_string($_POST['correo']);
    $mensaje  = $conn->real_escape_string($_POST['mensaje']);

    $query = "INSERT INTO contactos_recibidos (nombre, telefono, servicio, correo, mensaje) 
              VALUES ('$nombre', '$telefono', '$servicio', '$correo', '$mensaje')";

    if ($conn->query($query)) {
        // Redirección exitosa con el parámetro status=success
        header("Location: ../index.php?status=success#contacto");
        exit();
    } else {
        // Redirección con error
        header("Location: ../index.php?status=error#contacto");
        exit();
    }
}
?>