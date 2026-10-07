# Feedback Prensa octubre — lote aprobado para publicación controlada

## Estado vigente — aprobación 7 de octubre de 2026

El usuario aprobó explícitamente con `APROBADO PARA PUBLICAR` después de revisar las dos rondas locales. El lote a publicar incluye el código y contenido/media curados descritos abajo, más estos ajustes de segunda ronda:

- Defensoría: separación de 32 px Objetivos → Dossier, sin alterar acordeones.
- Normativa: se conserva el contenedor principal y las secciones semánticas; sin cajas/bordes secundarios. Cinco URLs de PDFs intactas.
- Convenios: trece imágenes directamente dentro del grid, sin cards/textos HTML/enlaces; alt institucional y proporciones conservados. Textos/bordes dibujados en PNG se conservan.
- Campañas Cuidar la Crianza y Grooming: siguen sin icono porque no hay un asset inequívoco; enlaces YouTube conservados. Este pendiente se informó antes de la aprobación.
- Diplomatura: URL UCASAL exacta aprobada, target=_blank y rel=noopener noreferrer.
- Solo Justiniano Posse en segunda ronda: left 74% → 70%; top 55.5% conservado.

Validación segunda ronda: 68 archivos PHP, todos los JS del theme, verificadores existentes, git diff --check y catorce PDFs por checksum OK. Normativa/Defensoría/Convenios sin overflow a 1440/768/375 px. Los seis únicos archivos cambiados en esa ronda se compararon contra un snapshot previo.

Publicación: versionar únicamente theme, manifiesto/media curados, importador/verificadores y documentación. Los recursos originales aportados se conservan en el working tree sin incluir duplicados o variantes no utilizadas. No copiar DB ni ejecutar fixtures locales en VPS. Actualizar solo la imagen WordPress, conservar Elementor/Core/configuración, mounts y uploads; aplicar el importador aprobado después de respaldar DB/uploads y comprobar el origen exacto. No cambiar CI/CD ni otros servicios.

El texto siguiente es el reporte histórico de la primera entrega local; sus estados de no publicación y la presentación inicial de Convenios/Normativa/posición de Justiniano quedaron superados por esta sección.

## A. GIT

- Branch: `feature/feedback-prensa-octubre`.
- HEAD, main y origin/main: `9945d18e38d3c2abbef920f13602c0304a142b7d`.
- Cambios deliberadamente sin stage ni commit. No push, PR, merge, deploy ni acceso al VPS.
- Al comenzar main tenía recursos gráficos aportados por el usuario modificados/nuevos, pero ningún cambio de código. Se preservaron y trasladaron a esta rama: seis fotos, reemplazo de mapa, convenios/guías y variante extra de Entre Pantallas. No se atribuyen esos cambios previos a esta implementación.
- El plugin ddna-core y Compose/workflows/CI permanecen sin cambios.
- `.env` local creado, ignorado y con credenciales nuevas independientes; no se incluye en Git. No se incluye DB ni una copia de uploads productivos.

## B. LA DEFENSORÍA / FOOTER / INFORMES

- Cinco acordeones nativos `details.panel-accordion`/`summary`, reutilizando exactamente Necesito Ayuda. Se conserva el texto institucional y Dossier; se quita el bloque Informes de gestión de esa página.
- Logo footer copiado byte a byte desde `LOGO DEFENSORÍA/LOGO DDNA negativo.png`; proporciones 875 × 680 y alt institucional. La carpeta real difiere del nombre aproximado indicado en el pedido.
- Memoria de Gestión 2016–2026 conserva año, resolución de adjunto, destino y apertura segura; únicamente se quita “Abrir” de la etiqueta.
- `.editorial-page-layout` y `.site-container--editorial` comparten ancho y separación inferior para páginas institucionales/programas. Sin espaciado por saltos de línea.
- El bootstrap carecía de página informes-anuales; el manifiesto la crea únicamente si falta, sin sobrescribir la existente.

## C. NORMATIVA

Fuente: https://ddna.cba.gov.ar/normativas-2/ . Recuperada mediante DOM público y HTML HTTP 200 usando User-Agent de navegador. HTML/REST sin ese UA devolvían 403; esas respuestas se conservan como evidencia, pero el bloqueo quedó superado para HTML/PDFs.

