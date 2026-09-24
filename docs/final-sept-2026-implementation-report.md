# Implementación local — contenidos finales septiembre 2026

Fecha: 2026-09-14. Fuente aprobada: `DDNA_CONTENIDOS_FINAL_SEPT_2026_Codex.md`. Solicitud adjunta leída completamente y aplicada; las notas del documento de referencia no se trataron como contenido público.

## Protección inicial

- Repositorio inicial limpio, `main` en `39d66f9`.
- Checkpoint explícito `fcf4b52`: `checkpoint before final sept 2026 content update`.
- Respaldo de posts, metadatos y opciones afectables en `ddna_backup_before_final_sept_2026`, sin autoload; solo base local. No incluye contraseñas de usuarios.
- Ningún registro histórico, archivo, adjunto ni noticia eliminado. Las cinco entradas nativas publicadas anteriores permanecen; cuatro pertenecen a Novedades.
- No hubo push, despliegue, cambios de DNS, URLs productivas, redirecciones o escritura en producción.

## Cambios de contenido

- Portada conserva seis IDs/hashes, video, hero, iconos y sistema desplegable; nombres y bajadas ajustados a la fuente final. Los textos de accesos y del menú Home se obtienen del menú WordPress Accesos rápidos. Header mantiene abreviatura “Observatorio” por legibilidad; acceso central y panel usan el nombre completo.
- La Defensoría: ¿Quiénes somos?, ¿Qué hacemos?, misión, visión y objetivos con texto aprobado. Control de dossier preparado sin URL falsa. Informes de Gestión como apartado estructural provisional sin años/listado inventado.
- Submenú “Quiénes somos” apunta al contenido completo de La Defensoría, no a la página inicial vacía; la página y su slug antiguos se conservan, sin redirecciones.
- Necesito Ayuda: introducción, siete grupos de consultas, derecho a ser escuchado, equipo asesor, comunicación y consulta incierta; todo texto final. El contenido se administra en la página Asistencia y se reutiliza en la Home.
- Quiero Saber: cuatro guías de prevención, ocho de crianza, acceso de materiales y playlist exacta de recursos didácticos. Informes Anuales, Comunicados y Pronunciamientos y sublista temporal de materiales retirados de este panel. Etiquetas/archivos individuales de guías pendientes de validación porque la nueva fuente solo define cantidades.
- Programas: seis destacados aprobados, obtenidos del CPT existente. Tres remiten a páginas nuevas; tres esperan dossiers. Detrás del Humo permanece almacenado, pero no destacado ni mostrado en el carrusel final. Va con Vos y Entre Pantallas conservan slugs e imágenes existentes; sus textos temporales se sustituyen y no se inventan dossiers.
- Campañas: tres destacadas finales (Hay Otra Forma / Maltrato, Hay Otra Forma / Bullying y Ciberbullying, Consumo Problemático), con iconos oficiales existentes. Campañas demo anteriores dejan de ser destacadas; no se borran. Destinos pendientes no generan enlaces a páginas vacías.
- Capacitaciones: diplomatura IA y Derechos Digitales de NNyA; Seminarios preparado sin oferta ficticia. Retiradas del panel las etiquetas de diplomaturas/seminario anteriores.
- Centro de Mediación: retiradas referencias temporales de los paneles; no se eliminaron archivos ni historial.
- Territorio: seis markers, mapa, coordenadas y lógica compartida preservados. Planilla institucional cotejada; domicilio de Cosquín actualizado. MUNA enlaza a la página de cooperación con 20 municipios en cuatro cohortes; sin nuevos pins masivos.
- Observatorio: nombre completo y bajada final, sin consulta automática que presente contenidos no aprobados como indicadores finales.
- Actualidad: Agenda, Novedades y Prensa; Prensa incluye Medios y Comunicados. Novedades conserva posts, thumbnails, fecha, extracto, permalink, archivo, búsqueda y paginación nativa cuando hay suficientes resultados. Acceso Ver más añadido al archivo real.
- Comunicados: agrupación 2025/2022 y solo títulos; tres URLs pendientes, no presentes como publicaciones locales exactas. Pueden completarse en Ajustes o resolverse automáticamente desde posts/documentos publicados con esos títulos. Consultas acotadas por título, no recorrido masivo de noticias.
- Contacto: fijo 351 428 8881, asistencia y adolescencia, ambos correos y domicilio final centralizados en `ddna_institutional`; footer, Contacto y Asistencia reutilizan valores. Enlaces telefónicos `tel:` y correos `mailto:`.

## Páginas WordPress

Actualizadas conservando slugs: `/defensoria/`, `/asistencia/`, `/contacto/`.

Creadas (sin duplicados):

1. `/normativas/` — estructura, contenido pendiente.
2. `/convenios/` — los 12 convenios aprobados.
3. `/programas-participacion-nnya/` — Foros, Protagonismo, Activá y dos enlaces Drive.
4. `/acompanamiento-formacion-adultos/` — texto aprobado completo.
5. `/cooperacion-internacional-interinstitucional/` — ¿Qué hacemos?, MUNA, objetivos, 20 municipios y 4 cohortes.
6. `/agenda/` — estructura sin eventos ficticios.
7. `/prensa/` — accesos Medios y Comunicados.
8. `/defensoria-en-los-medios/` — estructura sin notas ficticias.
9. `/comunicados/` — títulos por año y destinos pendientes.

Todas usan el header, footer, plantilla `page.php`, contenedor y estilos existentes; las páginas aprobadas usan el componente visual de contenido. No se crearon CPT nuevos. Páginas sin material aprobado pueden estar deliberadamente vacías debajo de su título.

