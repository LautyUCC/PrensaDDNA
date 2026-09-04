/** Pause the decorative Home video when the visitor prefers reduced motion. */
(() => {
	const video = document.querySelector('.home-hero__video');

	if (!video || !window.matchMedia) {
		return;
	}

	const motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');
	const updatePlayback = () => {
		if (motionPreference.matches) {
			video.pause();
			video.removeAttribute('autoplay');
			return;
		}

		video.muted = true;
		video.play().catch(() => {});
	};

	updatePlayback();
	motionPreference.addEventListener('change', updatePlayback);
})();
