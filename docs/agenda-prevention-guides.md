# Agenda y Guías para la Prevención — revisión local, 7/10/2026

Estado: implementado en `feature/agenda-prevention-guides`, desde `ff8e85f80a5da35d48900187929a81caec6e928c`. Sin commit, push, PR, merge, SSH ni deploy. La aprobación previa de otros lotes no autoriza publicar este lote.

## Arquitectura y Agenda

WordPress local usa el theme padre `ddna-theme` y el plugin propio `ddna-core`; no hay child theme, Elementor ni ACF. Se reutilizan el modelo de campos, metaboxes, nonces, capabilities y sanitizadores existentes. La página Agenda existente (ID 67) se conserva y usa `page-agenda.php`.

El CPT `agenda_evento` vive en `app/plugins/ddna-core/inc/agenda.php`; no tiene archivo público ni single/rewrite que colisione con `/agenda/`. Aparece como Agenda en administración. Usa título y descripción del editor nativo, y `_agenda_fecha` (YYYY-MM-DD), `_agenda_hora` (HH:mm, 00:00–23:59), `_agenda_lugar` (texto). Solo Agenda utiliza el editor clásico: un único formulario guarda título, descripción y metaboxes juntos. Otros tipos mantienen su editor actual.

Para cargar: entrar a http://localhost:8080/wp-admin/ → Agenda → Añadir Evento, completar los cinco datos y publicar. Se puede editar, pasar a borrador o enviar a papelera con controles nativos. Una fecha/hora/lugar inválidos o título/descripción vacíos impiden publicar: se guarda como borrador y se muestra aviso. Nonce y `edit_post` protegen el guardado; se ignoran revisiones y autosaves. REST valida antes de escribir. Los borradores pueden quedar incompletos.

Se mantienen capabilities estándar de posts, coherentes con los CPT existentes: administrador/editor administran Agenda; autores conservan permisos estándar sobre contenido propio; suscriptores y visitantes no crean ni editan. No se agregaron roles ni privilegios globales.

El calendario propio es una tabla mensual de lunes a domingo, con tarjetas desplegables nativas `<details>` para fecha/hora/lugar/descripción. Es más simple que incorporar FullCalendar para este alcance (sin arrastrar, vistas semanales ni recurrencia). Conserva tipografía Forma DJR, naranja #ff8c00, botones pill y bordes institucionales. Solo `/agenda/` carga los nuevos CSS/JS. En móvil hay desplazamiento horizontal dentro del calendario, sin desbordamiento de página, y lista vertical de detalles para consulta fácil.

El primer mes llega renderizado por PHP. Navegación GET `?agenda_month=1..12`, tabla, enlaces y detalles nativos funcionan sin JS. Con JS, un fetch actualiza el mes sin recargar; solicitudes anteriores se cancelan, se anuncia carga/error y se conserva el calendario anterior si falla. Enlaces a eventos abren el detalle y enfocan su resumen; teclado nativo Enter/Espacio abre/cierra. No hay modal ni trampa de foco.

GET `/wp-json/ddna/v1/agenda?year=2026&month=10` devuelve year/month/today/timezone y eventos con exclusivamente id/title/date/time/location/description. Se restringe al año actual de WordPress y meses 1–12. La API editorial `/wp/v2/agenda-eventos` requiere permisos de edición también para leer; evita exponer autor/metadatos internos a visitantes. Escrituras usan autenticación y permisos REST nativos.

Una consulta mensual limita `post_type=agenda_evento`, estado publicado, sin contraseña, fechas del mes, máximo 500 eventos; orden fecha/hora/título/ID y cache de meta batched (sin N+1). Los pasados siguen visibles, hoy se marca y no se convierte la fecha ISO por UTC. El entorno local está configurado con zona **+00:00**: no se cambió. El calendario usa `current_datetime`, `wp_timezone` y `wp_date`, respetando la configuración que exista en cada WordPress.

Se dejaron seis eventos claramente ficticios: dos el 5/10 (09:00,15:30), uno el 17/10, uno el 28/11, uno en enero y otro en diciembre de 2026. `seed-agenda-local.php` permite recrearlos, solo en localhost y WP_ENVIRONMENT_TYPE=local. No son contenido para producción. La prueba manual adicional ID183 quedó en papelera recuperable.

## Guías: inventario antes del reemplazo

