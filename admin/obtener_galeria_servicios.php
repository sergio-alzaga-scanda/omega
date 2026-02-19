<?php
require_once '../config/db.php';
$id = (int)$_GET['id'];
$res = $conn->query("SELECT id, ruta_imagen FROM servicio_galeria WHERE servicio_id = $id");
$imgs = [];
while($r = $res->fetch_assoc()){ $imgs[] = $r; }
echo json_encode($imgs);