<?php $articulos = getItems($conn, 'blog'); ?>

<section class="blog-section py-5" id="blog">
    <div class="container">
        <h2 class="section-title-red">Blog</h2>
        <p class="section-subtitle mb-5">Tendencias, estrategia y experiencias que inspiran.</p>
        
        <div class="carousel-blog-container">
            <button class="nav-blog prev" id="blogPrev">❮</button>
            
            <div class="blog-viewport">
                <div class="blog-track" id="blogTrack">
                    <?php while($b = $articulos->fetch_assoc()): ?>
                    <div class="blog-card-wrapper">
                        <div class="blog-card" 
                             data-bs-toggle="modal" 
                             data-bs-target="#blogModal"
                             data-titulo="<?php echo htmlspecialchars($b['titulo'] ?? ''); ?>"
                             data-contenido="<?php echo htmlspecialchars($b['contenido_completo'] ?? ''); ?>"
                             data-img="<?php echo htmlspecialchars($b['imagen_url'] ?? ''); ?>"
                             data-cat="<?php echo htmlspecialchars($b['categoria'] ?? ''); ?>">
                            
                            <img src="<?php echo htmlspecialchars($b['imagen_url'] ?? ''); ?>" alt="Blog">
                            <div class="blog-overlay">
                                <span class="badge-cat"><?php echo htmlspecialchars($b['categoria'] ?? ''); ?></span>
                                <h4><?php echo htmlspecialchars($b['titulo'] ?? ''); ?></h4>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <button class="nav-blog next" id="blogNext">❯</button>
        </div>
    </div>
</section>

<div class="modal fade" id="blogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body p-0">
                <div class="position-relative">
                    <img id="blogModalImg" src="" class="img-fluid w-100 rounded-top-4" style="max-height: 400px; object-fit: cover;">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="p-4">
                    <span id="blogModalCat" class="text-danger fw-bold small text-uppercase"></span>
                    <h2 id="blogModalTitle" class="fw-bold mt-2"></h2>
                    <hr class="red-divider">
                    <div id="blogModalContent" class="text-secondary mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.section-title-red { color: #d3122a; font-size: 3.5rem; font-weight: 800; margin-bottom: 5px; }
.section-subtitle { font-size: 1.5rem; color: #333; font-weight: 600; }

.carousel-blog-container { position: relative; display: flex; align-items: center; padding: 20px 0; }
.blog-viewport { width: 100%; overflow: hidden; }
.blog-track { display: flex; gap: 20px; transition: transform 0.5s ease; }

.blog-card-wrapper { min-width: 320px; flex-shrink: 0; }
.blog-card { 
    height: 500px; border-radius: 25px; overflow: hidden; 
    position: relative; cursor: pointer; transition: 0.3s;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.blog-card img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
.blog-card:hover img { transform: scale(1.1); filter: brightness(0.7); }

.blog-overlay { 
    position: absolute; bottom: 0; left: 0; width: 100%; padding: 30px; 
    background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white;
}
.badge-cat { font-size: 0.75rem; font-weight: 700; background: #d3122a; padding: 5px 12px; border-radius: 5px; margin-bottom: 10px; display: inline-block; }
.blog-overlay h4 { font-weight: 700; line-height: 1.2; font-size: 1.25rem; }

.nav-blog { background: #d3122a; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; z-index: 10; position: absolute; }
.prev { left: -25px; } .next { right: -25px; }

.red-divider { width: 50px; height: 4px; background: #d3122a; border: none; opacity: 1; }

@media (max-width: 768px) { .blog-card-wrapper { min-width: 280px; } .section-title-red { font-size: 2.5rem; } }
</style>