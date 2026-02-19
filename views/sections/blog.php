<?php
// 1. CONFIGURACIÓN DE RUTAS Y DATOS
if (!isset($base_url)) {
    $base_url = "/omega/"; 
}

// OBTENCIÓN DE DATOS DEL BLOG
$blog_res = $conn->query("SELECT * FROM blog ORDER BY orden ASC, id DESC");
$blogs_array = [];
if ($blog_res) { 
    while ($row = $blog_res->fetch_assoc()) { 
        $blogs_array[] = $row; 
    } 
}

// Función auxiliar para video de YouTube
if (!function_exists('obtenerEmbedUrlBlog')) {
    function obtenerEmbedUrlBlog($url) {
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            if (strpos($url, 'youtube.com/watch') !== false) {
                parse_str(parse_url($url, PHP_URL_QUERY), $vars);
                $id = $vars['v'] ?? '';
            } else {
                $id = basename(parse_url($url, PHP_URL_PATH));
            }
            return "https://www.youtube.com/embed/" . $id . "?autoplay=0&controls=1&enablejsapi=1&rel=0";
        }
        return $url;
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="modal fade" id="modalZoomBlog" tabindex="-1" aria-hidden="true" style="z-index: 12000;">
    <div class="modal-dialog modal-fullscreen d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.9) !important;">
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
        <div class="container text-center">
            <img src="" id="imgZoomTargetBlog" class="img-fluid" style="max-height: 85vh; border-radius: 10px;">
        </div>
    </div>
</div>

<section class="blog-section" id="blog">
    <div class="container-blog">
        <h2 class="blog-main-title">Blog</h2>
        <p class="blog-main-subtitle">Tendencias, estrategia y experiencias que inspiran.</p>

        <div class="blog-carousel-wrapper">
            <button class="blog-nav-btn blog-prev" id="blogPrev"><i class="bi bi-chevron-left"></i></button>
            
            <div class="blog-viewport">
                <div class="blog-track" id="blogTrack">
                    <?php 
                    $i = 0; 
                    foreach ($blogs_array as $b): 
                        $posicion_clase = ($i % 2 == 0) ? 'overlay-top' : 'overlay-bottom';
                        $i++;
                    ?>
                    <div class="blog-card-item blog-item-card" role="button" onclick="abrirModalBlog(<?php echo $b['id']; ?>)">
                        <div class="blog-card-inner">
                            <img src="<?php echo $base_url . htmlspecialchars($b['imagen_portada'] ?? ''); ?>" alt="Portada Blog" loading="lazy">
                            <div class="blog-card-overlay <?php echo $posicion_clase; ?>">
                                <span class="blog-tag"><?php echo htmlspecialchars($b['categoria'] ?? 'Tendencias'); ?></span>
                                <h3><?php echo htmlspecialchars($b['titulo'] ?? ''); ?></h3>
                                <span class="ver-mas-visual">VER MÁS <i class="bi bi-plus-lg"></i></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="blog-nav-btn blog-next" id="blogNext"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
</section>

<?php foreach ($blogs_array as $b): ?>
<div class="modal fade modal-blog-detail" id="modalBlog<?php echo (int)$b['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden modal-grande-personalizado">
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-lg-7 bg-black contenedor-img-modal">
                        <div id="carBlog<?php echo $b['id']; ?>" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="8000">
                            <div class="carousel-inner h-100">
                                <?php if (!empty($b['video_url'])): ?>
                                <div class="carousel-item active h-100 item-video">
                                    <div class="full-frame-img">
                                        <iframe width="100%" height="100%" src="<?php echo obtenerEmbedUrlBlog($b['video_url']); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="carousel-item <?php echo empty($b['video_url']) ? 'active' : ''; ?> h-100">
                                    <div class="img-wrapper-fijo cursor-zoom" onclick="zoomBlog('<?php echo $b['imagen_portada']; ?>')">
                                        <img src="<?php echo $base_url . htmlspecialchars($b['imagen_portada'] ?? ''); ?>" alt="Imagen principal">
                                    </div>
                                </div>
                                <?php 
                                $bid = (int)$b['id'];
                                $gal = $conn->query("SELECT * FROM blog_galeria WHERE blog_id = $bid");
                                while($img = $gal->fetch_assoc()): ?>
                                <div class="carousel-item h-100">
                                    <div class="img-wrapper-fijo cursor-zoom" onclick="zoomBlog('<?php echo $img['ruta_imagen']; ?>')">
                                        <img src="<?php echo $base_url . $img['ruta_imagen']; ?>" alt="Galería">
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php if($gal->num_rows > 0 || !empty($b['video_url'])): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carBlog<?php echo $b['id']; ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carBlog<?php echo $b['id']; ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 p-4 p-md-5 bg-white col-info-scroll text-start">
                        <div class="info-modal-content">
                            <span class="text-danger fw-bold small text-uppercase mb-2 d-block"><?php echo htmlspecialchars($b['categoria'] ?? 'Blog'); ?></span>
                            <h2 class="fw-bold text-dark mt-2 mb-4"><?php echo htmlspecialchars($b['titulo'] ?? ''); ?></h2>
                            <hr class="border-danger opacity-100 mb-4" style="width: 50px; border-width: 3px;">
                            <div class="txt-desc-larga text-muted fs-5"><?php echo nl2br(htmlspecialchars($b['contenido_html'] ?? '')); ?></div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 
</div>
<?php endforeach; ?>

<style>
/* --- ESTILOS MEJORADOS --- */
:root { --brand-red: #d3122a; }
.blog-section { padding: 80px 0; background-color: #fff; overflow: hidden; }
.container-blog { max-width: 1400px; margin: 0 auto; padding: 0 20px; position: relative; }
.blog-main-title { font-size: 3.5rem; font-weight: 900; color: var(--brand-red); margin-bottom: 5px; text-align: center; }
.blog-main-subtitle { text-align: center; color: #333; font-size: 1.2rem; font-weight: 700; margin-bottom: 50px; }

/* CARRUSEL DE TARJETAS */
.blog-carousel-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
.blog-viewport { overflow: hidden; width: 100%; padding: 20px 0; }
.blog-track { display: flex; gap: 25px; transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); will-change: transform; }

.blog-card-item { flex: 0 0 calc(25% - 19px); height: 480px; cursor: pointer; transition: 0.3s; }
@media (max-width: 1200px) { .blog-card-item { flex: 0 0 calc(33.333% - 17px); } }
@media (max-width: 992px) { .blog-card-item { flex: 0 0 calc(50% - 13px); } }
@media (max-width: 576px) { .blog-card-item { flex: 0 0 100%; height: 400px; } }

.blog-card-inner { position: relative; width: 100%; height: 100%; border-radius: 25px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.blog-card-inner img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
.blog-card-item:hover .blog-card-inner img { transform: scale(1.05); }

/* OVERLAYS */
.blog-card-overlay { position: absolute; left: 25px; right: 25px; color: white; z-index: 2; text-shadow: 0 2px 5px rgba(0,0,0,0.8); pointer-events: none; }
.blog-card-overlay h3 { font-size: 1.6rem; font-weight: 800; line-height: 1.2; margin: 10px 0; }
.blog-tag { color: var(--brand-red); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; }
.overlay-top { top: 35px; }
.overlay-bottom { bottom: 35px; }
.ver-mas-visual { border: 2px solid white; color: white; padding: 5px 15px; font-weight: 700; font-size: 0.8rem; border-radius: 5px; margin-top: 10px; display: inline-block; }

/* NAVEGACIÓN */
.blog-nav-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 45px; height: 45px; background: white; color: var(--brand-red); border: 2px solid var(--brand-red); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 100; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.blog-nav-btn:hover { background: var(--brand-red); color: white; }
.blog-prev { left: -10px; } .blog-next { right: -10px; }

/* MODAL */
.carousel-item { transition: transform 1.2s ease-in-out !important; }
.full-frame-img { width: 100%; height: 100%; background: #000; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.full-frame-img img { max-height: 100%; max-width: 100%; object-fit: contain; }
@media (min-width: 992px) { .modal-grande-personalizado, .contenedor-img-modal, .col-info-scroll { height: 80vh; min-height: 600px; } .col-info-scroll { overflow-y: auto; } }
@media (max-width: 991px) { .modal-grande-personalizado { height: auto; max-height: 90vh; overflow-y: auto; } .contenedor-img-modal { height: 300px; } .col-info-scroll { padding: 30px 20px !important; } }
.btn-close-custom { position: absolute; top: 15px; right: 15px; z-index: 1100; width: 40px; height: 40px; background: var(--brand-red); color: white !important; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function abrirModalBlog(id) {
    const modalEl = document.getElementById('modalBlog' + id);
    if (modalEl && typeof bootstrap !== 'undefined') {
        const m = new bootstrap.Modal(modalEl);
        m.show();
    }
}

function zoomBlog(url) {
    const target = document.getElementById('imgZoomTargetBlog');
    if(target) {
        target.src = "<?php echo $base_url; ?>" + url;
        new bootstrap.Modal(document.getElementById('modalZoomBlog')).show();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // CORRECCIÓN DE ERROR offsetWidth
    const track = document.getElementById('blogTrack');
    const next = document.getElementById('blogNext');
    const prev = document.getElementById('blogPrev');
    const slides = document.querySelectorAll('.blog-item-card');

    if (track && slides.length > 0) {
        let index = 0;
        const moveBlog = () => {
            const slideWidth = slides[0].offsetWidth + 25; 
            const visible = Math.round(track.parentElement.offsetWidth / slideWidth);
            const max = slides.length - visible;
            if (index > max) index = max;
            if (index < 0) index = 0;
            track.style.transform = `translateX(-${index * slideWidth}px)`;
            if(prev) prev.disabled = (index === 0);
            if(next) next.disabled = (index >= max);
        };
        if(next) next.addEventListener('click', () => { index++; moveBlog(); });
        if(prev) prev.addEventListener('click', () => { index--; moveBlog(); });
        window.addEventListener('resize', moveBlog);
        moveBlog();
    }

    // MANEJO DE MODALES
    document.querySelectorAll('.modal-blog-detail').forEach(modal => {
        const carouselEl = modal.querySelector('.carousel');
        if (carouselEl && typeof bootstrap !== 'undefined') {
            const carousel = new bootstrap.Carousel(carouselEl);
            carouselEl.addEventListener('slide.bs.carousel', function () {
                const iframe = modal.querySelector('iframe');
                if (iframe) iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            });
        }
        modal.addEventListener('hidden.bs.modal', function () {
            const iframe = modal.querySelector('iframe');
            if (iframe) { const s = iframe.src; iframe.src = ''; iframe.src = s; }
        });
        modal.addEventListener('shown.bs.modal', function () {
            if (carouselEl) {
                const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                bsCarousel.cycle();
            }
        });
    });
});
</script>