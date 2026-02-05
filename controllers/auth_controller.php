<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Campos incompletos.']);
        exit;
    }

    // Consulta simple para buscar al usuario
    $stmt = $conn->prepare("SELECT id, nombre, password_hash FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // EVALUACIÓN EN TEXTO PLANO
        // Comparamos directamente la variable $password con el valor de la base de datos
        if ($password === $user['password_hash']) {
            
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['nombre'];

            // Actualizar registro de acceso
            $conn->query("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = " . $user['id']);

            echo json_encode(['success' => true, 'message' => 'Acceso concedido.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'El usuario no existe.']);
    }
    $stmt->close();
}
?>