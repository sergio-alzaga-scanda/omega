<?php
require_once '../config/db.php';
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $id = $data['id'];
    $status = $data['status'];
    $sql = "UPDATE contactos_recibidos SET status = '$status' WHERE id = $id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    }
}
?>