<?php
// 1. CONFIGURACIÓN DE RUTAS
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

<div class="modal fade" id="modalZoomImagen" tabindex="-1" aria-hidden="true" style="z-index: 11000;">
    <div class="modal-dialog modal-fullscreen d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.9) !important;">
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal">
            <i class="bi bi-x"></i>
        </button>
        <div class="container text-center">
            <img src="" id="imgZoomTarget" class="img-fluid" style="max-height: 85vh; border-radius: 10px;">
        </div>
    </div>
</div>

<section class="exito-slider-section" id="casos">
    <div class="exito-slider-container">
        <h2 class="exito-slider-title text-center text-md-start">Casos de éxito</h2>

        <div class="exito-slider-wrapper">
            <button class="exito-nav-btn exito-prev" id="exitoPrev">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

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

            <button class="exito-nav-btn exito-next" id="exitoNext">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<?php foreach ($casos_array as $c): ?>
<div class="modal fade" id="exitoModal<?php echo (int)$c['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden modal-height-fija">
            
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x"></i>
            </button>

            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-md-7 bg-black contenedor-visual-modal">
                        <div id="carousel<?php echo $c['id']; ?>" class="carousel slide h-100" data-bs-ride="carousel">
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

                    <div class="col-12 col-md-5 p-4 p-md-5 bg-white info-modal-scroll">
                        <h2 class="fw-bold text-danger mb-3"><?php echo htmlspecialchars($c['titulo'] ?? ''); ?></h2>
                        <div class="texto-detalle-completo">
                            <p class="text-muted fs-5"><?php echo nl2br(htmlspecialchars($c['descripcion_larga'] ?? '')); ?></p>
                            
                            <?php if (!empty($c['comentario_cliente'])): ?>
                            <div class="p-3 mt-4 bg-light rounded border-start border-danger border-4">
                                <p class="fst-italic mb-1 small">“<?php echo htmlspecialchars($c['comentario_cliente'] ?? ''); ?>”</p>
                                <small class="fw-bold">- <?php echo htmlspecialchars($c['nombre_cliente'] ?? ''); ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<style>
/* --- ESTILOS ORIGINALES DEL LISTADO (RESTAURADOS) --- */
.exito-slider-section { padding: 60px 0; overflow: hidden; }
.exito-slider-container { max-width: 1200px; margin: auto; position: relative; padding: 0 15px; }
.exito-slider-wrapper { position: relative; }
.exito-slider-viewport { overflow: hidden; }
.exito-slider-track { display: flex; gap: 20px; transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1); }
.exito-slide { flex: 0 0 calc(33.333% - 14px); min-width: 280px; }

@media (max-width: 992px) { .exito-slide { flex: 0 0 calc(50% - 10px); } }
@media (max-width: 768px) { .exito-slide { flex: 0 0 100%; } }

.exito-card { background: #fff; border-radius: 12px; overflow: hidden; cursor: pointer; border: 1px solid #eee; height: 100%; }
.exito-card-img-container { height: 250px; }
.exito-card-img-container img { width: 100%; height: 100%; object-fit: cover; }
.exito-card-body { padding: 20px; }
.text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

/* FLECHAS ORIGINALES */
.exito-nav-btn {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 50px; height: 50px; background: transparent; color: #d3122a;
    border: none; display: flex; align-items: center; justify-content: center;
    z-index: 100; cursor: pointer; transition: all 0.2s ease;
}
.exito-nav-btn svg { width: 100%; height: 100%; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.2)); }
.exito-prev { left: -60px; }
.exito-next { right: -60px; }
.exito-nav-btn:hover { transform: translateY(-50%) scale(1.1); color: #b00f23; }
.exito-nav-btn:disabled { opacity: 0.1; cursor: default; }

@media (max-width: 1100px) {
    .exito-prev { left: -10px; color: white; }
    .exito-next { right: -10px; color: white; }
    .exito-nav-btn svg { filter: drop-shadow(0px 0px 5px rgba(0,0,0,0.8)); }
}

/* --- ESTILOS DEL MODAL (AMPLIADO Y FIJO) --- */
@media (min-width: 768px) {
    .modal-height-fija { height: 80vh; min-height: 600px; }
    .contenedor-visual-modal, .info-modal-scroll { height: 80vh; min-height: 600px; }
    .info-modal-scroll { overflow-y: auto; } /* Scroll solo en el texto */
}

.full-frame-img { width: 100%; height: 100%; background: #000; display: flex; align-items: center; }
.full-frame-img img { width: 100%; height: 100%; object-fit: cover; }

.info-modal-scroll::-webkit-scrollbar { width: 6px; }
.info-modal-scroll::-webkit-scrollbar-thumb { background: #d3122a; border-radius: 10px; }

/* BOTÓN CERRAR PERSONALIZADO */
.btn-close-custom {
    color: #fff; position: absolute; top: 15px; right: 15px; z-index: 1060;
    width: 40px; height: 40px; background-color: #d3122a; color: white !important;
    border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.cursor-zoom { cursor: zoom-in; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('exitoTrack');
    const next = document.getElementById('exitoNext');
    const prev = document.getElementById('exitoPrev');
    const slides = document.querySelectorAll('.exito-slide');
    if (!track || slides.length === 0) return;

    let index = 0;

    function moveSlider() {
        const slideWidth = slides[0].offsetWidth + 20;
        const visibleSlides = Math.round(track.parentElement.offsetWidth / slideWidth);
        const maxIndex = slides.length - visibleSlides;

        if (index > maxIndex) index = maxIndex;
        if (index < 0) index = 0;

        track.style.transform = `translateX(-${index * slideWidth}px)`;
        prev.disabled = (index === 0);
        next.disabled = (index >= maxIndex);
    }

    next.addEventListener('click', () => { index++; moveSlider(); });
    prev.addEventListener('click', () => { index--; moveSlider(); });
    window.addEventListener('resize', moveSlider);
    moveSlider();
});

function zoomImage(url) {
    const imgTarget = document.getElementById('imgZoomTarget');
    imgTarget.src = "<?php echo $base_url; ?>" + url;
    new bootstrap.Modal(document.getElementById('modalZoomImagen')).show();
}
</script>