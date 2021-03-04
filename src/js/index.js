import Glide from '@glidejs/glide';
import cookies from './cookies';
import sideMenu from './sideMenu';
import stickyHeader from './stickyHeader';

// unhide anything waiting to know that JavaScript is available
document.querySelectorAll('.hidden-if-no-script').forEach((element) => {
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

const menu = sideMenu();
const header = stickyHeader(
  document.querySelector('.sticky-header'),
  document.querySelector('.page-content'),
  document.querySelector('.header-clone-holder'),
  'cloned-header'
);

const permission = cookies();

// check if user has previously accepted the use of cookies
if (!permission.checkForPermissionCookie()) {
  // if user hasn't yet accepted cookies, display the .cookie.check modal (was hidden by default in CSS)
  const cookieModal = document.querySelector('.cookie-check');
  cookieModal.style.display = 'block';

  // when user clicks the "accept cookies" button store a cookie and hide the modal again
  const acceptButton = document.querySelector('#cookies-accept');
  acceptButton.addEventListener('click', () => {
    permission.storePermissionCookie();
    cookieModal.style.display = 'none';
  });
}
