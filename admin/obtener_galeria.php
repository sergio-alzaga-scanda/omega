<?php
require_once '../config/db.php';
$id = (int)$_GET['id'];
$res = $conn->query("SELECT id, ruta_imagen FROM caso_galeria WHERE caso_id = $id");
$imgs = [];
while($row = $res->fetch_assoc()) { $imgs[] = $row; }
header('Content-Type: application/json');
echo json_encode($imgs);