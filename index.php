<?php 
require_once 'config/db.php'; 
require_once 'models/DataModel.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRIMACIA | Home</title>
    <style>body { margin: 0; font-family: sans-serif; }</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
    <?php include 'views/sections/header.php'; ?>
    <?php include 'views/sections/que_es.php'; ?>
    
    <?php include 'views/sections/servicios.php'; ?>
    <?php include 'views/sections/casos_exito.php'; ?>
    <?php include 'views/sections/clientes.php'; ?>
    <?php include 'views/sections/contacto.php'; ?>
    <?php include 'views/sections/pie.php'; ?>

    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>