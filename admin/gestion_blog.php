<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: ../index.php"); exit(); }
// Cambiamos la consulta a la tabla blog
$blogs = $conn->query("SELECT * FROM blog ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Blog | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .blog-img-preview { width: 80px; height: 100px; object-fit: cover; border-radius: 8px; }
        .btn-save-lg { font-weight: 800; text-transform: uppercase; padding: 18px; font-size: 1.1rem; }
        .modal-header { background-color: #1a1a1a; color: white; border-bottom: 4px solid var(--brand-red); }
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
                <h2 class="fw-bold">Gestión de Blog</h2>
                <button class="btn btn-danger btn-lg shadow fw-bold px-4" onclick="abrirModalNuevo()">
                    <i class="bi bi-plus-circle me-2"></i> NUEVA ENTRADA
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Portada</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Fecha</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($b = $blogs->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../<?php echo $b['imagen_portada']; ?>" class="blog-img-preview"></td>
                                <td class="fw-bold text-uppercase"><?php echo $b['titulo']; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $b['categoria']; ?></span></td>
                                <td><?php echo $b['fecha']; ?></td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm me-2" onclick='abrirModalEditar(<?php echo json_encode($b); ?>)'>
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmarEliminar(<?php echo $b['id']; ?>)">
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

<div class="modal fade" id="modalBlogMaster" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="procesar_blog.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" id="formBlog">
            <input type="hidden" name="accion" id="form_accion" value="nuevo">
            <input type="hidden" name="id" id="form_id">
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modal_titulo_label">NUEVA ENTRADA DE BLOG</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">TÍTULO DE LA ENTRADA</label>
                            <input type="text" name="titulo" id="in_titulo" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">CATEGORÍA</label>
                            <input type="text" name="categoria" id="in_categoria" class="form-control" placeholder="Ej: Tendencias, BTL...">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">RESUMEN (Texto sobre tarjeta)</label>
                            <textarea name="resumen" id="in_resumen" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">CONTENIDO HTML (Detalle modal)</label>
                            <textarea name="contenido_html" id="in_cont_html" class="form-control" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-muted">FECHA</label>
                                <input type="date" name="fecha" id="in_fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-muted">URL VIDEO (Youtube)</label>
                                <input type="url" name="video_url" id="in_video" class="form-control" placeholder="https://...">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">IMAGEN PORTADA (Vertical recomendada)</label>
                            <input type="file" name="imagen" class="form-control" onchange="previewImg(event)">
                            <div class="mt-3 text-center bg-light p-2 border rounded">
                                <img id="img_preview" src="#" style="max-height: 150px; display: none; border-radius: 8px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-danger">IMÁGENES PARA CARRUSEL INTERNO</label>
                            <input type="file" name="galeria[]" id="in_galeria" class="form-control" multiple accept="image/*">
                            <div id="previsualizacion_cola" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>

                        <div id="galeria_actual_container" class="mb-3" style="display:none;">
                            <label class="fw-bold small text-muted d-block mb-2">GALERÍA ACTUAL</label>
                            <div id="lista_galeria" class="d-flex flex-wrap gap-3 p-2 bg-light border rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer p-0">
                <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg shadow-none" style="border-radius: 0 0 10px 10px;">
                    <i class="bi bi-cloud-arrow-up me-2"></i> GUARDAR EN BLOG
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const modalMaster = new bootstrap.Modal(document.getElementById('modalBlogMaster'));
    let archivosEnCola = [];

    function previewImg(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const out = document.getElementById('img_preview');
            out.src = reader.result;
            out.style.display = 'inline-block';
        };
        if(event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
    }

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
                div.innerHTML = `<img src="${event.target.result}"><button type="button" class="btn-del-photo" onclick="quitarDeCola('${fileId}')"><i class="bi bi-x"></i></button><span class="badge-new">NUEVA</span>`;
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

    function abrirModalNuevo() {
        document.getElementById('formBlog').reset();
        archivosEnCola = [];
        document.getElementById('previsualizacion_cola').innerHTML = '';
        document.getElementById('form_accion').value = 'nuevo';
        document.getElementById('form_id').value = '';
        document.getElementById('modal_titulo_label').innerText = 'NUEVA ENTRADA DE BLOG';
        document.getElementById('img_preview').style.display = 'none';
        document.getElementById('galeria_actual_container').style.display = 'none';
        modalMaster.show();
    }

    function abrirModalEditar(datos) {
        document.getElementById('formBlog').reset();
        archivosEnCola = [];
        document.getElementById('previsualizacion_cola').innerHTML = '';
        document.getElementById('form_accion').value = 'editar';
        document.getElementById('form_id').value = datos.id;
        document.getElementById('modal_titulo_label').innerText = 'EDITAR ENTRADA';
        
        document.getElementById('in_titulo').value = datos.titulo;
        document.getElementById('in_categoria').value = datos.categoria;
        document.getElementById('in_resumen').value = datos.resumen;
        document.getElementById('in_cont_html').value = datos.contenido_html;
        document.getElementById('in_fecha').value = datos.fecha;
        document.getElementById('in_video').value = datos.video_url || '';
        
        if(datos.imagen_portada) {
            document.getElementById('img_preview').src = '../' + datos.imagen_portada;
            document.getElementById('img_preview').style.display = 'inline-block';
        }

        const contenedorGaleria = document.getElementById('lista_galeria');
        const seccionGaleria = document.getElementById('galeria_actual_container');
        contenedorGaleria.innerHTML = 'Cargando...';
        seccionGaleria.style.display = 'block';

        fetch(`obtener_galeria_blog.php?id=${datos.id}`)
            .then(res => res.json())
            .then(images => {
                contenedorGaleria.innerHTML = '';
                if(images.length > 0) {
                    images.forEach(img => {
                        const div = document.createElement('div');
                        div.className = 'gal-item';
                        div.innerHTML = `<img src="../${img.ruta_imagen}"><button type="button" class="btn-del-photo" onclick="eliminarFotoBlog(${img.id}, this)"><i class="bi bi-x"></i></button>`;
                        contenedorGaleria.appendChild(div);
                    });
                } else { seccionGaleria.style.display = 'none'; }
            });
        modalMaster.show();
    }

    function eliminarFotoBlog(fotoId, btn) {
        Swal.fire({
            title: '¿Borrar imagen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'SÍ',
            confirmButtonColor: '#d3122a'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`eliminar_foto_blog.php?id=${fotoId}`)
                    .then(res => res.json())
                    .then(data => { if(data.success) btn.parentElement.remove(); });
            }
        });
    }

    function confirmarEliminar(id) {
        Swal.fire({
            title: '¿ELIMINAR ENTRADA?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'SÍ, BORRAR TODO',
            confirmButtonColor: '#d3122a'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = `eliminar_blog.php?id=${id}`;
        });
    }

    const params = new URLSearchParams(window.location.search);
    if(params.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡Actualizado!', confirmButtonColor: '#d3122a' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>