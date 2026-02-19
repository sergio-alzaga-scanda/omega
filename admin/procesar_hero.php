<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $btn_text = $conn->real_escape_string($_POST['texto_boton']);
    $bg_color = $_POST['color_fondo'];
    $main_color = $_POST['color_primario'];

    $extra_sql = "";

    // Logo Principal
    if (!empty($_FILES['logo']['name'])) {
        $ruta = "assets/logo_" . time() . "_" . $_FILES['logo']['name'];
        if(move_uploaded_file($_FILES['logo']['tmp_name'], "../" . $ruta)) {
            $extra_sql .= ", logo_url = '$ruta'";
        }
    }

    // Logo Pie (Corregido: ahora usa logo_pie)
    if (!empty($_FILES['logo_pie']['name'])) {
        $ruta_pie = "assets/logo_pie_" . time() . "_" . $_FILES['logo_pie']['name'];
        if(move_uploaded_file($_FILES['logo_pie']['tmp_name'], "../" . $ruta_pie)) {
            $extra_sql .= ", logo_pie_url = '$ruta_pie'"; // Asegúrate que esta columna exista o ajusta el nombre
        }
    }

    // Video Recuadro
    if (!empty($_FILES['video']['name'])) {
        $ruta_video = "assets/vid_" . time() . "_" . $_FILES['video']['name'];
        if(move_uploaded_file($_FILES['video']['tmp_name'], "../" . $ruta_video)) {
            $extra_sql .= ", video_url = '$ruta_video'";
        }
    }

    $sql = "UPDATE hero_config SET 
            titulo = '$titulo', 
            texto_boton = '$btn_text', 
            color_fondo = '$bg_color', 
            color_primario = '$main_color' 
            $extra_sql
            WHERE id = 1";

    if ($conn->query($sql)) {
        header("Location: gestion_hero.php?status=success");
    } else {
        header("Location: gestion_hero.php?status=error");
    }
    exit();
}