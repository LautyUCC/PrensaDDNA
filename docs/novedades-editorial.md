# Sistema editorial de Novedades

Estado local vigente: **222 novedades publicadas** (219 históricas oficiales + las nuevas locales 1–3), en `feature/novedades-reconciliation`. La carga anterior de 44 sigue documentada como antecedente; producción no se modificó en esta reconciliación. Ver `novedades-reconciliation-control.md` para inventario, fechas, exclusiones, medios y QA. No se crean usuarios ni se modifican roles.

## Cómo publicar una novedad

1. Entrar a `http://localhost:8080/wp-admin/` con una cuenta editorial existente.
2. Abrir **Novedades → Añadir novedad**.
3. Escribir el título y el contenido en el editor nativo de WordPress. Se pueden agregar párrafos, listas, enlaces y fotos mediante bloques; no hace falta HTML.
4. Elegir la **Imagen destacada**: es la imagen principal de la noticia.
5. Si el panel inferior está plegado, abrir **Meta Boxes** (también se puede enfocar el botón y presionar Enter). En **Publicación en Novedades**, dejar marcado **Mostrar esta entrada en Novedades**. Al guardar se agrega automáticamente la etiqueta `novedad`, sin quitar etiquetas adicionales.
6. Para una galería, usar **Agregar imágenes del carrusel**. El selector abre la Biblioteca de Medios de WordPress. **Subir/Bajar** establece el orden y **Quitar** retira una imagen de la selección, sin borrar el archivo de Medios.
7. Marcar **Mostrar carrusel** para navegación con botones, teclado y desplazamiento táctil. Si está desmarcado y hay fotos adicionales, se muestra una galería estática. La imagen principal es independiente de esta selección.
8. Administrar la fecha en **Publicación**, previsualizar y publicar. La nueva entrada aparece automáticamente en Home y en el archivo.

El contenido sigue siendo una Entrada nativa; se puede editar también desde **Entradas**. Para incluir una entrada existente, marcar el control de Novedades o asignar la etiqueta `novedad`. Desmarcar el control excluye esa entrada de Novedades conservando otras etiquetas y categorías. Los permisos son los normales de WordPress; no se cambiaron roles.

## Presentación y URLs

- Home y sección Actualidad: `http://localhost:8080/#actualidad`.
- Archivo y Ver más: `http://localhost:8080/category/novedades/`.
- Se conserva la categoría histórica para su URL y el menú; las consultas usan el tag `novedad` como criterio editorial.
- Home mantiene la configuración existente de 12 entradas; el archivo muestra 12 por página con búsqueda y paginación, exclusivamente por fecha editorial descendente, sin ordenar por ID.
- Los individuales usan un template común de título, fecha, imagen destacada, contenido y galería/carrusel opcional. No hay contenido hardcodeado.

## Fuentes y decisiones vigentes

- El archivo oficial completo aporta 219 entradas, sus fechas originales y contenido. Se conserva el texto literal; se retiran wrappers/CSS/controles del constructor anterior y encabezados que repiten el título, y se adaptan galerías a markup semántico del template nuevo.
- Las nuevas locales 1–3 usan **2026-10-09**, fecha confirmada por el usuario. Sus tres fotos distintas son portadas únicas; dos archivos numerados eran idénticos a sus PORTADA por SHA256 y no se repiten como carrusel.
- Las carpetas 4–10 se conservan en disco y se excluyen de publicación/importación. No existen fuentes numeradas 11–13 en el bundle revisado; la política también las bloquea.
- Los carruseles históricos se identifican por su semántica original y conservan el orden declarado. Galerías estáticas e imágenes interiores conservan su posición; no se convierten automáticamente en carrusel.
- Las imágenes y PDFs recuperables se sirven desde Medios local. Los videos externos conservan enlaces a sus fuentes. Los 13 recursos inaccesibles se documentan en el informe: no se inventan sustitutos ni se dejan enlaces locales rotos.
- La nota local de lanzamiento del ciclo «Cuidar para Crecer» en zona norte permanece en borrador REVIEW; no se confunde con el encuentro de zona sur ni se destruye su material.

## Importación reproducible

`build-novedades-manifest.py` extrae las fuentes PDF/DOCX y produce el manifest versionado, con contenido, identificación estable por carpeta, slugs, hashes, portadas, galerías, imágenes inline, enlaces y pendientes. Requiere Python y PyMuPDF. No contacta WordPress.

`import-novedades.php` se ejecuta únicamente de forma explícita mediante WP-CLI y hace dry-run por defecto. Antes de escribir verifica todo el bundle: checksums, tamaños, formatos, rutas contenidas en el directorio de fuentes y colisiones de slug con contenido ajeno.

Desde la raíz del repo, para LOCAL:

```sh
docker compose --profile tools run --rm \
  -v "$PWD/RECURSOS GRÁFICOS - WEB DDNA 2026/novedades:/var/www/html/news-source:ro" \
  -e DDNA_NEWS_ASSET_ROOT=/var/www/html/news-source \
  cli eval-file /var/www/html/scripts/import-novedades.php dry-run
```

