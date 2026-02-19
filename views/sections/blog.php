<?php
// 1. CONFIGURACIÓN DE RUTAS Y DATOS
if (!isset($base_url)) { $base_url = "/omega/"; }

$blog_res = $conn->query("SELECT * FROM blog ORDER BY id ASC");
$blogs_array = [];
if ($blog_res) { 
    while ($row = $blog_res->fetch_assoc()) { $blogs_array[] = $row; } 
}

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

<div class="modal fade" id="modalZoomBlog" tabindex="-1" aria-hidden="true" style="z-index: 13000;">
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
            <button class="blog-nav-btn blog-prev" id="blogPrev" aria-label="Anterior"><i class="bi bi-chevron-left"></i></button>
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
            <button class="blog-nav-btn blog-next" id="blogNext" aria-label="Siguiente"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
</section>

<?php foreach ($blogs_array as $b): ?>
<div class="modal fade modal-blog-detail" id="modalBlog<?php echo (int)$b['id']; ?>" tabindex="-1" aria-labelledby="labelModal<?php echo $b['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg modal-grande-personalizado">
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Cerrar"><i class="bi bi-x"></i></button>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-lg-7 bg-black contenedor-img-modal">
                        <div id="carBlog<?php echo $b['id']; ?>" class="carousel slide h-100" data-bs-ride="false">
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
                            <span class="text-danger small text-uppercase mb-1 d-block" style="letter-spacing: 2px; font-weight: 900;"><?php echo htmlspecialchars($b['categoria'] ?? 'Blog'); ?></span>
                            <h2 class="fw-bold text-dark mt-2 mb-3" id="labelModal<?php echo $b['id']; ?>" style="font-size: 1.8rem; line-height: 1.2;"><?php echo htmlspecialchars($b['titulo'] ?? ''); ?></h2>
                            <hr class="border-danger opacity-100 mb-4" style="width: 60px; border-width: 4px;">
                            <div class="txt-desc-larga">
                                <div class="text-muted" style="line-height: 1.6; font-size: 1rem;">
                                    <?php echo nl2br(htmlspecialchars($b['contenido_html'] ?? '')); ?>
                                </div>
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
/* --- ESTILOS --- */
:root { --brand-red: #d3122a; }
.blog-section { padding: 80px 0; background-color: #fff; overflow: hidden; }
.container-blog { max-width: 1400px; margin: 0 auto; padding: 0 20px; position: relative; }
.blog-main-title { font-size: 3.5rem; font-weight: 900; color: var(--brand-red); margin-bottom: 5px; text-align: center; }
.blog-main-subtitle { text-align: center; color: #333; font-size: 1.2rem; font-weight: 700; margin-bottom: 50px; }

.blog-carousel-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
.blog-viewport { overflow: hidden; width: 100%; padding: 20px 0; touch-action: pan-y; }
.blog-track { display: flex; gap: 25px; transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); will-change: transform; }

.blog-card-item { flex: 0 0 calc(25% - 19px); height: 480px; cursor: pointer; transition: 0.3s; }
@media (max-width: 1200px) { .blog-card-item { flex: 0 0 calc(33.333% - 17px); } }
@media (max-width: 992px) { .blog-card-item { flex: 0 0 calc(50% - 13px); } }
@media (max-width: 576px) { .blog-card-item { flex: 0 0 100%; height: 400px; } }

.blog-card-inner { position: relative; width: 100%; height: 100%; border-radius: 25px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.blog-card-inner img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
.blog-card-overlay { position: absolute; left: 25px; right: 25px; color: white; z-index: 2; text-shadow: 0 2px 5px rgba(0,0,0,0.8); pointer-events: none; }
.overlay-top { top: 35px; } .overlay-bottom { bottom: 35px; }
.blog-tag { color: var(--brand-red); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; }
.ver-mas-visual { border: 2px solid white; color: white; padding: 5px 15px; font-weight: 700; font-size: 0.8rem; border-radius: 5px; margin-top: 10px; display: inline-block; }

.blog-nav-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 45px; height: 45px; background: white; color: var(--brand-red); border: 2px solid var(--brand-red); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 100; cursor: pointer; transition: 0.3s; }
.blog-nav-btn:disabled { opacity: 0.3; cursor: not-allowed; }
.blog-prev { left: -10px; } .blog-next { right: -10px; }

/* MODAL SCROLL CORREGIDO */
@media (min-width: 992px) {
    .modal-grande-personalizado { height: 85vh; overflow: hidden; }
    .col-info-scroll { height: 85vh; overflow-y: auto; }
}
@media (max-width: 991px) {
    .modal-grande-personalizado { height: auto; min-height: 100vh; overflow-y: auto; border-radius: 0; }
    .contenedor-img-modal { height: 350px; }
    .col-info-scroll { height: auto; overflow-y: visible !important; }
}
.btn-close-custom { position: absolute; top: 15px; right: 15px; z-index: 1100; width: 40px; height: 40px; background: var(--brand-red); color: white !important; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
.img-wrapper-fijo { width: 100%; height: 100%; background: #000; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.img-wrapper-fijo img { max-height: 100%; max-width: 100%; object-fit: contain; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function abrirModalBlog(id) {
    const el = document.getElementById('modalBlog' + id);
    if (el) { new bootstrap.Modal(el).show(); }
}

function zoomBlog(url) {
    const t = document.getElementById('imgZoomTargetBlog');
    if(t) { t.src = "<?php echo $base_url; ?>" + url; new bootstrap.Modal(document.getElementById('modalZoomBlog')).show(); }
}

document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('blogTrack');
    const next = document.getElementById('blogNext');
    const prev = document.getElementById('blogPrev');
    const slides = document.querySelectorAll('.blog-item-card');

    // CORRECCIÓN: Verificar que existan slides antes de calcular offsetWidth
    if (track && slides.length > 0) {
        let index = 0;
        const moveBlog = () => {
            if (!slides[index]) return; // Protección extra
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

        // Swipe móvil
        let startX = 0;
        track.addEventListener('touchstart', e => { startX = e.changedTouches[0].screenX; }, {passive: true});
        track.addEventListener('touchend', e => {
            let endX = e.changedTouches[0].screenX;
            if (startX - endX > 50) index++;
            if (endX - startX > 50) index--;
            moveBlog();
        }, {passive: true});

        window.addEventListener('resize', moveBlog);
        moveBlog();
    }

    // Limpieza de videos al cerrar modales
    document.querySelectorAll('.modal-blog-detail').forEach(m => {
        m.addEventListener('hidden.bs.modal', () => {
            const ifr = m.querySelector('iframe');
            if (ifr) { const s = ifr.src; ifr.src = ''; ifr.src = s; }
        });
    });
});
</script>