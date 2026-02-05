document.addEventListener("DOMContentLoaded", () => {
  /* ==========================
   * NAVBAR SCROLL
   * ========================== */
  const navbar = document.querySelector(".navbar");

  if (navbar) {
    window.addEventListener("scroll", () => {
      navbar.classList.toggle("scrolled", window.scrollY > 50);
    });
  }

  /* ==========================
   * CONTADORES
   * ========================== */
  const counters = document.querySelectorAll(".js-counter");
  const statsContainer =
    document.getElementById("stats-container") ||
    document.querySelector(".counters-grid");

  if (!counters.length || !statsContainer) return;

  const ANIMATION_DURATION = 2000; // ms
  let countersStarted = false;

  const animateCounter = (counter) => {
    const target = Number(counter.dataset.target);
    const startTime = performance.now();

    const update = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / ANIMATION_DURATION, 1);
      const value = Math.floor(progress * target);

      counter.textContent = value;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        counter.textContent = target;
      }
    };

    requestAnimationFrame(update);
  };

  const startCounters = () => {
    if (countersStarted) return;
    countersStarted = true;
    counters.forEach(animateCounter);
  };

  /* ==========================
   * INTERSECTION OBSERVER
   * ========================== */
  const observer = new IntersectionObserver(
    ([entry], obs) => {
      if (entry.isIntersecting) {
        startCounters();
        obs.unobserve(entry.target);
      }
    },
    { threshold: 0.4 },
  );

  observer.observe(statsContainer);
});

//===================================================================
////=============================  CARRUCEL  =========================
//===================================================================

/**
 * Lógica del Carrusel de Casos de Éxito
 */
window.addEventListener("load", () => {
  const track = document.getElementById("track-casos");
  const btnNext = document.getElementById("btnNext");
  const btnPrev = document.getElementById("btnPrev");

  if (!track) return;

  let currentIndex = 0;
  const items = document.querySelectorAll(".case-slide-item");
  const totalOriginal = items.length / 2; // Basado en el duplicado de PHP

  const move = (direction) => {
    const gap = 30; // Debe coincidir con el CSS
    const cardWidth = items[0].offsetWidth + gap;

    if (direction === "next") {
      currentIndex++;
      if (currentIndex >= totalOriginal) currentIndex = 0;
    } else {
      currentIndex--;
      if (currentIndex < 0) currentIndex = totalOriginal - 1;
    }

    track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
  };

  btnNext.addEventListener("click", () => move("next"));
  btnPrev.addEventListener("click", () => move("prev"));
});

/**
 * Lógica del Carrusel de Clientes
 */

// Aseguramos que el script corra después de cargar imágenes
window.addEventListener("load", () => {
  const track = document.getElementById("logos-track");
  const btnLeft = document.getElementById("btn-fast-left");
  const btnRight = document.getElementById("btn-fast-right");

  if (!track) return;

  let posX = 0;
  const baseSpeed = 1.0; // Velocidad de inicio constante
  let currentSpeed = baseSpeed;
  let direction = 1; // 1 = hacia la izquierda (normal)

  const animateLogos = () => {
    posX -= currentSpeed * direction;

    // El punto de reinicio es el ancho de un set original de 5 logos + sus gaps
    const singleSetWidth = track.scrollWidth / 3;

    if (Math.abs(posX) >= singleSetWidth) {
      posX = 0;
    }

    track.style.transform = `translateX(${posX}px)`;
    requestAnimationFrame(animateLogos);
  };

  // Listeners para aceleración
  btnRight.addEventListener("mouseenter", () => {
    currentSpeed = 6.0;
    direction = 1;
  });
  btnRight.addEventListener("mouseleave", () => {
    currentSpeed = baseSpeed;
  });

  btnLeft.addEventListener("mouseenter", () => {
    currentSpeed = 6.0;
    direction = -1;
  });
  btnLeft.addEventListener("mouseleave", () => {
    currentSpeed = baseSpeed;
  });

  // Iniciar el movimiento automáticamente
  animateLogos();
});
// js/main.js o al final de home.php

document.addEventListener("DOMContentLoaded", () => {
  // Verificar si existe el parámetro 'status' en la URL
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get("status");

  if (status === "success") {
    Swal.fire({
      title: "¡Mensaje Enviado!",
      text: "Gracias por escribirnos. Nos pondremos en contacto contigo lo antes posible.",
      icon: "success",
      confirmButtonColor: "#d3122a", // Rojo Primicia
      confirmButtonText: "Entendido",
      background: "#ffffff",
      customClass: {
        title: "font-weight-bold",
      },
    }).then(() => {
      // Limpiar la URL para que no se repita la alerta al recargar
      const cleanUrl =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname;
      window.history.replaceState({ path: cleanUrl }, "", cleanUrl);
    });
  } else if (status === "error") {
    Swal.fire({
      title: "Error",
      text: "Hubo un problema al enviar tu mensaje. Por favor, intenta más tarde.",
      icon: "error",
      confirmButtonColor: "#d3122a",
    });
  }
});
//========================================================================================
//=====================================   BLOG  ====================================
//========================================================================================

document.addEventListener("DOMContentLoaded", () => {
  // Carrusel Blog
  const blogTrack = document.getElementById("blogTrack");
  const blogNext = document.getElementById("blogNext");
  const blogPrev = document.getElementById("blogPrev");
  let blogIndex = 0;

  if (blogTrack && blogNext) {
    const moveBlog = (dir) => {
      const cardWidth =
        document.querySelector(".blog-card-wrapper").offsetWidth + 20;
      const max = blogTrack.children.length - 3; // Muestra 3 tarjetas

      if (dir === "next") blogIndex = blogIndex >= max ? 0 : blogIndex + 1;
      else blogIndex = blogIndex <= 0 ? max : blogIndex - 1;

      blogTrack.style.transform = `translateX(-${blogIndex * cardWidth}px)`;
    };

    blogNext.addEventListener("click", () => moveBlog("next"));
    blogPrev.addEventListener("click", () => moveBlog("prev"));
  }

  // Modal Blog
  const blogModal = document.getElementById("blogModal");
  if (blogModal) {
    blogModal.addEventListener("show.bs.modal", (event) => {
      const btn = event.relatedTarget;
      document.getElementById("blogModalTitle").innerText =
        btn.getAttribute("data-titulo");
      document.getElementById("blogModalContent").innerHTML =
        btn.getAttribute("data-contenido");
      document.getElementById("blogModalImg").src =
        btn.getAttribute("data-img");
      document.getElementById("blogModalCat").innerText =
        btn.getAttribute("data-cat");
    });
  }
});
