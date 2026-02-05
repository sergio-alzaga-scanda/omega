<?php 
// Obtenemos la configuración dinámica desde la base de datos
$hero = getHeroConfig($conn); 

// Definimos la ruta del logo: si existe en BD lo usamos, si no, el de respaldo
$ruta_logo_publico = !empty($hero['logo_url']) ? $hero['logo_url'] : 'assets/logo.png';
?>

<style>
  :root {
    --bg-dark: #2d2d2d; /* fondo fijo del navbar */
    --brand-red: <?php echo $hero['color_primario']; ?>; /* mantiene color principal */
}

.navbar {
    position: fixed; /* siempre visible */
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 5%;
    box-sizing: border-box;
    background-color: var(--bg-dark);
    z-index: 1000; /* encima del contenido */
}

.brand-group {
    display: flex;
    align-items: center;
}

.logo-primicia {
    height: 45px;
    margin-left: 140px;
    transition: all 0.3s ease;
    z-index: 1001;
    /* Aseguramos que el logo no se deforme si las dimensiones cambian */
    object-fit: contain; 
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
}

.btn-blog-outline {
    border: 1px solid var(--brand-red);
    padding: 7px 20px;
    border-radius: 5px;
    color: var(--brand-red) !important;
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

.hamburger.active span:nth-child(1) { transform: rotate(-45deg) translate(-5px, 6px); }
.hamburger.active span:nth-child(2) { opacity: 0; }
.hamburger.active span:nth-child(3) { transform: rotate(45deg) translate(-5px, -6px); }

/* Mobile */
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
        width: 70%;
        height: 100vh;
        background-color: var(--bg-dark);
        flex-direction: column;
        justify-content: center;
        gap: 40px;
        box-shadow: -10px 0 20px rgba(0,0,0,0.5);
        transition: right 0.3s ease;
    }

    .nav-links.active {
        right: 0;
    }

    .nav-links a {
        font-size: 18px;
    }
}

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
}

/* Clase que se activa al hacer scroll */
.brand-shape-irregular.hidden {
    opacity: 0;
    transform: translateY(-50px); /* opcional, efecto de subida */
    pointer-events: none; /* que no interfiera */
}

</style>

<nav class="navbar">
    <div class="brand-group">
        <img src="<?php echo $ruta_logo_publico; ?>" class="logo-primicia" alt="Primicia">
    </div>

    <button class="hamburger" id="hamb-btn">
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
    /* El JavaScript permanece igual para mantener la funcionalidad del menú móvil */
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('hamb-btn');
        const menu = document.getElementById('nav-menu');
        const links = document.querySelectorAll('.nav-links a');

        const toggleMenu = () => {
            btn.classList.toggle('active');
            menu.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : 'auto';
        };

        btn.addEventListener('click', toggleMenu);

        links.forEach(link => {
            link.addEventListener('click', () => {
                btn.classList.remove('active');
                menu.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });
    });
</script>