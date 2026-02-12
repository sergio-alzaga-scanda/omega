<?php
$base_url = "/omega/"; 
$servicios_res = getItems($conn, 'servicios');
$servicios_array = [];
if ($servicios_res) { while ($row = $servicios_res->fetch_assoc()) { $servicios_array[] = $row; } }
?>

<section class="servicios-section" id="servicios">
    <div class="container-services">
        <h2 class="section-title">Servicios</h2>
        <div class="services-list">
            <?php foreach ($servicios_array as $s): ?>
            <div class="service-item">
                <div class="service-image">
                    <img src="<?php echo $base_url . $s['imagen_url']; ?>" alt="<?php echo htmlspecialchars($s['titulo']); ?>">
                </div>
                <div class="service-info">
                    <h4><?php echo htmlspecialchars($s['titulo']); ?></h4>
                    <p class="text-truncate-3"><?php echo nl2br(htmlspecialchars($s['descripcion'])); ?></p>
                    <span class="service-tags d-block mb-3"><?php echo htmlspecialchars($s['subtitulos_rojos']); ?></span>
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
                    <div class="col-12 col-md-7 bg-black contenedor-img-modal">
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

                    <div class="col-12 col-md-5 p-4 p-md-5 bg-white col-info-scroll">
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<style>
/* --- ESTILOS DEL MODAL PARA DESCRIPCIÓN LARGA --- */

@media (min-width: 992px) {
    /* Forzamos que el modal sea grande y no crezca hacia abajo */
    .modal-grande-personalizado {
        height: 80vh; 
        min-height: 600px;
    }

    .contenedor-img-modal, 
    .col-info-scroll {
        height: 80vh;
        min-height: 600px;
    }

    /* Scroll solo en la columna de texto */
    .col-info-scroll {
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
}

/* Contenedor de imagen para que siempre rellene el espacio sin deformarse */
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
    object-fit: cover; /* Mantiene la imagen llenando el área fija */
}

/* Personalización del Scrollbar */
.col-info-scroll::-webkit-scrollbar {
    width: 6px;
}
.col-info-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.col-info-scroll::-webkit-scrollbar-thumb {
    background: #d3122a; /* Rojo Primacía */
    border-radius: 10px;
}

/* Botón de cerrar flotante */
.btn-close-custom {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1070;
    width: 40px;
    height: 40px;
    background-color: #d3122a;
    color: white !important;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.btn-close-custom:hover {
    background-color: #000;
    transform: scale(1.1);
}
</style>

<style>
/* Estilos del Front */
.servicios-section { background-color: #0c0c0c; padding: 100px 0; position: relative; overflow: hidden; color: white; }
.container-services { max-width: 1100px; margin: 0 auto; padding: 0 20px; z-index: 5; position: relative;}
.section-title { font-size: 3rem; font-weight: 900; color: #333; margin-bottom: 60px; text-transform: uppercase; }
.services-list { display: flex; flex-direction: column; gap: 50px; }
.service-item { display: flex; align-items: center; gap: 40px; }
.service-image { flex: 0 0 350px; height: 220px; border-radius: 8px; overflow: hidden; }
.service-image img { width: 100%; height: 100%; object-fit: cover; }
.btn-ver-mas { background: transparent; border: 1.5px solid #d3122a; color: #d3122a; padding: 10px 25px; font-weight: 700; border-radius: 5px; transition: 0.3s; }
.btn-ver-mas:hover { background: #d3122a; color: white; }

/* Estilos Modal Grande */
@media (min-width: 992px) {
    .modal-grande-personalizado, .contenedor-img-modal, .columna-info-modal { height: 75vh; min-height: 550px; }
    .columna-info-modal { overflow-y: auto; }
}
.img-wrapper-fijo { width: 100%; height: 100%; background: #000; display: flex; align-items: center; justify-content: center; }
.img-wrapper-fijo img { width: 100%; height: 100%; object-fit: cover; }
.btn-close-custom { position: absolute; top: 15px; right: 15px; z-index: 10; background: #d3122a; color: white; border: none; width: 40px; height: 40px; border-radius: 50%; font-size: 1.5rem; }
</style>