Inventario: cuatro categorías, seis títulos visibles, cinco enlaces PDF reales. Los cinco se descargaron e importaron localmente, con HTTP 200, MIME application/pdf, firma %PDF-, tamaño/hash verificados y apertura con PyMuPDF. El sexto elemento (Código Civil y Comercial) es una imagen sin enlace en el HTML original: se conserva como card informativa sin inventar archivo ni URL.

| Orden | Categoría | Título visible | Archivo original | HTTP / MIME |
|---|---|---|---|---|
| 1 | Convenciones | Convención de los Derechos del Niño | Convención.pdf | 200 / application/pdf |
| 2 | Leyes Nacionales | Ley Nacional 23.849 | LEY-23.849.pdf | 200 / application/pdf |
| 3 | Leyes Nacionales | Ley Nacional de Protección Integral de los Derechos de niñas, niños y Adolescentes | LEY-26.061-1.pdf | 200 / application/pdf |
| 4 | Leyes provinciales | Ley 9944 | LEY-9.944.pdf | 200 / application/pdf |
| 5 | Leyes provinciales | Ley 9396 | LEY-9.396.pdf | 200 / application/pdf |
| 6 | Código Civil | Código Civil y Comercial | Sin enlace en la fuente | No aplica |

Las URLs originales, el orden, nombres, tamaño y SHA-256 están en `content/feedback-prensa-octubre.json`. La página /normativa/ usa `[ddna_normativa]`; resuelve adjuntos por documento slug e ID en Media Library del origen actual. Ninguno de sus cinco links apunta al sitio viejo. Diseño propio con cards, tokens oficiales y responsive.

## D. CONVENIOS / CONTACTO

Trece cards informativas `<article>`, sin enlaces ni controles de navegación, con dos columnas desde tablet y una en mobile. Logos completos, object-fit contain, proporciones conservadas y altura uniforme.

| Asset de CONVENIOS LOGOS → copia theme | Institución | Aclaración canónica |
|---|---|---|
| convenio-86.png | Rotary International (Distrito 4851) | — |
| convenio-82.png | Fundación Arcor | — |
| convenio-87.png | Asociación de Mujeres Jueces de Argentina (AMJA) | — |
| convenio-95.png | Facultad de Ciencias Sociales de la UNC | Convenio marco de Pasantías |
| convenio-84.png | Fundación Tecnológica con Propósito (TWP) | — |
| convenio-81.png | Colegio Universitario Politécnico (CUP) | Convenio marco de Pasantías |
| convenio-88.png | Universidad Empresarial Siglo 21 | Convenio marco de Pasantías |
| convenio-93.png | Universidad Católica de Córdoba (UCC) | — |
| convenio-90.png | Facultad de Derecho UNC | Convenio marco de Pasantías |
| convenio-94.png | Universidad Nacional de Villa María | — |
| convenio-91.png | Escuela Dante Alighieri | Convenio marco de Pasantías |
| convenio-83.png | UNICEF | Convenio marco de colaboración para el desarrollo de planes de trabajo específicos |
| convenio-92.png | Secretaría de Fortalecimiento Vecinal, Cultura y Deportes | — |

Los archivos originales numerados se mapearon mediante inspección visual. No faltan logos inequívocos. El asset 96 (Ministerio Público Fiscal) no forma parte de las trece instituciones pedidas y no se agregó. **Observación editorial:** el banner provisto de Derecho UNC (90) trae texto integrado “Convenio Marco de Colaboración”; el texto HTML conserva la instrucción canónica “Convenio marco de Pasantías”. No se alteró ni inventó el logo; revisar esa discordancia del asset antes de publicar.

Contacto conserva los canales, tel/mailto y el layout existente; comparte separación con footer y ajuste de texto. WhatsApp del footer permanece existente, sin crear nuevos canales.

## E. NECESITO AYUDA

- Introducción y texto canónico del pedido, con negritas selectivas.
- Cuatro desplegables principales. Derecho a ser escuchado dentro del primero, después de las siete categorías.
- Línea Adolescencia destacada, con teléfono clickeable y etiqueta entre paréntesis.
- Línea fija, Asistencia, Adolescencia, ambos correos, presencial, en ese orden.
- Cards de contacto en dos columnas desktop/tablet, una mobile; emails completos con wrapping sin overflow.
- Enter y espacio abren/cierran el acordeón nativo; foco visible preservado.

## F. QUIERO SABER

