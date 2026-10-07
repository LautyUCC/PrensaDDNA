<?php
/** Existing /agenda page; monthly calendar and native details work without JavaScript. */
get_header();
$now = current_datetime(); $year = (int) $now->format( 'Y' ); $month = (int) $now->format( 'n' );
if ( isset( $_GET['agenda_month'] ) && is_scalar( $_GET['agenda_month'] ) && preg_match( '/^(?:[1-9]|1[0-2])$/D', (string) $_GET['agenda_month'] ) ) { $month = (int) $_GET['agenda_month']; }
$events = function_exists( 'ddna_core_agenda_events' ) ? ddna_core_agenda_events( $year, $month ) : array();
?>
<main class="site-main" id="main-content"><div class="site-container agenda-container">
 <?php get_template_part( 'template-parts/components/return-home-menu' ); ?>
 <h1>Agenda</h1><p class="agenda-intro">Actividades de la Defensoría. Consultá los eventos de <?php echo esc_html( $year ); ?>.</p>
 <section class="agenda-calendar" data-agenda data-endpoint="<?php echo esc_url( rest_url( 'ddna/v1/agenda' ) ); ?>" data-year="<?php echo esc_attr( $year ); ?>" data-month="<?php echo esc_attr( $month ); ?>" data-today="<?php echo esc_attr( $now->format( 'Y-m-d' ) ); ?>" aria-labelledby="agenda-month-title">
  <nav class="agenda-toolbar" aria-label="Elegir mes de la Agenda">
   <a class="button button--outline" data-agenda-prev href="<?php echo esc_url( add_query_arg( 'agenda_month', max( 1, $month - 1 ), get_permalink() ) ); ?>"<?php if ( 1 === $month ) : ?> aria-disabled="true" tabindex="-1"<?php endif; ?>>Mes anterior</a>
   <form method="get" class="agenda-month-form"><label for="agenda-month">Mes</label><select name="agenda_month" id="agenda-month">
    <?php for ( $m = 1; $m <= 12; $m++ ) : ?><option value="<?php echo esc_attr( $m ); ?>" <?php selected( $m, $month ); ?>><?php echo esc_html( ddna_theme_agenda_month_names()[ $m - 1 ] ); ?></option><?php endfor; ?>
   </select><button type="submit">Ver mes</button></form>
   <a class="button button--outline" data-agenda-next href="<?php echo esc_url( add_query_arg( 'agenda_month', min( 12, $month + 1 ), get_permalink() ) ); ?>"<?php if ( 12 === $month ) : ?> aria-disabled="true" tabindex="-1"<?php endif; ?>>Mes siguiente</a>
  </nav>
  <h2 id="agenda-month-title"><?php echo esc_html( ddna_theme_agenda_month_label( $year, $month ) ); ?></h2>
  <p class="agenda-status" role="status" aria-live="polite"><?php echo $events ? 'Seleccioná una actividad para consultar sus detalles.' : 'No hay actividades publicadas para este mes.'; ?></p>
  <div class="agenda-grid-wrap" role="region" aria-label="Calendario mensual" tabindex="0"><?php ddna_theme_agenda_grid( $year, $month, $events, $now->format( 'Y-m-d' ) ); ?></div>
  <div class="agenda-details"><h3>Actividades del mes</h3><div data-agenda-details><?php ddna_theme_agenda_details( $events ); ?></div></div>
  <p class="agenda-timezone">Horarios según la zona configurada en WordPress: <?php echo esc_html( wp_timezone_string() ); ?>.</p>
 </section>
</div></main>
<?php get_footer(); ?>
