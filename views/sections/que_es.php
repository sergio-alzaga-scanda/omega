<?php
// Recuperamos datos con tu conexión $conn mysqli (puerto 3307)
$info_res = $conn->query("SELECT clave, valor FROM configuracion WHERE seccion = 'nosotros'");
$info = [];
while($row = $info_res->fetch_assoc()) { $info[$row['clave']] = $row['valor']; }

$puntos = $conn->query("SELECT * FROM porque_primicia_puntos ORDER BY orden ASC");
$stats = $conn->query("SELECT * FROM contadores ORDER BY id ASC");
?>

<section class="porque-primicia">
    <div class="container-centered">
        <div class="header-nosotros">
            <h2 class="txt-red-small"><?php echo mb_strtoupper($info['titulo']); ?></h2>
            <h3 class="main-headline">
                <span class="gray-text">TRANSFORMAMOS ESTRATEGIAS</span><br>
                <span class="gray-text">EN</span> <span class="black-text">EXPERIENCIAS MEMORABLES</span>
            </h3>
            <hr class="red-divider">
            <p class="txt-description-centered"><?php echo nl2br($info['descripcion']); ?></p>
        </div>

        <div class="features-list">
            <?php while($item = $puntos->fetch_assoc()): ?>
            <div class="feature-item">
                <div class="arrow-container">
                    <svg class="big-arrow" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" fill="currentColor"/></svg>
                </div>
                <div class="feature-content">
                    <h4><?php echo $item['titulo']; ?></h4>
                    <p><?php echo $item['descripcion']; ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="counters-grid" id="stats-container">
    <?php while($s = $stats->fetch_assoc()): ?>
    <div class="counter-card">
        <div class="number-row">
            <span class="js-counter" data-target="<?php echo $s['numero']; ?>">0</span>
            <span class="suffix"><?php echo $s['sufijo']; ?></span>
        </div>
        <p class="label"><?php echo $s['etiqueta']; ?></p>
    </div>
    <?php endwhile; ?>
</div>
    </div>
</section>

<style>
    .counters-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    min-height: 150px; /* Asegura que el Observer tenga un área que detectar */
    margin-top: 50px;
    visibility: visible;
    opacity: 1;
}
    .container-centered { max-width: 1000px; margin: 0 auto; text-align: left; padding: 100px 20px; }
    
    .txt-red-small { color: var(--brand-red); font-size: 1.4rem; font-weight: 700; margin-bottom: 10px; }
    
    .main-headline { line-height: 1.1; margin-bottom: 30px; font-weight: 900; font-size: 2.8rem; }
    .gray-text { color: #888; }
    .black-text { color: #000; }

    .red-divider { width: 60px; height: 3px; background-color: #ddd; border: none; margin: 30px 0; }
    .txt-description-centered { color: #555; font-size: 1.1rem; line-height: 1.6; margin-bottom: 60px; }

    /* Flechas vistosas */
    .feature-item { display: flex; align-items: flex-start; margin-bottom: 35px; transition: transform 0.3s ease; }
    .feature-item:hover { transform: translateX(15px); }
    .arrow-container { margin-right: 20px; }
    .big-arrow { width: 35px; height: 35px; color: var(--brand-red); filter: drop-shadow(2px 2px 5px rgba(211, 18, 42, 0.2)); }
    
    .feature-content h4 { font-weight: 900; font-size: 1.3rem; color: #222; margin: 0 0 5px 0; }
    .feature-content p { color: #666; font-size: 1rem; margin: 0; }

    /* Contadores */
    .counters-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 80px; padding-top: 50px; border-top: 1px solid #eee; }
    .number-row { color: var(--brand-red); font-size: 4.8rem; font-weight: 900; display: flex; align-items: center; justify-content: center; }
    .counter-card { text-align: center; }
    .label { color: var(--brand-red); font-size: 1.2rem; font-weight: 400; letter-spacing: 3px; margin-top: 5px; }

    @media (max-width: 768px) {
        .main-headline { font-size: 1.8rem; }
        .counters-grid { grid-template-columns: 1fr; gap: 50px; }
    }
</style>