## Arquitectura y repetibilidad

- `content/final-sept-2026.json`: manifiesto versionado con el HTML del texto aprobado, páginas y selección final. No contiene dominios locales ni notas internas como contenido público.
- `scripts/apply-final-sept-2026.php`: actualización explícita exclusiva de LOCAL/loopback, respaldo previo, upsert por slug y marca `ddna_content_version`. Una segunda ejecución no sobrescribe cambios del administrador.
- `bootstrap-local.sh`: aplica el manifiesto al preparar una nueva instalación; scripts demo y navegación no reintroducen contenido viejo si ya hay versión final.
- `compose.yaml`: CLI e init reciben el mismo bloque de configuración que WordPress y mounts de scripts/contenido; sin duplicar dominios en distintos bloques. Corregida la discrepancia por la que CLI se identificaba como production pese a tratarse del sitio local.
- `ddna-core/inc/editorial-links.php`: opción y pantalla nativas con nonce de Settings API, permisos manage_options, whitelist y saneamiento de URLs; ninguna presentación pública en el plugin.
- `ddna-theme/inc/editorial-content.php`: renderización/shortcodes reutilizables de contacto, recursos, prensa y títulos. Contenido institucional queda en páginas editables; títulos/imagen/extracto/orden/destinos siguen en CPT.
- No se instaló ACF, Elementor, otro page builder ni dependencias JS. Elementor no está instalado en el estado real; no existe integración que preservar. Posts conservan `the_content()`.
- Para staging futuro: transferir código propio y contenido aprobado mediante procedimiento autorizado, y hacer search-replace estándar de URLs generadas en base de datos. Este actualizador local no puede ejecutarse en staging/producción.

## Correcciones técnicas acotadas

- Los porcentajes de pins mobile ahora se calculan sobre `.territory-map__canvas`, no sobre el wrapper que crece al aparecer un popup. Se corrige un desplazamiento preexistente sin cambiar coordenadas, estilos ni lógica de markers.
- Títulos de programas largos ya no quedan limitados arbitrariamente a tres líneas; ajuste fluido dentro del componente existente.
- Controles de campañas visibles cuando realmente existe overflow del carrusel; se preserva JS vanilla y navegación de teclado.
- Wrap fluido para nombres largos de Observatorio, contacto y nuevas páginas, sin introducir múltiples breakpoints arbitrarios.
- Links externos configurados usan target seguro y rel cuando corresponden; recursos no entregados usan controles no interactivos accesibles en vez de href vacío o #.

## Verificación realizada

- Chrome headless real mediante DevTools, sin agregar dependencias al sitio.
- Home y todos sus paneles, popups y doce páginas aprobadas: 320, 375, 430, 768, 1024, 1366, 1440 y 1920 px. Sin overflow horizontal accidental; checks de seis accesos, hashes, un único popup, estabilidad de markers, retorno del foco y controles de carrusel pasan. Cero errores JS capturados durante las interacciones.
- Tab, Shift+Tab, Enter, Espacio, Escape y foco visible: eventos reales en 375 y 1440 px; pasan. Hover/tooltip desktop comprobado; mobile no depende del hover.
- Capturas revisadas de Quiero Conocer en 375/1440; fallback sin imagen conservado donde faltan assets aprobados. Capturas de prueba excluidas de Git en `backups/`.
- Único H1 en Home y páginas aprobadas; ningún post histórico eliminado.
- Títulos H2 de secciones y H3 de tarjetas/temas/cohortes normalizados sin usarlos solo como decoración.
- PHP lint de 58 archivos de tema, plugin y scripts: sin errores.
- HTTP 200 en Home, doce páginas aprobadas, archivo Novedades, búsqueda y acceso wp-admin (redirección esperada a login sin sesión). No se accedió a un dashboard interactivo con credenciales ni se mostraron contraseñas.
- Settings API y metadatos propios mantienen permisos/saneamiento; formulario administrativo propio comprobado mediante renderización PHP autenticada simulada sin iniciar sesión ni cambiar roles.
- Base saludable, WordPress conectado, tema activo, CLI disponible, logs recientes sin warnings/fatales del sitio. `git diff --check` sin errores.
- Segunda ejecución del actualizador: salida de no-op confirmada.

## Archivos afectados

- Configuración: `compose.yaml`.
- Scripts: `bootstrap-local.sh`, `setup-home-content.php`, `setup-navigation.php`, `setup-institutional-settings.php`; nuevos `apply-final-sept-2026.php`, `verify-final-sept-2026.php`, `test-final-sept-2026.ps1`.
- Plugin: bootstrap, `institutional-settings.php`, `meta-fields.php`; nuevo `editorial-links.php`.
- Tema: bootstrap; nuevo `inc/editorial-content.php` y `components/institutional-contact.php`; componentes program/campaign-card, contenido de página, footer, quick-access, trainings, navegación primaria/institucional, paneles Ayuda/Saber/Actualidad/Observatorio/Territorio; CSS home-programs, home-campaigns, home-panels y territory.
- Datos/documentación: manifiesto, gap-analysis, pending e implementation-report, README y alcance/documentación administrativa actualizados.

## Pendientes y acceso

Consultar `final-sept-2026-pending.md`; los faltantes de URLs y contenido no fueron asumidos ni completados con material viejo.

Home: http://localhost:8080/ — Admin: http://localhost:8080/wp-admin/

Páginas enumeradas arriba bajo el mismo origen. Docker queda corriendo. Esta entrega sigue siendo local: la migración histórica completa permanece sin decidir.
