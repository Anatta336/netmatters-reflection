export default function sideMenu(
  menuButtonSelector = '.hamburger-menu',
  scrimHolderSelector = '.page-scrim-holder',
  menuContentSelector = '.menu-content') {
  const menuButton = document.querySelector(menuButtonSelector);
  const scrimHolder = document.querySelector(scrimHolderSelector);
  const menu = document.querySelector(menuContentSelector);
  let isMenuOpen = false;

  menuButton.addEventListener('click', onClickMenuButton);

  function onClickMenuButton(event) {
    event.stopImmediatePropagation();
    toggle();
  }
  
  function onClickPageWithMenuOpen(event) {
    // don't allow clicks to trigger interactions on the page contents
    // TODO: is this needed?
    // event.preventDefault();
    hide();
  }

  function toggle() {
    console.log('toggle', isMenuOpen);
    if (isMenuOpen) {
      hide();
    } else {
      show();
    }
  }

  function show() {
    console.log('show');
    scrimHolder.classList.add('menu-open');
    scrimHolder.addEventListener('click', onClickPageWithMenuOpen);
    isMenuOpen = true;
  }

  function hide() {
    console.log('hide');
    scrimHolder.classList.remove('menu-open');
    scrimHolder.removeEventListener('click', onClickPageWithMenuOpen);
    isMenuOpen = false;
  }

  return {
    toggle,
    show,
    hide
  };
};