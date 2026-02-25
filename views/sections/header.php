<?php $hero = getHeroConfig($conn); ?>

<style>
    :root {
        --bg-dark: <?php echo $hero['color_fondo']; ?>;
        --brand-red: <?php echo $hero['color_primario']; ?>;
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
        margin-bottom: 40px;
    }

    /* --- CAMBIOS PRINCIPALES A PARTIR DE AQUÍ --- */

    .hero-container {
        width: 100%;
        height: 100vh; /* 100vh fuerza al contenedor a medir exactamente el 100% del alto de la pantalla del usuario */
        display: flex;
        flex-direction: column;
        background-color: #000;
        overflow: hidden; /* Esto es un seguro para evitar que cualquier elemento hijo cause scroll */
    }

    .hero-main-text {
        flex: 1; /* Al estar en una columna flex, esto le dice que tome TODO el espacio que sobra debajo de tu menú */
        display: flex;
        align-items: center; 
        justify-content: center; 
        overflow: hidden;
        /* padding: 20px; Puedes descomentar esto si quieres que el video no pegue exactamente en los bordes de la pantalla */
    }

    .hero-video-box {
        width: 100%;
        height: 100%; /* Toma todo el espacio que le da hero-main-text */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-video-box video {
        width: 100%;
        height: 100%;
        /* 'contain' hace la magia: ajusta el video sin deformarlo para que quepa en la caja, dejando bandas negras si es necesario */
        object-fit: contain; 
    }
</style>
<div style="padding-bottom: 3%;">
<?php include 'views/sections/menu.php'; ?>
</div>
<div class="hero-container">
    

    <div class="hero-main-text">
        <div class="hero-video-box">
            <video autoplay muted loop playsinline controls>
                <source src="./assets/1.mp4" type="video/mp4">
                Tu navegador no soporta videos.
            </video>
        </div>
    </div>
</div>