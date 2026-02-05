<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'actualizar_textos') {
        $tit = $conn->real_escape_string($_POST['titulo']);
        $sub = $conn->real_escape_string($_POST['subtitulo']);
        $conn->query("UPDATE configuracion SET valor = '$tit' WHERE clave = 'titulo' AND seccion = 'nosotros'");
        $conn->query("UPDATE configuracion SET valor = '$sub' WHERE clave = 'subtitulo' AND seccion = 'nosotros'");
    } 

    elseif ($accion === 'actualizar_contadores') {
        foreach ($_POST['numero'] as $id => $num) {
            $etiq = $conn->real_escape_string($_POST['etiqueta'][$id]);
            $suf = $conn->real_escape_string($_POST['sufijo'][$id]);
            $conn->query("UPDATE contadores SET numero = '$num', etiqueta = '$etiq', sufijo = '$suf' WHERE id = '$id'");
        }
    }

    elseif ($accion === 'actualizar_puntos') {
        foreach ($_POST['titulo'] as $id => $tit) {
            $tit_esc = $conn->real_escape_string($tit);
            $desc_esc = $conn->real_escape_string($_POST['descripcion'][$id]);
            $conn->query("UPDATE porque_primicia_puntos SET titulo = '$tit_esc', descripcion = '$desc_esc' WHERE id = '$id'");
        }
    }

    elseif ($accion === 'nuevo_contador') {
        $etiq = $conn->real_escape_string($_POST['etiqueta']);
        $num = (int)$_POST['numero'];
        $suf = $conn->real_escape_string($_POST['sufijo']);
        $conn->query("INSERT INTO contadores (numero, sufijo, etiqueta) VALUES ('$num', '$suf', '$etiq')");
    }

    elseif ($accion === 'nuevo_punto') {
        $tit = $conn->real_escape_string($_POST['titulo']);
        $desc = $conn->real_escape_string($_POST['descripcion']);
        $conn->query("INSERT INTO porque_primicia_puntos (titulo, descripcion) VALUES ('$tit', '$desc')");
    }

    header("Location: gestion_nosotros.php?status=success");
    exit();
}