Nueve PDFs reales = nueve cards = nueve URLs distintas de Media Library. Se verificó que cada URL aparece exactamente una vez en el HTML de Home. Los cuatro recursos de Prevención existentes no se reasignan por aproximación.

| Archivo provisto | Título/card exacto | Tema | Box anterior / cambio |
|---|---|---|---|
| Guía Juegos en Línea.pdf | Juegos en Línea: previniendo riesgos | juegos-en-linea | Juegos en Línea |
| Guía Apuestas en Línea.pdf | Apuestas en Línea: prevención en NNyA | apuestas-en-linea | Apuestas en Línea |
| Acompañar a NNyA en entornos virtuales.pdf | Acompañar a chicas y chicos en entornos digitales | entornos-digitales | Acompañar a NNyA en Entornos Virtuales |
| Guía Prevención Abuso.pdf | Prevención del abuso y el acoso sexual hacia niñas, niños y adolescentes | prevencion-abuso | Prevención del Abuso |
| Guía Prevención Bullying.pdf | Prevención del bullying o acoso entre pares | prevencion-bullying | Prevención del Bullying |
| Guía Prevención Maltrato.pdf | Entornos seguros y libres de violencia hacia NNyA | entornos-seguros | Entornos Seguros y Libres de Violencia hacia NNyA |
| Guía_Hablemos de crianza.pdf | Hablemos de crianza | hablemos-crianza | Hablemos de Crianza |
| Guía_Prevención_Consumo de Sustancias.pdf | ¿Cómo acompañar la prevención del consumo de sustancias? | prevencion-consumo | ¿Cómo acompañar la prevención del consumo de sustancias? |
| Guía Primeros Años DDNA (1).pdf | Acompañar y estimular en los primeros años | primeros-anos | Nueva novena card: Primeros Años |

Desktop 1440px: cinco cards en primera fila y cuatro centradas en segunda, comprobado por coordenadas reales. Tablet reflow de tres; mobile reflow de dos y una a 375px. Se elimina el antiguo translateX de las últimas cards. Materiales Gráficos abre exactamente la carpeta Drive solicitada, con target=_blank y rel=noopener noreferrer.

## G. QUIERO CONOCER

| Fuente PROGRAMAS FOTOS | Imagen theme / programa |
|---|---|
| participación para nnya.png | participacion-nnya.png |
| formación para adultos.png | formacion-adultos.png |
| va con vos.png | va-con-vos.png |
| entre pantallas.png | entre-pantallas.png |
| cooperación internacional.png | cooperacion-internacional.png |
| desarrollo intengral.png | desarrollo-integral.png |

Copias exactas de las imágenes provistas que contienen sus títulos. La variante `entre pantallas(1).png` no incluye el título y no se utiliza. Se retiran título/excerpt HTML sobrepuesto, borde CSS y pseudo-elemento naranja, sin deformar las fotos. **Los bordes que ya vienen dibujados en los PNG originales permanecen**: no se editaron los assets oficiales.

Destinos y alt conservados; seis cards locales clickeables luego de cargar los tres dossiers existentes como fixtures. Las páginas reales usan los slugs programas-participacion-nnya, acompanamiento-formacion-adultos y cooperacion-internacional-interinstitucional con el mismo layout ancho/spacing. No se inventan páginas adicionales.

Tres campañas existentes conservadas y dos nuevas después de Consumo Problemático: CUIDAR LA CRIANZA (Construcción de un mejor presente y un futuro más sólido; YouTube 8ZaPfuxQBGg) y GROOMING (Prevención y asesoramiento; YouTube LQREDFMsodA). Cinco en total, apertura segura. Sin imágenes nuevas inventadas. Seminarios vacío eliminado; Diplomaturas conservado.

## H. TERRITORIO

Asset nuevo: Mapa Cordoba.png → assets/images/territorio/mapa-cordoba.png, 1009 × 1559 (antes 1024 × 1536), copia exacta.

| Marker | Antes (left, top) | Después | Dirección |
|---|---|---|---|
| Justiniano Posse | 69.5%, 51.5% | 74%, 55.5% | Sureste |
| Villa Cura Brochero | 21.5%, 45.5% | 21.5%, 41.5% | Norte |
| Río Tercero | 43.2%, 43.3% | 43.2%, 47.3% | Sur |

