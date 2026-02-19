<?php 
// Recuperamos la configuración de contacto
$c = getSeccion($conn, 'contacto'); 

// --- SOLUCIÓN INTEGRADA: Consulta dinámica de servicios ---
// Consultamos los títulos de la tabla servicios para alimentar el combo
$servicios_db = $conn->query("SELECT titulo FROM servicios ORDER BY titulo ASC");
?>

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
                
                <select name="servicio" required>
                    <option value="" disabled selected>Tipo de Servicio</option>
                    <?php 
                    if ($servicios_db && $servicios_db->num_rows > 0): 
                        while($s = $servicios_db->fetch_assoc()): 
                    ?>
                        <option value="<?php echo htmlspecialchars($s['titulo']); ?>">
                            <?php echo htmlspecialchars($s['titulo']); ?>
                        </option>
                    <?php 
                        endwhile; 
                    endif; 
                    ?>
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

/* Mejora visual para el select */
select {
    cursor: pointer;
    color: #555;
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

@media (max-width: 600px) {
    .form-group-row { grid-template-columns: 1fr; }
}
</style>