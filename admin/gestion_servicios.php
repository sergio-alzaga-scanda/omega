<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: ../index.php"); exit(); }

$servicios = $conn->query("SELECT * FROM servicios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .service-img-prev { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; }
        .btn-save-lg { font-weight: 800; text-transform: uppercase; padding: 15px; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>

        <main class="col-md-10 ms-sm-auto p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Gestión de Servicios</h2>
                <button class="btn btn-danger btn-lg shadow fw-bold" onclick="abrirNuevoServicio()">
                    <i class="bi bi-plus-circle me-2"></i> AGREGAR SERVICIO
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark text-uppercase">
                            <tr>
                                <th>Imagen</th>
                                <th>Servicio</th>
                                <th>Etiquetas (Rojo)</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($s = $servicios->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../<?php echo $s['imagen_url']; ?>" class="service-img-prev"></td>
                                <td>
                                    <span class="fw-bold d-block"><?php echo htmlspecialchars($s['titulo']); ?></span>
                                    <small class="text-muted"><?php echo substr($s['descripcion'], 0, 80); ?>...</small>
                                </td>
                                <td><span class="badge bg-danger"><?php echo htmlspecialchars($s['subtitulos_rojos']); ?></span></td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm me-2" onclick='abrirEditarServicio(<?php echo json_encode($s); ?>)'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmarEliminarServicio(<?php echo $s['id']; ?>)">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="modalServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="formServicio" action="procesar_servicios.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="id" id="ser_id">
            <input type="hidden" name="accion" id="ser_accion" value="nuevo">
            
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="ser_modal_label">REGISTRAR SERVICIO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold small">TÍTULO DEL SERVICIO</label>
                            <input type="text" name="titulo" id="ser_titulo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small">DESCRIPCIÓN</label>
                            <textarea name="descripcion" id="ser_desc" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-danger">SUBTÍTULOS / ETIQUETAS (ROJO)</label>
                            <input type="text" name="subtitulos_rojos" id="ser_tags" class="form-control" placeholder="Ej: LOGÍSTICA | MONTAJE | AUDIO">
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <label class="fw-bold small d-block text-start mb-2">IMAGEN DEL SERVICIO</label>
                        <input type="file" name="imagen" class="form-control mb-3" onchange="previewServicio(event)">
                        <div class="p-2 border rounded bg-light">
                            <img id="ser_preview" src="#" style="max-height: 200px; display: none; border-radius: 10px;">
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg">GUARDAR SERVICIO</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    let modalInstance = new bootstrap.Modal(document.getElementById('modalServicio'));

    function abrirNuevoServicio() {
        document.getElementById('formServicio').reset();
        document.getElementById('ser_accion').value = 'nuevo';
        document.getElementById('ser_id').value = '';
        document.getElementById('ser_modal_label').innerText = 'REGISTRAR NUEVO SERVICIO';
        document.getElementById('ser_preview').style.display = 'none';
        modalInstance.show();
    }

    function abrirEditarServicio(datos) {
        document.getElementById('ser_accion').value = 'editar';
        document.getElementById('ser_id').value = datos.id;
        document.getElementById('ser_modal_label').innerText = 'EDITAR SERVICIO: ' + datos.titulo;
        document.getElementById('ser_titulo').value = datos.titulo;
        document.getElementById('ser_desc').value = datos.descripcion;
        document.getElementById('ser_tags').value = datos.subtitulos_rojos;
        
        const prev = document.getElementById('ser_preview');
        prev.src = '../' + datos.imagen_url;
        prev.style.display = 'inline-block';
        
        modalInstance.show();
    }

    function previewServicio(event) {
        const reader = new FileReader();
        reader.onload = () => {
            const out = document.getElementById('ser_preview');
            out.src = reader.result;
            out.style.display = 'inline-block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function confirmarEliminarServicio(id) {
    Swal.fire({
        title: '¿ELIMINAR ESTE SERVICIO?',
        text: "Se borrará el título, la descripción y la imagen del servidor.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d3122a', // Rojo Primacía
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'SÍ, ELIMINAR',
        cancelButtonText: 'CANCELAR',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Verifica que este nombre coincida con tu archivo procesador
            window.location.href = `procesar_servicios.php?eliminar=${id}`;
        }
    });
}
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡LISTO!', text: 'El servicio se actualizó correctamente.', confirmButtonColor: '#d3122a' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>