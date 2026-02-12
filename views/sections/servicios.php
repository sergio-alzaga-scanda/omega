<?php
// Configuración de base y obtención de datos
$base_url = "/omega/"; 
$servicios_res = getItems($conn, 'servicios');
$servicios_array = [];
if ($servicios_res) { 
    while ($row = $servicios_res->fetch_assoc()) { 
        $servicios_array[] = $row; 
    } 
}
?>

<section class="servicios-section" id="servicios">
    <div class="container-services">
        <h2 class="section-title">Servicios</h2>
        
        <div class="services-wrapper">
            <?php foreach ($servicios_array as $s): ?>
            <div class="service-item">
                <div class="service-image">
                    <img src="<?php echo $base_url . $s['imagen_url']; ?>" alt="<?php echo htmlspecialchars($s['titulo']); ?>" loading="lazy">
                </div>
                <div class="service-info">
                    <span class="service-tags"><?php echo htmlspecialchars($s['subtitulos_rojos']); ?></span>
                    <h4><?php echo htmlspecialchars($s['titulo']); ?></h4>
                    <p class="text-truncate-3"><?php echo nl2br(htmlspecialchars($s['descripcion'])); ?></p>
                    <button class="btn-ver-mas" data-bs-toggle="modal" data-bs-target="#servicioModal<?php echo (int)$s['id']; ?>">
                        VER MÁS <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="shape-right"></div>
    <div class="shape-left-bottom"></div>
</section>

<?php foreach ($servicios_array as $s): ?>
<div class="modal fade" id="servicioModal<?php echo (int)$s['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden modal-grande-personalizado">
            
            <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x"></i>
            </button>

            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-lg-7 bg-black contenedor-img-modal">
                        <div id="carSer<?php echo $s['id']; ?>" class="carousel slide h-100" data-bs-ride="carousel">
                            <div class="carousel-inner h-100">
                                <div class="carousel-item active h-100">
                                    <div class="img-wrapper-fijo">
                                        <img src="<?php echo $base_url . $s['imagen_url']; ?>" alt="Imagen principal">
                                    </div>
                                </div>
                                
                                <?php 
                                $sid = $s['id'];
                                $gal = $conn->query("SELECT * FROM servicio_galeria WHERE servicio_id = $sid");
                                while($img = $gal->fetch_assoc()): ?>
                                <div class="carousel-item h-100">
                                    <div class="img-wrapper-fijo">
                                        <img src="<?php echo $base_url . $img['ruta_imagen']; ?>" alt="Imagen galería">
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

                    <div class="col-12 col-lg-5 p-4 p-md-5 bg-white col-info-scroll">
                        <div class="info-modal-content">
                            <span class="text-danger fw-bold small text-uppercase mb-2 d-block">
                                <?php echo htmlspecialchars($s['subtitulos_rojos']); ?>
                            </span>

                            <h2 class="fw-bold text-dark mt-2 mb-4">
                                <?php echo htmlspecialchars($s['titulo']); ?>
                            </h2>

                            <hr class="border-danger opacity-100 mb-4" style="width: 50px; border-width: 3px;">

                            <div class="txt-desc-larga">
                                <p class="text-muted fs-5" style="line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($s['descripcion_larga'] ?? '')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div> </div> </div> </div> </div>
<?php endforeach; ?>

<style>
/* --- VARIABLES Y RESET --- */
:root {
    --primary-red: #d3122a;
    --dark-bg: #0c0c0c;
    --card-bg: #161616;
}

/* --- ESTILOS SECCIÓN FRONT --- */
.servicios-section { 
    background-color: var(--dark-bg); 
    padding: 80px 0; 
    position: relative; 
    overflow: hidden; 
    color: white; 
}

.container-services { 
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 20px; 
    z-index: 5; 
    position: relative;
}

.section-title { 
    font-size: clamp(2.5rem, 6vw, 4rem); 
    font-weight: 900; 
    color: rgba(255,255,255,0.05); /* Efecto de texto de fondo o sutil */
    text-align: center;
    margin-bottom: 50px; 
    text-transform: uppercase; 
    -webkit-text-stroke: 1px rgba(255,255,255,0.1);
}

.services-wrapper { 
    display: flex; 
    flex-direction: column; 
    gap: 30px; 
}

/* Item de Servicio Responsivo */
.service-item { 
    display: flex; 
    flex-direction: column; /* Móvil: vertical */
    background: var(--card-bg);
    border-radius: 15px; 
    overflow: hidden; 
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.4s ease;
}

@media (min-width: 768px) {
    .service-item { 
        flex-direction: row; /* Tablet/Desktop: horizontal */
        height: 320px;
    }
    .service-image { flex: 0 0 400px; }
}

.service-image { 
    width: 100%; 
    height: 250px; 
}

@media (min-width: 768px) { .service-image { height: auto; } }

.service-image img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    transition: transform 0.6s ease;
}

.service-item:hover {
    transform: translateY(-5px);
    border-color: var(--primary-red);
}

.service-item:hover .service-image img { 
    transform: scale(1.1); 
}

.service-info { 
    padding: 30px; 
    display: flex; 
    flex-direction: column; 
    justify-content: center; 
    flex-grow: 1;
}

.service-tags { 
    color: var(--primary-red); 
    font-weight: 700; 
    font-size: 0.8rem; 
    text-transform: uppercase; 
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.service-info h4 { 
    font-size: 1.8rem; 
    font-weight: 800; 
    margin-bottom: 15px; 
}

.text-truncate-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;  
    overflow: hidden;
    color: #aaa;
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

/* --- ESTILOS DEL MODAL --- */
@media (min-width: 992px) {
    .modal-grande-personalizado {
        height: 80vh; 
        min-height: 600px;
    }
    .contenedor-img-modal, .col-info-scroll {
        height: 80vh;
        min-height: 600px;
    }
    .col-info-scroll {
        overflow-y: auto;
    }
}

/* Ajustes Modal Móvil */
@media (max-width: 991px) {
    .contenedor-img-modal { height: 350px; }
    .col-info-scroll { padding: 30px 20px !important; }
}

.img-wrapper-fijo {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #000;
}

.img-wrapper-fijo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Botón de cerrar flotante */
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
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
}

.btn-close-custom:hover {
    background-color: #000;
    transform: rotate(90deg);
}

/* Scrollbar Personalizada */
.col-info-scroll::-webkit-scrollbar { width: 6px; }
.col-info-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
.col-info-scroll::-webkit-scrollbar-thumb { background: var(--primary-red); border-radius: 10px; }

/* Formas decorativas (Shapes) */
.shape-right {
    position: absolute; top: 0; right: -100px; width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(211,18,42,0.1) 0%, transparent 70%);
    z-index: 1;
}
</style>