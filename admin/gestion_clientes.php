<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: ../index.php"); exit(); }

// Consultamos los clientes
$clientes = $conn->query("SELECT * FROM clientes ORDER BY orden ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes | Primacía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --brand-red: #d3122a; }
        .logo-preview-img { width: 100px; height: 60px; object-fit: contain; background: #f8f9fa; border: 1px solid #ddd; padding: 5px; border-radius: 8px; }
        .btn-save-lg { font-weight: 800; text-transform: uppercase; padding: 15px; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <?php include 'menu.php'; ?>

        <main class="col-md-10 ms-sm-auto p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Gestión de Clientes (Logos)</h2>
                <button class="btn btn-danger btn-lg shadow fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                    <i class="bi bi-plus-circle me-2"></i> AGREGAR LOGO DE CLIENTE
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark text-uppercase">
                            <tr>
                                <th>Orden</th>
                                <th>Logo</th>
                                <th>Nombre Comercial</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($cl = $clientes->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold"><?php echo $cl['orden']; ?></td>
                                <td>
                                    <img src="../<?php echo $cl['logo_url']; ?>" class="logo-preview-img shadow-sm">
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($cl['nombre']); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm me-2" 
                                            onclick='abrirEditarCliente(<?php echo json_encode($cl); ?>)'>
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm border-0" 
                                            onclick="confirmarEliminarCliente(<?php echo $cl['id']; ?>)">
                                        <i class="bi bi-trash3-fill"></i>
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

<div class="modal fade" id="modalCliente" tabindex="-1">
    <div class="modal-dialog">
        <form id="formCliente" action="procesar_clientes.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="id" id="client_id">
            <input type="hidden" name="accion" id="client_accion" value="nuevo">
            
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">AGREGAR CLIENTE</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="fw-bold small">NOMBRE DE LA EMPRESA</label>
                    <input type="text" name="nombre" id="client_nombre" class="form-control" placeholder="Ej: Coca-Cola" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small">NÚMERO DE ORDEN (Aparición)</label>
                    <input type="number" name="orden" id="client_orden" class="form-control" value="0">
                </div>
                <div class="mb-3 text-center">
                    <label class="fw-bold small d-block text-start mb-2">LOGO (Fondo transparente sugerido)</label>
                    <input type="file" name="logo" class="form-control mb-3" onchange="previewLogo(event)">
                    <div class="p-3 border rounded bg-light">
                        <img id="logo_prev" src="#" style="max-height: 100px; display: none;">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-danger btn-lg w-100 btn-save-lg">GUARDAR CLIENTE</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function previewLogo(event) {
        const reader = new FileReader();
        reader.onload = () => {
            const out = document.getElementById('logo_prev');
            out.src = reader.result;
            out.style.display = 'inline-block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function abrirEditarCliente(datos) {
        document.getElementById('modalTitle').innerText = "EDITAR CLIENTE";
        document.getElementById('client_accion').value = "editar";
        document.getElementById('client_id').value = datos.id;
        document.getElementById('client_nombre').value = datos.nombre;
        document.getElementById('client_orden').value = datos.orden;
        
        const prev = document.getElementById('logo_prev');
        prev.src = '../' + datos.logo_url;
        prev.style.display = 'inline-block';
        
        new bootstrap.Modal(document.getElementById('modalCliente')).show();
    }

    // Resetear modal al presionar "Agregar"
    document.querySelector('[data-bs-target="#modalNuevoCliente"]').addEventListener('click', () => {
        document.getElementById('formCliente').reset();
        document.getElementById('modalTitle').innerText = "AGREGAR CLIENTE";
        document.getElementById('client_accion').value = "nuevo";
        document.getElementById('logo_prev').style.display = 'none';
        new bootstrap.Modal(document.getElementById('modalCliente')).show();
    });

    function confirmarEliminarCliente(id) {
        Swal.fire({
            title: '¿ELIMINAR LOGO?',
            text: "El cliente dejará de aparecer en el carrusel principal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d3122a',
            confirmButtonText: 'SÍ, BORRAR',
            cancelButtonText: 'CANCELAR'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `eliminar_cliente.php?id=${id}`;
            }
        });
    }

    // Alert Status
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('status') === 'success') {
        Swal.fire({ icon: 'success', title: '¡LISTO!', text: 'Los cambios se aplicaron correctamente.', confirmButtonColor: '#d3122a', timer: 1500 });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>