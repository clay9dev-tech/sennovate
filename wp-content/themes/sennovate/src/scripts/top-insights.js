let mobileSwiper = null;

function initMobileSwiper() {
  const container = document.querySelector('.js-mobile-swiper');
  const slides = container?.querySelectorAll('.post-card');

  if (!container || !slides) return;

  if (window.innerWidth <= 768 && !mobileSwiper) {
    mobileSwiper = new Swiper(container, {
      slidesPerView: 1.3,
      spaceBetween: 16,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  } else if (window.innerWidth > 768 && mobileSwiper) {
    mobileSwiper.destroy(true, true);
    location.reload(); // Reload to restore original layout
  }
}

document.addEventListener('DOMContentLoaded', initMobileSwiper);
window.addEventListener('resize', initMobileSwiper);
