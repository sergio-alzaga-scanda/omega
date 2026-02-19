<?php
session_start();
require_once '../config/db.php';
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificación de integridad del POST por archivos pesados
    if (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
        header("Location: gestion_blog.php?status=error&msg=archivo_muy_grande");
        exit();
    }

    $accion    = $_POST['accion'] ?? 'nuevo';
    $id        = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $titulo    = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $video_url = $conn->real_escape_string($_POST['video_url'] ?? '');

    if ($titulo === '') {
        header("Location: gestion_blog.php?status=error&msg=faltatitulo");
        exit();
    }

    $titulo_esc  = $conn->real_escape_string($titulo);
    $categoria   = $conn->real_escape_string($_POST['categoria'] ?? '');
    $resumen     = $conn->real_escape_string($_POST['resumen'] ?? '');
    $cont_html   = $conn->real_escape_string($_POST['contenido_html'] ?? '');
    $fecha       = $conn->real_escape_string($_POST['fecha'] ?? date('Y-m-d'));

    $folder = "assets/blog/";
    $upload_dir = "../" . $folder;
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }

    // 1. PROCESAR PORTADA
    $img_sql = "";
    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] == 0) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_p = "blog_portada_" . uniqid() . "." . $ext;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_dir . $nombre_p)) {
            $ruta_p = $folder . $nombre_p;
            $img_sql = ", imagen_portada = '$ruta_p'";
        }
    }

    if ($accion === 'editar' && $id > 0) {
        $sql = "UPDATE blog SET 
                titulo='$titulo_esc', 
                categoria='$categoria', 
                resumen='$resumen', 
                contenido_html='$cont_html',
                video_url='$video_url',
                fecha='$fecha'
                $img_sql 
                WHERE id=$id";
        $conn->query($sql);
        $blog_id = $id;
    } else {
        $ruta_p = isset($ruta_p) ? $ruta_p : '';
        $sql = "INSERT INTO blog (titulo, categoria, resumen, contenido_html, video_url, imagen_portada, fecha) 
                VALUES ('$titulo_esc', '$categoria', '$resumen', '$cont_html', '$video_url', '$ruta_p', '$fecha')";
        if ($conn->query($sql)) {
            $blog_id = $conn->insert_id;
        }
    }

    // 2. PROCESAR GALERÍA MÚLTIPLE
    if (isset($blog_id) && $blog_id > 0 && !empty($_FILES['galeria']['name'][0])) {
        foreach ($_FILES['galeria']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['galeria']['error'][$key] == 0) {
                $ext_g = pathinfo($_FILES['galeria']['name'][$key], PATHINFO_EXTENSION);
                $nombre_g = "blog_gal_" . $blog_id . "_" . uniqid() . "." . $ext_g;
                if (move_uploaded_file($tmp_name, $upload_dir . $nombre_g)) {
                    $ruta_g = $folder . $nombre_g;
                    $conn->query("INSERT INTO blog_galeria (blog_id, ruta_imagen) VALUES ($blog_id, '$ruta_g')");
                }
            }
        }
    }

    header("Location: gestion_blog.php?status=success");
    exit();
}
ob_end_flush();
?>