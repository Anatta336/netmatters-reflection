import Glide from '@glidejs/glide';

// unhide anything waiting to know that JavaScript is available
document.querySelectorAll('.hiddenIfNoScript').forEach((element) => {
  element.style.display = 'block';
});

const glide = new Glide('.glide', {
  type: 'carousel',
  startAt: 0,
  perView: 1,
  perTouch: 1,
  gap: 0,
  autoplay: 4500,
  hoverpause: true,
  animationDuration: 250,
}).mount();