Reemplazar `dry-run` por `apply` únicamente para aplicar. El mount es de lectura; no cambia compose ni accede a fuentes productivas.

La deduplicación usa SHA-256 de archivos originales existentes y del bundle. Los uploads tienen filenames deterministas. Los IDs de origen dependen de la carpeta, no del título o del hash del texto. Repetir `apply` conserva las entradas ya completadas y las ediciones hechas por Prensa, sin duplicar posts/media. Una importación interrumpida puede completar solo sus entradas incompletas.

El importador local de PDF/DOCX acepta solo las fuentes 1–3 y conserva entradas completadas. Las fuentes YA PUBLICADAS ahora se reconcilian mediante el inventario histórico; el builder no vuelve a incorporarlas desde carpetas sin fecha.

Para el histórico se usa `scripts/reconcile-novedades.php`, **bloqueado fuera de localhost, sin override productivo**. Dry-run por defecto; valida identidades, slugs, originales, SHA256, MIME, fechas y política antes de escribir. El recibo `ddna_news_reconciliation_receipt` y la marca por entrada preservan la idempotencia y las ediciones posteriores cuando el manifiesto no cambia. Los medios se deduplican por hash del archivo original. La DB y el snapshot previo están respaldados fuera del repo en `../news-reconciliation-20261009/`.

```sh
docker compose --profile tools run --rm cli eval-file /var/www/html/scripts/reconcile-novedades.php dry-run
```

Reemplazar por `apply` requiere la autorización local vigente y el mismo bundle revisado. `inventory-historical-novedades.py --output <carpeta-externa>` hace un nuevo inventario de lectura y detecta todas las páginas reales, sin asumir cantidades ni fusionar automáticamente nuevas coincidencias. `build-historical-novedades.py --cache <carpeta-externa>` genera contenido desde el inventario reconciliado versionado, descarga originales recuperables y requiere `beautifulsoup4`; las dependencias probadas están en `scripts/requirements-news-reconciliation.txt`.

La presente fase no autoriza commit/push/PR/merge/VPS. Solo **LISTO PARA PUSH** autoriza después commit y push de esta feature, sin PR ni despliegue.

## Validaciones

`verify-novedades.php` coteja cada título, texto, fecha, tag, imagen principal, selección/orden de galería, inline, URLs y checksum de medios con el manifest. `test-novedades-editorial.php` prueba el flujo editorial con fixtures locales descartables y limpieza en finally. El informe de control incluye resultados HTTP y pendientes por novedad.

## Antecedente: publicación de la carga inicial — 9 de octubre de 2026

El usuario autorizó «APROBADO PARA PUBLICAR EN REPOSIROTIO Y VPS», incluyendo el sistema, la carga inicial, los ajustes visuales revisados y la retirada de contenidos de prueba. La fase de publicación integra el código aprobado a main mediante PR, conserva los originales ajenos al lote y utiliza el reemplazo exclusivo del servicio WordPress del VPS de revisión (`http://179.199.132.207`).

Antes de escribir se crea checkpoint privado de imagen, contenedor, filas DB, uploads y servicios. Se reutilizan la imagen y Compose vigentes sin actualizar Core ni dependencias. El bundle versionado contiene las fuentes exactas de Novedades; los assets se importan mediante Medios, sin rutas locales en runtime. `retire-demo-content.php` resuelve las cuatro noticias seed por slug y verifica título/texto antes de enviarlas a papelera; los eventos deben estar marcados como demo. Dry-run por defecto y origen/aprobación exactos fuera de local. El recibo permite restauración selectiva, sin borrar Medios ni sustituir DB.

Las validaciones editoriales con fixtures son exclusivamente locales. `verify-novedades.php` es de lectura y admite el destino remoto con `DDNA_NEWS_VERIFY_ORIGIN` igual al origen exacto. El registro operativo y backups quedan fuera del repositorio en `news-publication-20261009/` y el release privado del VPS.


## Publicación autorizada — 9 de octubre de 2026

La instrucción posterior `LISTO PARA PUSH Y PUBLICAR EN VPS` autoriza commit/push de la feature y publicación controlada en `http://179.199.132.207`, sin PR/merge ni cambios en otros servicios. Los estados «solo local» anteriores describen la fase de revisión cerrada. El importador ahora exige aprobación/origen exactos fuera de local; resuelve históricos existentes, retiros y REVIEW por `_ddna_news_source_id`, sin transportar IDs locales. Preflight completo antes de escribir, respaldo editorial por entrada y recibo incremental de medios, además del checkpoint privado DB/uploads. El verificador remoto es de lectura y el auditor HTTP toma el origen del resultado validado.

El bundle procede del commit, sin recursos ajenos ni base local. Se verifica la imagen activa contra los bytes de la revisión base, se deriva la nueva imagen sin actualizar Core/dependencias, y se sustituye únicamente WordPress con los mismos mounts, variables y Compose. Backups y evidencias operativas permanecen fuera de Git en `news-reconciliation-publication-20261009/` y el release privado del VPS. Las 13 fuentes inaccesibles y el borrador REVIEW se conservan como pendientes ya informados.
