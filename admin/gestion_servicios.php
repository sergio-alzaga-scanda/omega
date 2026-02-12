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
    <title>Gestión de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .service-img-prev { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>
        <main class="col-md-10 ms-sm-auto p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Gestión de Servicios</h2>
                <button class="btn btn-danger fw-bold" onclick="abrirNuevoServicio()">+ AGREGAR SERVICIO</button>
            </div>
            <div class="card border-0 shadow-sm p-4">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Imagen</th><th>Título</th><th>Etiquetas</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($s = $servicios->fetch_assoc()): ?>
                        <tr>
                            <td><img src="../<?php echo $s['imagen_url']; ?>" class="service-img-prev"></td>
                            <td><strong><?php echo $s['titulo']; ?></strong></td>
                            <td><span class="badge bg-danger"><?php echo $s['subtitulos_rojos']; ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" onclick='abrirEditarServicio(<?php echo json_encode($s); ?>)'><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-outline-danger btn-sm" onclick="confirmarEliminarServicio(<?php echo $s['id']; ?>)"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="modalServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formServicio" action="procesar_servicios.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <input type="hidden" name="id" id="ser_id">
            <input type="hidden" name="accion" id="ser_accion" value="nuevo">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="ser_modal_label">GESTIÓN DE SERVICIO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Título del Servicio</label>
                        <input type="text" name="titulo" id="ser_titulo" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Descripción Corta (Lista)</label>
                        <textarea name="descripcion" id="ser_desc" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-primary">Descripción Larga (Modal)</label>
                        <textarea name="descripcion_larga" id="ser_desc_larga" class="form-control" rows="3" required placeholder="Este texto aparecerá al abrir el modal"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-danger">Etiquetas (Rojo)</label>
                        <input type="text" name="subtitulos_rojos" id="ser_tags" class="form-control" placeholder="Ej: AUDIO | VIDEO">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Imagen Portada</label>
                        <input type="file" name="imagen" class="form-control mb-2" onchange="previewServicio(event)">
                        <img id="ser_preview" src="#" style="max-height: 80px; display: none;" class="rounded">
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold text-info">Añadir Imágenes a la Galería (Opcional)</label>
                        <input type="file" name="galeria[]" class="form-control" multiple>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-danger p-3 fw-bold">GUARDAR SERVICIO</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let modalIns = new bootstrap.Modal(document.getElementById('modalServicio'));
    function abrirNuevoServicio() {
        document.getElementById('formServicio').reset();
        document.getElementById('ser_accion').value = 'nuevo';
        document.getElementById('ser_preview').style.display = 'none';
        modalIns.show();
    }
    function abrirEditarServicio(d) {
        document.getElementById('ser_accion').value = 'editar';
        document.getElementById('ser_id').value = d.id;
        document.getElementById('ser_titulo').value = d.titulo;
        document.getElementById('ser_desc').value = d.descripcion;
        document.getElementById('ser_desc_larga').value = d.descripcion_larga; // Carga descripción larga
        document.getElementById('ser_tags').value = d.subtitulos_rojos;
        const p = document.getElementById('ser_preview');
        p.src = '../' + d.imagen_url; p.style.display = 'block';
        modalIns.show();
    }
    function previewServicio(e) {
        const r = new FileReader();
        r.onload = () => { const o = document.getElementById('ser_preview'); o.src = r.result; o.style.display = 'block'; };
        r.readAsDataURL(e.target.files[0]);
    }
    function confirmarEliminarServicio(id) {
        Swal.fire({ title: '¿Eliminar?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d3122a' }).then(r => {
            if(r.isConfirmed) window.location.href = `procesar_servicios.php?eliminar=${id}`;
        });
    }
</script>
</body>
</html>