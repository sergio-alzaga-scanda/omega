<?php 
/**
 * ARCHIVO: views/sections/menu.php
 */
// Obtenemos la configuración dinámica desde la base de datos
$hero = getHeroConfig($conn); 

// Definimos la ruta del logo
$ruta_logo_publico = !empty($hero['logo_url']) ? $hero['logo_url'] : 'assets/logo.png';
?>

<style>
    :root {
        --bg-dark: #2d2d2d; 
        --brand-red: <?php echo $hero['color_primario']; ?>; 
        --transition-speed: 0.3s;
    }

    /* IMAGEN DECORATIVA SUPERIOR IZQUIERDA (1.png) */
    .deco-top-left {
        position: absolute;
        top: 0;
        left: 0;
        width: 300px; 
        z-index: 1002; 
        pointer-events: none;
    }

    .navbar {
        position: fixed; 
        top: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 5%;
        box-sizing: border-box;
        background-color: var(--bg-dark);
        z-index: 1000; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .brand-group {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 1003;
        /* Mueve el logo considerablemente más a la derecha */
        margin-left: 400px; 
    }

    .logo-primicia {
        height: 45px;
        transition: transform var(--transition-speed) ease;
        object-fit: contain; 
    }

    .logo-primicia:hover {
        transform: scale(1.05);
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 30px;
        transition: 0.4s ease;
        /* Ajuste de margen derecho para los enlaces */
        margin-right: 50px; 
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 1px;
        position: relative;
        transition: color var(--transition-speed) ease;
    }

    .nav-links a:hover {
        color: var(--brand-red);
    }

    /* Botón de Blog */
    .btn-blog-outline {
        border: 1px solid var(--brand-red);
        padding: 7px 20px;
        border-radius: 5px;
        color: var(--brand-red) !important;
        background-color: transparent;
        transition: all var(--transition-speed) ease !important;
    }

    .btn-blog-outline:hover {
        background-color: var(--brand-red);
        color: white !important;
        box-shadow: 0 4px 15px rgba(211, 18, 42, 0.4);
    }

    /* Hamburguesa para móvil */
    .hamburger {
        display: none;
        cursor: pointer;
        background: none;
        border: none;
        z-index: 1005;
        padding: 10px;
    }

    .hamburger span {
        display: block;
        width: 25px;
        height: 3px;
        background-color: white;
        margin: 5px 0;
        transition: 0.4s;
    }

    /* --- AJUSTES RESPONSIVOS --- */

    @media (max-width: 1400px) {
        /* Evita que el logo choque con el menú en pantallas medianas-grandes */
        .brand-group { margin-left: 200px; }
        .deco-top-left { 
            display: none; 
        }
    }
    @media (max-width: 1600px) {
        /* Evita que el logo choque con el menú en pantallas medianas-grandes */
        .brand-group { margin-left: 200px; }
        .deco-top-left { 
            display: none; 
        }
    }

    @media (max-width: 1100px) {
        .brand-group { margin-left: 100px; }
        .nav-links { gap: 15px; margin-right: 20px; }
        .deco-top-left { 
            display: none; 
        }
    }

    @media (max-width: 992px) {
        .brand-group { margin-left: 60px; }
        .nav-links { margin-right: 0; }
    }

    @media (max-width: 768px) {
        /* Ocultamos la imagen decorativa por completo */
        .deco-top-left { 
            display: none; 
        }

        .brand-group { margin-left: 0; }
        .logo-primicia { height: 35px; }
        .hamburger { display: block; }
        
        /* ... resto de tu código del menú ... */
        .nav-links {
            position: fixed;
            top: 0;
            right: -100%;
            width: 75%;
            height: 100vh;
            background-color: var(--bg-dark);
            flex-direction: column;
            justify-content: center;
            margin-right: 0;
        }
        .nav-links.active { right: 0; }
    }
</style>

<img src="assets/flechas/1.png" class="deco-top-left" alt="decoracion">

<nav class="navbar">
    <div class="brand-group">
        <a href="index.php">
            <img src="<?php echo $ruta_logo_publico; ?>" class="logo-primicia" alt="Primicia">
        </a>
    </div>

    <button class="hamburger" id="hamb-btn" aria-label="Abrir menú">
        <span></span><span></span><span></span>
    </button>

    <div class="nav-links" id="nav-menu">
        <a href="index.php#servicios">SERVICIOS</a>
        <a href="index.php#casos">CASOS DE ÉXITO</a>
        <a href="index.php#contacto">CONTACTO</a>
        <a href="./blog.php" class="btn-blog-outline">BLOG</a>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('hamb-btn');
        const menu = document.getElementById('nav-menu');
        const links = document.querySelectorAll('.nav-links a');

        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            menu.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : 'auto';
        });

        links.forEach(link => {
            link.addEventListener('click', () => {
                btn.classList.remove('active');
                menu.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });
    });
</script>