/** Native silent loop; no scroll/timer pause and no playback-control listeners. */
(() => {
 const video = document.querySelector('.home-hero__video');
 if (!video) { return; }
 const preference = window.matchMedia('(prefers-reduced-motion: reduce)');
 video.loop = true;
 video.muted = true;
 const updatePreference = () => {
  if (preference.matches) { video.removeAttribute('autoplay'); video.pause(); }
  else {
   video.setAttribute('autoplay', '');
   video.play().catch(() => {}); // Do not override browser autoplay restrictions.
  }
 };
 preference.addEventListener('change', updatePreference);
 updatePreference();
})();
