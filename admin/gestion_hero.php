<?php
session_start();
require_once '../config/db.php';

// Protección de sesión
if (!isset($_SESSION['admin_id'])) { 
    header("Location: ../index.php"); 
    exit(); 
}

// Consultar configuración actual del Hero
$res = $conn->query("SELECT * FROM hero_config WHERE id = 1");
$h = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Hero | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: <?php echo $h['color_primario']; ?>; }
        body { background-color: #f8f9fa; }
        
        .preview-box { 
            background: <?php echo $h['color_fondo']; ?>; 
            color: white; 
            padding: 60px 40px; 
            border-radius: 15px; 
            text-align: center;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .btn-preview { 
            background-color: <?php echo $h['color_primario']; ?>; 
            color: white; 
            border: none; 
            padding: 12px 30px; 
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Ajuste para que el contenido no quede oculto tras el menú lateral */
        .main-content {
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>

        <main class="col-md-10 ms-sm-auto main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Configuración del Hero (Header)</h2>
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Volver al Dashboard</a>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 p-4" style="border-radius: 15px;">
                        <form action="procesar_hero.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Título de Impacto</label>
                                <textarea name="titulo" class="form-control" rows="3" required><?php echo htmlspecialchars($h['titulo']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Texto del Botón</label>
                                <input type="text" name="texto_boton" class="form-control" value="<?php echo htmlspecialchars($h['texto_boton']); ?>" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Color de Fondo</label>
                                    <input type="color" name="color_fondo" class="form-control form-control-color w-100" value="<?php echo $h['color_fondo']; ?>" title="Elige el color de fondo">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Color del Botón (Primario)</label>
                                    <input type="color" name="color_primario" class="form-control form-control-color w-100" value="<?php echo $h['color_primario']; ?>" title="Elige el color del botón">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Logo del Header</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div class="form-text">Formatos sugeridos: PNG transparente o SVG.</div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold p-3" style="background-color: #d3122a; border: none;">
                                <i class="bi bi-save me-2"></i> GUARDAR CAMBIOS
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="sticky-top" style="top: 20px; z-index: 1;">
                        <h5 class="text-secondary fw-bold mb-3">Vista Previa del Sitio</h5>
                        <div class="preview-box shadow-sm">
                            <?php if(!empty($h['logo_url'])): ?>
                                <img src="../<?php echo $h['logo_url']; ?>" alt="Logo" class="mb-4" style="max-height: 50px;">
                            <?php endif; ?>

                            <h1 class="display-6 fw-bold mb-4"><?php echo $h['titulo']; ?></h1>
                            <button class="btn-preview rounded-pill shadow-sm">
                                <?php echo $h['texto_boton']; ?>
                            </button>
                        </div>
                        <p class="text-muted small mt-3 text-center">
                            <i class="bi bi-info-circle me-1"></i> 
                            Los cambios se verán reflejados en el sitio público tras guardar.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Capturar parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'success') {
        Swal.fire({
            icon: 'success',
            title: '¡Actualización Exitosa!',
            text: 'Los cambios en el Hero se han guardado correctamente.',
            confirmButtonColor: '#d3122a', // Color Primacía
            timer: 3000
        }).then(() => {
            // Limpiar la URL para que no repita la alerta al recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    } else if (status === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Error al actualizar',
            text: 'Hubo un problema con la base de datos. Inténtalo de nuevo.',
            confirmButtonColor: '#d3122a'
        });
    }
});
</script>
</body>
</html>