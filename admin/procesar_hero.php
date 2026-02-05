<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapar datos para seguridad
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $btn_text = $conn->real_escape_string($_POST['texto_boton']);
    $bg_color = $_POST['color_fondo'];
    $main_color = $_POST['color_primario'];

    $logo_sql = "";
    // Procesar logo si se subió uno nuevo
    if (!empty($_FILES['logo']['name'])) {
        $ruta = "assets/logo_" . time() . "_" . $_FILES['logo']['name'];
        if(move_uploaded_file($_FILES['logo']['tmp_name'], "../" . $ruta)) {
            $logo_sql = ", logo_url = '$ruta'";
        }
    }

    // Actualizar registro único
    $sql = "UPDATE hero_config SET 
            titulo = '$titulo', 
            texto_boton = '$btn_text', 
            color_fondo = '$bg_color', 
            color_primario = '$main_color' 
            $logo_sql
            WHERE id = 1";

    if ($conn->query($sql)) {
        // Redirigir con parámetro de éxito
        header("Location: gestion_hero.php?status=success");
    } else {
        // Redirigir con parámetro de error
        header("Location: gestion_hero.php?status=error");
    }
    exit();
}