const swiper = new Swiper("#swiper1", {
    spaceBetween: 20,
    slidesPerView: 6,
    breakpoints: {
        1: {
            slidesPerView: 1,
        },
        350: {
            slidesPerView: 2,
        },
        580: {
            slidesPerView: 3,
        },
        770: {
            slidesPerView: 4,
        },
        1200: {
            slidesPerView: 5,
        },
        1400: {
            slidesPerView: 6,
        },
    },
    navigation: {
        nextEl: ".swiper-button-next-trending",
        prevEl: ".swiper-button-prev-trending",
    },
});


const swiper1 = new Swiper("#swiper2", {
    spaceBetween: 15,
    slidesPerView: 8,
    breakpoints: {
        0: {
            slidesPerView: 2,
        },
        350: {
            slidesPerView: 3,
        },
        708: {
            slidesPerView: 4,
        },
        800: {
            slidesPerView: 5,
        },
        992: {
            slidesPerView: 6,
        },
        1200: {
            slidesPerView: 7,
        },
        1400: {
            slidesPerView: 8,
        }
    },
    navigation: {
        nextEl: ".swiper-button-next-recommended",
        prevEl: ".swiper-button-prev-recommended",
    },
});
