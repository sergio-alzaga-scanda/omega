<?php
// views/sections/clientes.php
$res_clientes = $conn->query("SELECT * FROM clientes ORDER BY orden ASC");
$clientes = [];
while($row = $res_clientes->fetch_assoc()) { $clientes[] = $row; }

// Triplicamos para un desplazamiento infinito fluido
$clientes_loop = array_merge($clientes, $clientes, $clientes); 
?>

<section class="clientes-section">
    <div class="container-fluid">
        <h3 class="clientes-title">Clientes</h3>
        
        <div class="carousel-outer-centered">
            <button class="speed-nav prev-speed" id="btn-fast-left" aria-label="Anterior">
                <svg viewBox="0 0 100 100">
                    <path d="M70,10 L30,50 L70,90" fill="none" stroke="#d3122a" stroke-width="25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            <div class="carousel-view">
                <div class="logos-track" id="logos-track">
                    <?php foreach ($clientes_loop as $c): ?>
                    <div class="logo-box">
                        <img src="<?php echo htmlspecialchars($c['logo_url']); ?>" alt="<?php echo htmlspecialchars($c['nombre']); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="speed-nav next-speed" id="btn-fast-right" aria-label="Siguiente">
                <svg viewBox="0 0 100 100">
                    <path d="M30,10 L70,50 L30,90" fill="none" stroke="#d3122a" stroke-width="25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<style>
.clientes-section { background-color: #2d2d2d; padding: 100px 0; overflow: hidden; }
.clientes-title { color: #d3122a; font-size: 2.8rem; font-weight: 700; margin-left: 15%; margin-bottom: 50px; }

.carousel-outer-centered { 
    display: flex; 
    align-items: center; 
    justify-content: center;
    padding: 0 5%; 
    position: relative; 
    gap: 30px;
}

.carousel-view { width: 100%; max-width: 1200px; overflow: hidden; }

.logos-track { 
    display: flex; 
    gap: 40px; 
    width: max-content; 
    will-change: transform; 
}

.logo-box { 
    background: #fff; 
    width: 210px; 
    height: 140px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 12px; 
    flex-shrink: 0; 
    padding: 30px; 
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.logo-box img { max-width: 165%; max-height: 165%; object-fit: contain; }

/* ESTILO DE LAS FLECHAS ROJAS UNIFICADO */
.speed-nav { 
    background: transparent; 
    border: none; 
    width: 45px; 
    height: 70px;
    cursor: pointer; 
    z-index: 10; 
    padding: 0;
    transition: transform 0.3s;
}

.speed-nav svg { width: 100%; height: 100%; }
.speed-nav:hover { transform: scale(1.2); }

@media (max-width: 1200px) {
    .carousel-outer-centered { padding: 0 20px; }
    .logo-box { width: 180px; height: 120px; }
    .speed-nav { width: 30px; height: 50px; }
}
</style>