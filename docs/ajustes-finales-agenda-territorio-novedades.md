# Ajustes finales locales — Agenda, Territorio y Novedades

Fecha: 7/10/2026. Branch `feature/agenda-prevention-guides`, HEAD `ff8e85f80a5da35d48900187929a81caec6e928c`. No se hizo commit, stage, push, merge, pipeline remoto, deploy ni acceso al VPS. Se conserva todo el lote anterior de Agenda/Guías y los archivos personales previos del usuario.

## Agenda

Se agregó `agenda` a `ddna_theme_is_institutional_page()` en `inc/template-tags.php`. Esto reutiliza sin duplicar el header existente y `template-parts/navigation/primary.php`, con las clases `site-header--home` y `primary-navigation--home`, el mismo HTML, CSS y JS que Contacto/Normativa/Convenios. No se oculta el logo institucional: se reemplaza la navegación horizontal tradicional por el desplegable existente.

Los seis destinos del menú coinciden exactamente con Contacto. Se verificó apertura/cierre, aria-expanded, cierre con Escape, calendario y cambio octubre/noviembre. Resoluciones desktop 1512px, tablet 768px y móvil 390px, sin desbordamiento de página. Agenda conserva eventos, API y sus 34 pruebas.

## Territorio

Anterior: `app/themes/ddna-theme/assets/images/territorio/mapa-cordoba.png` (fondo naranja).

Fuente nueva provista: `RECURSOS GRÁFICOS - WEB DDNA 2026/mapa cordoba.png`.

Nuevo asset servido: `app/themes/ddna-theme/assets/images/territorio/mapa-cordoba-2026.png`. Su nuevo nombre evita reutilizar una copia del recurso anterior en caché; el archivo previo queda preservado sin ser usado por el panel.

Ambos tienen 1009 × 1559 px; la silueta y divisiones mantienen geometría/posición. El nuevo PNG usa transparencia. No se editaron porcentajes de marcadores, popovers, datos, botones, textos, CSS o JS de Territorio. Solo se sustituyó la URL del recurso en `template-parts/home/panel-territorio.php`.

SHA256 del nuevo asset y del PNG provisto: `6587902993fd15d4e40e3a9524f62dd909257b5077c21598488505917d565ab7`.

HTTP 200 y bytes idénticos a la fuente. Se abrieron/cerraron fichas de Córdoba Capital, Colonia Caroya, Bell Ville, Cosquín, Cruz del Eje y Justiniano Posse, también mediante teclado. Las fichas y logos MUNA existentes se conservaron. Se revisó escala y disposición de los marcadores en desktop/tablet/móvil; la concentración de marcadores del área central es la misma que en la implementación anterior.

## Novedades

El botón Home “Ver más novedades” enlaza a `/category/novedades/`. Esa URL caía en `archive.php`, cuyo `the_archive_title()` agrega `Category:`; no utilizaba la página/template `page-novedades.php`, el buscador ni el espaciado de `news-archive.css`. La causa del pegado al footer era esa ruta de template/CSS, no cierres HTML incorrectos ni un footer con position fuera del flujo.

Se agregó `category-novedades.php` que reutiliza `page-novedades.php`, sin duplicar el listado ni cambiar la URL. `inc/enqueue.php` carga los estilos existentes también en esa categoría. Se conserva `.news-archive` con padding-block y el panel existente; se midieron 60.48px de separación panel/footer en desktop y 40px en tablet/móvil, sin solapamiento. No se agregaron br ni espaciadores manuales.

`inc/news-archive.php` configura solo la consulta principal pública de esa categoría: 12 por página, orden fecha/ID descendente, buscador `buscar` sanitizado. El ID desempata noticias con la misma fecha: una prueba con trece noticias detectó repetidos entre páginas y confirmó la corrección. Otros archivos/categorías mantienen su comportamiento.

El template usa la consulta principal existente en categoría, sin una consulta duplicada; sigue soportando su uso como página. Formularios y paginación usan la URL correcta del archivo y conservan el término de búsqueda. H1 “Novedades”, sin prefijo automático; filtro de título limitado a esa categoría. Se limitan títulos de tarjeta a tres líneas; imágenes conservan object-fit:cover y tarjetas/grids/responsive existentes.

No existe una página publicada en `/novedades/` en esta DB; no se creó otra ruta. La URL comprobada y enlazada desde Home es `/category/novedades/`.

## Guías y revisión general

Se mantiene el inventario del lote anterior: dos PDFs propios migrados a Medios local y dos enlaces originales de Drive con nueva pestaña/noopener noreferrer. `verify-prevention-guides.php` valida cuatro destinos únicos y PDFs por firma/hash. No se volvió a importar ni duplicar contenido de guías.

Home, Agenda, Guías, Territorio y Novedades se revisaron en navegador a 1512/768/390px. Sin overflow de página ni imágenes rotas visibles; consola sin errores JS en las cinco vistas observadas. Novedades muestra dos columnas en desktop/tablet y una en móvil, búsqueda UNICEF funcional, imágenes proporcionadas, footer después del contenido. Las capturas durante animaciones de entrada se actualizaron cuando fue necesario para revisión estable.

