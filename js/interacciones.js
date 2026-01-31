// Blog Histórico - JavaScript interactivo

document.addEventListener('DOMContentLoaded', function() {
    
    // Animación de entrada para tarjetas de artículos
    function animarTarjetas() {
        const tarjetas = document.querySelectorAll('.card-article');
        tarjetas.forEach((tarjeta, index) => {
            setTimeout(() => {
                tarjeta.classList.add('fade-in-up');
            }, index * 100);
        });
    }
    
    // Efecto de scroll suave para enlaces internos
    function setupScrollSuave() {
        const enlaces = document.querySelectorAll('a[href^="#"]');
        enlaces.forEach(enlace => {
            enlace.addEventListener('click', function(e) {
                e.preventDefault();
                const objetivo = document.querySelector(this.getAttribute('href'));
                if (objetivo) {
                    objetivo.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Animación del contador de likes
    function animarContadorLikes() {
        const botonesLike = document.querySelectorAll('.btn-like');
        botonesLike.forEach(boton => {
            boton.addEventListener('click', function() {
                if (!this.disabled) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                }
            });
        });
    }
    
    // Validación de formularios en tiempo real
    function setupValidacionFormularios() {
        const formularios = document.querySelectorAll('form');
        formularios.forEach(formulario => {
            const campos = formulario.querySelectorAll('.form-control');
            
            campos.forEach(campo => {
                campo.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
                
                campo.addEventListener('focus', function() {
                    this.classList.remove('is-invalid');
                });
            });
        });
    }
    
    // Efecto parallax en el hero
    function setupParallax() {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-section');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    }
    
    // Tooltips para elementos interactivos
    function setupTooltips() {
        const elementosConTooltip = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        elementosConTooltip.forEach(elemento => {
            new bootstrap.Tooltip(elemento);
        });
    }
    
    // Animación de estadísticas en panel admin
    function animarEstadisticas() {
        const statsCards = document.querySelectorAll('.stats-card h3');
        statsCards.forEach(card => {
            const valor = parseInt(card.textContent);
            let actual = 0;
            const incremento = Math.ceil(valor / 30);
            
            function actualizarValor() {
                if (actual < valor) {
                    actual += incremento;
                    if (actual > valor) actual = valor;
                    card.textContent = actual;
                    setTimeout(actualizarValor, 50);
                }
            }
            
            // Iniciar animación cuando sea visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        actualizarValor();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(card);
        });
    }
    
    // Efecto de partículas flotantes
    function crearParticulas() {
        const hero = document.querySelector('.hero-section');
        if (hero) {
            for (let i = 0; i < 20; i++) {
                const particula = document.createElement('div');
                particula.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: rgba(255,255,255,0.5);
                    border-radius: 50%;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    animation: flotar ${5 + Math.random() * 10}s infinite linear;
                `;
                hero.appendChild(particula);
            }
        }
    }
    
    // CSS para animación de partículas
    const style = document.createElement('style');
    style.textContent = `
        @keyframes flotar {
            0% { transform: translateY(0px) translateX(0px); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(50px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Inicializar todas las funciones
    animarTarjetas();
    setupScrollSuave();
    animarContadorLikes();
    setupValidacionFormularios();
    setupParallax();
    setupTooltips();
    animarEstadisticas();
    crearParticulas();
    
    // Efecto de ripple en botones
    document.querySelectorAll('.btn').forEach(boton => {
        boton.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                border-radius: 50%;
                background: rgba(255,255,255,0.5);
                left: ${x}px;
                top: ${y}px;
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // CSS para efecto ripple
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(rippleStyle);
});