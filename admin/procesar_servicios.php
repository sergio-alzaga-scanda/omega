<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { exit("Acceso denegado"); }

$folder = "assets/servicios/";
$upload_dir = "../" . $folder;

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// --- ELIMINAR SERVICIO COMPLETO ---
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    // Borrar portada
    $res = $conn->query("SELECT imagen_url FROM servicios WHERE id = $id");
    if ($s = $res->fetch_assoc()) {
        if (!empty($s['imagen_url']) && file_exists("../" . $s['imagen_url'])) unlink("../" . $s['imagen_url']);
    }

    // Borrar galería
    $gal = $conn->query("SELECT ruta_imagen FROM servicio_galeria WHERE servicio_id = $id");
    while($img = $gal->fetch_assoc()){
        if(file_exists("../".$img['ruta_imagen'])) unlink("../".$img['ruta_imagen']);
    }

    $conn->query("DELETE FROM servicios WHERE id = $id");
    header("Location: gestion_servicios.php?status=success");
    exit();
}

// --- GUARDAR O EDITAR ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $accion = $_POST['accion'];
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $descripcion_larga = $conn->real_escape_string($_POST['descripcion_larga']);
    $tags = $conn->real_escape_string($_POST['subtitulos_rojos']);

    if ($accion == 'nuevo') {
        $img_path = "";
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombre_img = "serv_" . uniqid() . "." . $ext;
            $img_path = $folder . $nombre_img;
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../" . $img_path);
        }
        $conn->query("INSERT INTO servicios (titulo, descripcion, descripcion_larga, subtitulos_rojos, imagen_url) 
                      VALUES ('$titulo', '$descripcion', '$descripcion_larga', '$tags', '$img_path')");
        $servicio_id = $conn->insert_id;
    } else {
        $servicio_id = $id;
        $img_sql = "";
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombre_img = "serv_" . uniqid() . "." . $ext;
            $img_path = $folder . $nombre_img;
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../" . $img_path);
            $img_sql = ", imagen_url='$img_path'";
        }
        $conn->query("UPDATE servicios SET titulo='$titulo', descripcion='$descripcion', 
                      descripcion_larga='$descripcion_larga', subtitulos_rojos='$tags' $img_sql WHERE id=$id");
    }

    // --- PROCESAR GALERÍA NUEVA ---
    if (isset($_FILES['galeria']) && !empty($_FILES['galeria']['name'][0])) {
        foreach ($_FILES['galeria']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['galeria']['error'][$key] == 0) {
                $ext = pathinfo($_FILES['galeria']['name'][$key], PATHINFO_EXTENSION);
                $nombre_gal = "gal_ser_" . uniqid() . "." . $ext;
                $ruta_gal = $folder . $nombre_gal;
                if (move_uploaded_file($tmp_name, "../" . $ruta_gal)) {
                    $conn->query("INSERT INTO servicio_galeria (servicio_id, ruta_imagen) VALUES ($servicio_id, '$ruta_gal')");
                }
            }
        }
    }

    header("Location: gestion_servicios.php?status=success");
    exit();
}