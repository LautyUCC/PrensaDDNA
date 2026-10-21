# Actualización de contenidos de octubre 2026

Implementado y validado **solo en localhost**, pendiente de revisión del usuario. No se hizo commit, push, PR, merge, SSH, deploy ni intervención en producción. WordPress local queda funcionando en http://localhost:8080/.

## A. Subsedes

Los datos se almacenan en `app/themes/ddna-theme/inc/territory-venues.php`, fuente central de los marcadores y fichas del panel Territorio. El panel admite overrides del CPT `subsede`; no hay entradas locales de ese CPT que sobrescriban estos datos. No se encontraron duplicados activos de estos campos que requieran sincronización.

| Subsede / campo | Antes | Después |
| --- | --- | --- |
| Laguna Larga / dirección | Hipólito Yrigoyen esquina Julio A. Roca | Juan Domingo Perón esquina Entre Rios - Barrio Arturo Illia |
| San Francisco / atención | Lunes a viernes, 7:30 a 13:00 hs. | Lunes a viernes, 8:00 a 13:00 hs. |
| Las Varillas / atención | Lunes a viernes, 8:00 a 14:00 hs. | Lunes a viernes, 8:00 a 13:00 hs. |

No cambiaron teléfonos, correos, coordenadas, otras subsedes ni otros campos. Laguna Larga mantiene atención de 9:00 a 13:00 hs.

## B. Convenios

- Fuente exacta: `RECURSOS GRÁFICOS - WEB DDNA 2026/CONVENIOS LOGOS/convenio-92.png`.
- PNG de **1644 × 430 px**, 81.734 bytes; no se encontró otra variante con ese nombre. Es idéntico por SHA-256 al asset del tema ya existente.
- Ubicación final: `app/themes/ddna-theme/assets/images/convenios/convenio-92.png`; no fue necesario reemplazarlo ni duplicarlo.
- La institución ya estaba registrada en la opción `ddna_feedback_conventions`, posición 13, pero una condición del shortcode ocultaba su presentación. Se eliminó únicamente esa condición.
- El orden actual es el orden institucional explícito de la opción/manifiesto; no contiene fechas ni una regla de más recientes primero. Se conservó ese orden: Rotary, Arcor, AMJA, Ciencias Sociales UNC, TWP, CUP, Siglo 21, UCC, Derecho UNC, Villa María, Dante, UNICEF y **Secretaría de Fortalecimiento Vecinal, Cultura y Deportes**.
- Se muestran 13 imágenes en el contenedor existente, sin enlaces, textos o cards añadidos. Alt conservado y corroborado con el asset. Los otros doce no cambiaron.

## C. Novedades

Se recorrió íntegramente la nueva carpeta `RECURSOS GRÁFICOS - WEB DDNA 2026/FALTA PUBLICAR`: **ocho carpetas (4–11), 69 archivos editoriales: 56 PNG, siete PDF y seis DOCX**. Archivos del sistema ignorados. Se leyeron los documentos reales, incluyendo párrafos, énfasis, saltos, hipervínculos e instrucciones de imágenes. Cada fuente está identificada por checksum en el manifiesto.

Ninguna fuente define una fecha inequívoca de publicación. El usuario confirmó expresamente **21/10/2026 para las ocho**. WordPress guarda esa fecha real de publicación; se usa 12:00 como hora técnica uniforme, sin atribuirla al horario de los eventos. Las fechas de actividades en agosto se conservaron dentro del texto. El archivo mantiene fecha descendente; las ocho comparten día y hora.

