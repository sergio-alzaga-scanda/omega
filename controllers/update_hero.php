<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $color_fondo = $_POST['color_fondo'];
    $color_primario = $_POST['color_primario'];
    $titulo = $_POST['titulo'];
    $texto_boton = $_POST['texto_boton'];

    $sql = "UPDATE hero_config SET 
            color_fondo = ?, 
            color_primario = ?, 
            titulo = ?, 
            texto_boton = ? 
            WHERE id = 1";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$color_fondo, $color_primario, $titulo, $texto_boton]);

    header("Location: ../admin.php?status=success");
}
?>