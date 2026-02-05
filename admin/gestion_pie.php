<?php
session_start();
require_once '../config/db.php';
// Verificar sesión
if (!isset($_SESSION['admin_id'])) { header("Location: ../index.php"); exit(); }

// Recuperamos la configuración actual (Sección 'contacto' según tu código)
$res = $conn->query("SELECT * FROM configuracion WHERE seccion = 'contacto'");
$c = [];
while($row = $res->fetch_assoc()) {
    $c[$row['clave']] = $row['valor'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Pie de Página | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .card-admin { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .section-header { border-left: 5px solid var(--brand-red); padding-left: 15px; margin-bottom: 20px; font-weight: 800; }
        .btn-save-lg { padding: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>

        <main class="col-md-10 ms-sm-auto p-4">
            <h2 class="fw-bold mb-4">Administración: Pie de Página y Contacto</h2>

            <form action="procesar_pie.php" method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card card-admin p-4 h-100">
                            <div class="section-header">DATOS DE CONTACTO</div>
                            <div class="mb-3">
                                <label class="small fw-bold">CORREO ELECTRÓNICO</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($c['email'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">WHATSAPP (NÚMERO CON LADA)</label>
                                <input type="text" name="whatsapp" class="form-control" value="<?php echo htmlspecialchars($c['whatsapp'] ?? ''); ?>" placeholder="Ej: +52 771 123 4567" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-admin p-4 h-100">
                            <div class="section-header">REDES SOCIALES</div>
                            <div class="mb-3">
                                <label class="small fw-bold"><i class="bi bi-facebook me-2"></i>FACEBOOK (URL)</label>
                                <input type="url" name="facebook" class="form-control" value="<?php echo htmlspecialchars($c['facebook'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold"><i class="bi bi-instagram me-2"></i>INSTAGRAM (URL)</label>
                                <input type="url" name="instagram" class="form-control" value="<?php echo htmlspecialchars($c['instagram'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold"><i class="bi bi-linkedin me-2"></i>LINKEDIN (URL)</label>
                                <input type="url" name="linkedin" class="form-control" value="<?php echo htmlspecialchars($c['linkedin'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card card-admin p-4">
                            <div class="section-header">INFORMACIÓN LEGAL</div>
                            <div class="mb-3">
                                <label class="small fw-bold">TEXTO DE COPYRIGHT / AVISO</label>
                                <input type="text" name="aviso_privacidad" class="form-control" value="<?php echo htmlspecialchars($c['aviso_privacidad'] ?? 'Aviso de Privacidad'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg shadow">
                        <i class="bi bi-cloud-check-fill me-2"></i> GUARDAR CAMBIOS EN EL PIE DE PÁGINA
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡Listo!', text: 'El pie de página se actualizó correctamente.', confirmButtonColor: '#d3122a' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>