| Novedad | Fecha | Featured Image exacta | Carrusel | Nº imágenes total | Estado |
| --- | --- | --- | --- | --- | --- |
| [Comenzó la segunda edición de la Diplomatura en Inteligencia Artificial y Derechos Digitales de Niñas, Niños y Adolescentes](http://localhost:8080/comenzo-la-segunda-edicion-de-la-diplomatura-en-inteligencia-artificial-y-derechos-digitales-de-ninas-ninos-y-adolescentes/) | 21/10/2026 | `PORTADA - Novedad web (7).png` (carpeta 4) | Sí: 1 foto(s) | 2 | UPDATED |
| [Comenzamos “El Juego de los Derechos” junto a chicas y chicos de barrio Sacchi](http://localhost:8080/comenzamos-el-juego-de-los-derechos-junto-a-chicas-y-chicos-de-barrio-sacchi/) | 21/10/2026 | `PORTADA - Novedad web.png` (carpeta 5) | Sí: 8 foto(s) | 9 | UPDATED |
| [Realizamos el segundo encuentro de “El juego de los derechos” junto a chicas y chicos de barrio Villa Unión](http://localhost:8080/realizamos-el-segundo-encuentro-de-el-juego-de-los-derechos-junto-a-chicas-y-chicos-de-barrio-villa-union/) | 21/10/2026 | `PORTADA - Novedad web (2).png` (carpeta 6) | Sí: 9 foto(s) | 10 | UPDATED |
| [Realizamos la charla abierta “Lo que no dicen con palabras. Señales, vínculos y violencias en las adolescencias. ¿Qué podemos hacer los adultos?”](http://localhost:8080/realizamos-la-charla-abierta-lo-que-no-dicen-con-palabras-senales-vinculos-y-violencias-en-las-adolescencias-que-podemos-hacer-los-adultos/) | 21/10/2026 | `PORTADA - Novedad web (3).png` (carpeta 7) | Sí: 1 foto(s) | 2 | UPDATED |
| [Entre Pantallas: continuamos promoviendo la ciudadanía digital en las escuelas durante agosto](http://localhost:8080/entre-pantallas-continuamos-promoviendo-la-ciudadania-digital-en-las-escuelas-durante-agosto/) | 21/10/2026 | `PORTADA - Novedad web (4).png` (carpeta 8) | No; 7 fotos dentro del texto | 8 | UPDATED |
| [Durante agosto, el programa Va con Vos continuó llegando a escuelas y comunidades](http://localhost:8080/durante-agosto-el-programa-va-con-vos-continuo-llegando-a-escuelas-y-comunidades/) | 21/10/2026 | `PORTADA - Novedad web (1).png` (carpeta 9) | No; 4 fotos dentro del texto | 5 | UPDATED |
| [Hablemos de vínculos: una jornada de participación y reflexión junto a adolescentes](http://localhost:8080/hablemos-de-vinculos-una-jornada-de-participacion-y-reflexion-junto-a-adolescentes/) | 21/10/2026 | `PORTADA - Novedad web (5).png` (carpeta 10) | Sí: 10 foto(s) | 11 | UPDATED |
| [Programa de Protección Digital: Entre Pantallas, más herramientas para cuidar a chicas y chicos en los entornos digitales](http://localhost:8080/programa-de-proteccion-digital-entre-pantallas-mas-herramientas-para-cuidar-a-chicas-y-chicos-en-los-entornos-digitales/) | 21/10/2026 | `PORTADA - Novedad web (6).png` (carpeta 11) | Sí: 8 foto(s) | 9 | CREATED |

El total cuenta la portada más las fotos adicionales. Las portadas se seleccionaron por el único archivo **PORTADA** de cada carpeta, sin asumir la primera foto. En las notas de Entre Pantallas agosto y Va con Vos agosto se respetaron las siete y cuatro imágenes señaladas dentro del texto, respectivamente, sin duplicarlas en un carrusel. En las otras seis se usó la galería/carrusel nativo, con archivos numerados en orden natural; las dos galerías con una sola foto adicional tienen los controles deshabilitados por el comportamiento existente. No hay carruseles vacíos.

Se preservó el enlace real de la charla de María Zysman: https://www.youtube.com/live/BFurEJ4k3fQ?si=gfIE6UHsDYUq4_dB. Los PDF/DOCX de las carpetas son fuentes editoriales, sin instrucciones para ofrecerlos como descargas; no se publicaron como adjuntos de lectura.

Se reutilizaron por `_ddna_news_source_id` las siete entradas previamente retiradas (IDs locales 311, 316, 317, 327, 329, 337 y 342), actualizando sus textos, imágenes, galerías, fecha y estado local. Polideportivos es la única entrada nueva (ID local 1828). Ningún ID local es requisito del importador: las identidades se resuelven en cada destino por origen estable. Se contrastaron también slug, título normalizado y fecha+título, incluyendo papelera; una identidad ajena/ambigua bloquea el preflight.

Las ocho están publicadas **en local**, administrables en wp-admin, con tag `novedad`, categoría `novedades` y Media Library. Se importaron **56 originales nuevos**, sin modificar ni borrar medios anteriores; se deduplican por SHA-256 del original. No hay rutas `/Users/...` en el contenido servido. No quedan entradas REVIEW ni datos editoriales pendientes en este lote.

## D. Entre Pantallas

- Asset anterior del tema: `app/themes/ddna-theme/assets/images/programs/entre-pantallas.png` (PNG, 1638 × 1450 px); se conserva para reversión.
- Fuente nueva exacta: `RECURSOS GRÁFICOS - WEB DDNA 2026/entre pantallas-67.png` (PNG, **3275 × 2900 px**, 925.481 bytes).
- Copia idéntica por SHA-256 en `app/themes/ddna-theme/assets/images/programs/entre-pantallas-67.png`.
- Único cambio de mapeo en `app/themes/ddna-theme/template-parts/components/program-card.php` para el slug `programa-entre-pantallas`.
- Se conservan título, contenido, destino del dossier, posición, tamaño visual, interacción y las otras cinco tarjetas. El enlace externo del dossier fue inspeccionado, sin modificarlo ni abrir servicios externos durante el QA.

## E. Archivos del lote

Modificados:

1. `app/themes/ddna-theme/inc/territory-venues.php` — tres campos puntuales.
2. `app/themes/ddna-theme/inc/editorial-content.php` — elimina la exclusión visual del Convenio 92.
3. `app/themes/ddna-theme/template-parts/components/program-card.php` — mapeo de una imagen.
4. `scripts/import-novedades.php` — selector explícito del nuevo lote, colisiones incluyendo papelera, actualización acotada, recibo y snapshots independientes. El modo anterior conserva su manifiesto y restricción 1–3.
5. `docs/project-scope.md` — autorización y límites de esta fase local.

Nuevos:

6. `app/themes/ddna-theme/assets/images/programs/entre-pantallas-67.png`.
7. `scripts/build-october-news-manifest.py` — construcción reproducible desde las fuentes reales, sin modificarlas.
8. `scripts/verify-october-news.php` — verificación read-only de identidad, fecha, tag, portadas, originales, orden de galerías, imágenes inline y enlaces.
9. `content/novedades-octubre-2026.json` — inventario editorial, HTML, fecha confirmada, fuentes/checksums y assets.
10. `docs/actualizacion-contenidos-octubre-2026.md` — este informe.

Las nuevas fuentes originales ya estaban sin seguimiento al iniciar. No se editaron, movieron ni eliminaron. El manifiesto usa sus rutas relativas; el importador recibe un montaje temporal de solo lectura, sin cambiar `compose.yaml`. No se duplicó el bundle de 127 MB dentro del repositorio.

## F. Git

- Rama: `feature/actualizacion-contenidos-octubre`.
- Base: `14bcd225b74a2733e2048bc0bec0ca3fa0900077`, coincidente con `origin/main` al fetch inicial.
- Sin nuevos commits, sin staging, sin push ni PR.
- Estado final completo: 11 modificados, 379 eliminaciones previas y 106 archivos sin seguimiento (incluye los recursos originales nuevos y cinco archivos nuevos de este lote). Los diez archivos propios están enumerados arriba.
- El estado inicial contenía seis PNG modificados y 379 eliminaciones de recursos, además de archivos nuevos del usuario. **Los 486 archivos/rutas previamente modificados, eliminados o sin seguimiento conservan exactamente su estado y checksum inicial.** Las eliminaciones previas de la antigua carpeta `novedades` no forman parte de este lote ni se restauraron.
- El diff propio comprende los diez archivos anteriores. Las fuentes nuevas utilizadas se deberán incluir de forma selectiva en una eventual publicación aprobada, sin stagear las eliminaciones ni los cambios ajenos.
- Copias completas del estado Git inicial y final en `../contenidos-octubre-20261021/`.

## G. Validaciones

- Cinco archivos PHP: sintaxis correcta. Siete scripts JS existentes: `node --check` correcto. Builder Python: compilación correcta. `git diff --check`: correcto.
- Ocho textos coinciden exactamente con sus documentos fuente, normalizando únicamente espacios/saltos de maquetación y retirando instrucciones editoriales de fotos.
- Las ocho fechas, títulos, portadas originales, tag, galerías y orden de imágenes inline pasan el verificador; 56 hashes de originales comprobados.
- Preflight sin escrituras correcto. Primera aplicación: **1 CREATED, 7 UPDATED, 56 media**. Segunda aplicación: **0 CREATED, 0 UPDATED, 8 SKIPPED**. Snapshot completo de posts, meta y términos idéntico entre ambas ejecuciones.
- La comparación con el checkpoint inicial confirma que solo cambiaron las siete identidades objetivo preexistentes. Se agregaron una entrada, 56 adjuntos y siete revisiones nativas de WordPress; el resto de posts, medios y términos anteriores quedó intacto. La opción de Convenios no cambió.
- QA en **375, 430, 768, 1024 y 1440 px**: 25 comprobaciones de subsedes/Convenios/Programas y 45 de notas/archivo. Sin overflow horizontal, cortes de los nuevos datos, errores JavaScript ni respuestas HTTP de error en los recursos locales revisados.
- Dirección completa y horarios correctos en los popups, Convenio 92 visible, seis imágenes de Programas cargadas. Las galerías con varias fotos responden a flecha de teclado; las fotos inline conservan su posición. Archivo con las ocho notas y orden cronológico descendente.
- Imágenes nuevas y enlaces internos comprobados, wp-admin llega al login local y la API de Medios responde 200. El enlace YouTube se verificó contra la fuente, sin consultar su disponibilidad externa.
- Revisión visual de capturas mobile/desktop y comprobación adicional en viewport de las fotos con carga diferida. Las capturas largas pueden omitir pintura fuera del viewport; las capturas enfocadas confirman su visualización real.
- Docker existente `ddna-rebuild`: MariaDB `3140158777c4` healthy y WordPress `869176e1163d` running/HTTP 200. Se arrancaron los contenedores existentes, sin recrearlos ni tocar volúmenes. No se levantó Dashboard ni otro stack.

Evidencia local fuera del repositorio: `../contenidos-octubre-20261021/`, con inventario y hashes, checkpoint local protegido, recibos/verificaciones, capturas, QA, importación e idempotencia. No se registraron secretos en los informes.

## H. URLs exactas para revisión

- Subsedes: http://localhost:8080/#territorio
- Convenios: http://localhost:8080/convenios/
- Archivo de Novedades: http://localhost:8080/category/novedades/
- Quiero Conocer: http://localhost:8080/#quiero-conocer
- Administración de Novedades: http://localhost:8080/wp-admin/edit.php?tag=novedad

Las ocho URLs individuales están enlazadas por título en la tabla de Novedades. Se obtuvieron de `get_permalink()` después de la importación y respondieron 200.

## Operación y reversión selectiva

Comandos locales reproducibles, ejecutados desde la raíz del repositorio:

```sh
# Requiere PyMuPDF==1.26.5; usar un entorno aislado.
python scripts/build-october-news-manifest.py

docker compose run --rm -T \
  -v "$PWD/RECURSOS GRÁFICOS - WEB DDNA 2026:/sources:ro" \
  -e DDNA_NEWS_ASSET_ROOT=/sources \
  cli eval-file scripts/import-novedades.php dry-run octubre-2026

# La aplicación de este lote ya está realizada en LOCAL.
# Una nueva ejecución sin cambios es idempotente.
docker compose run --rm -T \
  -v "$PWD/RECURSOS GRÁFICOS - WEB DDNA 2026:/sources:ro" \
  -e DDNA_NEWS_ASSET_ROOT=/sources \
  cli eval-file scripts/import-novedades.php apply octubre-2026

docker compose run --rm -T cli eval-file scripts/verify-october-news.php
```

El recibo `ddna_news_october_2026_receipt` conserva `created_posts`, `created_media`, checksum del manifiesto y `updated_before` (post completo, meta y términos de cada una de las siete entradas antes de escribir). Es independiente del recibo histórico. Se creó además el checkpoint local `../contenidos-octubre-20261021/local-before.sql` con permisos 0600; no se añade al repositorio.

Ante una reversión de contenido, resolver las identidades en el destino y usar **su propio recibo**: devolver únicamente `created_posts` a papelera y restaurar únicamente las entradas en `updated_before`, con sus campos, metadatos y términos anteriores, incluyendo el estado de papelera. Conservar el resto de la DB. Los medios anteriores nunca se borran. Los medios nuevos pueden conservarse para revisión; eliminarlos solo si están en `created_media` y se verifica antes que no son usados por otros contenidos. No restaurar toda MariaDB para deshacer este lote.

Para revertir el código, recuperar solo los tres cambios del tema y el mapeo de imagen desde la revisión base; el asset anterior permanece disponible. Una futura operación productiva requerirá checkpoint y rollback propios en el VPS y autorización exacta posterior; **no se ejecutaron en esta fase**. No correr `reconcile-novedades.php` con la política histórica que retiraba 4–13, porque esta nueva autorización reincorpora expresamente 4–11.

No se ejecutan tareas de infraestructura, DNS, Cloudflare, Caddy, Supabase, Dashboard o VPS. La siguiente acción depende de la revisión del usuario.

¿Aprobás estos cambios para publicarlos en GitHub y VPS?
