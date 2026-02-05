<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: ../index.php"); exit(); }

// Consultas
$nosotros_res = $conn->query("SELECT * FROM configuracion WHERE seccion = 'nosotros'");
$nosotros = [];
while($row = $nosotros_res->fetch_assoc()) { $nosotros[$row['clave']] = $row; }

$contadores = $conn->query("SELECT * FROM contadores ORDER BY id ASC");
$puntos = $conn->query("SELECT * FROM porque_primicia_puntos ORDER BY orden ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Nosotros | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .card-nosotros { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .section-header { border-left: 8px solid var(--brand-red); padding-left: 15px; margin-bottom: 25px; }
        .btn-save-lg { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 15px; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>

        <main class="col-md-10 ms-sm-auto p-4">
            <h2 class="fw-bold mb-4">Gestión de Sección: Nosotros</h2>

            <div class="card card-nosotros p-4">
                <div class="section-header"><h5>Textos de Cabecera</h5></div>
                <form action="procesar_nosotros.php" method="POST">
                    <input type="hidden" name="accion" value="actualizar_textos">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Título de Sección</label>
                            <input type="text" name="titulo" class="form-control form-control-lg" value="<?php echo htmlspecialchars($nosotros['titulo']['valor']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Subtítulo (Rojo)</label>
                            <input type="text" name="subtitulo" class="form-control form-control-lg" value="<?php echo htmlspecialchars($nosotros['subtitulo']['valor']); ?>">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg shadow">Guardar Cambios en Cabecera</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card card-nosotros p-4">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h5>Cifras / Contadores</h5>
                    <button class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalContador">
                        <i class="bi bi-plus-lg"></i> NUEVO CONTADOR
                    </button>
                </div>
                <form action="procesar_nosotros.php" method="POST">
                    <input type="hidden" name="accion" value="actualizar_contadores">
                    <div class="row g-3">
                        <?php while($cont = $contadores->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="p-4 border rounded bg-white position-relative">
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 position-absolute top-0 end-0 m-2" onclick="confirmarEliminar(<?php echo $cont['id']; ?>, 'contadores')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Etiqueta</label>
                                <input type="text" name="etiqueta[<?php echo $cont['id']; ?>]" class="form-control mb-3 fw-bold" value="<?php echo $cont['etiqueta']; ?>">
                                <div class="input-group">
                                    <input type="number" name="numero[<?php echo $cont['id']; ?>]" class="form-control form-control-lg" value="<?php echo $cont['numero']; ?>">
                                    <input type="text" name="sufijo[<?php echo $cont['id']; ?>]" class="form-control w-25 form-control-lg" value="<?php echo $cont['sufijo']; ?>" placeholder="+">
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg shadow">Actualizar Todas las Cifras</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card card-nosotros p-4">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h5>Puntos Clave (Beneficios)</h5>
                    <button class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalPunto">
                        <i class="bi bi-plus-lg"></i> NUEVO PUNTO CLAVE
                    </button>
                </div>
                <form action="procesar_nosotros.php" method="POST">
                    <input type="hidden" name="accion" value="actualizar_puntos">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark text-uppercase">
                                <tr>
                                    <th style="width: 30%;">Título</th>
                                    <th style="width: 60%;">Descripción</th>
                                    <th class="text-center">Borrar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($p = $puntos->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="titulo[<?php echo $p['id']; ?>]" class="form-control fw-bold" value="<?php echo $p['titulo']; ?>">
                                    </td>
                                    <td>
                                        <textarea name="descripcion[<?php echo $p['id']; ?>]" class="form-control" rows="2"><?php echo $p['descripcion']; ?></textarea>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="confirmarEliminar(<?php echo $p['id']; ?>, 'puntos')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg shadow">Guardar Cambios en Puntos Clave</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="modalContador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="procesar_nosotros.php" method="POST" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="accion" value="nuevo_contador">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">NUEVA CIFRA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="fw-bold small">ETIQUETA</label>
                    <input type="text" name="etiqueta" class="form-control" placeholder="Ej: PROYECTOS" required>
                </div>
                <div class="row">
                    <div class="col-8">
                        <label class="fw-bold small">NÚMERO</label>
                        <input type="number" name="numero" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label class="fw-bold small">SUFIJO</label>
                        <input type="text" name="sufijo" class="form-control" placeholder="+">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-lg w-100">CREAR CONTADOR</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalPunto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="procesar_nosotros.php" method="POST" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="accion" value="nuevo_punto">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">NUEVO PUNTO CLAVE</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="fw-bold small">TÍTULO DEL PUNTO</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small">DESCRIPCIÓN</label>
                    <textarea name="descripcion" class="form-control" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-lg w-100">CREAR PUNTO</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡LISTO!', text: 'Los datos se guardaron correctamente.', confirmButtonColor: '#d3122a' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    function confirmarEliminar(id, tipo) {
        Swal.fire({
            title: '¿ELIMINAR ESTE ELEMENTO?',
            text: "Esta acción borrará el dato de la base de datos.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d3122a',
            confirmButtonText: 'ELIMINAR',
            cancelButtonText: 'CANCELAR'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `eliminar_nosotros.php?id=${id}&tipo=${tipo}`;
            }
        });
    }
</script>
</body>
</html>