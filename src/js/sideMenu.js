export default function sideMenu(
    menuButtonSelector = '.hamburger-menu',
    scrimHolderSelector = '.page-scrim-holder',
    menuContentSelector = '.menu-content',
  ) {
  const menuButton = document.querySelector(menuButtonSelector);
  const scrimHolder = document.querySelector(scrimHolderSelector);
  const menu = document.querySelector(menuContentSelector);
  let isMenuOpen = false;

  const toInformOfMenuState = [
    menuButton,
    scrimHolder,
    menu,
  ];

  menuButton.addEventListener('click', onClickMenuButton);

  function informOfMenuState() {
    if (isMenuOpen) {
      toInformOfMenuState.forEach((element) => {
        element.classList.add('menu-open');
      });
    } else {
      toInformOfMenuState.forEach((element) => {
        element.classList.remove('menu-open');
      });
    }
  }

  function onClickMenuButton(event) {
    // stop propagation so it can't also trigger onClickPageWithMenuOpen
    event.stopImmediatePropagation();
    toggle();
  }
  
  function onClickPageWithMenuOpen(event) {
    hide();
  }

  function toggle() {
    if (isMenuOpen) {
      hide();
    } else {
      show();
    }
  }

  function show() {
    isMenuOpen = true;
    informOfMenuState();
    scrimHolder.addEventListener('click', onClickPageWithMenuOpen);
  }

  function hide() {
    isMenuOpen = false;
    informOfMenuState();
    scrimHolder.removeEventListener('click', onClickPageWithMenuOpen);
  }

  return {
    toggle,
    show,
    hide
  };
};
