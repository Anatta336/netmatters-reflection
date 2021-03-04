/**
 * 
 * @param {NodeListOf<HTMLElement>} menuButtons 
 * @param {HTMLElement} scrimHolder
 */
export default function sideMenu(
    menuButtons,
    scrimHolder,
  ) {
  let isMenuOpen = false;

  const menuOpenClassName = 'menu-open';

  /**
   * Array of elements that will be given the menu-open class when the menu is open.
   * @type {Array<HTMLElement>}
   */
  const toInformOfMenuState = [];
  addInformOnMenuOpen(scrimHolder);
  menuButtons.forEach(menuButton => {
    addInformOnMenuOpen(menuButton);
  });

  menuButtons.forEach(menuButton => {
    menuButton.addEventListener('click', onClickMenuButton);
  })

  /**
   * Broadcast menu state to all elements by setting their class.
   */
  function broadcastMenuState() {
    toInformOfMenuState.forEach(element => {
      updateMenuOpenClass(element);
    });
  }

  /**
   * Add or remove menu-open class to an element depending on whether the menu
   * is currently open.
   * @param {HTMLElement} element Element to set the class on.
   */
  function updateMenuOpenClass(element) {
    if (isMenuOpen) {
      element.classList.add(menuOpenClassName);
    } else {
      element.classList.remove(menuOpenClassName);
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
    broadcastMenuState();
    scrimHolder.addEventListener('click', onClickPageWithMenuOpen);
  }

  function hide() {
    isMenuOpen = false;
    broadcastMenuState();
    scrimHolder.removeEventListener('click', onClickPageWithMenuOpen);
  }

  /**
   * Adds element to have the menu-open class set whenwhen the menu is open.
   * @param {HTMLElement} element Element that will be given the menu-open class when the menu is open.
   */
  function addInformOnMenuOpen(element) {
    // apply current state
    updateMenuOpenClass(element);

    // inform of future changes
    toInformOfMenuState.push(element);
  }

  return {
    toggle,
    show,
    hide,
    addInformOnMenuOpen,
  };
};
