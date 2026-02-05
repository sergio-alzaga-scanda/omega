<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Consulta para la gráfica
$res_stats = $conn->query("SELECT status, COUNT(*) as total FROM contactos_recibidos GROUP BY status");
$data_grafica = ['Nueva solicitud'=>0, 'En proceso'=>0, 'Cliente'=>0, 'No aplica'=>0];
while($row = $res_stats->fetch_assoc()) {
    $data_grafica[$row['status']] = (int)$row['total'];
}

// Consulta para la tabla completa
$contactos = $conn->query("SELECT * FROM contactos_recibidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrativo | PRIMACÍA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --brand-red: #d3122a; --sidebar-dark: #121212; }
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: var(--sidebar-dark); color: white; }
        .nav-link { color: #adb5bd; margin: 5px 15px; border-radius: 8px; }
        .nav-link.active { background: var(--brand-red); color: white; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        /* Estilo para ajustar texto largo del mensaje */
        .msg-truncate { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
    <?php include 'menu.php'; ?>
        <main class="col-md-10 ms-sm-auto p-4">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card card-custom p-4 bg-white">
                        <h5 class="fw-bold mb-3">Estatus de Prospección</h5>
                        <canvas id="statusChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="card card-custom p-4 bg-white">
                <h5 class="fw-bold mb-4">Listado Completo de Prospectos</h5>
                <div class="table-responsive">
                    <table id="tablaProspectos" class="table table-striped table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Servicio</th>
                                <th>Mensaje</th>
                                <th>Estatus</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $contactos->fetch_assoc()): ?>
                            <tr>
                                <td><span class="d-none"><?php echo $row['fecha']; ?></span><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['servicio']); ?></td>
                                <td>
                                    <div class="msg-truncate" onclick="verMensaje('<?php echo addslashes(htmlspecialchars($row['mensaje'])); ?>')">
                                        <?php echo htmlspecialchars($row['mensaje']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $badge = ['Nueva solicitud'=>'primary','En proceso'=>'warning text-dark','Cliente'=>'success','No aplica'=>'danger'];
                                    $c = $badge[$row['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $c; ?>"><?php echo $row['status']; ?></span>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" onchange="cambiarEstatus(<?php echo $row['id']; ?>, this.value)">
                                        <option value="Nueva solicitud" <?php if($row['status']=='Nueva solicitud') echo 'selected'; ?>>Nueva solicitud</option>
                                        <option value="En proceso" <?php if($row['status']=='En proceso') echo 'selected'; ?>>En proceso</option>
                                        <option value="Cliente" <?php if($row['status']=='Cliente') echo 'selected'; ?>>Cliente</option>
                                        <option value="No aplica" <?php if($row['status']=='No aplica') echo 'selected'; ?>>No aplica</option>
                                    </select>
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

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inicialización del DataTable
    $('#tablaProspectos').DataTable({
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        columnDefs: [
            { targets: [5, 7], orderable: false } // Desactivar orden en mensaje y acciones
        ]
    });

    // Gráfica
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Nueva solicitud', 'En proceso', 'Cliente', 'No aplica'],
            datasets: [{
                data: <?php echo json_encode(array_values($data_grafica)); ?>,
                backgroundColor: ['#0d6efd', '#ffc107', '#198754', '#dc3545'],
                borderRadius: 5
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
});

function verMensaje(msg) {
    Swal.fire({ title: 'Mensaje Completo', text: msg, confirmButtonColor: '#d3122a' });
}

function cambiarEstatus(id, status) {
    fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire({ icon: 'success', title: '¡Actualizado!', showConfirmButton: false, timer: 1000 })
            .then(() => location.reload());
        }
    });
}
</script>
</body>
</html>