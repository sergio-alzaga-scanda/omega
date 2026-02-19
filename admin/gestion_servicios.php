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
    <title>Gestión de Servicios | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .service-img-prev { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
        .gal-item { position: relative; width: 100px; height: 75px; }
        .gal-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }
        .btn-del-photo { 
            position: absolute; top: -5px; right: -5px; 
            background: var(--brand-red); color: white; 
            border: none; border-radius: 50%; 
            width: 22px; height: 22px; font-size: 12px; 
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;
        }
        .badge-new { font-size: 0.6rem; position: absolute; bottom: 0; width: 100%; text-align: center; background: rgba(13, 110, 253, 0.8); color: white; border-radius: 0 0 5px 5px; }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>
        <main class="col-md-10 ms-sm-auto p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Gestión de Servicios</h2>
                <button class="btn btn-danger fw-bold shadow-sm" onclick="abrirNuevoServicio()">
                    <i class="bi bi-plus-circle me-2"></i> AGREGAR SERVICIO
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
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
                            <td class="fw-bold"><?php echo $s['titulo']; ?></td>
                            <td><span class="badge bg-danger"><?php echo $s['subtitulos_rojos']; ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm me-1" onclick='abrirEditarServicio(<?php echo json_encode($s); ?>)'><i class="bi bi-pencil"></i></button>
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
    <div class="modal-dialog modal-xl">
        <form id="formServicio" action="procesar_servicios.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="id" id="ser_id">
            <input type="hidden" name="accion" id="ser_accion" value="nuevo">
            
            <div class="modal-header bg-dark text-white border-bottom border-danger border-4">
                <h5 class="modal-title fw-bold" id="ser_modal_label">GESTIÓN DE SERVICIO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">TÍTULO DEL SERVICIO</label>
                            <input type="text" name="titulo" id="ser_titulo" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">DESCRIPCIÓN CORTA (LISTADO)</label>
                            <textarea name="descripcion" id="ser_desc" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-primary">DESCRIPCIÓN LARGA (DETALLE MODAL)</label>
                            <textarea name="descripcion_larga" id="ser_desc_larga" class="form-control" rows="6" required></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">ETIQUETAS (SEPARADAS POR | )</label>
                            <input type="text" name="subtitulos_rojos" id="ser_tags" class="form-control" placeholder="AUDIO | VIDEO | ILUMINACIÓN">
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">IMAGEN PORTADA</label>
                            <input type="file" name="imagen" id="in_portada" class="form-control" onchange="previewPortada(event)">
                            <div class="mt-2 text-center bg-light p-2 border rounded">
                                <img id="ser_preview" src="#" style="max-height: 120px; display: none;" class="rounded shadow-sm">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-info">AÑADIR FOTOS A LA GALERÍA</label>
                            <input type="file" name="galeria[]" id="in_galeria" class="form-control" multiple accept="image/*">
                            <div id="previsualizacion_cola" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>

                        <div id="galeria_actual_container" class="mb-3" style="display:none;">
                            <label class="fw-bold small text-muted d-block mb-2">GALERÍA ACTUAL EN SERVIDOR</label>
                            <div id="lista_galeria" class="d-flex flex-wrap gap-3 p-2 bg-light border rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer p-0">
                <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold p-3" style="border-radius: 0 0 10px 10px;">
                    <i class="bi bi-cloud-arrow-up me-2"></i> GUARDAR CAMBIOS EN SERVICIOS
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let modalIns = new bootstrap.Modal(document.getElementById('modalServicio'));
    let archivosEnCola = []; // Manejo de archivos nuevos

    // --- PREVIEW PORTADA ---
    function previewPortada(e) {
        const r = new FileReader();
        r.onload = () => { const o = document.getElementById('ser_preview'); o.src = r.result; o.style.display = 'inline-block'; };
        if(e.target.files[0]) r.readAsDataURL(e.target.files[0]);
    }

    // --- MANEJO DE GALERÍA NUEVA (COLA) ---
    document.getElementById('in_galeria').addEventListener('change', function(e) {
        const container = document.getElementById('previsualizacion_cola');
        const files = Array.from(e.target.files);

        files.forEach(file => {
            const fileId = Date.now() + Math.random();
            archivosEnCola.push({ id: fileId, file: file });

            const reader = new FileReader();
            reader.onload = function(event) {
                const div = document.createElement('div');
                div.className = 'gal-item';
                div.setAttribute('data-queue-id', fileId);
                div.innerHTML = `
                    <img src="${event.target.result}">
                    <button type="button" class="btn-del-photo" onclick="quitarDeCola('${fileId}')">
                        <i class="bi bi-x"></i>
                    </button>
                    <span class="badge-new">NUEVA</span>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        actualizarInputFile();
    });

    function quitarDeCola(id) {
        archivosEnCola = archivosEnCola.filter(item => item.id != id);
        const el = document.querySelector(`[data-queue-id="${id}"]`);
        if(el) el.remove();
        actualizarInputFile();
    }

    function actualizarInputFile() {
        const dt = new DataTransfer();
        archivosEnCola.forEach(item => dt.items.add(item.file));
        document.getElementById('in_galeria').files = dt.files;
    }

    // --- FUNCIONES MODAL ---
    function abrirNuevoServicio() {
        document.getElementById('formServicio').reset();
        archivosEnCola = [];
        document.getElementById('previsualizacion_cola').innerHTML = '';
        document.getElementById('ser_accion').value = 'nuevo';
        document.getElementById('ser_preview').style.display = 'none';
        document.getElementById('galeria_actual_container').style.display = 'none';
        modalIns.show();
    }

    function abrirEditarServicio(d) {
        document.getElementById('formServicio').reset();
        archivosEnCola = [];
        document.getElementById('previsualizacion_cola').innerHTML = '';
        
        document.getElementById('ser_accion').value = 'editar';
        document.getElementById('ser_id').value = d.id;
        document.getElementById('ser_titulo').value = d.titulo;
        document.getElementById('ser_desc').value = d.descripcion;
        document.getElementById('ser_desc_larga').value = d.descripcion_larga;
        document.getElementById('ser_tags').value = d.subtitulos_rojos;
        
        const p = document.getElementById('ser_preview');
        if(d.imagen_url) { p.src = '../' + d.imagen_url; p.style.display = 'inline-block'; }

        // Cargar galería del servidor
        const contenedorGaleria = document.getElementById('lista_galeria');
        const seccionGaleria = document.getElementById('galeria_actual_container');
        contenedorGaleria.innerHTML = '<div class="spinner-border spinner-border-sm text-danger"></div>';
        seccionGaleria.style.display = 'block';

        fetch(`obtener_galeria_servicios.php?id=${d.id}`)
            .then(res => res.json())
            .then(images => {
                contenedorGaleria.innerHTML = '';
                if(images.length > 0) {
                    images.forEach(img => {
                        const div = document.createElement('div');
                        div.className = 'gal-item';
                        div.innerHTML = `
                            <img src="../${img.ruta_imagen}">
                            <button type="button" class="btn-del-photo" onclick="eliminarFotoServidor(${img.id}, this)">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                        contenedorGaleria.appendChild(div);
                    });
                } else {
                    seccionGaleria.style.display = 'none';
                }
            });

        modalIns.show();
    }

    function eliminarFotoServidor(fotoId, btn) {
        Swal.fire({
            title: '¿Eliminar imagen?',
            text: "Se borrará permanentemente del servidor.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d3122a',
            confirmButtonText: 'SÍ, BORRAR'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`eliminar_foto_galeria_servicio.php?id=${fotoId}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) btn.parentElement.remove();
                    });
            }
        });
    }

    function confirmarEliminarServicio(id) {
        Swal.fire({ 
            title: '¿ELIMINAR SERVICIO?', 
            text: 'Se borrará el servicio y todas sus imágenes asociadas.',
            icon: 'error', 
            showCancelButton: true, 
            confirmButtonColor: '#d3122a' 
        }).then(r => {
            if(r.isConfirmed) window.location.href = `procesar_servicios.php?eliminar=${id}`;
        });
    }

    const params = new URLSearchParams(window.location.search);
    if(params.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡Hecho!', confirmButtonColor: '#d3122a' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>