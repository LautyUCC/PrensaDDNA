<?php
/** Line icons for quick links. @package DDNA_Theme */
$ddna_icon = isset( $args['icon'] ) ? $args['icon'] : 'default';
$paths = array(
	'consultas' => '<path d="M8 10h28v20H20l-8 7v-7H8z"/><path d="M15 16h14M15 22h10"/><path d="M28 7h14v17h-6"/>',
	'talleres'  => '<circle cx="18" cy="17" r="6"/><path d="M8 38c1-8 5-12 10-12s9 4 10 12M29 10h14v15H32l-5 4v-4h-3M34 15h5M34 20h4"/>',
	'datos'     => '<rect x="7" y="7" width="38" height="34" rx="3"/><path d="M12 14h28M15 34V24M22 34V18M29 34V27M36 34V21"/>',
	'recursos'  => '<rect x="7" y="9" width="38" height="30" rx="3"/><path d="m22 18 12 7-12 7zM18 44h16"/>',
	'subsede'   => '<path d="M26 45s13-12 13-24a13 13 0 1 0-26 0c0 12 13 24 13 24Z"/><circle cx="26" cy="21" r="5"/><path d="M9 44h34"/>',
	'mapeo'     => '<path d="M8 14h36v28H8zM15 14V8h22v6M18 22h16M18 28h16M18 34h10"/><circle cx="8" cy="14" r="3"/><circle cx="44" cy="14" r="3"/><circle cx="8" cy="42" r="3"/><circle cx="44" cy="42" r="3"/>',
	'default'   => '<circle cx="26" cy="26" r="18"/><path d="M26 17v11M26 35h.01"/>',
);
?>
<svg class="quick-access-card__icon" viewBox="0 0 52 52" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $paths[ isset( $paths[ $ddna_icon ] ) ? $ddna_icon : 'default' ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></svg>
