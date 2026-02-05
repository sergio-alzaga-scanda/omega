<?php $c = getSeccion($conn, 'contacto'); ?>

<section class="contacto-section" id="contacto">
   

    <div class="container-contacto">
        <h2 class="contact-title">Hagamos experiencias juntos.</h2>
        <h3 class="contact-subtitle">¡CONTÁCTANOS!</h3>

        <form action="controllers/send_form.php" method="POST" class="contact-form">
            <div class="form-group full">
                <input type="text" name="nombre" placeholder="Nombre completo" required>
            </div>
            <div class="form-group-row">
                <input type="tel" name="telefono" placeholder="Teléfono">
                <select name="servicio">
                    <option value="" disabled selected>Tipo de Servicio</option>
                    <option value="digital">Experiencias Digitales</option>
                    <option value="btl">BTL Estratégico</option>
                    <option value="eventos">Eventos Corporativos</option>
                </select>
            </div>
            <div class="form-group full">
                <input type="email" name="correo" placeholder="Correo" required>
            </div>
            <div class="form-group full">
                <textarea name="mensaje" placeholder="Mensaje" rows="5"></textarea>
            </div>
            <button type="submit" class="btn-enviar">ENVIAR</button>
        </form>

        
    </div>
</section>

<style>
.contacto-section {
    background: #fff;
    padding: 0px 0 40px;
    position: relative;
    overflow: hidden;
    text-align: center;
}



.container-contacto { position: relative; z-index: 5; max-width: 800px; margin: 0 auto; padding: 0 20px; }

.contact-title { color: var(--brand-red); font-size: 2.2rem; font-weight: 700; margin: 0; }
.contact-subtitle { color: #333; font-size: 1.5rem; font-weight: 900; margin-top: 5px; }

.contact-form { margin: 40px 0; display: flex; flex-direction: column; gap: 15px; }
.form-group-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

input, select, textarea {
    background: #ebebeb;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 0.9rem;
    width: 100%;
    box-sizing: border-box;
}

.btn-enviar {
    background: var(--brand-red);
    color: white;
    padding: 18px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.3s;
}

.btn-enviar:hover { background: #b00e23; }

.footer-info { margin-top: 60px; display: flex; flex-direction: column; align-items: center; gap: 15px; }
.footer-logo { height: 40px; margin-bottom: 20px; }

.contact-link { color: #333; text-decoration: none; font-size: 0.95rem; display: block; margin: 5px 0; }
.contact-link .icon { color: var(--brand-red); font-weight: bold; margin-right: 8px; }

.social-label { font-weight: 700; margin-top: 20px; color: #333; }
.social-icons { display: flex; gap: 20px; margin-top: 10px; }

.social-btn {
    width: 45px;
    height: 45px;
    background: var(--brand-red);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.2rem;
    transition: transform 0.3s;
}
.social-btn:hover { transform: scale(1.1); }

.copyright { font-size: 0.75rem; color: #888; margin-top: 50px; }

@media (max-width: 600px) {
    .form-group-row { grid-template-columns: 1fr; }
  
}
</style>