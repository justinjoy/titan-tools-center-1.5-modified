//Scroll Animations
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    // console.log(entry);
    if (entry.isIntersecting) {
      entry.target.classList.add('animations-show-item');
      observer.unobserve(entry.target);
    } else {
      entry.target.classList.remove('animations-show-item');
    }
  });
});

const hiddenElements = document.querySelectorAll('.animations-hidden-item');
hiddenElements.forEach((el) => observer.observe(el));

jQuery('document').ready(function () {
  // Main Banner Swiper
  const swiper = new Swiper('.product-swiper', {
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false, // Similar to autoplayHoverPause: true logic, but Swiper handles it differently.
    },
    lazy: true, // Lazy loading
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    // Breakpoints for responsive design
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      600: {
        slidesPerView: 1,
      },
      1000: {
        slidesPerView: 1,
      },
      1200: {
        slidesPerView: 1,
      }
    }
  });

  // Right Side Swiper
  const swiperRight = new Swiper('.product-swiper-right', {
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    lazy: true,
    spaceBetween: 20, // margin: 20 equivalent
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      600: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      781: {
        slidesPerView: 1,
      },
      1000: {
        slidesPerView: 1,
      },
      1200: {
        slidesPerView: 1,
      }
    }
  });
});