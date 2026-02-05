<?php
// 1. Obtener la página actual para la clase 'active'
$pagina_actual = basename($_SERVER['PHP_SELF']);

// 2. Consultar el logo desde la base de datos (tabla hero_config)
$query_logo = $conn->query("SELECT logo_url FROM hero_config WHERE id = 1");
$logo_db = $query_logo->fetch_assoc();

// Definir ruta por defecto si el campo en la BD está vacío
$ruta_logo = !empty($logo_db['logo_url']) ? "../" . $logo_db['logo_url'] : "../assets/logo-primacia.png";

// Función para determinar si el enlace debe estar activo
function es_activa($archivo, $pagina_actual) {
    return ($archivo === $pagina_actual) ? 'active' : '';
}
?>

<style>
    :root { --brand-red: #d3122a; --sidebar-dark: #121212; }
    .sidebar { min-height: 100vh; background: var(--sidebar-dark); color: white; }
    .nav-link { color: #adb5bd; margin: 5px 15px; border-radius: 8px; transition: 0.3s; }
    .nav-link.active { background: var(--brand-red) !important; color: white !important; }
    .sidebar-brand { padding: 30px; text-align: center; }
    .logo-admin { max-height: 50px; width: auto; object-fit: contain; }
</style>

<nav class="col-md-2 d-none d-md-block sidebar px-0 shadow">
    <div class="sidebar-brand">
        <img src="<?php echo $ruta_logo; ?>" alt="Primacía" class="img-fluid logo-admin">
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo es_activa('dashboard.php', $pagina_actual); ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="gestion_hero.php" class="nav-link <?php echo es_activa('gestion_hero.php', $pagina_actual); ?>">
                <i class="bi bi-layout-text-window-reverse me-2"></i> Header / Hero
            </a>
        </li>
        <li class="nav-item">
            <a href="gestion_nosotros.php" class="nav-link <?php echo es_activa('gestion_nosotros.php', $pagina_actual); ?>">
                <i class="bi bi-info-square me-2"></i> Nosotros
            </a>
        </li>
        <li class="nav-item">
    <a href="gestion_servicios.php" class="nav-link <?php echo es_activa('gestion_servicios.php', $pagina_actual); ?>">
        <i class="bi bi-briefcase me-2"></i> Servicios
    </a>
</li>
        <li class="nav-item">
            <a href="gestion_casos.php" class="nav-link <?php echo es_activa('gestion_casos.php', $pagina_actual); ?>">
                <i class="bi bi-star me-2"></i> Casos de Éxito
            </a>
        </li>
        <li class="nav-item">
            <a href="gestion_clientes.php" class="nav-link <?php echo es_activa('gestion_clientes.php', $pagina_actual); ?>">
                <i class="bi bi-buildings me-2"></i> Clientes (Logos)
            </a>
        </li>
        <li class="nav-item">
            <a href="gestion_pie.php" class="nav-link <?php echo es_activa('gestion_pie.php', $pagina_actual); ?>">
                <i class="bi bi-envelope-paper me-2"></i> Pie de Página
            </a>
        </li>
    </ul>

    <div class="mt-5 pt-5 px-4 text-center">
        <a href="logout.php" class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
        </a>
    </div>
</nav>