# Ajustes finales de Prensa — revisión local

Fecha: 13 de octubre de 2026. Rama: `feature/ajustes-finales-prensa`. Base y HEAD sin cambios: `3c4547d6516c67ff39b2413d3b660b3e8d32338b`.
Se ejecutaron git status, branch y fetch origin antes de trabajar. La base conserva dos commits anteriores a este lote sobre origin/main (reconciliación ya desplegada y su registro). No hay commit, stage, push, PR, merge, SSH ni deploy de esta ronda. Los cambios previos de recursos gráficos y archivos personales siguen preservados.

## A. Guías — mapping completo

Autorización adicional del usuario: crear/subir las guías en su Drive, hacerlas públicas para lectura y enlazarlas. Se creó [DDNA — Guías públicas web 2026](https://drive.google.com/drive/folders/1GGwH65NrFIzlUiax6JtZFpXBR8aWOuz6), con once PDFs exactos, acceso “Cualquier usuario que tenga el vínculo”, rol “Lector”. Las dos URLs de Drive anteriores se conservaron.
Las once descargas anónimas devolvieron HTTP 200, firma PDF, tamaño y SHA256 idénticos a los originales. Las dos guías preexistentes también devolvieron HTTP 200 y PDF sin autenticación. No quedan guías pendientes por URL. Ningún PDF original se eliminó ni reemplazó.

| Guía | Link actual al comenzar | Link final |
|---|---|---|
| Guía de Navegación Segura | [Actual](https://drive.google.com/file/d/1gF1JkFpE3MZ9MJtt7teQpS3za6j5MhF2/view) | [Drive](https://drive.google.com/file/d/1gF1JkFpE3MZ9MJtt7teQpS3za6j5MhF2/view) |
| Guía de Juegos en Línea | [Actual](http://localhost:8080/wp-content/uploads/2026/10/guia-prevencion-juegos-en-linea.pdf) | [Drive](https://drive.google.com/file/d/1-BIpbilwA8Mp-zAlfQ4I9AOwBA54X7Kp/view) |
| Guía sobre abuso sexual hacia NNyA | [Actual](http://localhost:8080/wp-content/uploads/2026/10/guia-prevencion-abuso-sexual.pdf) | [Drive](https://drive.google.com/file/d/1sptEz6j3yojULximf_E2FEg7ImrDmb1Q/view) |
| Guía sobre Bullying | [Actual](https://drive.google.com/file/d/1Y7D2JJpMqpAUn0ypyD3E7kmu5uElzYwY/view) | [Drive](https://drive.google.com/file/d/1Y7D2JJpMqpAUn0ypyD3E7kmu5uElzYwY/view) |
| Juegos en Línea: previniendo riesgos | [Actual](http://localhost:8080/wp-content/uploads/2026/10/juegos-en-linea.pdf) | [Drive](https://drive.google.com/file/d/1pJ1Uj9WK5Bg-s3rokP9OSmId72QKO_A5/view) |
| Apuestas en Línea: prevención en NNyA | [Actual](http://localhost:8080/wp-content/uploads/2026/10/apuestas-en-linea.pdf) | [Drive](https://drive.google.com/file/d/1otVTbtkDVfB7PaPCAUr2h7WprlWRNtKJ/view) |
| Acompañar a chicas y chicos en entornos digitales | [Actual](http://localhost:8080/wp-content/uploads/2026/10/entornos-digitales.pdf) | [Drive](https://drive.google.com/file/d/1zhfZ9hfSsbRk6HAzsorlwSfyk12RI2n5/view) |
| Prevención del abuso y el acoso sexual hacia niñas, niños y adolescentes | [Actual](http://localhost:8080/wp-content/uploads/2026/10/prevencion-abuso.pdf) | [Drive](https://drive.google.com/file/d/1pIHYsviJspqpss383EZcan1TfgwOVzfK/view) |
| Prevención del bullying o acoso entre pares | [Actual](http://localhost:8080/wp-content/uploads/2026/10/prevencion-bullying.pdf) | [Drive](https://drive.google.com/file/d/1ZNz8_2vA6vvGV2RgW9ziEDUVIeZ1dMEN/view) |
| Entornos seguros y libres de violencia hacia NNyA | [Actual](http://localhost:8080/wp-content/uploads/2026/10/entornos-seguros.pdf) | [Drive](https://drive.google.com/file/d/1Hg8snlR4dH7PRmjX3IGfC3bk6_2aka1s/view) |
| Hablemos de crianza | [Actual](http://localhost:8080/wp-content/uploads/2026/10/hablemos-crianza.pdf) | [Drive](https://drive.google.com/file/d/10qZXkph6aGYEivC-yhKcmM6HXI34ffOj/view) |
| ¿Cómo acompañar la prevención del consumo de sustancias? | [Actual](http://localhost:8080/wp-content/uploads/2026/10/prevencion-consumo.pdf) | [Drive](https://drive.google.com/file/d/1tgqGJig2GDXqplzF36CjRucGM60yFvVa/view) |
| Acompañar y estimular en los primeros años | [Actual](http://localhost:8080/wp-content/uploads/2026/10/primeros-anos.pdf) | [Drive](https://drive.google.com/file/d/1aTAqPj1OiiHical3KEet5n8101-aDq7q/view) |

Los once documentos conservan `_ddna_file_id` y sus archivos. Solo se actualizó `_ddna_external_url` para esos once slugs. El panel prioriza Drive exclusivamente para Guías; los demás documentos mantienen su resolución actual. Nueva pestaña con `noopener noreferrer`.
`content/guide-drive-links.json` conserva correspondencias y hashes. `apply-guide-drive-links.php` valida todos los originales antes de escribir, es dry-run por defecto, reutiliza documentos y respalda las URLs anteriores en `ddna_guide_drive_backup_v1`. Fuera de local exige aprobación exacta y origen explícito. No se ejecutó fuera de localhost.

## B. Teléfono

Formato visible: **+54 351 428-8881 / 82 / 83 / 88**. Enlace marcable: `tel:+543514288881`.
Actualizado en Contacto, footer y Necesito Ayuda → ¿Cómo podés comunicarte?, tanto dentro de Home como en /asistencia/. Configuración central: defaults y normalización de variantes del número anterior al leer; las opciones almacenadas se preservaron. Los scripts de preparación y su check usan el formato nuevo. El footer no duplica +54 y el helper telefónico marca solo el primer número antes de una barra.
Línea Asistencia (`351 402 0503`), Línea Adolescencia (`351 239 8953`), WhatsApp y otros teléfonos permanecen iguales. La búsqueda en código vigente no encontró el formato viejo fuera de compatibilidad deliberada. Territorio, histórico y documentación de rondas anteriores se conservaron conforme al alcance.

## C. Programas

Siete CPT publicados, seis destacados, más tres páginas editoriales asociadas. Se detectan desde el CPT Programa y la correspondencia de slug de las páginas, incluyendo sus descendientes; no se inventaron slugs. Todas estas diez rutas comparten el header Home/institucional, los seis destinos aprobados, JS existente y botón Volver al inicio con el componente e icono compartidos y `home_url('/')`.

| Página publicada | URL local |
|---|---|
| Programas de Participación para NNyA | [http://localhost:8080/programas-participacion-nnya/](http://localhost:8080/programas-participacion-nnya/) |
| Acompañamiento y Formación para Adultos | [http://localhost:8080/acompanamiento-formacion-adultos/](http://localhost:8080/acompanamiento-formacion-adultos/) |
| Cooperación Internacional e Interinstitucional | [http://localhost:8080/cooperacion-internacional-interinstitucional/](http://localhost:8080/cooperacion-internacional-interinstitucional/) |
| Va con Vos | [http://localhost:8080/programas/programa-va-con-vos/](http://localhost:8080/programas/programa-va-con-vos/) |
| Entre Pantallas | [http://localhost:8080/programas/programa-entre-pantallas/](http://localhost:8080/programas/programa-entre-pantallas/) |
| Desarrollo Integral en los Primeros Años de Vida | [http://localhost:8080/programas/desarrollo-integral-primeros-anos-vida/](http://localhost:8080/programas/desarrollo-integral-primeros-anos-vida/) |
| Detrás del Humo | [http://localhost:8080/programas/programa-detras-del-humo/](http://localhost:8080/programas/programa-detras-del-humo/) |
| Programas de Participación para NNyA | [http://localhost:8080/programas/programas-participacion-nnya/](http://localhost:8080/programas/programas-participacion-nnya/) |
| Acompañamiento y Formación para Adultos | [http://localhost:8080/programas/acompanamiento-formacion-adultos/](http://localhost:8080/programas/acompanamiento-formacion-adultos/) |
| Cooperación Internacional e Interinstitucional | [http://localhost:8080/programas/cooperacion-internacional-interinstitucional/](http://localhost:8080/programas/cooperacion-internacional-interinstitucional/) |

El menú horizontal del header ya no aparece en estas rutas. No se modificaron contenidos ni las relaciones entre programas. Apertura/cierre, Escape y navegación al Home verificados.

## D. Menú compartido

Estado final aprobado: panel junto al botón MENÚ, extendido hacia su izquierda y alineado con su borde derecho (`right: 0`, `left: auto`, sin translate). Ancho fit-content, mínimo responsivo 14rem y techo 16rem, limitados al viewport; 224px efectivos a 1440/1024/768/430/375. Padding horizontal del panel y opciones 8px; divisores centrados de 196px. Una columna grid 1fr y texto centrado sin wrapping. Separación vertical del botón: 9,6px. No se cambió botón, iconos, comportamiento ni enlaces.
Impacto compartido autorizado: el layout del desplegable cambia en Home y páginas que ya usaban el header institucional (incluidas Normativa/Convenios y las dos páginas de campañas que lo reutilizan). Sus textos, enlaces, orden y contenidos no cambian. El header se reutiliza también en Programas.

## E. Paginación Novedades

La regla de layout ahora apunta al ul de paginación, en lugar de afectar también enlaces con clase page-numbers. Lista centrada con flex-wrap; Anterior/Siguiente tienen min-width 8rem (128 px), padding horizontal 1,25rem y white-space nowrap. Ambos completos y equivalentes, sin overflow a 375 px. No cambió ninguna consulta, fecha, noticia o imagen.

## F. Archivos de este lote

- `app/themes/ddna-theme/assets/css/components/additional-adjustments.css`
- `app/themes/ddna-theme/template-parts/components/institutional-contact.php`

- `app/plugins/ddna-core/inc/institutional-settings.php`
- `app/themes/ddna-theme/assets/css/components/header.css`
- `app/themes/ddna-theme/assets/css/components/navigation.css`
- `app/themes/ddna-theme/assets/css/components/news-archive.css`
- `app/themes/ddna-theme/inc/template-tags.php`
- `app/themes/ddna-theme/template-parts/content/content-page.php`
- `app/themes/ddna-theme/template-parts/content/content-single.php`
- `app/themes/ddna-theme/template-parts/footer/site-footer.php`
- `app/themes/ddna-theme/template-parts/home/panel-quiero-saber.php`
- `scripts/apply-final-sept-2026.php`
- `scripts/setup-institutional-settings.php`
- `scripts/verify-feedback-prensa-octubre.php`
- `scripts/verify-final-sept-2026.php`
- `scripts/verify-prevention-guides.php`
- `content/guide-drive-links.json`
- `scripts/apply-guide-drive-links.php`
- `scripts/verify-guide-drive-links.php`
- `docs/ajustes-finales-prensa-20261013.md`

Total: 20 archivos. No se incluyen aquí los cambios preexistentes en los recursos gráficos ni DDNA_Flujo_CI_CD.docx.

## G. Validaciones

- PHP syntax: 90 archivos, PASS.
- JS syntax: los 7 JS del theme, PASS.
- git diff --check: PASS.
- verify-guide-drive-links.php: 13 destinos Drive, 11 originales conservados, PASS.
- verify-prevention-guides.php: 4 destinos únicos y PDFs conservados, PASS.
- verify-historical-novedades.php: 219 históricos íntegros y 222 novedades publicadas, PASS.
- Comparación SHA256 de posts/contenidos/fechas frente al snapshot anterior: idénticos. Opciones institucionales almacenadas: idénticas.
- Browser QA: diez rutas Programas en desktop 1440 y en viewports reales de iframe de 768 y 375 px, PASS. La API de viewport no aplicó nuevos tamaños al tab; los tamaños tablet/mobile se comprobaron por clientWidth real dentro de los iframes, no por la etiqueta del test.
- Contacto, Necesito Ayuda y Guías sin overflow a 375 px; 13 enlaces Drive, nueva pestaña segura, PASS.
- Anterior/Siguiente completos a 1440/768/375, PASS.
- Regresión antigua verify-feedback-prensa-octubre.php: falla únicamente “Convenios: trece imágenes…”. El shortcode produce 12 imágenes, fallo ya documentado antes de este lote en docs/ajustes-finales-agenda-territorio-novedades.md. No se modificó Convenios. La suite antigua de septiembre exige 5 posts; no corresponde al histórico actual y no se ejecutó como validación editorial.

## H. Localhost para revisión

- [Home](http://localhost:8080/)
- [Contacto](http://localhost:8080/contacto/)
- [Necesito Ayuda en Home](http://localhost:8080/#necesito-ayuda)
- [Necesito Ayuda como página](http://localhost:8080/asistencia/)
- [Participación NNyA](http://localhost:8080/programas-participacion-nnya/)
- [Acompañamiento y Formación para Adultos](http://localhost:8080/acompanamiento-formacion-adultos/)
- [Cooperación Internacional e Interinstitucional](http://localhost:8080/cooperacion-internacional-interinstitucional/)
- [Va con Vos](http://localhost:8080/programas/programa-va-con-vos/)
- [Entre Pantallas](http://localhost:8080/programas/programa-entre-pantallas/)
- [Desarrollo Integral](http://localhost:8080/programas/desarrollo-integral-primeros-anos-vida/)
- [Novedades / Ver más](http://localhost:8080/category/novedades/)
- [Novedades, página 2, ambos controles](http://localhost:8080/category/novedades/?paged=2)
- [Quiero Saber / Guías](http://localhost:8080/#quiero-saber)

WordPress y MariaDB locales quedan funcionando con los volúmenes existentes. VPS, Dashboard, Supabase, Caddy, Cloudflare y DNS no se tocaron.
Capturas, recibos y comparación previa/final fuera del repositorio: `/Users/lautyvallino/Projects/DDNA/final-adjustments-local-20261013/`.

## Puerta de aprobación

El usuario aprobó el 13/10/2026 con `APROBADO PARA PUSHEAR Y PUBLICAR EN LA VPS`. Autoriza commit/push/PR/merge y despliegue controlado WordPress conforme a la solicitud original. Se revalidan también los dos commits de reconciliación heredados, ya activos en VPS. El estado previo solo local corresponde a la fase de revisión cerrada. Los recibos de publicación y el checkpoint se guardan fuera de Git.

### Últimos ajustes visuales aprobados

Línea Fija mantiene su número y tel, icono, título, naranja y ancho/padding de tarjeta. Spans de número principal/alternativas permiten un salto controlado. La fuente se adapta al ancho útil de la card con mínimo 20px. En Necesito Ayuda: una línea a 1440/1024/430, dos a 768/375; Contacto y Asistencia también pasan los cinco tamaños. El resto de los archivos preservó sus hashes durante este ajuste. El menú compacto vuelve a estar junto al botón, extendido hacia la izquierda, conforme a la última revisión del usuario.