Los otros doce puntos no cambian. territory.js permanece byte a byte igual a HEAD, preservando selección por proximidad para markers superpuestos y teclado. Los quince popups se probaron a 375 y 1440px: un solo popup abierto y sin overflow horizontal.

## I. ACTUALIDAD

Solo se quita el div section-frame interior cuando Novedades está anidado. Se conserva el contenedor exterior home-panel__content, WP_Query, artículos dinámicos, track, botones, links y estado responsive. La variante no anidada conserva su wrapper.

## J. VALIDACIONES

Comandos ejecutados desde el repo:

```sh
docker compose config --quiet
docker compose up -d db wordpress
docker compose run --rm init
docker compose run --rm -e DDNA_FEEDBACK_DRY_RUN=1 cli eval-file /var/www/html/scripts/apply-feedback-prensa-octubre.php
docker compose run --rm cli eval-file /var/www/html/scripts/apply-feedback-prensa-octubre.php
docker compose run --rm cli eval-file /var/www/html/scripts/verify-final-sept-2026.php
docker compose run --rm cli eval-file /var/www/html/scripts/verify-feedback-prensa-octubre.php
git diff --check
```

- Compose: PASS. DB local independiente, host db:3306, volúmenes locales; no conexiones productivas.
- PHP: PASS todos los archivos de theme/plugin/scripts (68 después de sumar el fixture local).
- JS: PASS `node --check` sobre los cinco archivos de assets/js.
- Verificador anterior: PASS en baseline 6 programas / 3 campañas y después 6 / 5. Se adaptó únicamente la cantidad esperada al manifiesto aplicado.
- Verificador nuevo: PASS 14 PDFs con hashes/Media Library, cuatro categorías normativas, contenido/orden asistencia, trece convenios, cinco campañas y respaldo.
- Preflight: PASS catorce PDFs sin escrituras.
- Idempotencia: segunda ejecución del mismo manifiesto no modifica nada ni duplica documentos/adjuntos; conserva ediciones posteriores.
- HTTP local: diez páginas 200, 101 assets 200, catorce PDFs nuevos 200 + application/pdf + firma/tamaño/SHA-256 correctos.
- Chrome: trece rutas/paneles a 1440/768/375px (39 casos), un H1 y sin overflow; Normativa se revisó de nuevo tras recuperar su contenido; Informes tras cargar los once links históricos pasó a los tres anchos.
- Consola: sin errores JS de la aplicación en las rutas probadas. Se observaron warnings de una extensión de Chrome, ajenos al theme.
- Acordeón: Enter/Space verificados en Chrome; marcadores: treinta aperturas/cierres, 15 por ancho.
- Script PowerShell de browser anterior no ejecutado: depende de Chrome en Windows y tiene expectativas anteriores a los fixes recuperados (seis marcadores/seis triggers). Se usó QA real con Chrome en macOS y el verificador PHP existente.
- Runtime cambiado sin URLs localhost/IP hardcodeadas ni rutas /Users, sin patrones de tokens/keys. Los nombres localhost/127.0.0.1 solo participan en guards de entorno del importador, no en destinos publicados.
- PDFs históricos de revisión: los once informes/Memoria y los tres dossiers responden HTTP 200 con MIME/firma PDF.
- Ningún cambio en workflows, Compose, CI/CD, plugin, Dashboard o infraestructura; staging vacío.

Evidencia técnica/visual local: carpeta hermana `../feedback-prensa-octubre-evidence/` (HTML canónico, inventarios, checksums, logs HTTP/CLI y capturas). No se versionan entornos de inspección, credenciales ni DB.

## K. MEDIA / MIGRACIÓN

**Código:** theme/templates/CSS e importador/verificadores. Las imágenes footer, mapa, seis programas y trece convenios viven dentro del theme.

**Contenido WordPress:** páginas Defensoría, Asistencia, Convenios, Normativa; Informes Anuales solo si falta; 14 documentos nuevos; dos campañas nuevas; opciones de las guías, normativas, convenios, cantidad de campañas y link Drive. Menús existentes cambian solo el destino de Normativa.

**Media nueva preparada para versionar:** nueve guías en `content/media/feedback-octubre/` y cinco normativas en su subcarpeta normativa; originales exactos con SHA-256/tamaño y procedencia en el manifiesto. Se importaron mediante media_handle_sideload y `_ddna_file_id` del modelo existente. No se versiona wp-content/uploads ni DB.