## Tests y límites

- `verify-agenda.php`: **34 verificaciones pasan**.
- `verify-prevention-guides.php`: **4 destinos únicos válidos**.
- `python3 scripts/verify-final-local.py`: **4 grupos de comprobaciones pasan**: menú idéntico a Contacto, PNG exacto servido por HTTP, 12 noticias/paginación sin repeticiones/título/estructura footer, búsqueda/paginación del término/cero resultados. Crea trece noticias ficticias marcadas solo en localhost y las elimina en finally; se confirmó cero fixtures restantes.
- PHP syntax checks de los archivos modificados/nuevos: OK. La suite antigua también comprobó 80 PHP sin error sintáctico.
- `node --check` de Agenda y Territorio: OK. `git diff --check`: OK.
- `verify-feedback-prensa-octubre.php`: **falla** “Convenios: trece imágenes…”. El shortcode actual produce 12 imágenes; esta ronda no modifica Convenios, sus assets ni sus datos.
- `verify-final-sept-2026.php`: **falla** “Página: comunicados”. La página existe/publicada con título “Comunicados y pronunciamientos”; el manifiesto antiguo espera “Comunicados”. Esta ronda no modifica esa página ni el manifiesto.

Estas dos suites no están verdes. Se documentan sus diferencias con el estado local actual, sin cambiar contenidos fuera del alcance ni ajustar tests antiguos para ocultarlas. No se hicieron pruebas en dispositivos físicos ni auditoría integral de accesibilidad.

## Archivos de esta ronda

Modificados sobre el trabajo previo:
- `app/themes/ddna-theme/inc/template-tags.php`
- `app/themes/ddna-theme/inc/enqueue.php`
- `app/themes/ddna-theme/functions.php`
- `app/themes/ddna-theme/page-novedades.php`
- `app/themes/ddna-theme/assets/css/components/news-archive.css`
- `app/themes/ddna-theme/template-parts/home/panel-territorio.php`
- `docs/agenda-prevention-guides.md` (referencia a esta ronda)

Nuevos:
- `app/themes/ddna-theme/category-novedades.php`
- `app/themes/ddna-theme/inc/news-archive.php`
- `app/themes/ddna-theme/assets/images/territorio/mapa-cordoba-2026.png`
- `scripts/verify-final-local.py`
- `docs/ajustes-finales-agenda-territorio-novedades.md`

Sin temporales, backups, logs, archivos del sistema ni credenciales detectadas en los archivos del lote. Evidencia/capturas/logs de verificación se guardan fuera del repo en `../ajustes-finales-local-20261007/`. Los recursos personales preexistentes no se borraron.

## Git — salidas exactas

`git status` (incluye los cambios previos conservados):

```text
On branch feature/agenda-prevention-guides
Changes not staged for commit:
  (use "git add/rm <file>..." to update what will be committed)
  (use "git restore <file>..." to discard changes in working directory)
	modified:   .gitattributes
	modified:   "RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/cooperaci\303\263n internacional.png"
	modified:   "RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/desarrollo intengral.png"
	modified:   "RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/entre pantallas.png"
	modified:   "RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/formaci\303\263n para adultos.png"
	modified:   "RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/participaci\303\263n para nnya.png"
	modified:   "RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/va con vos.png"
	deleted:    "RECURSOS GR\303\201FICOS - WEB DDNA 2026/mapa.png"
	modified:   app/plugins/ddna-core/ddna-core.php
	modified:   app/plugins/ddna-core/inc/admin.php
	modified:   app/plugins/ddna-core/inc/meta-fields.php
	modified:   app/themes/ddna-theme/assets/css/components/news-archive.css
	modified:   app/themes/ddna-theme/functions.php
	modified:   app/themes/ddna-theme/inc/enqueue.php
	modified:   app/themes/ddna-theme/inc/template-tags.php
	modified:   app/themes/ddna-theme/page-novedades.php
	modified:   app/themes/ddna-theme/template-parts/components/return-home-menu.php
	modified:   app/themes/ddna-theme/template-parts/home/panel-quiero-saber.php
	modified:   app/themes/ddna-theme/template-parts/home/panel-territorio.php

Untracked files:
  (use "git add <file>..." to include in what will be committed)
	DDNA_Flujo_CI_CD.docx
	"RECURSOS GR\303\201FICOS - WEB DDNA 2026/CAMPA\303\221AS FOTOS/"
	"RECURSOS GR\303\201FICOS - WEB DDNA 2026/CONVENIOS LOGOS/"
	"RECURSOS GR\303\201FICOS - WEB DDNA 2026/Gu\303\255as para una crianza cuidada - WEB/"
	"RECURSOS GR\303\201FICOS - WEB DDNA 2026/PROGRAMAS FOTOS/entre pantallas(1).png"
	"RECURSOS GR\303\201FICOS - WEB DDNA 2026/mapa cordoba.png"
	app/plugins/ddna-core/inc/agenda.php
	app/themes/ddna-theme/assets/css/components/agenda.css
	app/themes/ddna-theme/assets/images/territorio/mapa-cordoba-2026.png
	app/themes/ddna-theme/assets/js/agenda.js
	app/themes/ddna-theme/category-novedades.php
	app/themes/ddna-theme/inc/agenda.php
	app/themes/ddna-theme/inc/news-archive.php
	app/themes/ddna-theme/page-agenda.php
	content/media/prevention/
	content/prevention-guides.json
	docs/agenda-prevention-guides.md
	docs/ajustes-finales-agenda-territorio-novedades.md
	scripts/apply-prevention-guides.php
	scripts/seed-agenda-local.php
	scripts/verify-agenda.php
	scripts/verify-final-local.py
	scripts/verify-prevention-guides.php

no changes added to commit (use "git add" and/or "git commit -a")
```

