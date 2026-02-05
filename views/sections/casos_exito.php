<?php
// 1. CONFIGURACIÓN DE RUTAS (Ajusta omega si cambia el nombre de la carpeta)
$base_url = "/omega/"; 

// 2. OBTENCIÓN DE DATOS
$casos_res = getItems($conn, 'casos_exito');
$casos_array = [];

if ($casos_res) {
    while ($row = $casos_res->fetch_assoc()) {
        $casos_array[] = $row;
    }
}
?>

<div class="modal fade" id="modalZoomImagen" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-fullscreen d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.95) !important;">
        <button type="button" class="btn-close-zoom shadow-lg" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> CERRAR VISTA
        </button>
        <div class="container text-center">
            <img src="" id="imgZoomTarget" class="img-fluid" style="max-height: 85vh; border-radius: 10px; box-shadow: 0 0 50px rgba(0,0,0,1);">
        </div>
    </div>
</div>

<section class="exito-slider-section" id="casos">
    <div class="exito-slider-container">
        <h2 class="exito-slider-title text-center text-md-start">Casos de éxito</h2>

        <div class="exito-slider-wrapper">
            <button class="exito-nav-btn exito-prev d-none d-md-block" id="exitoPrev">‹</button>

            <div class="exito-slider-viewport">
                <div class="exito-slider-track" id="exitoTrack">
                    <?php foreach ($casos_array as $c): ?>
                        <div class="exito-slide">
                            <div class="exito-card" data-bs-toggle="modal" data-bs-target="#exitoModal<?php echo (int)$c['id']; ?>">
                                <div class="exito-card-img-container">
                                    <img src="<?php echo $base_url . htmlspecialchars($c['imagen_url'] ?? ''); ?>" alt="Portada">
                                </div>
                                <div class="exito-card-body">
                                    <h4><?php echo htmlspecialchars($c['titulo'] ?? ''); ?></h4>
                                    <p class="text-truncate-2"><?php echo htmlspecialchars($c['descripcion_corta'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="exito-nav-btn exito-next d-none d-md-block" id="exitoNext">›</button>
        </div>
    </div>
</section>

<?php foreach ($casos_array as $c): ?>
<div class="modal fade" id="exitoModal<?php echo (int)$c['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered mx-auto modal-ultra-wide">
        <div class="modal-content border-0 shadow-2xl overflow-hidden shadow-master">
            
            <button type="button" class="btn-close-master shadow" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="modal-body p-0 h-100">
                <div class="row g-0 h-100">
                    <div class="col-12 col-md-7 bg-black d-flex align-items-center h-md-100 container-visual">
                        <div id="carousel<?php echo $c['id']; ?>" class="carousel slide w-100 h-100" data-bs-ride="carousel">
                            <div class="carousel-inner h-100">
                                <div class="carousel-item active h-100">
                                    <div class="full-frame-img cursor-zoom" onclick="zoomImage('<?php echo $c['imagen_url']; ?>')">
                                        <img src="<?php echo $base_url . htmlspecialchars($c['imagen_url'] ?? ''); ?>" alt="Portada">
                                    </div>
                                </div>
                                <?php 
                                $cid = (int)$c['id'];
                                $gal = $conn->query("SELECT * FROM caso_galeria WHERE caso_id = $cid");
                                while($img = $gal->fetch_assoc()):
                                ?>
                                <div class="carousel-item h-100">
                                    <div class="full-frame-img cursor-zoom" onclick="zoomImage('<?php echo $img['ruta_imagen']; ?>')">
                                        <img src="<?php echo $base_url . htmlspecialchars($img['ruta_imagen']); ?>" alt="Galería">
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php if($gal->num_rows > 0): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $c['id']; ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $c['id']; ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12 col-md-5 p-4 p-md-5 bg-white d-flex flex-column h-md-100">
                        <div class="flex-grow-1 overflow-hidden d-flex flex-column">
                            <h2 class="fw-bold text-danger mb-3 display-6"><?php echo htmlspecialchars($c['titulo'] ?? ''); ?></h2>
                            <div class="custom-scroll pe-2 mb-4 scroll-texto-detalle">
                                <p class="text-muted fs-5 lh-base"><?php echo nl2br(htmlspecialchars($c['descripcion_larga'] ?? '')); ?></p>
                            </div>
                        </div>
                        <?php if (!empty($c['comentario_cliente'])): ?>
                        <div class="mt-auto p-4 bg-light rounded-4 border-start border-danger border-5 shadow-sm">
                            <p class="fst-italic text-secondary mb-2 small">“<?php echo htmlspecialchars($c['comentario_cliente'] ?? ''); ?>”</p>
                            <small class="text-dark fw-bold">—— <?php echo htmlspecialchars($c['nombre_cliente'] ?? ''); ?></small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<style>
/* --- ESTILOS DE ESTRUCTURA Y MÓVIL --- */
.modal-ultra-wide { max-width: 98% !important; margin: 10px auto !important; }

@media (max-width: 768px) {
    .modal-content { height: 95vh !important; }
    .container-visual { min-height: 45%; }
    .scroll-texto-detalle { overflow-y: auto; max-height: 35vh; }
}

@media (min-width: 769px) {
    .modal-content { height: 85vh !important; }
    .h-md-100 { height: 100% !important; }
    .scroll-texto-detalle { overflow-y: auto; flex-grow: 1; }
}

/* --- IMÁGENES --- */
.full-frame-img { width: 100%; height: 100%; background: #000; }
.full-frame-img img { width: 100%; height: 100%; object-fit: cover; }
.cursor-zoom { cursor: zoom-in; }

/* --- BOTONES DE CIERRE --- */
.btn-close-master {
    position: absolute; top: 15px; right: 15px; z-index: 1050;
    width: 50px; height: 50px; background: #d3122a; color: white;
    border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; cursor: pointer;
}

.btn-close-zoom {
    position: absolute; top: 20px; right: 20px; z-index: 10001;
    background: #d3122a; color: #fff; border: none; padding: 12px 30px;
    border-radius: 50px; font-weight: 800;
}

@media (max-width: 768px) {
    .btn-close-zoom { top: auto; bottom: 30px; right: 50%; transform: translateX(50%); width: 90%; text-align: center; }
}

/* --- SLIDER Y CARDS --- */
.exito-slider-section { padding: 60px 0; background: #fff; overflow: hidden; }
.exito-slider-container { max-width: 1440px; margin: auto; padding: 0 20px; }
.exito-slider-title { color: #d3122a; font-weight: 800; margin-bottom: 40px; }
.exito-slider-viewport { width: 100%; overflow: hidden; touch-action: pan-y pinch-zoom; }
.exito-slider-track { display: flex; gap: 30px; transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1); }
.exito-slide { flex: 0 0 100%; }

@media (min-width: 768px) { .exito-slide { flex: 0 0 calc(50% - 15px); } .exito-slider-container { padding: 0 80px; } }
@media (min-width: 1200px) { .exito-slide { flex: 0 0 calc(33.333% - 20px); } }

.exito-card-img-container { width: 100%; height: 350px; overflow: hidden; }
.exito-card-img-container img { width: 100%; height: 100%; object-fit: cover; }
.text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3em; }

.exito-nav-btn { position: absolute; top: 40%; border: none; background: transparent; font-size: 60px; color: #d3122a; cursor: pointer; opacity: 0.4; }
.exito-prev { left: -60px; } .exito-next { right: -60px; }

.custom-scroll::-webkit-scrollbar { width: 5px; }
.custom-scroll::-webkit-scrollbar-thumb { background: #d3122a; border-radius: 10px; }
</style>



<script>
const PROJECT_BASE = "<?php echo $base_url; ?>";

function zoomImage(url) {
    const modalZoomElement = document.getElementById('modalZoomImagen');
    const modalZoom = new bootstrap.Modal(modalZoomElement);
    const imgTarget = document.getElementById('imgZoomTarget');

    // Construir ruta absoluta para Omega
    let cleanUrl = (PROJECT_BASE + url).replace(/\/+/g, '/');
    imgTarget.src = window.location.origin + cleanUrl; 

    modalZoom.show();
}

window.addEventListener('load', () => {
    const track = document.getElementById('exitoTrack');
    const next = document.getElementById('exitoNext');
    const prev = document.getElementById('exitoPrev');
    if (!track) return;

    const slides = document.querySelectorAll('.exito-slide');
    const gap = 30;
    let index = 0;

    // Swipe Táctil
    let touchStartX = 0;
    track.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX, {passive: true});
    track.addEventListener('touchend', e => {
        let touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 60) next.click();
        if (touchEndX - touchStartX > 60) prev.click();
    }, {passive: true});

    function moveSlider() {
        const itemsPerView = window.innerWidth < 768 ? 1 : (window.innerWidth < 1200 ? 2 : 3);
        const maxIndex = Math.max(0, slides.length - itemsPerView);
        if (index > maxIndex) index = maxIndex;
        
        const slideWidth = slides[0].offsetWidth + gap;
        track.style.transform = `translateX(-${index * slideWidth}px)`;
        
        if(prev) prev.style.opacity = index === 0 ? "0.1" : "0.5";
        if(next) next.style.opacity = index >= maxIndex ? "0.1" : "0.5";
    }

    next.addEventListener('click', () => {
        const itemsPerView = window.innerWidth < 768 ? 1 : (window.innerWidth < 1200 ? 2 : 3);
        if (index < slides.length - itemsPerView) { index++; moveSlider(); }
    });

    prev.addEventListener('click', () => { if (index > 0) { index--; moveSlider(); } });

    window.addEventListener('resize', moveSlider);
    moveSlider();
});
</script>