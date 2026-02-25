<?php 
// Recuperamos la configuración de contacto de la base de datos
$c = getSeccion($conn, 'contacto'); 
$hero = getHeroConfig($conn); 

// Definimos la ruta del logo: si existe en BD lo usamos, si no, el de respaldo
$ruta_logo_pie = !empty($hero['logo_pie']) ? $hero['logo_pie'] : 'assets/logo.png';
?>

<section class="contacto-section" id="contacto">
    <img src="assets/flechas/4.png" class="footer-deco-image" alt="decoracion">

    <div class="container-contacto">
        <div class="header-contacto mb-5">
            <h2 class="titulo-contacto">Hagamos experiencias juntos.</h2>
            <h3 class="subtitulo-contacto">¡CONTÁCTANOS!</h3>
        </div>

        

        <div class="footer-info">
            <img src="<?php echo $ruta_logo_pie; ?>" alt="PRIMACIA" class="footer-logo">
            
            <div class="direct-links">
                <a href="mailto:<?php echo $c['email']; ?>" class="contact-link">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg>
                    </span> 
                    <?php echo $c['email']; ?>
                </a>
                <a href="https://api.whatsapp.com/send?phone=525579047845&text=Hola%20me%20interesa%20m%C3%A1s%20informaci%C3%B3n" target="_blank" class="contact-link">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                        </svg>
                    </span> 
                    5579047845
                </a>
            </div>

            <p class="social-label">Síguenos en redes</p>
            <div class="social-icons">
                <a href="<?php echo $c['facebook']; ?>" target="_blank" class="social-btn fb">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                    </svg>
                </a>
                <a href="<?php echo $c['instagram']; ?>" target="_blank" class="social-btn ig">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                    </svg>
                </a>
                <a href="<?php echo $c['linkedin']; ?>" target="_blank" class="social-btn in">in</a>
            </div>

            <p class="copyright">
    © 2026 by JuxXxii created for PRIMACÍA. 
    <a href="./assets/aviso.pdf" download style="text-decoration: none; color: inherit;">Aviso de Privacidad</a>
</p>

            <div class="admin-access">
                <button type="button" class="btn-login-minimal" data-bs-toggle="modal" data-bs-target="#loginModal">
                    ACCESO ADMINISTRATIVO
                </button>
            </div>
        </div>
    </div>
</section>

<style>
/* Variables y Estilos Generales */
:root {
    --brand-red: #d3122a; 
}

.contacto-section {
    background: #fff;
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
    text-align: center;
}

/* Imagen decorativa 2.png */
.footer-deco-image {
    position: absolute;
    top: 15%;
    right: -50px;
    width: 400px; /* Tamaño ajustable según necesidad visual */
    pointer-events: none;
    z-index: 1;
    opacity: 1;
}

.container-contacto { 
    position: relative; 
    z-index: 5; 
    max-width: 650px; /* Ancho ajustado para que el formulario se vea como en la imagen */
    margin: 0 auto; 
    padding: 0 20px; 
}

/* Estilos de Texto */
.titulo-contacto { color: var(--brand-red); font-weight: 600; font-size: 2.1rem; margin-bottom: 0px; }
.subtitulo-contacto { font-weight: 800; font-size: 1.4rem; color: #222; margin-bottom: 30px; }

/* Estilo de Inputs del Formulario */
.form-control-custom {
    width: 100%;
    background-color: #f2f2f2; /* Color gris suave de los campos en la imagen */
    border: none;
    border-radius: 6px;
    padding: 12px 18px;
    color: #555;
    font-size: 0.9rem;
    transition: 0.3s;
}

.form-control-custom:focus {
    outline: none;
    background-color: #e9e9e9;
}

/* Botón ENVIAR */
.btn-enviar-rojo {
    width: 100%;
    background-color: var(--brand-red);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 1rem;
    transition: 0.3s;
}

.btn-enviar-rojo:hover { background-color: #b00f23; }

/* Información de Marca y Footer */
.footer-info { display: flex; flex-direction: column; align-items: center; gap: 15px; margin-top: 40px; }
.footer-logo { height: 40px; margin-bottom: 15px; }

.direct-links { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
.contact-link { color: #444; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
.contact-link .icon { color: var(--brand-red); margin-right: 5px; }

.social-label { font-weight: 800; margin-top: 20px; color: #222; text-transform: uppercase; font-size: 0.8rem; }
.social-icons { display: flex; gap: 12px; margin-top: 8px; }

.social-btn {
    width: 40px; height: 40px;
    background: var(--brand-red);
    color: white;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none; transition: 0.3s;
}
.social-btn:hover { transform: translateY(-3px); color: #fff; }

.copyright { font-size: 0.7rem; color: #aaa; margin-top: 50px; margin-bottom: 5px; }

.btn-login-minimal {
    background: none; border: none;
    color: #e5e5e5;
    font-size: 0.55rem; font-weight: 700;
    letter-spacing: 2px; cursor: pointer;
}

@media (max-width: 768px) {
    .footer-deco-image { width: 200px; right: -30px; }
    .titulo-contacto { font-size: 1.8rem; }
}
</style>