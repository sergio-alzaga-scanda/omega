<?php
// models/DataModel.php

function getHeroConfig($conn) {
    $sql = "SELECT * FROM hero_config LIMIT 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getSeccion($conn, $seccion) {
    $sql = "SELECT clave, valor FROM configuracion WHERE seccion = '$seccion'";
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['clave']] = $row['valor'];
    }
    return $data;
}

function getItems($conn, $tabla) {
    $sql = "SELECT * FROM $tabla ORDER BY id DESC";
    return $conn->query($sql);
}
?>