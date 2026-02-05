<?php
$base_url = "/omega/"; 
$servicios = getItems($conn, 'servicios');
?>

<section class="servicios-section" id="servicios">
    <div class="container-services">
        <h2 class="section-title">Servicios</h2>
        <div class="services-list">
            <?php while($s = $servicios->fetch_assoc()): ?>
            <div class="service-item">
                <div class="service-image">
                    <img src="<?php echo $base_url . $s['imagen_url']; ?>" alt="<?php echo htmlspecialchars($s['titulo']); ?>">
                </div>
                <div class="service-info">
                    <h4><?php echo htmlspecialchars($s['titulo']); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($s['descripcion'])); ?></p>
                    <span class="service-tags"><?php echo htmlspecialchars($s['subtitulos_rojos']); ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<style>
.servicios-section {
    background-color: var(--bg-dark);
    padding: 100px 0;
    position: relative;
    overflow: hidden;
    color: white;
}

.container-services {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 5;
}

.section-title {
    font-size: 3rem;
    font-weight: 900;
    color: #444; /* Gris oscuro como en la imagen */
    margin-bottom: 60px;
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.service-item {
    display: flex;
    align-items: center;
    gap: 40px;
    transition: transform 0.3s ease;
}

.service-item:hover {
    transform: translateX(10px);
}

.service-image {
    flex: 0 0 300px;
    height: 200px;
    overflow: hidden;
    border-radius: 4px;
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.service-info h4 {
    font-size: 1.8rem;
    font-weight: 800;
    margin: 0 0 15px 0;
}

.service-info p {
    color: #ccc;
    line-height: 1.5;
    font-size: 0.95rem;
    max-width: 600px;
    margin-bottom: 15px;
}

.service-tags {
    color: var(--brand-red);
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
}

/* Formas Orgánicas Rojas laterales */
.shape-right {
    position: absolute;
    top: 50px;
    right: -50px;
    width: 300px;
    height: 600px;
    background-color: var(--brand-red);
    border-radius: 50% 0 0 50%;
    opacity: 0.9;
    z-index: 1;
}

.shape-left-bottom {
    position: absolute;
    bottom: 50px;
    left: -80px;
    width: 250px;
    height: 250px;
    background-color: var(--brand-red);
    border-radius: 0 50% 50% 0;
    z-index: 1;
}

@media (max-width: 768px) {
    .service-item { flex-direction: column; text-align: center; }
    .service-image { flex: 0 0 auto; width: 100%; }
    .section-title { font-size: 2.2rem; }
    .shape-right, .shape-left-bottom { display: none; }
}
</style>