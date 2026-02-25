<?php
// 1. CONFIGURACIÓN DE RUTAS Y DATOS
$base_url = "/omega/"; 
$servicios_res = getItems($conn, 'servicios'); 
$servicios_array = [];
if ($servicios_res) { 
    while ($row = $servicios_res->fetch_assoc()) { 
        $servicios_array[] = $row; 
    } 
}
?>

<div class="modal fade" id="modalZoomServicio" tabindex="-1" aria-hidden="true" style="z-index: 11000;">
    <div class="modal-dialog modal-fullscreen d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.9) !important;">
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal">
            <i class="bi bi-x"></i>
        </button>
        <div class="container text-center">
            <img src="" id="imgZoomTargetServicio" class="img-fluid" style="max-height: 90vh; max-width: 90vw; border-radius: 5px; object-fit: contain;">
        </div>
    </div>
</div>

<section class="servicios-section" id="servicios">
    <img src="assets/flechas/2.png" class="deco-arrow_2 deco-top-left_2" alt="decoracion">
    <img src="assets/flechas/4.jpg" class="deco-arrow_2 deco-bottom-right_2" alt="decoracion">
    
    <div class="container-services">
        <h2 class="section-title">Servicios</h2>
        
        <div class="services-wrapper">
            <?php foreach ($servicios_array as $s): ?>
            <div class="service-item">
                <div class="service-image">
                    <img src="<?php echo $base_url . $s['imagen_url']; ?>" alt="<?php echo htmlspecialchars($s['titulo']); ?>" loading="lazy">
                </div>
                <div class="service-info text-start">
                    <span class="service-tags"><?php echo htmlspecialchars($s['subtitulos_rojos']); ?></span>
                    <h4 style="color: white;"><?php echo htmlspecialchars($s['titulo']); ?></h4>
                    <p class="text-truncate-3"><?php echo nl2br(htmlspecialchars($s['descripcion'])); ?></p>
                    <button class="btn-ver-mas" data-bs-toggle="modal" data-bs-target="#servicioModal<?php echo (int)$s['id']; ?>">
                        VER MÁS <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php foreach ($servicios_array as $s): ?>
<div class="modal fade" id="servicioModal<?php echo (int)$s['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden modal-grande-personalizado bg-dark-custom">
            
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x"></i>
            </button>

            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-lg-7 bg-black contenedor-img-modal">
                        <div id="carSer<?php echo $s['id']; ?>" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="4000">
                            <div class="carousel-inner h-100">
                                <div class="carousel-item active h-100">
                                    <div class="img-wrapper-fijo cursor-zoom" onclick="zoomServicio('<?php echo $s['imagen_url']; ?>')">
                                        <img src="<?php echo $base_url . $s['imagen_url']; ?>" alt="Imagen principal" class="img-completa-modal">
                                    </div>
                                </div>
                                <?php 
                                $sid = (int)$s['id'];
                                $gal = $conn->query("SELECT * FROM servicio_galeria WHERE servicio_id = $sid");
                                while($img = $gal->fetch_assoc()): ?>
                                <div class="carousel-item h-100">
                                    <div class="img-wrapper-fijo cursor-zoom" onclick="zoomServicio('<?php echo $img['ruta_imagen']; ?>')">
                                        <img src="<?php echo $base_url . $img['ruta_imagen']; ?>" alt="Imagen galería" class="img-completa-modal">
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>

                            <?php if($gal->num_rows > 0): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carSer<?php echo $s['id']; ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carSer<?php echo $s['id']; ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 p-4 p-md-5 col-info-scroll text-start">
                        <div class="info-modal-content">
                            <span class="text-danger fw-bold small text-uppercase mb-2 d-block">
                                <?php echo htmlspecialchars($s['subtitulos_rojos']); ?>
                            </span>

                            <h2 class="fw-bold mt-2 mb-4 modal-title-custom">
                                <?php echo htmlspecialchars($s['titulo']); ?>
                            </h2>

                            <hr class="border-danger opacity-100 mb-4" style="width: 50px; border-width: 3px;">

                            <div class="txt-desc-larga">
                                <p class="text-light-gray fs-5" style="line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($s['descripcion_larga'] ?? '')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 
</div>
<?php endforeach; ?>

<style>
/* --- ESTILOS GENERALES Y COLORES PERSONALIZADOS --- */
:root {
    --primary-red: #d3122a;
    --dark-bg: rgba(52, 52, 52, 1); 
    --menu-text: rgba(130, 130, 130, 1);
    --card-bg: rgba(60, 60, 60, 1); 
}

