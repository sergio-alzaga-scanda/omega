<?php $hero = getHeroConfig($conn); ?>

<style>
    :root {
        --bg-dark: <?php echo $hero['color_fondo']; ?>;
        --brand-red: <?php echo $hero['color_primario']; ?>;
    }

    .hero-container {
        background-color: var(--bg-dark);
        /* Cambiado a min-height para que crezca si hay video */
        min-height: 80vh; 
        width: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 100px 0; /* Espacio para el menú y respiro inferior */
    }

    .brand-shape-irregular {
        position: absolute;
        top: 0;
        left: 0;
        width: 180px;
        height: 320px;
        background-color: var(--brand-red);
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
        margin-bottom: 30px;
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
        margin-bottom: 40px; /* Espacio antes del video */
    }

    /* Contenedor del video en recuadro */
    .hero-video-box {
        width: 100%;
        max-width: 700px; /* Tamaño del recuadro */
        margin: 0 auto;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .hero-video-box video {
        width: 100%;
        display: block;
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

        <?php if(!empty($hero['video_url'])): ?>
            <div class="hero-video-box">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $hero['video_url']; ?>" type="video/mp4">
                    Tu navegador no soporta videos.
                </video>
            </div>
        <?php endif; ?>
    </div>
</div>