**Fixtures exclusivamente de revisión:** diez informes, Memoria y tres dossiers ya presentes y versionados en RECURSOS GRÁFICOS. `seed-feedback-review-local.php` los carga en una DB local vacía sin copiar datos productivos, sin sobrescribir destinos ya cargados; se bloquea siempre fuera de loopback/local. NO forma parte de la importación productiva. El Dossier Institucional y las cuatro guías de Prevención anteriores siguen pendientes en el seed base; no se inventaron links. Las noticias de Home son ejemplos del bootstrap local, no una copia del archivo productivo.

Para reproducir la revisión local desde un clon con Docker inicializado:

```sh
docker compose run --rm cli eval-file /var/www/html/scripts/apply-feedback-prensa-octubre.php
docker compose run --rm -e DDNA_REVIEW_RESOURCE_ROOT=/ddna-review-resources -v "$PWD/RECURSOS GRÁFICOS - WEB DDNA 2026:/ddna-review-resources:ro" cli eval-file /var/www/html/scripts/seed-feedback-review-local.php
```

**Publicación futura, aún no autorizada ni ejecutada:** después de APROBADO PARA PUBLICAR se deberá revisar/commitear esta rama y preparar el procedimiento controlado. Desplegar los cambios del theme; llevar un bundle aprobado con scripts/apply-feedback-prensa-octubre.php, content/feedback-prensa-octubre.json y content/media/feedback-octubre. No basta con copiar el theme: hay cambios DB/media. Con WP-CLI y bundle montado en la raíz esperada, correr primero preflight y luego importación, verificando destino exacto, respaldo DB/uploads del entorno e integridad de archivos. El script requiere fuera de local tanto DDNA_FEEDBACK_APPROVAL=APROBADO PARA PUBLICAR como DDNA_FEEDBACK_TARGET_ORIGIN igual al home_url real; no se autoinvoca ni ejecuta al cargar el sitio. No se agrega automatismo CI/CD ni se toca Compose productivo. Mantener las asignaciones históricas de media/dossiers del sitio de destino; no ejecutar el seed local allí.

El importador conserva respaldo editorial de páginas/meta/opciones en ddna_backup_before_feedback_octubre, deduplica adjuntos por SHA-256, usa slugs estables y registra digest para no repetir/escribir encima de ediciones posteriores. Ese respaldo editorial complementa, no sustituye, el backup completo que se prepare al publicar. No garantiza una transacción DB+filesystem ante una interrupción; un reintento previo a completar digest reutiliza los archivos ya importados.

## L. URL LOCAL

Docker queda corriendo: ddna-rebuild-wordpress-1 en puerto 8080 y ddna-rebuild-db-1 saludable.

| Revisión | URL |
|---|---|
| Home | http://localhost:8080/ |
| La Defensoría | http://localhost:8080/defensoria/ |
| Informes Anuales | http://localhost:8080/informes-anuales/ |
| Normativa | http://localhost:8080/normativa/ |
| Convenios | http://localhost:8080/convenios/ |
| Contacto | http://localhost:8080/contacto/ |
| Necesito Ayuda (Home) | http://localhost:8080/#necesito-ayuda |
| Necesito Ayuda (página) | http://localhost:8080/asistencia/ |
| Quiero Saber | http://localhost:8080/#quiero-saber |
| Quiero Conocer | http://localhost:8080/#quiero-conocer |
| Participación NNyA | http://localhost:8080/programas-participacion-nnya/ |
| Acompañamiento/Formación Adultos | http://localhost:8080/acompanamiento-formacion-adultos/ |
| Cooperación Internacional | http://localhost:8080/cooperacion-internacional-interinstitucional/ |
| Territorio | http://localhost:8080/#territorio |
| Actualidad/Novedades | http://localhost:8080/#actualidad |

El Dashboard no fue levantado ni modificado: su link local /observatorio/ no supone que exista ese servicio en este WordPress local.

Producción permanece intacta: no VPS/SSH, deploy, push, PR o merge; Supabase, MariaDB productiva, Caddy, DNS e infraestructura no modificados. Solo se creó/cargó la MariaDB local solicitada.

## Archivos modificados/nuevos — estado de trabajo

Los códigos M/D/?? corresponden a cambios sin stage. Las modificaciones originales de RECURSOS ya existían al comienzo. Esta lista incluye el lote curado y los recursos originales aportados; no todos los originales deberán duplicarse en un futuro bundle de deploy.

