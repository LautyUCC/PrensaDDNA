# Sistema editorial de Novedades

Implementado en la feature branch actual, exclusivamente en WordPress local. La importación agrega 44 novedades (34 YA PUBLICADAS y 10 FALTA PUBLICAR). Las cuatro novedades demostrativas anteriores se retiraron a la papelera local por instrucción del usuario el 9 de octubre; quedan 44 novedades publicadas. No crea usuarios ni modifica roles.

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
- Home mantiene la configuración existente de 12 entradas; el archivo muestra 12 por página con búsqueda y paginación, por fecha descendente e ID como desempate.
- Los individuales usan un template común de título, fecha, imagen destacada, contenido y galería/carrusel opcional. No hay contenido hardcodeado.

## Fuentes y decisiones de esta carga

Consultar `novedades-inventario.md`, `novedades-control.md` y `../content/novedades-manifest.json`.

- Los títulos y textos provienen de los PDF/DOCX entregados. Si existen ambos se prefiere DOCX para los párrafos y se recuperan también los hipervínculos de las anotaciones PDF.
- Las imágenes principales provienen de los filenames/carpetas PORTADA; en PRECONGRESO se distingue Novedades Web.png de la miniatura. No se escoge la primera foto de cada carpeta.
- El usuario autorizó carrusel para las novedades con varias fotos. Se excluyen portadas, miniaturas y gráficos de botones de la galería adicional. Las referencias `(Imagen N)` de Entre Pantallas y Va con Vos se convierten en bloques de imagen en su lugar original, sin duplicarlas en un carrusel.
- Cuando los filenames numeran fotos se respeta su orden natural. No se inventan captions; se utiliza el título fuente como texto alternativo de contexto, editable en Medios.
- No hay fechas de publicación original completas e inequívocas. Las fechas de eventos no se usan como fechas de publicación. Esta carga utiliza su fecha efectiva y registra `first_import_time`; todas quedan pendientes de verificación histórica.
- DIPLO UCASAL y FORO JUEGO DE LOS DERECHOS no traen imágenes: quedan publicados con el texto real y sin imagen destacada.
- Premio Programa Protección Digital trae solo una portada: se publica localmente con el título literal de carpeta y sin inventar texto. Su texto y confirmación de título siguen pendientes.
- El comunicado de Discapacidad incluye un PDF adjunto local proporcionado; se importa en Medios y se enlaza desde el cuerpo.
- Los PDFs usados solo como fuente de redacción no se agregan como adjuntos públicos. Tampoco se importan miniaturas/botones auxiliares no utilizados. Los originales permanecen en el bundle fuente.

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

El recibo `ddna_news_import_receipt` identifica todos los posts/media creados y las etiquetas añadidas a entradas previas. Un fallo detiene la carga y conserva ese recibo: no restaura ni borra toda la DB. Antes de la carga local se exportó la DB y se registraron los posts previos en `/tmp/ddna-news-review/`, fuera del repositorio.

Fuera de un WordPress con entorno `local` y hostname loopback, el importador está bloqueado salvo que se proporcionen **ambas** condiciones: `DDNA_NEWS_APPROVAL=APROBADO PARA PUBLICAR` y `DDNA_NEWS_TARGET_ORIGIN` igual al origen real del WordPress. Esta fase no las configuró ni ejecutó producción. La publicación de código y una carga productiva posterior siguen pendientes de la aprobación exacta del usuario y del checkpoint requerido.

## Validaciones

`verify-novedades.php` coteja cada título, texto, fecha, tag, imagen principal, selección/orden de galería, inline, URLs y checksum de medios con el manifest. `test-novedades-editorial.php` prueba el flujo editorial con fixtures locales descartables y limpieza en finally. El informe de control incluye resultados HTTP y pendientes por novedad.

## Publicación autorizada — 9 de octubre de 2026

El usuario autorizó «APROBADO PARA PUBLICAR EN REPOSIROTIO Y VPS», incluyendo el sistema, la carga inicial, los ajustes visuales revisados y la retirada de contenidos de prueba. La fase de publicación integra el código aprobado a main mediante PR, conserva los originales ajenos al lote y utiliza el reemplazo exclusivo del servicio WordPress del VPS de revisión (`http://179.199.132.207`).

Antes de escribir se crea checkpoint privado de imagen, contenedor, filas DB, uploads y servicios. Se reutilizan la imagen y Compose vigentes sin actualizar Core ni dependencias. El bundle versionado contiene las fuentes exactas de Novedades; los assets se importan mediante Medios, sin rutas locales en runtime. `retire-demo-content.php` resuelve las cuatro noticias seed por slug y verifica título/texto antes de enviarlas a papelera; los eventos deben estar marcados como demo. Dry-run por defecto y origen/aprobación exactos fuera de local. El recibo permite restauración selectiva, sin borrar Medios ni sustituir DB.

Las validaciones editoriales con fixtures son exclusivamente locales. `verify-novedades.php` es de lectura y admite el destino remoto con `DDNA_NEWS_VERIFY_ORIGIN` igual al origen exacto. El registro operativo y backups quedan fuera del repositorio en `news-publication-20261009/` y el release privado del VPS.