/* --- SECCIÓN FRONT / PORTADA --- */
.servicios-section { 
    background-color: var(--dark-bg); 
    padding: 80px 0; 
    position: relative; 
    overflow: hidden; 
    color: var(--menu-text); 
}

/* ESTILOS PARA LAS FLECHAS DECORATIVAS */
.deco-arrow_2 {
    position: absolute;
    pointer-events: none; 
    z-index: 1; 
    opacity: 0.9; 
}

.deco-top-left_2 {
    top: -50px;
    right: -50px;
    width: 350px; 
}

.deco-bottom-right_2 {
    bottom: -20px;
    left: -20px;
    width: 300px;
    mix-blend-mode: screen; 
}

@media (max-width: 768px) {
    .deco-arrow_2 {
        width: 150px;
        opacity: 0.5;
    }
}

.container-services { max-width: 1200px; margin: 0 auto; padding: 0 20px; z-index: 5; position: relative; }

.section-title { 
    font-size: clamp(2rem, 5vw, 3.5rem); 
    font-weight: 900; 
    color: var(--menu-text); 
    text-align: left; 
    margin-bottom: 50px; 
    text-transform: uppercase; 
}

.services-wrapper { display: flex; flex-direction: column; gap: 30px; }

/* Item de servicio original de la portada */
.service-item { 
    display: flex; 
    flex-direction: column; 
    background: var(--card-bg); 
    border-radius: 15px; 
    overflow: hidden; 
    border: 1px solid rgba(255,255,255,0.05); 
    transition: all 0.4s ease; 
}

@media (min-width: 768px) { 
    .service-item { flex-direction: row; height: 320px; } 
    .service-image { flex: 0 0 400px; } 
}

/* Comportamiento original sin zoom forzado */
.service-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
.service-item:hover { transform: translateY(-5px); border-color: var(--primary-red); }

.service-info { 
    padding: 30px; 
    display: flex; 
    flex-direction: column; 
    justify-content: center; 
    flex-grow: 1; 
    text-align: left; 
}

.service-tags{
    color: #d3122a !important;
}

.service-info h4 { 
    font-size: 1.8rem; 
    font-weight: 800; 
    margin-bottom: 15px; 
    color: var(--menu-text); 
}

.text-truncate-3 { 
    color: rgba(180, 180, 180, 1); 
    margin-bottom: 20px; 
}

.btn-ver-mas { 
    align-self: flex-start; 
    background: transparent; 
    border: 2px solid var(--primary-red); 
    color: var(--primary-red); 
    padding: 10px 25px; 
    font-weight: 700; 
    border-radius: 5px; 
    transition: 0.3s; 
}

.btn-ver-mas:hover { 
    background: var(--primary-red); 
    color: white; 
}

/* --- ESTILOS DEL MODAL (IMAGEN COMPLETA) --- */
.img-wrapper-fijo {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #000; 
}

.img-completa-modal {
    max-width: 100%;
    max-height: 100%;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important; 
}

.cursor-zoom { cursor: zoom-in; }
.bg-dark-custom { background-color: var(--dark-bg) !important; }
.modal-title-custom { color: var(--menu-text) !important; }
.text-light-gray { color: rgba(180, 180, 180, 1); }

@media (min-width: 992px) {
    .modal-grande-personalizado { height: 80vh; min-height: 600px; }
    .contenedor-img-modal { height: 80vh; min-height: 600px; }
    .col-info-scroll { 
        height: 80vh; 
        min-height: 600px; 
        overflow-y: auto; 
        background-color: var(--dark-bg); 
        text-align: left;
    }
}

.btn-close-custom { 
    position: absolute; 
    top: 20px; 
    right: 20px; 
    z-index: 1070; 
    width: 45px; 
    height: 45px; 
    background-color: var(--primary-red); 
    color: white !important; 
    border: none; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 1.8rem; 
    transition: 0.3s; 
}
</style>

<script>
/**
 * Abre el modal de zoom con la imagen seleccionada
 */
function zoomServicio(url) {
    const imgTarget = document.getElementById('imgZoomTargetServicio');
    imgTarget.src = "<?php echo $base_url; ?>" + url;
    const modalZoom = new bootstrap.Modal(document.getElementById('modalZoomServicio'));
    modalZoom.show();
}

/**
 * Asegura que el autoplay de los carruseles se reinicie al abrir el modal
 */
document.addEventListener('DOMContentLoaded', function () {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            const carousel = this.querySelector('.carousel');
            if (carousel) {
                const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);
                bsCarousel.cycle();
            }
        });
    });
});
</script>