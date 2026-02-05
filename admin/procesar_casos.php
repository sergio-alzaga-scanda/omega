<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $desc_c = $conn->real_escape_string($_POST['descripcion_corta']);
    $desc_l = $conn->real_escape_string($_POST['descripcion_larga']);
    $n_cliente = $conn->real_escape_string($_POST['nombre_cliente']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // 1. Procesar Imagen Principal (Portada)
    $img_principal_sql = "";
    if (!empty($_FILES['imagen']['name'])) {
        $nombre_p = time() . "_portada_" . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../assets/" . $nombre_p);
        $ruta_p = "assets/" . $nombre_p;
        $img_principal_sql = ", imagen_url = '$ruta_p'";
    }

    if ($accion === 'editar') {
        $sql = "UPDATE casos_exito SET titulo='$titulo', descripcion_corta='$desc_c', descripcion_larga='$desc_l', nombre_cliente='$n_cliente' $img_principal_sql WHERE id=$id";
        $conn->query($sql);
        $caso_id = $id;
    } else {
        $ruta_p = isset($ruta_p) ? $ruta_p : '';
        $sql = "INSERT INTO casos_exito (titulo, descripcion_corta, descripcion_larga, imagen_url, nombre_cliente) VALUES ('$titulo', '$desc_c', '$desc_l', '$ruta_p', '$n_cliente')";
        $conn->query($sql);
        $caso_id = $conn->insert_id;
    }

    // 2. PROCESAR GALERÍA (Múltiples imágenes)
    if (!empty($_FILES['galeria']['name'][0])) {
        foreach ($_FILES['galeria']['tmp_name'] as $key => $tmp_name) {
            $nombre_g = time() . "_gal_" . $_FILES['galeria']['name'][$key];
            if (move_uploaded_file($tmp_name, "../assets/" . $nombre_g)) {
                $ruta_g = "assets/" . $nombre_g;
                $conn->query("INSERT INTO caso_galeria (caso_id, ruta_imagen) VALUES ($caso_id, '$ruta_g')");
            }
        }
    }

    header("Location: gestion_casos.php?status=success");
}