Fuente inspeccionada visualmente y mediante enlaces DOM: https://ddna.cba.gov.ar/guias-para-la-prevencion-2/. La fuente bloquea descargas HTTP de terminal con 403: los dos PDFs se descargaron mediante navegador normal. Los enlaces originales actuales apuntan a julio de 2025; no se usaron enlaces antiguos de auditorías previas. Inventario previo y HTML fuente conservados fuera del repo en `../agenda-prevention-local-20261007/`.

| Guía | URL original | Tipo | Acción | URL final local |
|---|---|---|---|---|
| Guía de Navegación Segura | [Original](https://drive.google.com/file/d/1gF1JkFpE3MZ9MJtt7teQpS3za6j5MhF2/view) | Drive externo | Enlace exacto conservado; nueva pestaña con noopener noreferrer | [Recurso final](https://drive.google.com/file/d/1gF1JkFpE3MZ9MJtt7teQpS3za6j5MhF2/view) |
| Guía de Juegos en Línea | [Original](https://ddna.cba.gov.ar/wp-content/uploads/2025/07/Guía-Juegos-en-Línea.pdf) | PDF interno | Descargado, validado y agregado a Medios local; deduplicación SHA256 | [Recurso final](http://localhost:8080/wp-content/uploads/2026/10/guia-prevencion-juegos-en-linea.pdf) |
| Guía sobre abuso sexual hacia NNyA | [Original](https://ddna.cba.gov.ar/wp-content/uploads/2025/07/Guía-para-la-prevención-del-Abuso-y-el-Acoso-Sexual-hacia-NNyA-2.pdf) | PDF interno | Descargado, validado y agregado a Medios local; deduplicación SHA256 | [Recurso final](http://localhost:8080/wp-content/uploads/2026/10/guia-prevencion-abuso-sexual.pdf) |
| Guía sobre Bullying | [Original](https://drive.google.com/file/d/1Y7D2JJpMqpAUn0ypyD3E7kmu5uElzYwY/view) | Drive externo | Enlace exacto conservado; nueva pestaña con noopener noreferrer | [Recurso final](https://drive.google.com/file/d/1Y7D2JJpMqpAUn0ypyD3E7kmu5uElzYwY/view) |

Se migraron **2 PDFs** y se mantuvieron **2 enlaces Drive**, sin descargar contenido externo. Los PDFs nuevos están versionados en `content/media/prevention/`, con hash/tamaño/origen en `content/prevention-guides.json`. Juegos en Línea: 15 páginas, 3501241 bytes; Abuso/Acoso Sexual: 24 páginas, 1181660 bytes. Firma PDF, MIME, parseo estricto con pypdf y SHA256 validan que no sean páginas HTML/error. pypdf se utilizó como verificación externa al repo; no es dependencia del sitio.

`apply-prevention-guides.php` valida el manifiesto completo antes de escribir; por defecto es dry-run, y solo permite entorno local/localhost. Busca PDFs existentes por hash del archivo real, reutiliza si coincide y crea solo los faltantes mediante Media Library. Repetir apply mantuvo 30 PDFs antes/después: sin duplicados. Solo actualiza los cuatro documentos `prevencion-*`, asociando `_ddna_file_id` o `_ddna_external_url`. Otras guías/documentos/uploads se conservan.

Las cuatro cajas mantienen etiquetas, orden, iconos 47–50, CSS, hover y responsive. Cambió la resolución del destino usando slugs explícitos y el campo externo existente. Drive usa target=_blank y rel=noopener noreferrer. No quedan PDFs de estas cajas apuntando al servidor anterior ni placeholders.

Todos los destinos respondieron HTTP 200. Los PDFs locales sirvieron application/pdf y bytes válidos; los Drive mostraron los documentos `Guías-de-Buenas-Prácticas.pdf` y `RevistaBULLYING-2022.pdf`. No hubo recursos sin validar.

## Comandos y resultados locales

```sh
docker compose --profile tools run --rm cli eval-file /var/www/html/scripts/verify-agenda.php
docker compose --profile tools run --rm cli eval-file /var/www/html/scripts/verify-prevention-guides.php
docker compose --profile tools run --rm cli eval-file /var/www/html/scripts/apply-prevention-guides.php
# Aplicación acotada local, ya ejecutada:
docker compose --profile tools run --rm -e DDNA_PREVENTION_MODE=apply cli eval-file /var/www/html/scripts/apply-prevention-guides.php
```

- Agenda: **34 verificaciones pasan** (CPT, formulario, roles, nonce, sanitización, fechas/bisiesto/horas, año/mes, orden, drafts/contraseña, API pública limitada, API editorial protegida, publicación válida e incompleta).
- Guías: **4 recursos únicos válidos**, sin vacío/# ni duplicados; importación repetida idempotente.
- PHP: syntax check del plugin/theme y cuatro scripts nuevos; JS Agenda: node --check; git diff --check.
- wp-admin real: login, alta/publicación, edición hora, borrador, papelera recuperable, con persistencia verificada.
- Navegador: meses octubre/noviembre/enero/diciembre sin salir de 2026; evento abre detalle y enfoca resumen. Resoluciones desktop, tablet 768px y móvil 390px, sin overflow de página. Guías: cuatro destinos exactos, atributos externos, iconos y foco de teclado, desktop/móvil. No se hicieron pruebas en dispositivos físicos ni auditoría automatizada integral de accesibilidad.
- HTTP sin JS: Agenda enero/octubre/diciembre con tabla y detalles presentes. API HTTP ordena los dos del mismo día y oculta el evento en papelera. Home, Agenda, Informes, Normativa, Contacto y Novedades responden 200 sin fatal PHP; assets Agenda ausentes fuera de Agenda. Los paneles Home Territorio/Quiero conocer permanecen.

## Archivos del lote

Modificados (7):
- `.gitattributes` (preserva bytes de los dos PDFs, sin normalización/diff textual)
- `app/plugins/ddna-core/ddna-core.php`
- `app/plugins/ddna-core/inc/admin.php`
- `app/plugins/ddna-core/inc/meta-fields.php`
- `app/themes/ddna-theme/functions.php`
- `app/themes/ddna-theme/inc/enqueue.php`
- `app/themes/ddna-theme/template-parts/home/panel-quiero-saber.php`

Creados (13, incluyendo esta documentación):
- `app/plugins/ddna-core/inc/agenda.php`
- `app/themes/ddna-theme/inc/agenda.php`
- `app/themes/ddna-theme/page-agenda.php`
- `app/themes/ddna-theme/assets/css/components/agenda.css`
- `app/themes/ddna-theme/assets/js/agenda.js`
- `content/prevention-guides.json`
- `content/media/prevention/guia-prevencion-juegos-en-linea.pdf`
- `content/media/prevention/guia-prevencion-abuso-sexual.pdf`
- `scripts/apply-prevention-guides.php`
- `scripts/seed-agenda-local.php`
- `scripts/verify-agenda.php`
- `scripts/verify-prevention-guides.php`
- `docs/agenda-prevention-guides.md`

Los cambios previos de recursos gráficos/archivo DOCX del usuario se mantuvieron sin incorporar al lote. Git status y diff --stat completos se conservan en la carpeta de evidencia, con inventario adicional de archivos nuevos (git diff --stat no los incluye al estar sin stage). No se creó commit local. Contenedores locales siguen corriendo en :8080.

## Punto de control

Etapa local terminada. Esperar aprobación explícita del usuario antes de push/publicación. No se tocó VPS, producción, Dashboard, CI/CD ni infraestructura. La importación productiva no está habilitada por estos scripts: se debe preparar en la segunda fase autorizada siguiendo el flujo existente, con respaldo y rollback, sin publicar fixtures.

## Ajustes finales posteriores

El menú de Agenda y los ajustes de Territorio/Novedades de la ronda siguiente están documentados en [ajustes-finales-agenda-territorio-novedades.md](ajustes-finales-agenda-territorio-novedades.md). El estado Git y listado anterior describen el primer lote; consultar ese informe para el estado acumulado vigente.


## Publicación autorizada — 7 de octubre de 2026

El usuario autorizó «APROBADO PARA PUSHEAR Y PUBLICAR EN LA VPS». Se publica desde la rama feature existente, sin integrar main ni modificar CI/CD, utilizando el reemplazo controlado del servicio WordPress del VPS de revisión. El importador exige aprobación/origen exactos y journal privado fuera del docroot; solo crea las cuatro guías nuevas, deduplica dos PDFs por hash y conserva Drive. En VPS bloquea cualquier slug preexistente. Rollback verifica que no haya ediciones posteriores y elimina únicamente registros/archivos creados por ese release. Nunca ejecutar seed-agenda-local, verify-agenda ni fixtures de verify-final-local en VPS.