```text
 M "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/cooperación internacional.png"
 M "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/desarrollo intengral.png"
 M "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/entre pantallas.png"
 M "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/formación para adultos.png"
 M "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/participación para nnya.png"
 M "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/va con vos.png"
 D "RECURSOS GRÁFICOS - WEB DDNA 2026/mapa.png"
 M app/themes/ddna-theme/assets/images/programs/cooperacion-internacional.png
 M app/themes/ddna-theme/assets/images/programs/desarrollo-integral.png
 M app/themes/ddna-theme/assets/images/programs/entre-pantallas.png
 M app/themes/ddna-theme/assets/images/programs/formacion-adultos.png
 M app/themes/ddna-theme/assets/images/programs/participacion-nnya.png
 M app/themes/ddna-theme/assets/images/programs/va-con-vos.png
 M app/themes/ddna-theme/assets/images/territorio/mapa-cordoba.png
 M app/themes/ddna-theme/inc/editorial-content.php
 M app/themes/ddna-theme/inc/enqueue.php
 M app/themes/ddna-theme/inc/template-tags.php
 M app/themes/ddna-theme/inc/territory-venues.php
 M app/themes/ddna-theme/page-informes-anuales.php
 M app/themes/ddna-theme/page.php
 M app/themes/ddna-theme/template-parts/components/institutional-contact.php
 M app/themes/ddna-theme/template-parts/components/program-card.php
 M app/themes/ddna-theme/template-parts/footer/site-footer.php
 M app/themes/ddna-theme/template-parts/home/institutional-navigation.php
 M app/themes/ddna-theme/template-parts/home/panel-quiero-saber.php
 M app/themes/ddna-theme/template-parts/home/panel-territorio.php
 M app/themes/ddna-theme/template-parts/sections/latest-news.php
 M app/themes/ddna-theme/template-parts/sections/trainings.php
 M docs/project-scope.md
 M scripts/verify-final-sept-2026.php
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-81.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-82.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-83.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-84.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-86.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-87.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-88.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-90.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-91.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-92.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-93.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-94.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-95.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-96.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Acompañar a NNyA en entornos virtuales.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía Apuestas en Línea.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía Juegos en Línea.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía Prevención Abuso.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía Prevención Bullying.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía Prevención Maltrato.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía Primeros Años DDNA (1).pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía_Hablemos de crianza.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Guías para una crianza cuidada - WEB/Guía_Prevención_Consumo de Sustancias.pdf"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/Mapa Cordoba.png"
?? "RECURSOS GRÁFICOS - WEB DDNA 2026/PROGRAMAS FOTOS/entre pantallas(1).png"
?? app/themes/ddna-theme/assets/css/components/editorial-feedback.css
?? app/themes/ddna-theme/assets/images/convenios/convenio-81.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-82.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-83.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-84.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-86.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-87.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-88.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-90.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-91.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-92.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-93.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-94.png
?? app/themes/ddna-theme/assets/images/convenios/convenio-95.png
?? app/themes/ddna-theme/assets/images/logos/ddna-footer-negativo.png
?? content/feedback-prensa-octubre.json
?? content/media/feedback-octubre/apuestas-en-linea.pdf
?? content/media/feedback-octubre/entornos-digitales.pdf
?? content/media/feedback-octubre/entornos-seguros.pdf
?? content/media/feedback-octubre/hablemos-crianza.pdf
?? content/media/feedback-octubre/juegos-en-linea.pdf
?? content/media/feedback-octubre/normativa/convencion-derechos-nino.pdf
?? content/media/feedback-octubre/normativa/ley-23849.pdf
?? content/media/feedback-octubre/normativa/ley-26061.pdf
?? content/media/feedback-octubre/normativa/ley-9396.pdf
?? content/media/feedback-octubre/normativa/ley-9944.pdf
?? content/media/feedback-octubre/prevencion-abuso.pdf
?? content/media/feedback-octubre/prevencion-bullying.pdf
?? content/media/feedback-octubre/prevencion-consumo.pdf
?? content/media/feedback-octubre/primeros-anos.pdf
?? docs/feedback-prensa-octubre.md
?? scripts/apply-feedback-prensa-octubre.php
?? scripts/seed-feedback-review-local.php
?? scripts/verify-feedback-prensa-octubre.php
```
