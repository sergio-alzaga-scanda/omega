<?php 
require_once 'config/db.php'; 
require_once 'models/DataModel.php'; 

// Lógica básica para detectar la página (puedes ajustarla según tu sistema de rutas)
$current_page = basename($_SERVER['PHP_SELF'], ".php");
$section = isset($_GET['p']) ? $_GET['p'] : 'home'; 

// Configuración de Metadatos según tu tabla
$seo = [
    'home' => [
        'title' => 'Agencia BTL en México | Experiencias únicas | Primacía',
        'description' => '15 años creando experiencias de marca que conectan, impactan y generan resultados. Activaciones BTL, stands, eventos corporativos y experiencias digitales en todo México.',
        'keywords' => 'estrategia CX, agencia btl méxico, activaciones de marca, experiencias de marca, Diseño de stands , eventos corporativos, marketing experiencial, agencia btl cdmx, eventos empresariales, experiencias digitales, sampling promocional, btl mexico, btl cdmx, agencia btl, agencia de activaciones , eventos de marca, experiencias de marca, Brand Experience, Experiencias únicas, Primacía, Agencia de marketing, Agencia de eventos, Agencia de publicidad',
        'h1' => 'PRIMACÍA | Agencia BTL en México'
    ],
    'servicios' => [
        'title' => 'Experiencias BTL que conectan y venden | Primacía',
        'description' => 'Activaciones, experiencias digitales, eventos corporativos y stands personalizados. Soluciones BTL estratégicas con ejecución impecable.',
        'keywords' => 'Experiencias únicas, Primacía, contratar agencia BTL, agencia BTL en México, agencia BTL CDMX, cotizar activación de marca, producción de eventos empresariales, proveedor de stands para exposiciones, empresa de activaciones promocionales, agencia de experiential marketing, empresa de eventos corporativos',
        'h1' => 'PRIMACÍA | Agencia BTL en México | Activaciones, Stands, Eventos Corporativos y Experiencias Digitales'
    ],
    'casos-de-exito' => [
        'title' => 'Casos de éxito en marketing BTL | Primacía',
        'description' => 'Descubre proyectos de activaciones, stands y experiencias digitales que conectaron con miles de personas y generaron resultados reales.',
        'keywords' => 'Experiencias únicas, Primacía, agencia btl, servicios btl, marketing experiencial, experiencias de marca, activaciones de marca, stands para exposiciones, eventos corporativos, experiencias digitales, agencia btl cdmx, producción de eventos, sampling promocional, experiential marketing méxico',
        'h1' => 'PRIMACÍA | Experiencias reales que transforman marcas'
    ],
    'contacto' => [
        'title' => 'Cotiza tu Activación o Evento BTL | Primacía',
        'description' => '¿Listo para activar tu marca? Solicita una cotización para activaciones BTL, stands, eventos corporativos y experiencias digitales con expertos en marketing experiencial.',
        'keywords' => 'cotizar activación BTL, cotizar evento corporativo, agencia marketing experiencial México, proveedor stands, presupuesto experiencias de marca, contacto agencia btl, cotizar activación, cotizar stand, agencia eventos corporativos, marketing experiencial méxico, proveedor btl, producción de experiencias, Primacía contacto, Experiencias únicas, Primacía',
        'h1' => 'PRIMACÍA | Hablemos de tu próxima experiencia de marca'
    ],
    'blog' => [
        'title' => 'Tendencias, estrategia y experiencias que inspiran',
        'description' => 'Descubre tendencias, estrategias y mejores prácticas en marketing experiencial, activaciones de marca y eventos corporativos.',
        'keywords' => 'blog btl, marketing experiencial, tendencias activaciones, estrategia eventos, experiential marketing méxico, Experiencias únicas, Primacía, marketing BTLm, activaciones de marca, experiencias de marca, estrategias BTL, tendencias marketing experiencial',
        'h1' => 'PRIMACÍA | Blog BTL y Marketing Experiencial'
    ]
];

// Seleccionar la data actual (por defecto home)
$current_seo = isset($seo[$section]) ? $seo[$section] : $seo['home'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $current_seo['title']; ?></title>
    <meta name="description" content="<?php echo $current_seo['description']; ?>">
    <meta name="keywords" content="<?php echo $current_seo['keywords']; ?>">
    <link rel="canonical" href="https://www.primacia.com.mx/<?php echo ($section !== 'home') ? $section . '/' : ''; ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { margin: 0; font-family: sans-serif; }</style>
</head>
<body>
    <h1 class="visually-hidden"><?php echo $current_seo['h1']; ?></h1>

    <?php include 'views/sections/header.php'; ?>
    
    <?php 
    // Si usas una sola página con IDs, mantén todos los includes. 
    // Si usas páginas separadas, podrías condicionar qué include mostrar aquí.
    include 'views/sections/que_es.php'; 
    include 'views/sections/servicios.php'; 
    include 'views/sections/casos_exito.php'; 
    include 'views/sections/clientes.php'; 
    include 'views/sections/contacto.php'; 
    include 'views/sections/pie.php'; 
    ?>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5">
                <div class="text-center mb-4">
                    
                    <h5 class="fw-bold">GESTIÓN DEL SITIO</h5>
                    <p class="text-muted small">Ingresa tus credenciales para continuar</p>
                </div>
                <form id="loginForm">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">USUARIO / EMAIL</label>
                        <input type="email" id="loginEmail" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">CONTRASEÑA</label>
                        <input type="password" id="loginPass" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <button type="submit" class="btn w-100 p-3 fw-bold text-white shadow-sm" style="background-color: var(--brand-red); border-radius: 10px;">
                        INICIAR SESIÓN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>