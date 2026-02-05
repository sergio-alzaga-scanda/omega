<?php 
require_once 'config/db.php'; 
require_once 'models/DataModel.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRIMACIA | Blog</title>
    <style>body { margin: 0; font-family: sans-serif; }</style>
</head>
<body>
    <?php include 'views/sections/menu.php'; ?>
    <br><br>
    <?php include 'views/sections/blog.php'; ?>
    <?php include 'views/sections/pie.php'; ?>

    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>