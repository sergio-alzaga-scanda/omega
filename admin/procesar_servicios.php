<?php
session_start();
require_once '../config/db.php';

// --- BLOQUE DE ELIMINACIÓN (GET) ---
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    // 1. Obtener la ruta de la imagen antes de borrar el registro
    $res = $conn->query("SELECT imagen_url FROM servicios WHERE id = $id");
    if($reg = $res->fetch_assoc()){
        $ruta_fisica = "../" . $reg['imagen_url'];
        // 2. Borrar archivo del servidor si existe y no es el default
        if($reg['imagen_url'] != 'assets/servicios/default.jpg' && file_exists($ruta_fisica)) {
            unlink($ruta_fisica);
        }
    }
    
    // 3. Borrar de la base de datos
    $sql_del = "DELETE FROM servicios WHERE id = $id";
    if ($conn->query($sql_del)) {
        header("Location: gestion_servicios.php?status=success");
    } else {
        header("Location: gestion_servicios.php?status=error");
    }
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $desc = $conn->real_escape_string($_POST['descripcion']);
    $tags = $conn->real_escape_string($_POST['subtitulos_rojos']);
    
    $img_sql = "";

    // 1. Validar si se subió una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $directorio = "../assets/servicios/";
        
        // Crear carpeta si no existe
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = "ser_" . time() . "." . $extension;
        $ruta_final = $directorio . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_final)) {
            // Guardamos la ruta relativa para la base de datos
            $path_db = "assets/servicios/" . $nombre_archivo;
            $img_sql = ", imagen_url = '$path_db'";
        }
    }

    if ($accion === 'editar') {
        $id = (int)$_POST['id'];
        // Si no hay imagen nueva, $img_sql estará vacío y no sobreescribirá la actual
        $sql = "UPDATE servicios SET titulo = '$titulo', descripcion = '$desc', subtitulos_rojos = '$tags' $img_sql WHERE id = $id";
    } else {
        // Para nuevos registros, si no subió foto, ponemos una por defecto
        $final_path = !empty($img_sql) ? str_replace(", imagen_url = ", "", $img_sql) : "'assets/servicios/default.jpg'";
        $sql = "INSERT INTO servicios (titulo, descripcion, imagen_url, subtitulos_rojos) 
                VALUES ('$titulo', '$desc', $final_path, '$tags')";
    }

    if ($conn->query($sql)) {
        header("Location: gestion_servicios.php?status=success");
    } else {
        // Depuración técnica en caso de error
        echo "Error en SQL: " . $conn->error;
    }
    exit();
}