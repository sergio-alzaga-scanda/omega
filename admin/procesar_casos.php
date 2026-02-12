<?php
session_start();
require_once '../config/db.php';
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? 'nuevo';
    $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';

    if ($titulo === '') {
        header("Location: gestion_casos.php?status=error&msg=faltatitulo");
        exit();
    }

    $titulo_esc  = $conn->real_escape_string($titulo);
    $desc_c      = $conn->real_escape_string($_POST['descripcion_corta'] ?? '');
    $desc_l      = $conn->real_escape_string($_POST['descripcion_larga'] ?? '');
    $n_cliente   = $conn->real_escape_string($_POST['nombre_cliente'] ?? '');
    $cargo       = $conn->real_escape_string($_POST['cargo_cliente'] ?? '');
    $comentario  = $conn->real_escape_string($_POST['comentario_cliente'] ?? '');

    $folder = "assets/casos/";
    $upload_dir = "../" . $folder;
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }

    // 1. PROCESAR PORTADA
    $img_sql = "";
    if (!empty($_FILES['imagen']['name'])) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_p = "portada_" . uniqid() . "." . $ext;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $nombre_p)) {
            $ruta_p = $folder . $nombre_p;
            // IMPORTANTE: Se usa 'imagen_url' para coincidir con tu DB
            $img_sql = ", imagen_url = '$ruta_p'";
        }
    }

    if ($accion === 'editar' && $id > 0) {
        $sql = "UPDATE casos_exito SET 
                titulo='$titulo_esc', 
                descripcion_corta='$desc_c', 
                descripcion_larga='$desc_l', 
                nombre_cliente='$n_cliente',
                cargo_cliente='$cargo', 
                comentario_cliente='$comentario' 
                $img_sql 
                WHERE id=$id";
        $conn->query($sql);
        $caso_id = $id;
    } else {
        $ruta_p = isset($ruta_p) ? $ruta_p : '';
        $sql = "INSERT INTO casos_exito (titulo, descripcion_corta, descripcion_larga, imagen_url, nombre_cliente, cargo_cliente, comentario_cliente) 
                VALUES ('$titulo_esc', '$desc_c', '$desc_l', '$ruta_p', '$n_cliente', '$cargo', '$comentario')";
        if ($conn->query($sql)) {
            $caso_id = $conn->insert_id;
        }
    }

    // 2. PROCESAR GALERÍA MÚLTIPLE
    if (isset($caso_id) && $caso_id > 0 && !empty($_FILES['galeria']['name'][0])) {
        foreach ($_FILES['galeria']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['galeria']['error'][$key] == 0) {
                $ext_g = pathinfo($_FILES['galeria']['name'][$key], PATHINFO_EXTENSION);
                $nombre_g = "gal_" . $caso_id . "_" . uniqid() . "." . $ext_g;
                if (move_uploaded_file($tmp_name, $upload_dir . $nombre_g)) {
                    $ruta_g = $folder . $nombre_g;
                    $conn->query("INSERT INTO caso_galeria (caso_id, ruta_imagen) VALUES ($caso_id, '$ruta_g')");
                }
            }
        }
    }

    header("Location: gestion_casos.php?status=success");
    exit();
}
ob_end_flush();