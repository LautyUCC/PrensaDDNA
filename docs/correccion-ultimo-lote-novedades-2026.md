# Corrección local del último lote de Novedades (4–11)

Estado de revisión: **APROBADO PARA PUBLICAR**, autorización explícita recibida el 21/10/2026.

El informe siguiente documenta la fase local validada. La fase de publicación tendrá checkpoint productivo, commit selectivo, PR/integración y despliegue exclusivo de WordPress; Normativas y los cambios previos permanecen excluidos.
Rama: `feature/correccion-ultimo-lote-novedades`. Base: `f7c72488fb581d2d5b586cc761e86d1a91c43622`.
Sin commit, staging, push, PR, merge ni acceso a VPS. WordPress y MariaDB locales permanecen funcionando.

## Resultado y alcance

Orden final: **11 → 10 → 9 → 8 → 7 → 6 → 5 → 4**.
Los ocho cuerpos quedan **JUSTIFICADOS**; títulos, fechas, subtítulos, botones, captions y controles conservan su alineación habitual.

Se revisaron los trece documentos originales (seis DOCX y siete PDF). Los pares DOCX/PDF coinciden en texto tras normalizar espacios y saltos de línea. Las cursivas de 8/9 corresponden solo a las instrucciones técnicas «Imagen n», representadas por las fotos; no hay cursivas editoriales ni listas perdidas. El subrayado de 7 corresponde al enlace de YouTube, que se conserva como enlace.

No se reescribió ni resumió ningún texto. Se eliminaron **once cierres `</strong>` huérfanos**, siete en 8 y cuatro en 9, que habían quedado al separar el subtítulo de la descripción. El generador ya los maneja correctamente.

**Posts modificados en base de datos:** solo **337 (8)** y **342 (9)**, en su HTML y checksum de importación; WordPress actualizó sus marcas de modificación. No se crearon posts, revisiones ni medios. La presentación y prioridad editorial afectan exclusivamente las ocho identidades detalladas abajo. Fechas, slugs, estados, taxonomías, portadas, galerías, captions y enlaces permanecen iguales.

## Fecha y orden editorial

Las fuentes no tienen una fecha inequívoca de publicación; las fechas de agosto dentro de 8/9 son fechas de actividades. El usuario reconfirmó expresamente **21/10/2026 para las ocho**. Se conserva también el horario técnico existente `12:00:00`, sin alterar fechas u horas para ordenar.

El filtro `ddna_october_news_orderby`, en `inc/news-october-batch.php`, conserva la fecha descendente como criterio principal y añade un desempate `CASE` por número editorial 11–4 **antes del LIMIT SQL**. Resuelve los IDs de cada instalación por `_ddna_news_source_id`, sin hardcodear IDs locales. Solo actúa en listados de Novedades y búsquedas con orden de fecha descendente; respeta consultas ascendentes, individuales, administración y otros listados. La búsqueda conserva su criterio de relevancia existente antes de la fecha, cuando corresponde.

Se comprobó el orden relativo de las **222 novedades fuera del lote** frente a la consulta sin el filtro y al checkpoint inicial: coincide. Se recorrieron las 230 publicaciones con paginaciones de 5 y 12, sin duplicados ni omisiones. Los empates históricos que antes podían variar entre límites SQL quedan verificados contra el listado completo; no se editaron sus fechas ni metadatos.

## Identificación inequívoca y QA por novedad

Cada fila se verificó por carpeta, título completo, slug, fecha y metadata/manifiesto, además del ID. Documento(s) relativos a `RECURSOS GRÁFICOS - WEB DDNA 2026/`.

### 4 — Comenzó la segunda edición de la Diplomatura en Inteligencia Artificial y Derechos Digitales de Niñas, Niños y Adolescentes

