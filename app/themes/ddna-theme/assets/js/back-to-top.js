/** Home-only return control; no scroll listeners or timers. */
(() => {
 const button = document.querySelector('.back-to-top');
 const start = document.querySelector('#main-content');
 if (!button || !start) { return; }
 button.hidden = false;
 button.addEventListener('click', () => {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  // Move keyboard context to the page start without a second scroll.
  if (!start.hasAttribute('tabindex')) { start.setAttribute('tabindex', '-1'); }
  start.focus({ preventScroll: true });
  window.scrollTo({ top: 0, behavior: reduced ? 'instant' : 'smooth' });
 });
})();
