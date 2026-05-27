/**
 * welcome.js
 * Animación de contadores en la sección de estadísticas de la landing.
 * Se activa cuando la sección .proof-stats entra en el viewport.
 */

/**
 * Anima todos los elementos .stat-num[data-target] contando hasta su valor objetivo.
 */
function animarContadores() {
    var els = document.querySelectorAll('.stat-num[data-target]');
    for (var i = 0; i < els.length; i++) {
        (function(el) {
            var target    = parseInt(el.getAttribute('data-target'), 10);
            var suffix    = el.getAttribute('data-suffix') || '';
            var duration  = 1600;
            var steps     = 60;
            var stepTime  = duration / steps;
            var current   = 0;
            var increment = target / steps;
            var timer = setInterval(function() {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = Math.floor(current).toLocaleString('es-ES') + suffix;
            }, stepTime);
        })(els[i]);
    }
}

/* Lanzar cuando la sección .proof-stats entra en pantalla */
var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            animarContadores();
            observer.disconnect();
        }
    });
}, { threshold: 0.3 });

var proofSection = document.querySelector('.proof-stats');
if (proofSection) observer.observe(proofSection);
