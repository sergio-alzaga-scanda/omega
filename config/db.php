<?php
// config/bd.php

$servername = "localhost";
$port = 3307;          // Tu puerto MySQL
$username = "root";    // Tu usuario
$password = "";        // Tu contraseña
$database = "primicia";    // Tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// FORZAR UTF8MB4
$conn->set_charset("utf8mb4");
$conn->query("SET NAMES 'utf8mb4'");
$conn->query("SET CHARACTER SET utf8mb4");
$conn->query("SET SESSION collation_connection = 'utf8mb4_unicode_ci'");
?>