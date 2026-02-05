<?php
session_start();
require_once '../config/db.php';

// Verificamos sesión e ID
if (!isset($_SESSION['admin_id'])) { exit("Acceso denegado"); }

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // OPCIONAL: Borrar archivos físicos de la carpeta assets para no llenar el disco
    $res = $conn->query("SELECT imagen_url FROM casos_exito WHERE id = $id");
    if($reg = $res->fetch_assoc()){
        if(file_exists("../".$reg['imagen_url'])) { unlink("../".$reg['imagen_url']); }
    }

    // Ejecutar eliminación (La galería se borra sola por el ON DELETE CASCADE si lo configuraste)
    $sql = "DELETE FROM casos_exito WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: gestion_casos.php?status=success");
    } else {
        header("Location: gestion_casos.php?status=error");
    }
} else {
    header("Location: gestion_casos.php");
}
exit();