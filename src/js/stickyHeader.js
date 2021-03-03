/**
 * Makes the given header element behave as a sticky header
 * within the scrollingElement.
 * @param {HTMLElement} scrollingElement 
 * @param {HTMLElement} header 
 */
export default function stickyHeader(
    scrollingElement,
    header,
  ) {

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

  function makeActive() {
    header.classList.add('sticky-active');
    headerContainer.prepend(replacementDiv);
  }

  function makeInactive() {
    header.classList.remove('sticky-active');
    if (replacementDiv.parentNode && replacementDiv.parentNode === headerContainer) {
      headerContainer.removeChild(replacementDiv);
    }
  }

  return {
    makeActive,
    makeInactive,
  };
};
