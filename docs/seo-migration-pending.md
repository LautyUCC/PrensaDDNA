# SEO técnico y decisiones pendientes de migración

## Alcance

Este documento separa las configuraciones aplicables al nuevo WordPress de las decisiones que dependen de una futura migración. No se crearon redirecciones 301, no se modificaron URLs productivas, no se eliminaron páginas y no se importó contenido histórico.

# IMPLEMENTADO

## Títulos y jerarquía semántica

- El tema declara soporte nativo para `title-tag`; WordPress genera el `<title>` según la vista.
- La Home contiene un único `h1` lógico con el nombre completo de la institución.
- Programas, Campañas y Novedades utilizan `h2`.
- Cada tarjeta administrable utiliza `h3`.
- Los headings no se utilizan exclusivamente para obtener tamaños visuales.

El nombre y la descripción general del sitio deben revisarse en **Ajustes → Generales** antes del despliegue para quitar cualquier referencia a “Desarrollo”.

## Meta description

La descripción se genera sin plugin SEO y sigue este orden:

- Home: extracto de la página configurada como portada;
- fallback de Home: descripción corta del sitio;
- fallback institucional final si ambos campos están vacíos;
- contenidos individuales: extracto del contenido;
- categorías y taxonomías: descripción del término.

La salida se convierte a texto plano, normaliza espacios y se limita a 160 caracteres. Esto permite editar la descripción principal desde WordPress sin modificar PHP.

## Canonical

- WordPress genera canonical nativo para la Home y contenidos individuales.
- No se agregó una segunda etiqueta canonical.
- Open Graph utiliza la misma URL absoluta en `og:url` para Home y contenidos individuales.
- No se forzaron canonicals de archivos o taxonomías cuya estrategia todavía no está decidida.

## Open Graph

Se implementaron metadatos básicos:

- `og:locale`;
- `og:type`;
- `og:title`;
- `og:description`;
- `og:url` cuando existe una URL canónica inequívoca;
- `og:site_name`;
- `og:image` usando primero la imagen destacada y luego el logo personalizado.

No se agregaron Twitter Cards específicas porque no existe una necesidad o cuenta/configuración confirmada; las plataformas que consumen Open Graph ya reciben los datos esenciales.

## Imágenes

- Todas las imágenes renderizadas en la Home tienen atributo `alt`.
- Hero y tarjetas utilizan imágenes responsivas mediante `srcset` y `sizes`.
- El Hero conserva dimensiones intrínsecas y prioridad alta.
- Logos dentro de enlaces usan texto alternativo vacío para evitar redundancia y el enlace posee un nombre accesible.
- El `alt` de los medios administrables procede de la Biblioteca de Medios.

La calidad SEO de futuros textos alternativos dependerá del criterio editorial. Deben describir la imagen cuando aporta información y permanecer vacíos cuando sea decorativa; no deben rellenarse con palabras clave artificiales.

## Sitemap XML

- El sitemap nativo de WordPress está disponible en `/wp-sitemap.xml`.
- Incluye automáticamente los tipos y taxonomías públicas que contienen elementos indexables.
- Se excluyó el sitemap de usuarios para no publicar nombres/archivos de autores innecesariamente.
- No se instaló un plugin SEO sólo para reemplazar esta funcionalidad nativa.

## Robots e indexación de entornos

- `/robots.txt` es servido por WordPress e incluye la referencia al sitemap.
- WordPress bloquea `/wp-admin/` y permite `admin-ajax.php`.
- Todo entorno cuyo `WP_ENVIRONMENT_TYPE` no sea `production` recibe `noindex, nofollow` mediante `wp_robots`, incluso si se copia accidentalmente la opción de visibilidad.
- En producción, el resultado vuelve a depender de **Ajustes → Lectura → Visibilidad en los motores de búsqueda**. Antes del lanzamiento debe confirmarse que esa opción permita indexar.

## URLs y enlaces

