<?php
/** Server-first monthly calendar; template and enhancement share one public event model. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function ddna_theme_agenda_month_names() {
 return array( 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
}
function ddna_theme_agenda_month_label( $year, $month ) {
 return ddna_theme_agenda_month_names()[ $month - 1 ] . ' ' . $year;
}
function ddna_theme_agenda_grid( $year, $month, $events, $today ) {
 $first = new DateTimeImmutable( sprintf( '%04d-%02d-01', $year, $month ), wp_timezone() );
 $offset = (int) $first->format( 'N' ) - 1; $days = (int) $first->format( 't' ); $by_day = array();
 foreach ( $events as $event ) { $by_day[ $event['date'] ][] = $event; }
 ?>
 <table class="agenda-grid"><caption class="screen-reader-text"><?php echo esc_html( ddna_theme_agenda_month_label( $year, $month ) ); ?></caption><thead><tr>
 <?php foreach ( array( 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo' ) as $day ) : ?><th scope="col"><abbr title="<?php echo esc_attr( $day ); ?>"><?php echo esc_html( mb_substr( $day, 0, 1 ) ); ?></abbr></th><?php endforeach; ?>
 </tr></thead><tbody>
 <?php for ( $cell = 0; $cell < (int) ceil( ( $offset + $days ) / 7 ) * 7; $cell++ ) : ?>
  <?php if ( 0 === $cell % 7 ) { echo '<tr>'; } $day = $cell - $offset + 1; ?>
  <?php if ( $day < 1 || $day > $days ) : ?><td class="agenda-day--empty"></td>
  <?php else : $date = sprintf( '%04d-%02d-%02d', $year, $month, $day ); ?>
   <td class="agenda-day<?php echo $date === $today ? ' is-today' : ( $date < $today ? ' is-past' : '' ); ?>"><span class="agenda-day__number"><?php echo esc_html( $day ); ?><?php if ( $date === $today ) : ?><span class="screen-reader-text">, hoy</span><?php endif; ?></span>
   <?php foreach ( $by_day[ $date ] ?? array() as $event ) : ?>
    <a class="agenda-event" href="#agenda-event-<?php echo esc_attr( $event['id'] ); ?>" data-agenda-event="<?php echo esc_attr( $event['id'] ); ?>"><time datetime="<?php echo esc_attr( $date . 'T' . $event['time'] ); ?>"><?php echo esc_html( $event['time'] ); ?></time><span><?php echo esc_html( $event['title'] ); ?></span></a>
   <?php endforeach; ?></td>
  <?php endif; ?>
  <?php if ( 6 === $cell % 7 ) { echo '</tr>'; } ?>
 <?php endfor; ?>
 </tbody></table>
 <?php
}
function ddna_theme_agenda_details( $events ) {
 foreach ( $events as $event ) : ?>
 <details class="agenda-detail" id="agenda-event-<?php echo esc_attr( $event['id'] ); ?>"><summary><?php echo esc_html( $event['title'] ); ?></summary><dl><dt>Fecha</dt><dd><?php echo esc_html( wp_date( 'd/m/Y', ( new DateTimeImmutable( $event['date'], wp_timezone() ) )->getTimestamp(), wp_timezone() ) ); ?></dd><dt>Hora</dt><dd><?php echo esc_html( $event['time'] ); ?></dd><dt>Lugar</dt><dd><?php echo esc_html( $event['location'] ); ?></dd></dl><div class="agenda-description"><?php echo wp_kses_post( $event['description'] ); ?></div></details>
 <?php endforeach;
}
