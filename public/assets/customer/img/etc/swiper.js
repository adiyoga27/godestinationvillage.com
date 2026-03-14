import Swiper from 'swiper';

setTimeout(() => {
    new Swiper('.swiper-container', {
     spaceBetween: 30,
     centeredSlides: true,
     loop: true,
     speed: 1000,
     autoplay: {
       delay: 3500,
       disableOnInteraction: false,
     },
     pagination: {
       el: '.swiper-pagination',
       clickable: true,
     },
     navigation: {
       nextEl: '.swiper-button-next',
       prevEl: '.swiper-button-prev',
     },
   });

    new Swiper('.tours-slider.swiper-container', {
     slidesPerView: 3,
     spaceBetween: 20,
     centeredSlides: true,
     loop: true,
     speed: 1000,
     autoplay: {
       delay: 3500,
       disableOnInteraction: false,
     },
     navigation: {
       nextEl: '.swiper-button-next',
       prevEl: '.swiper-button-prev',
     },
   });
 }, 400);