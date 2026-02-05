<?php $hero = getHeroConfig($conn); ?>

<style>
    :root {
        --bg-dark: <?php echo $hero['color_fondo']; ?>;
        --brand-red: <?php echo $hero['color_primario']; ?>;
    }

    .hero-container {
        background-color: var(--bg-dark);
        height: 70vh;
        width: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* La mancha roja irregular y pequeña */
    .brand-shape-irregular {
        position: absolute;
        top: 0;
        left: 0;
        width: 180px; /* Tamaño reducido */
        height: 320px;
        background-color: var(--brand-red);
        /* Curva fluida asimétrica */
        border-radius: 0 0 100% 15% / 0 0 100% 5%;
        z-index: 1;
    }

    .hero-main-text {
        z-index: 10;
        text-align: center;
        color: #fff;
        max-width: 900px;
        padding: 20px;
    }

    .hero-main-text h1 {
        font-size: clamp(1.8rem, 5vw, 4.2rem);
        font-weight: 800;
        margin-bottom: 45px;
        letter-spacing: -1px;
    }

    .btn-pill-cta {
        background-color: var(--brand-red);
        color: #fff;
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-pill-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(211, 18, 42, 0.3);
    }
</style>

<div class="hero-container">
    <div class="brand-shape-irregular"></div>
    
    <?php include 'views/sections/menu.php'; ?>

    <div class="hero-main-text">
        <h1><?php echo htmlspecialchars($hero['titulo']); ?></h1>
        <a href="#casos" class="btn-pill-cta">
            <?php echo htmlspecialchars($hero['texto_boton']); ?>
        </a>
    </div>
</div>