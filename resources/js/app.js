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
// import Swiper JS
import Swiper from "swiper";
// import Swiper styles
import "swiper/css";

// const swiper = new Swiper(...);
const swiper = new Swiper(".swiper", {
    // Optional parameters
    direction: "vertical",
    loop: true,
    grabCursor: true,

    navigation: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,
    },
    autoplay: {
        delay: 3000, // 3 detik per slide
        disableOnInteraction: false, // tetap autoplay meskipun user swipe
    },

    // Navigation arrows
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },

    // And if we need scrollbar
    scrollbar: {
        el: ".swiper-scrollbar",
    },
});
