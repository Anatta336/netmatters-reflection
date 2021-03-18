/**
 * Makes the given header element behave as a "peeking" header,
 * which appears at the top of the screen whenever the user scrolls up.
 * @param {HTMLElement} header The header that should be shown at the top of the screen.
 * @param {HTMLElement} scrollingElement The element to monitor for scroll events.
 * @param {HTMLElement} cloneHolder An element that will hold the cloned version of the header.
 */
export default function floatingHeader(
    header,
    scrollingElement,
    cloneHolder,
  ) {

  const activeClassName = 'header-active';
  const cloneClassName = 'cloned-header';

  /**
   * Deep cloned copy of the header, which is displayed when user scrolls upwards.
   * @type {HTMLElement}
   */
  const clonedHeader = header.cloneNode(true);
  clonedHeader.classList.add(cloneClassName);
  cloneHolder.appendChild(clonedHeader);

  /**
   * @type {HTMLInputElement}
   */
  const searchInputOnClone = clonedHeader.querySelector("input[type='search']");
  
  /**
   * @type {HTMLInputElement}
   */
  const searchInputOnOriginal = header.querySelector("input[type='search']");

  mimicInput(searchInputOnClone, searchInputOnOriginal);
  mimicInput(searchInputOnOriginal, searchInputOnClone);

  let prevScrollTop = 0;
  scrollingElement.addEventListener('scroll', function(event) {
    if (scrollingElement.scrollTop <= prevScrollTop
      && scrollingElement.scrollTop > header.clientHeight) {
      makeActive();
    } else {
      makeInactive();
    }
    prevScrollTop = scrollingElement.scrollTop;
  });

  let isActive = false;

  /**
   * 
   * @param {HTMLInputElement} source
   * @param {HTMLInputElement} destination 
   */
  function mimicInput(source, destination) {
    source.addEventListener('input', () => {
      destination.value = source.value;
    });
  }

  function makeActive() {
    if (isActive) {
      return;
    }

    isActive = true;
    clonedHeader.classList.add(activeClassName);
  }

  function makeInactive() {
    if (!isActive) {
      return;
    }

    isActive = false;
    clonedHeader.classList.remove(activeClassName);
  }

  return {
    makeActive,
    makeInactive,
  };
};
