// smooth scroll
$('nav a, .hero_btn').on('click', function (e) {
    if (this.hash !== '') {
        e.preventDefault();

        const hash = this.hash;

        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 1000);
    }
});

// doctor of the month carousel
$('.owl-carousel').owlCarousel({
    loop: true,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplaySpeed: 1000,
    autoplayHoverPause: true,
});

