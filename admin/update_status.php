<?php
require_once '../config/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id']) && isset($input['status'])) {
    $id = (int)$input['id'];
    $status = $conn->real_escape_string($input['status']);

    $sql = "UPDATE contactos_recibidos SET status = '$status' WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>