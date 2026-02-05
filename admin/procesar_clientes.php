<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $orden = (int)$_POST['orden'];
    
    $logo_sql = "";
    
    // Verificamos si se subió un archivo y si no tiene errores
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        
        $nombre_archivo = "logo_" . time() . "_" . basename($_FILES['logo']['name']);
        
        // Ruta física en el servidor para mover el archivo
        $dir_destino = "../assets/logos/";
        
        // Si la carpeta no existe, la creamos con permisos
        if (!is_dir($dir_destino)) {
            mkdir($dir_destino, 0777, true);
        }

        $ruta_final = $dir_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta_final)) {
            // Ruta que se guardará en la BD (relativa al index.php público)
            $path_db = "assets/logos/" . $nombre_archivo;
            $logo_sql = ", logo_url = '$path_db'";
        } else {
            // Error al mover el archivo
            header("Location: gestion_clientes.php?status=error&msg=upload_failed");
            exit();
        }
    }

    if ($accion === 'editar') {
        $id = (int)$_POST['id'];
        $sql = "UPDATE clientes SET nombre = '$nombre', orden = $orden $logo_sql WHERE id = $id";
    } else {
        $path_db = isset($path_db) ? $path_db : 'assets/logos/default.png';
        $sql = "INSERT INTO clientes (nombre, logo_url, orden) VALUES ('$nombre', '$path_db', $orden)";
    }

    if ($conn->query($sql)) {
        header("Location: gestion_clientes.php?status=success");
    } else {
        header("Location: gestion_clientes.php?status=error&msg=db_failed");
    }
}