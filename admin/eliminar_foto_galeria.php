<?php
require_once '../config/db.php';
$id = (int)$_GET['id'];
$res = $conn->query("SELECT ruta_imagen FROM caso_galeria WHERE id = $id");
if($f = $res->fetch_assoc()) {
    $ruta = "../" . $f['ruta_imagen'];
    if(file_exists($ruta)) unlink($ruta);
    $conn->query("DELETE FROM caso_galeria WHERE id = $id");
    echo json_encode(['success' => true]);
}