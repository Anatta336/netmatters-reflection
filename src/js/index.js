import Glide from '@glidejs/glide';

const glide = new Glide('.glide', {
  type: 'carousel',
  startAt: 0,
  perView: 1,
  perTouch: 1,
  autoplay: 4500,
  hoverpause: true,
}).mount();