- El nuevo entorno usa permalinks amigables `/%postname%/`.
- Los CPT tienen slugs legibles definidos en `ddna-core`.
- Menú principal, accesos rápidos y tarjetas utilizan URLs generadas o administradas por WordPress.
- Los enlaces internos no fuerzan pestañas nuevas.
- Los enlaces sociales externos usan `noopener noreferrer`.

Estos slugs describen el nuevo modelo, pero no implican que se hayan aprobado como destinos definitivos de URLs históricas.

# PENDIENTE

## Inventario definitivo de contenidos

Antes de una migración se debe decidir:

- qué páginas antiguas se conservarán;
- qué páginas serán reemplazadas por nuevas versiones;
- qué contenidos históricos se migrarán;
- qué Programas, Campañas, Novedades, Capacitaciones y Subsedes permanecerán publicados;
- qué contenidos deben archivarse sin desaparecer;
- qué documentos y PDFs deben mantenerse accesibles.

No debe inferirse esta selección a partir de la existencia actual de CPT o templates.

## Mapeo de URLs

Todavía debe construirse y aprobarse una matriz por URL con:

- URL productiva actual;
- tipo de contenido y estado HTTP actual;
- tráfico, enlaces entrantes y relevancia;
- URL de destino propuesta;
- acción: conservar, migrar, consolidar, retirar o responder 404/410;
- necesidad y destino exacto de una eventual redirección 301;
- responsable de aprobación.

Hasta contar con esa matriz no se deben cambiar slugs productivos ni crear reglas globales, aproximadas o automáticas.

## Redirecciones

- No hay redirecciones 301 definitivas implementadas.
- Falta decidir qué URLs cambian y cuál es el equivalente real de cada una.
- No deben redirigirse masivamente URLs antiguas hacia la Home: eso puede confundir a usuarios y motores de búsqueda.
- Las cadenas y bucles deberán verificarse en staging antes del lanzamiento.
- El mecanismo final dependerá también de las capacidades de Hostinger y del servidor web elegido.

## Documentos y PDFs

Debe definirse:

- qué PDFs siguen vigentes;
- cuáles conservan su URL de archivo;
- qué documentos tendrán una ficha HTML y qué canonical corresponderá;
- cómo se tratarán versiones, reemplazos y documentos obsoletos;
- qué metadatos y archivos deben migrarse;
- si un PDF retirado requiere reemplazo, redirección, 404 o 410.

No se deben eliminar adjuntos ni cambiar rutas hasta aprobar esta política.

## Taxonomías, archivos e indexación

Falta decidir qué archivos de CPT, categorías y taxonomías deben indexarse. La decisión depende de que tengan:

- contenido suficiente y diferenciable;
- introducción editorial;
- utilidad real para navegación y búsqueda;
- ausencia de duplicación con páginas institucionales.

Hasta entonces no se aplicaron `noindex` selectivos ni canonicals cruzados a esos archivos.

## Metadatos sociales y editoriales definitivos

Antes de producción se debe confirmar:

- nombre oficial corto del sitio;
- descripción institucional aprobada;
- imagen Open Graph oficial, idealmente 1200 × 630;
- si se implementarán Twitter/X Cards específicas;
- criterios editoriales para titles y meta descriptions de contenidos migrados.

## Datos estructurados

No se implementó JSON-LD institucional porque deben confirmarse el tipo de entidad, nombre legal, logo definitivo, datos de contacto oficiales, perfiles sociales y URLs de producción. Debe evitarse publicar schema incompleto o basado en datos locales.

## Lanzamiento y seguimiento

Queda pendiente:

1. rastreo completo del sitio productivo autorizado;
2. medición de URLs con tráfico y backlinks;
3. aprobación de la matriz de migración;
4. prueba de redirecciones en staging;
5. verificación de canonicals y sitemap con dominio definitivo;
6. apertura de indexación sólo al publicar;
7. alta/verificación en Search Console;
8. monitorización de 404, cobertura, indexación y rendimiento después del lanzamiento.

Ninguna de estas tareas autoriza por sí misma a migrar, eliminar o reemplazar contenido del sitio DDNA existente.
