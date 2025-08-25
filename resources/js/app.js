import "./bootstrap";
module.exports = {
    theme: {
        extend: {
            keyframes: {
                fadeSlider: {
                    "0%, 100%": { opacity: "0" },
                    "10%, 30%": { opacity: "1" },
                    "35%, 100%": { opacity: "0" },
                },
            },
            animation: {
                fadeSlider: "fadeSlider 15s infinite",
            },
        },
    },
};
// import Swiper from "swiper/bundle";
// import "swiper/css/bundle";

// const swiper = new Swiper(".swiper", {
//     slidesPerView: 3,
//     spaceBetween: 10,
//     slidesPerGroup: 1,
//     loop: true,
//     navigation: {
//         nextEl: ".swiper-button-next",
//         prevEl: ".swiper-button-prev",
//     },
//     breakpoints: {
//         640: { slidesPerView: 1 },
//         768: { slidesPerView: 2 },
//         1024: { slidesPerView: 4 },
//     },
// });
