<?php 
require_once 'config/db.php'; 
require_once 'models/DataModel.php'; 

// Lógica para SEO
$section = 'blog'; 

$seo_blog = [
    'title' => 'Tendencias, estrategia y experiencias que inspiran | Primacía',
    'description' => 'Descubre tendencias, estrategias y mejores prácticas en marketing experiencial, activaciones de marca y eventos corporativos.',
    'keywords' => 'blog btl, marketing experiencial, tendencias activaciones, estrategia eventos, experiential marketing méxico, Experiencias únicas, Primacía, marketing BTLm, activaciones de marca, experiencias de marca, estrategias BTL, tendencias marketing experiencial',
    'h1' => 'PRIMACÍA | Blog BTL y Marketing Experiencial'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $seo_blog['title']; ?></title>
    <meta name="description" content="<?php echo $seo_blog['description']; ?>">
    <meta name="keywords" content="<?php echo $seo_blog['keywords']; ?>">
    <link rel="canonical" href="https://www.primacia.com.mx/blog/">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Copiamos el estilo exacto del index para consistencia */
        body { margin: 0; font-family: sans-serif; background-color: #2d2d2d; }
        :root { --brand-red: #d3122a; }
        
        /* Ajuste para que el contenido del blog no quede oculto bajo el navbar fijo */
        .blog-container-wrapper {
            
            min-height: 80vh;
        }
    </style>
</head>
<body>
    <h1 class="visually-hidden"><?php echo $seo_blog['h1']; ?></h1>

    <?php 
    /**
     * IMPORTANTE: En tu index incluyes 'header.php'. 
     * Si tu navbar (menú) está dentro de 'header.php', usa ese.
     * Si están separados, incluye ambos en el mismo orden.
     */
    include 'views/sections/menu.php'; 
    ?>
    
    <div class="blog-container-wrapper">
        <?php include 'views/sections/blog.php'; ?>
    </div>

    <?php 
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
<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    // 1. Evitamos que el formulario recargue la página entera
    event.preventDefault();

    // 2. Obtenemos los valores de los inputs usando sus IDs
    const emailValue = document.getElementById('loginEmail').value;
    const passwordValue = document.getElementById('loginPass').value;

    // 3. Preparamos los datos para enviarlos por POST tal como los espera PHP
    const formData = new FormData();
    formData.append('email', emailValue);
    formData.append('password', passwordValue);

    // 4. Hacemos la petición al controlador
    fetch('controllers/auth_controller.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Convertimos la respuesta de PHP a JSON
    .then(data => {
        if (data.success) {
            // ¡Acceso concedido! 
            // Aquí puedes redirigir a la página de inicio o dashboard
            alert(data.message); // Puedes cambiar esto por un Toast o SweetAlert
            window.location.href = './admin/dashboard.php'; // <-- ACTUALIZA ESTA RUTA
        } else {
            // Acceso denegado o error (Muestra el mensaje del PHP)
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        alert('Hubo un problema al intentar conectar con el servidor.');
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
</body>
</html>