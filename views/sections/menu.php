<?php 
/**
 * ARCHIVO: views/sections/menu.php (o el nombre que uses para tu navbar)
 * Este archivo debe ser incluido en tus páginas principales.
 */

// Obtenemos la configuración dinámica desde la base de datos
// Asegúrate de que la conexión $conn esté disponible antes de este include
$hero = getHeroConfig($conn); 

// Definimos la ruta del logo: si existe en BD lo usamos, si no, el de respaldo
$ruta_logo_publico = !empty($hero['logo_url']) ? $hero['logo_url'] : 'assets/logo.png';
?>

<style>
    :root {
        --bg-dark: #2d2d2d; /* Fondo fijo del navbar */
        --brand-red: <?php echo $hero['color_primario']; ?>; /* Color dinámico de la marca */
        --transition-speed: 0.3s;
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
    }

    .logo-primicia {
        height: 45px;
        margin-left: 140px;
        transition: transform var(--transition-speed) ease;
        z-index: 1001;
        object-fit: contain; 
    }

    /* Efecto sutil al pasar el cursor sobre el logo */
    .logo-primicia:hover {
        transform: scale(1.05);
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 30px;
        transition: 0.4s ease;
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

    /* --- EFECTO DE COLOR AL PASAR EL CURSOR (HOVER) --- */
    .nav-links a:hover {
        color: var(--brand-red);
    }

    /* Línea decorativa debajo de los links (opcional, borra si no la quieres) */
    .nav-links a:not(.btn-blog-outline)::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background-color: var(--brand-red);
        transition: width var(--transition-speed) ease;
    }

    .nav-links a:not(.btn-blog-outline):hover::after {
        width: 100%;
    }

    /* --- BOTÓN DE BLOG CON EFECTO DE RELLENO --- */
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

    /* Icono hamburguesa */
    .hamburger {
        display: none;
        cursor: pointer;
        background: none;
        border: none;
        z-index: 1001;
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

    /* Animación de la hamburguesa activa */
    .hamburger.active span:nth-child(1) { transform: rotate(-45deg) translate(-5px, 6px); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: rotate(45deg) translate(-5px, -6px); }

    /* Estilos Responsivos */
    @media (max-width: 768px) {
        .logo-primicia { 
            margin-left: 0; 
            height: 35px; 
        }

        .hamburger {
            display: block;
        }

        .nav-links {
            position: fixed;
            top: 0;
            right: -100%;
            width: 75%;
            height: 100vh;
            background-color: var(--bg-dark);
            flex-direction: column;
            justify-content: center;
            gap: 40px;
            box-shadow: -10px 0 30px rgba(0,0,0,0.5);
            transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .nav-links.active {
            right: 0;
        }

        .nav-links a {
            font-size: 18px;
        }
    }

    /* Estilo de la mancha irregular del header que mencionaste antes */
    .brand-shape-irregular {
        position: absolute;
        top: 0;
        left: 0;
        width: 180px;
        height: 320px;
        background-color: var(--brand-red);
        border-radius: 0 0 100% 15% / 0 0 100% 5%;
        z-index: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
        pointer-events: none;
    }

    .brand-shape-irregular.hidden {
        opacity: 0;
        transform: translateY(-50px);
    }
</style>

<nav class="navbar">
    <div class="brand-group">
        <a href="index.php">
            <img src="<?php echo $ruta_logo_publico; ?>" class="logo-primicia" alt="Primicia">
        </a>
    </div>

    <button class="hamburger" id="hamb-btn" aria-label="Abrir menú">
        <span></span>
        <span></span>
        <span></span>
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

        // Función para abrir/cerrar el menú
        const toggleMenu = () => {
            btn.classList.toggle('active');
            menu.classList.toggle('active');
            // Bloquear el scroll cuando el menú móvil está abierto
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : 'auto';
        };

        btn.addEventListener('click', toggleMenu);

        // Cerrar el menú al hacer clic en cualquier enlace
        links.forEach(link => {
            link.addEventListener('click', () => {
                btn.classList.remove('active');
                menu.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });

        // Ocultar la mancha irregular al hacer scroll (opcional)
        window.addEventListener('scroll', () => {
            const shape = document.querySelector('.brand-shape-irregular');
            if(shape) {
                if (window.scrollY > 100) {
                    shape.classList.add('hidden');
                } else {
                    shape.classList.remove('hidden');
                }
            }
        });
    });
</script>