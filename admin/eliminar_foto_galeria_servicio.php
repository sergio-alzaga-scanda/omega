<?php
require_once '../config/db.php';
$id = (int)$_GET['id'];
$res = $conn->query("SELECT ruta_imagen FROM servicio_galeria WHERE id = $id");
if($f = $res->fetch_assoc()){
    if(file_exists("../".$f['ruta_imagen'])) unlink("../".$f['ruta_imagen']);
}
$del = $conn->query("DELETE FROM servicio_galeria WHERE id = $id");
echo json_encode(['success' => $del]);