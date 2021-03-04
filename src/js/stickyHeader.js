/**
 * Makes the given header element behave as a "peeking" header,
 * which appears at the top of the screen whenever the user scrolls up.
 * @param {HTMLElement} header The header that should be shown at the top of the screen.
 * @param {HTMLElement} scrollingElement The element to monitor for scroll events.
 * @param {HTMLElement} cloneHolder An element that will hold the cloned version of the header.
 * @param {string} cloneClassName Class to add to the cloned version of the header.
 */
export default function stickyHeader(
    header,
    scrollingElement,
    cloneHolder,
    cloneClassName,
  ) {

  /**
   * @type {HTMLElement} Deep cloned version of the header
   */
  const clonedHeader = header.cloneNode(true);
  clonedHeader.classList.add(cloneClassName);
  cloneHolder.appendChild(clonedHeader);

  const replacementDiv = document.createElement('div');
  replacementDiv.style.height = header.clientHeight + "px";

  const headerContainer = header.parentNode;

  let prevScrollTop = 0;
  scrollingElement.addEventListener('scroll', function(event) {
    if (scrollingElement.scrollTop <= prevScrollTop) {
      // scrolling up or stationary
      makeActive();
    } else {
      // scrolling down
      makeInactive();
    }
    prevScrollTop = scrollingElement.scrollTop;
  });

  let isActive = false;

  function makeActive() {
    if (isActive) {
      return;
    }


  }

  function makeInactive() {
    if (!isActive) {
      return;
    }


  }

  return {
    makeActive,
    makeInactive,
  };
};