- Post existente: **316**; source ID: `ddna-news-c4ac8c1d9bbb1d0a6222`.
- Carpeta: `FALTA PUBLICAR/4) NOVEDAD DIPLO UCASAL`.
- Slug: `comenzo-la-segunda-edicion-de-la-diplomatura-en-inteligencia-artificial-y-derechos-digitales-de-ninas-ninos-y-adolescentes`.
- Documento principal: `FALTA PUBLICAR/4) NOVEDAD DIPLO UCASAL/NOVEDAD DIPLO UCASAL.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Sin correcciones de contenido necesarias. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 1 fotos de galería + 0 fotos insertadas. Carrusel preservado. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **8**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [4](http://localhost:8080/comenzo-la-segunda-edicion-de-la-diplomatura-en-inteligencia-artificial-y-derechos-digitales-de-ninas-ninos-y-adolescentes/).

### 5 — Comenzamos “El Juego de los Derechos” junto a chicas y chicos de barrio Sacchi

- Post existente: **317**; source ID: `ddna-news-b944ba04a08c2be896a5`.
- Carpeta: `FALTA PUBLICAR/5) NOVEDAD FORO JUEGO DE LOS DERECHOS `.
- Slug: `comenzamos-el-juego-de-los-derechos-junto-a-chicas-y-chicos-de-barrio-sacchi`.
- Documento principal: `FALTA PUBLICAR/5) NOVEDAD FORO JUEGO DE LOS DERECHOS /NOVEDAD EL JUEGO DE LOS DERECHOS.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Sin correcciones de contenido necesarias. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 8 fotos de galería + 0 fotos insertadas. Carrusel preservado. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **7**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [5](http://localhost:8080/comenzamos-el-juego-de-los-derechos-junto-a-chicas-y-chicos-de-barrio-sacchi/).

### 6 — Realizamos el segundo encuentro de “El juego de los derechos” junto a chicas y chicos de barrio Villa Unión

- Post existente: **327**; source ID: `ddna-news-867444ab5bd387eb767c`.
- Carpeta: `FALTA PUBLICAR/6) NOVEDAD “El juego de los derechos”`.
- Slug: `realizamos-el-segundo-encuentro-de-el-juego-de-los-derechos-junto-a-chicas-y-chicos-de-barrio-villa-union`.
- Documento principal: `FALTA PUBLICAR/6) NOVEDAD “El juego de los derechos”/NOVEDAD SEGUNDO ENCUENTRO _EL JUEGO DE LOS DERECHOS_.docx`.
- PDF de contraste: `FALTA PUBLICAR/6) NOVEDAD “El juego de los derechos”/NOVEDAD SEGUNDO ENCUENTRO _EL JUEGO DE LOS DERECHOS_.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Sin correcciones de contenido necesarias. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 9 fotos de galería + 0 fotos insertadas. Carrusel preservado. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **6**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [6](http://localhost:8080/realizamos-el-segundo-encuentro-de-el-juego-de-los-derechos-junto-a-chicas-y-chicos-de-barrio-villa-union/).

### 7 — Realizamos la charla abierta “Lo que no dicen con palabras. Señales, vínculos y violencias en las adolescencias. ¿Qué podemos hacer los adultos?”

- Post existente: **329**; source ID: `ddna-news-77ea16e539bc2abc9ed3`.
- Carpeta: `FALTA PUBLICAR/7) NOVEDAD charla abierta “Lo que no dicen con palabras.`.
- Slug: `realizamos-la-charla-abierta-lo-que-no-dicen-con-palabras-senales-vinculos-y-violencias-en-las-adolescencias-que-podemos-hacer-los-adultos`.
- Documento principal: `FALTA PUBLICAR/7) NOVEDAD charla abierta “Lo que no dicen con palabras./Novedad charla 10_9.docx`.
- PDF de contraste: `FALTA PUBLICAR/7) NOVEDAD charla abierta “Lo que no dicen con palabras./Novedad charla 10_9.docx.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Sin correcciones de contenido necesarias. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 1 fotos de galería + 0 fotos insertadas. Carrusel preservado. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **5**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [7](http://localhost:8080/realizamos-la-charla-abierta-lo-que-no-dicen-con-palabras-senales-vinculos-y-violencias-en-las-adolescencias-que-podemos-hacer-los-adultos/).

### 8 — Entre Pantallas: continuamos promoviendo la ciudadanía digital en las escuelas durante agosto

- Post existente: **337**; source ID: `ddna-news-5e1ba134bba0a73077b9`.
- Carpeta: `FALTA PUBLICAR/8) NOVEDAD ENTRE PANTALLAS AGOSTO`.
- Slug: `entre-pantallas-continuamos-promoviendo-la-ciudadania-digital-en-las-escuelas-durante-agosto`.
- Documento principal: `FALTA PUBLICAR/8) NOVEDAD ENTRE PANTALLAS AGOSTO/NOVEDAD ENTRE PANTALLAS AGOSTO.docx`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Se quitaron siete cierres huérfanos de negrita. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 0 fotos de galería + 7 fotos insertadas. Fotos en sus posiciones dentro del texto; sin carrusel, conforme a las instrucciones de la fuente. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **4**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [8](http://localhost:8080/entre-pantallas-continuamos-promoviendo-la-ciudadania-digital-en-las-escuelas-durante-agosto/).

### 9 — Durante agosto, el programa Va con Vos continuó llegando a escuelas y comunidades

- Post existente: **342**; source ID: `ddna-news-c5d76048ea58e82d8b9f`.
- Carpeta: `FALTA PUBLICAR/9) NOVEDAD TALLERES VA CON VOS`.
- Slug: `durante-agosto-el-programa-va-con-vos-continuo-llegando-a-escuelas-y-comunidades`.
- Documento principal: `FALTA PUBLICAR/9) NOVEDAD TALLERES VA CON VOS/NOVEDAD TALLERES VA CON VOS.docx`.
- PDF de contraste: `FALTA PUBLICAR/9) NOVEDAD TALLERES VA CON VOS/NOVEDAD TALLERES VA CON VOS.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Se quitaron cuatro cierres huérfanos de negrita. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 0 fotos de galería + 4 fotos insertadas. Fotos en sus posiciones dentro del texto; sin carrusel, conforme a las instrucciones de la fuente. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **3**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [9](http://localhost:8080/durante-agosto-el-programa-va-con-vos-continuo-llegando-a-escuelas-y-comunidades/).

### 10 — Hablemos de vínculos: una jornada de participación y reflexión junto a adolescentes

- Post existente: **311**; source ID: `ddna-news-354a7be7ecd45f7016da`.
- Carpeta: `FALTA PUBLICAR/10) NOVEDAD ENCUENTRO ADOLESCENTES 2026`.
- Slug: `hablemos-de-vinculos-una-jornada-de-participacion-y-reflexion-junto-a-adolescentes`.
- Documento principal: `FALTA PUBLICAR/10) NOVEDAD ENCUENTRO ADOLESCENTES 2026/NOVEDAD HABLEMOS DE VINCULOS.docx`.
- PDF de contraste: `FALTA PUBLICAR/10) NOVEDAD ENCUENTRO ADOLESCENTES 2026/NOVEDAD HABLEMOS DE VINCULOS.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Sin correcciones de contenido necesarias. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 10 fotos de galería + 0 fotos insertadas. Carrusel preservado. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **2**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [10](http://localhost:8080/hablemos-de-vinculos-una-jornada-de-participacion-y-reflexion-junto-a-adolescentes/).

### 11 — Programa de Protección Digital: Entre Pantallas, más herramientas para cuidar a chicas y chicos en los entornos digitales

- Post existente: **1828**; source ID: `ddna-news-3135200c82d6b2e6d60f`.
- Carpeta: `FALTA PUBLICAR/11) NOVEDAD POLIDEPORTIVOS`.
- Slug: `programa-de-proteccion-digital-entre-pantallas-mas-herramientas-para-cuidar-a-chicas-y-chicos-en-los-entornos-digitales`.
- Documento principal: `FALTA PUBLICAR/11) NOVEDAD POLIDEPORTIVOS/NOVEDAD POLIDEPORTIVOS.docx`.
- PDF de contraste: `FALTA PUBLICAR/11) NOVEDAD POLIDEPORTIVOS/NOVEDAD POLIDEPORTIVOS.pdf`.
- Fecha: **21/10/2026**, confirmada por el usuario; sin cambios de hora.
- TEXTO COMPARADO: **coincidente**, título y cuerpo completos, sin cambios de palabras; hashes de documentos verificados.
- FORMATO: párrafos, negritas, subtítulos y enlaces cotejados. Sin correcciones de contenido necesarias. No hay listas en esta fuente.
- **ALINEACIÓN DEL CUERPO: JUSTIFICADO**. Uniforme en todos los párrafos, con última línea al inicio; español declarado solo para estos cuerpos.
- IMÁGENES: portada original + 8 fotos de galería + 0 fotos insertadas. Carrusel preservado. Orden y SHA-256 coincidentes, sin cambios de captions o vínculos.
- POSICIÓN dentro del lote: **1**.
- Responsive: **375 / 430 / 768 / 1024 / 1440: OK**. Sin overflow ni párrafos desplazados; imágenes cargadas y controles de carrusel comprobados cuando hay más de una diapositiva.
- URL local: [11](http://localhost:8080/programa-de-proteccion-digital-entre-pantallas-mas-herramientas-para-cuidar-a-chicas-y-chicos-en-los-entornos-digitales/).

## CSS y contenedor

Archivo: `app/themes/ddna-theme/assets/css/components/news-article.css`.

Antes: párrafos con alineación heredada, `line-height:1.65` y `overflow-wrap:anywhere`; subtítulos con la escala global grande.
Ahora, solo bajo `.news-article--october-2026`:

```css
.entry-content { line-height: 1.7; overflow-wrap: break-word; }
.entry-content p, .entry-content li {
  text-align: justify;
  text-align-last: start;
  hyphens: auto;
  overflow-wrap: break-word;
}
.entry-content p { margin-inline: 0; text-indent: 0; }
.entry-content h2 {
  margin-block: 1.75rem 1rem;
  font-size: clamp(1.2rem, 1.8vw, 1.6rem);
  line-height: 1.3;
}
.entry-content figcaption { text-align: start; hyphens: manual; }
```

Se mantiene la tipografía DDNA, el tamaño de cuerpo existente `clamp(1.05rem,1.3vw,1.25rem)`, el ancho y padding del artículo. Los párrafos conservan separación inferior de 1.25rem y comparten los bordes del contenedor. Solo se proporcionaron los subtítulos internos para evitar la escala de títulos de sección en textos largos. El `lang="es"` del cuerpo permite separación silábica española; las palabras no se modifican en el contenido.

## Validaciones

- 40/40 páginas individuales y tamaños: texto justificado, márgenes iguales, idioma español, título/fecha sin justificar, imágenes completas, sin overflow.
- 20/20 comprobaciones de Home, panel Actualidad, archivo y búsqueda de Novedades en los cinco anchos; el panel se abre mediante su hash nativo, sin forzar visibilidad.
- Carruseles existentes: flecha derecha desplaza las galerías con varias fotos; galerías de una foto preservadas.
- Capturas individuales adicionales de las once fotos inline de 8/9: pintadas y visibles al desplazarse, sin modificar las imágenes.
- Histórico de control: conserva su alineación y no recibe clase/idioma del lote.
- Comparación de texto, negritas, enlaces y fuentes: 8/8; trece documentos leídos; pares DOCX/PDF coincidentes.
- Verificador existente `scripts/verify-october-news.php`: 8/8 identidades únicas, fechas, portadas, orden e imágenes correctas.
- Test nuevo `scripts/test-october-news-presentation.php`: orden, histórico, búsquedas, consultas ajenas/ascendentes y paginaciones 5/12 OK. Solo lectura, sin fixtures.
- Test existente `scripts/test-historical-content.py`: 219 fuentes históricas fieles. Ejecutado con el entorno Python existente que contiene BeautifulSoup.
- PHP syntax: bootstrap, módulo, template y scripts nuevos OK.
- JS syntax: los siete scripts existentes del tema OK; no se modificó JavaScript.
- `git diff --check`: OK.
- Idempotencia: dry-run después de aplicar devuelve cero actualizaciones.
- Las 56 imágenes del manifiesto conservan sus hashes y orden; no hay archivos de medios nuevos.
- Todos los posts existentes conservan su identidad; únicamente cambiaron las dos filas y sus metadatos de checksum indicados. Taxonomías sin cambios.
- 441 rutas de trabajo previo conservan su hash/ausencia original, incluidos los cambios pendientes de Normativas y los recursos que ya estaban modificados/eliminados.
- No se ejecutó `test-novedades-editorial.php`: crea/elimina fixtures. Se usó la regresión específica de solo lectura.

Medición adicional: mayor separación entre palabras detectada 1.44 em, con revisión visual de las capturas. Se conserva justificado también en mobile.

## Archivos de esta corrección

1. `app/themes/ddna-theme/functions.php`: carga el módulo acotado al lote.
2. `app/themes/ddna-theme/inc/news-october-batch.php`: identidades estables, clase y desempate editorial.
3. `app/themes/ddna-theme/template-parts/content/content-news.php`: idioma español solo para los ocho cuerpos.
4. `app/themes/ddna-theme/assets/css/components/news-article.css`: justificado y escala de subtítulos acotados al lote.
5. `scripts/build-october-news-manifest.py`: separa correctamente cierre de negrita y salto de subtítulo de Word.
6. `content/novedades-octubre-2026.json`: únicamente limpieza de HTML de 8/9; mismo contenido editorial y assets.
7. `scripts/correct-october-news-format.php`: corrección update-only con preflight completo, checkpoint obligatorio, guardas de entorno, cero creación de posts/medios y dry-run predeterminado.
8. `scripts/test-october-news-presentation.php`: regresión de consultas de solo lectura.
9. `docs/correccion-ultimo-lote-novedades-2026.md`: este informe.

Los cambios de Normativas y los demás cambios previos NO forman parte de esta entrega. No hay archivos staged ni commits nuevos.

## Evidencias y rollback

Carpeta local fuera del repositorio: `/Users/lautyvallino/Projects/DDNA/novedades-correccion-local-20261021/`.
Incluye mapping inicial, manifiesto anterior, checksums globales/por fila antes/después, comparación de documentos, capturas por entrada y tamaño, QA, pruebas de consultas, recibo del update-only y checkpoint selectivo protegido.

`format-checkpoint.json` conserva filas y metadatos completos originales de 337/342 para restauración selectiva si hiciera falta. No reemplazar una base completa. El rollback de código se limita a los nueve archivos de esta entrega, preservando los archivos previos protegidos; no usar reset/clean masivo. El manifiesto anterior está disponible como `manifest-before.json`. No se ejecutó ningún rollback ni operación productiva.

Una futura publicación requiere la aprobación exacta solicitada. Antes de cualquier aplicación remota: revalidar identidades/estado, preservar checkpoint productivo y código/imagen anterior, dry-run y usar exclusivamente el corrector update-only para 8/9; el orden/justificado de las ocho es código. No ejecutar el importador histórico ni una importación global de DB. La autorización anterior de otros lotes no publica esta corrección.

## Revisión local

Archivo: http://localhost:8080/category/novedades/

- [11 — Programa de Protección Digital: Entre Pantallas, más herramientas para cuidar a chicas y chicos en los entornos digitales](http://localhost:8080/programa-de-proteccion-digital-entre-pantallas-mas-herramientas-para-cuidar-a-chicas-y-chicos-en-los-entornos-digitales/)
- [10 — Hablemos de vínculos: una jornada de participación y reflexión junto a adolescentes](http://localhost:8080/hablemos-de-vinculos-una-jornada-de-participacion-y-reflexion-junto-a-adolescentes/)
- [9 — Durante agosto, el programa Va con Vos continuó llegando a escuelas y comunidades](http://localhost:8080/durante-agosto-el-programa-va-con-vos-continuo-llegando-a-escuelas-y-comunidades/)
- [4 — Comenzó la segunda edición de la Diplomatura en Inteligencia Artificial y Derechos Digitales de Niñas, Niños y Adolescentes](http://localhost:8080/comenzo-la-segunda-edicion-de-la-diplomatura-en-inteligencia-artificial-y-derechos-digitales-de-ninas-ninos-y-adolescentes/)

¿Aprobás el orden, el contenido y la alineación justificada de estas Novedades para publicarlas?
