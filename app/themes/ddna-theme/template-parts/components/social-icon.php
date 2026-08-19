<?php
/** Accessible decorative social network icon. @package DDNA_Theme */
$network = $args['network'] ?? '';
$paths = array(
	'facebook' => '<path d="M15 8h4V3h-4c-4 0-7 3-7 7v3H4v5h4v11h5V18h5l1-5h-6v-3c0-1 1-2 2-2Z"/>',
	'instagram' => '<rect x="3" y="3" width="26" height="26" rx="7"/><circle cx="16" cy="16" r="6"/><circle cx="24" cy="8" r="1" fill="currentColor" stroke="none"/>',
	'x' => '<path d="M5 4l18 24M26 4 7 28M5 4h7l14 24h-7Z"/>',
	'youtube' => '<path d="M29 10c0-4-2-6-6-6H9c-4 0-6 2-6 6v12c0 4 2 6 6 6h14c4 0 6-2 6-6Z"/><path d="m13 11 8 5-8 5Z"/>',
	'google-play' => '<path d="M5 3v26l20-13ZM5 3l14 13M5 29l14-13"/>',
);
?>
<svg class="social-icon" viewBox="0 0 32 32" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $paths[ $network ] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></svg>
