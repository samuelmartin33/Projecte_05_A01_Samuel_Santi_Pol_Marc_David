/**
 * trabajos-index.js — carousel de ofertas de trabajo
 */
(function () {
    var carousel = document.getElementById('trabajosCarousel');
    if (!carousel) return;
    var cards = carousel.querySelectorAll('.carousel-oferta');
    var dots  = document.querySelectorAll('#carouselDots .carousel-dot');
    var current = 0;
    var timer;

    function goTo(index) {
        cards[current].classList.remove('active');
        if (dots[current]) dots[current].classList.remove('active');
        current = (index + cards.length) % cards.length;
        cards[current].classList.add('active');
        if (dots[current]) dots[current].classList.add('active');
    }

    function startTimer() {
        timer = setInterval(function () { goTo(current + 1); }, 3200);
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            clearInterval(timer);
            goTo(parseInt(this.dataset.index));
            startTimer();
        });
    });

    startTimer();
})();
