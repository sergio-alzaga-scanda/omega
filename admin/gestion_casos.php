<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: ../index.php"); exit(); }
$casos = $conn->query("SELECT * FROM casos_exito ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Casos | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .case-img-preview { width: 100px; height: 70px; object-fit: cover; border-radius: 8px; }
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>

        <main class="col-md-10 ms-sm-auto p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Gestión de Casos de Éxito</h2>
                <button class="btn btn-danger btn-lg shadow fw-bold px-4" onclick="abrirModalNuevo()">
                    <i class="bi bi-plus-circle me-2"></i> AGREGAR PROYECTO
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Imagen</th>
                                <th>Proyecto</th>
                                <th>Cliente</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($c = $casos->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../<?php echo $c['imagen_url']; ?>" class="case-img-preview"></td>
                                <td class="fw-bold text-uppercase"><?php echo $c['titulo']; ?></td>
                                <td><?php echo $c['nombre_cliente']; ?></td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm me-2" onclick='abrirModalEditar(<?php echo json_encode($c); ?>)'>
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmarEliminar(<?php echo $c['id']; ?>)">
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

<div class="modal fade" id="modalCasosMaster" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="procesar_casos.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="accion" id="form_accion" value="nuevo">
            <input type="hidden" name="id" id="form_id">
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modal_titulo_label">REGISTRAR PROYECTO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">TÍTULO DEL PROYECTO</label>
                            <input type="text" name="titulo" id="in_titulo" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">DESCRIPCIÓN CORTA</label>
                            <textarea name="descripcion_corta" id="in_desc_c" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">DESCRIPCIÓN LARGA (DETALLE)</label>
                            <textarea name="descripcion_larga" id="in_desc_l" class="form-control" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">IMAGEN PORTADA (Principal)</label>
                            <input type="file" name="imagen" class="form-control" onchange="previewImg(event)">
                            <div class="mt-3 text-center bg-light p-2 border rounded">
                                <img id="img_preview" src="#" style="max-height: 180px; display: none; border-radius: 8px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-danger">AÑADIR MÁS FOTOS A LA GALERÍA</label>
                            <input type="file" name="galeria[]" class="form-control" multiple accept="image/*">
                        </div>

                        <div id="galeria_actual_container" class="mb-3" style="display:none;">
                            <label class="fw-bold small text-muted d-block mb-2">GALERÍA ACTUAL (Clic en X para borrar)</label>
                            <div id="lista_galeria" class="d-flex flex-wrap gap-3 p-2 bg-light border rounded">
                                </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-muted">CLIENTE</label>
                                <input type="text" name="nombre_cliente" id="in_cliente" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-muted">CARGO</label>
                                <input type="text" name="cargo_cliente" id="in_cargo" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">TESTIMONIO / COMENTARIO</label>
                            <textarea name="comentario_cliente" id="in_comentario" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer p-0">
                <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg shadow-none" style="border-radius: 0 0 10px 10px;">
                    <i class="bi bi-cloud-arrow-up me-2"></i> GUARDAR CAMBIOS EN BASE DE DATOS
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const modalMaster = new bootstrap.Modal(document.getElementById('modalCasosMaster'));

    function confirmarEliminar(id) {
        Swal.fire({
            title: '¿ELIMINAR ESTE PROYECTO?',
            text: "Esta acción es irreversible y borrará toda la galería asociada.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d3122a',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'SÍ, ELIMINAR',
            cancelButtonText: 'CANCELAR',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `eliminar_caso.php?id=${id}`;
            }
        });
    }

    function abrirModalNuevo() {
        document.querySelector('form').reset();
        document.getElementById('form_accion').value = 'nuevo';
        document.getElementById('form_id').value = '';
        document.getElementById('modal_titulo_label').innerText = 'REGISTRAR NUEVO PROYECTO';
        document.getElementById('img_preview').style.display = 'none';
        document.getElementById('galeria_actual_container').style.display = 'none';
        modalMaster.show();
    }

    function abrirModalEditar(datos) {
        document.getElementById('form_accion').value = 'editar';
        document.getElementById('form_id').value = datos.id;
        document.getElementById('modal_titulo_label').innerText = 'EDITAR PROYECTO: ' + datos.titulo;
        
        document.getElementById('in_titulo').value = datos.titulo;
        document.getElementById('in_desc_c').value = datos.descripcion_corta;
        document.getElementById('in_desc_l').value = datos.descripcion_larga;
        document.getElementById('in_cliente').value = datos.nombre_cliente;
        document.getElementById('in_cargo').value = datos.cargo_cliente;
        document.getElementById('in_comentario').value = datos.comentario_cliente;
        
        // Portada
        const preview = document.getElementById('img_preview');
        if(datos.imagen_url) {
            preview.src = '../' + datos.imagen_url;
            preview.style.display = 'inline-block';
        } else {
            preview.style.display = 'none';
        }

        // Cargar Galería vía AJAX
        const contenedorGaleria = document.getElementById('lista_galeria');
        const seccionGaleria = document.getElementById('galeria_actual_container');
        contenedorGaleria.innerHTML = '<div class="spinner-border spinner-border-sm text-danger"></div>';
        seccionGaleria.style.display = 'block';

        fetch(`obtener_galeria.php?id=${datos.id}`)
            .then(res => res.json())
            .then(images => {
                contenedorGaleria.innerHTML = '';
                if(images.length > 0) {
                    images.forEach(img => {
                        const div = document.createElement('div');
                        div.className = 'gal-item';
                        div.innerHTML = `
                            <img src="../${img.ruta_imagen}">
                            <button type="button" class="btn-del-photo" onclick="eliminarFotoGaleria(${img.id}, this)">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                        contenedorGaleria.appendChild(div);
                    });
                } else {
                    seccionGaleria.style.display = 'none';
                }
            })
            .catch(err => {
                console.error("Error cargando galería", err);
                seccionGaleria.style.display = 'none';
            });
        
        modalMaster.show();
    }

    function eliminarFotoGaleria(fotoId, btn) {
        Swal.fire({
            title: '¿Eliminar imagen?',
            text: "Se borrará solo esta foto de la galería.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d3122a',
            confirmButtonText: 'SÍ, BORRAR'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`eliminar_foto_galeria.php?id=${fotoId}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            btn.parentElement.remove();
                            // Ocultar sección si ya no hay fotos
                            if(document.getElementById('lista_galeria').children.length === 0) {
                                document.getElementById('galeria_actual_container').style.display = 'none';
                            }
                        }
                    });
            }
        });
    }

    function previewImg(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const out = document.getElementById('img_preview');
            out.src = reader.result;
            out.style.display = 'inline-block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Alerta de éxito/error al volver de procesar_casos.php
    const params = new URLSearchParams(window.location.search);
    if(params.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡Hecho!', text: 'La información se actualizó correctamente.', confirmButtonColor: '#d3122a' });
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (params.get('status') === 'error') {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo procesar la solicitud.', confirmButtonColor: '#d3122a' });
    }
</script>
</body>
</html>