`git diff --stat` (los nuevos archivos sin stage no aparecen en este comando):

```text
 .gitattributes                                     |   1 +
 .../cooperaci\303\263n internacional.png"          | Bin 3083812 -> 2648572 bytes
 .../PROGRAMAS FOTOS/desarrollo intengral.png"      | Bin 1689996 -> 1504453 bytes
 .../PROGRAMAS FOTOS/entre pantallas.png"           | Bin 852276 -> 759139 bytes
 .../formaci\303\263n para adultos.png"             | Bin 721193 -> 651222 bytes
 .../participaci\303\263n para nnya.png"            | Bin 3980216 -> 3384480 bytes
 .../PROGRAMAS FOTOS/va con vos.png"                | Bin 1103952 -> 1033543 bytes
 .../mapa.png"                                      | Bin 1004656 -> 0 bytes
 app/plugins/ddna-core/ddna-core.php                |   1 +
 app/plugins/ddna-core/inc/admin.php                |   5 ++++-
 app/plugins/ddna-core/inc/meta-fields.php          |   7 +++++++
 .../assets/css/components/news-archive.css         |   2 +-
 app/themes/ddna-theme/functions.php                |   4 ++++
 app/themes/ddna-theme/inc/enqueue.php              |   8 +++++++-
 app/themes/ddna-theme/inc/template-tags.php        |   5 ++++-
 app/themes/ddna-theme/page-novedades.php           |  12 +++++++-----
 .../template-parts/components/return-home-menu.php |   6 ++++--
 .../template-parts/home/panel-quiero-saber.php     |  12 +++++++-----
 .../template-parts/home/panel-territorio.php       |   2 +-
 19 files changed, 48 insertions(+), 17 deletions(-)
```

No hay commit nuevo. WordPress local sigue funcionando en http://localhost:8080/. Detenerse aquí y esperar aprobación explícita antes de cualquier publicación.

## Último ajuste: navegación de Novedades

Se incluyó `is_category('novedades')` y el slug de página `novedades` en `ddna_theme_is_institutional_page()`. El header anterior provenía de la rama tradicional de `header.php` + `template-parts/navigation/primary.php`; ahora Novedades reutiliza su rama institucional, idéntica a Agenda y Contacto, sin copiar HTML/CSS/JS ni generar un segundo menú. Se conserva la URL `/category/novedades/` también en búsqueda/paginación.

`page-novedades.php` incluye `template-parts/components/return-home-menu.php` antes del panel de noticias. Se agregaron dos argumentos opcionales al componente (`label`, `url`): Novedades muestra “Volver al inicio” y enlaza a `home_url('/')`; los demás usos mantienen “Volver al menú” y el fragmento `#menu-principal-home`. Se conserva la clase, icono SVG, CSS, posición antes del contenido, hover/focus y comportamiento responsive. Agenda y Novedades comparten tanto el menú como el componente de regreso. No se agregó un estilo independiente.

Archivos de esta última ronda: `inc/template-tags.php`, `page-novedades.php`, `template-parts/components/return-home-menu.php`, `scripts/verify-final-local.py` y esta documentación.

Validación: cinco grupos HTTP pasan (incluido menú y regreso en búsqueda/página 2), 34 pruebas de Agenda pasan y cuatro guías válidas. PHP y git diff --check OK. Mouse y Enter del botón de regreso llegan a Home; menú abre/cierra y Escape restablece aria-expanded. Comparación de Agenda/Novedades/Contacto a 1512/768/390px: un solo menú, mismos destinos, sin overflow, sin imágenes rotas. Home/Guías/Territorio revisados en esos tamaños; se preservan mapa/destinos. Separación panel/footer: 60.48px desktop, 40px tablet/móvil. Consola del recorrido sin errores JS. Las dos diferencias de suites antiguas documentadas arriba permanecen fuera de este ajuste. Evidencias fuera del repo: `../navegacion-novedades-local-20261007/`. Sin nuevo commit ni publicación.
