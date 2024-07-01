// core version + navigation, pagination modules:
import Swiper from 'swiper';

import 'Swiper/swiper-bundle.css';
import SwiperCore,{ Navigation, Pagination } from 'swiper/core';
SwiperCore.use([Navigation, Pagination ]);
// import Swiper and modules styles


const swiper = new Swiper('.swiper-container', {
    // Optional parameters
    // direction: 'vertical',
    loop: true,

    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
      el: '.swiper-scrollbar',
    },
  });
