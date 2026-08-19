# Modelo de contenido del nuevo WordPress DDNA

## Principios

- El personal administrador debe poder publicar y actualizar información sin editar código ni construir tarjetas manualmente.
- La estructura y los datos institucionales pertenecen a `ddna-core`; su presentación pertenece al tema.
- Los estados históricos se conservan y se señalan, no se eliminan por dejar de estar vigentes.
- Los archivos institucionales deben alojarse preferentemente en la Biblioteca de Medios; las URLs externas son una alternativa transitoria.
- No se almacenarán consultas o datos personales sensibles dentro de estos tipos de contenido.

## Tipos nativos

### Entradas: novedades

Para noticias, anuncios, crónicas, efemérides y actualidad. Conservan autores, fecha, categorías, etiquetas, feed, imagen, extracto y archivo cronológico. La categoría inicial es `Novedades`.

### Páginas: información institucional

Para Inicio, Qué es la Defensoría, Autoridades, Asistencia, Contacto, privacidad, accesibilidad y otras páginas únicas. No se usan para repetir fichas de programas, sedes o documentos.

## Tipos institucionales

| CPT | Uso | Datos estructurados principales |
|---|---|---|
| `programa` | Líneas de acción permanentes o históricas | estado, fechas, contacto, micrositio, destacado |
| `campana` | Campañas con identidad, período y acción propia | estado, fechas, CTA, URL, destacado |
| `documento` | Biblioteca de PDFs y documentos oficiales | archivo/URL, año, fecha, versión, emisor, accesibilidad |
| `capacitacion` | Cada edición concreta de una formación | estado, fechas, modalidad, lugar, duración, inscripción, contacto, arancel |
| `subsede` | Ubicaciones y atención territorial | estado, domicilio, localidad, departamento, canales, horario, mapa, coordenadas, cobertura, responsable |
| `recurso` | Videos, apps, micrositios, formularios y herramientas | tipo, archivo/URL, texto del enlace, destacado |

## Taxonomías

- `ddna_tema`: temática compartida por novedades y contenido institucional.
- `ddna_publico`: público destinatario de programas, campañas, documentos, capacitaciones y recursos.
- `tipo_documento`: informe anual, normativa, guía, comunicado, pronunciamiento, convenio, folleto u otros.
- `tipo_capacitacion`: diplomatura, seminario, taller, jornada, curso o conversatorio.

Las taxonomías son jerárquicas para permitir vocabularios controlados y filtros. No se usa una taxonomía para estados o fechas: son atributos propios de cada ficha.

## Criterios editoriales

- Una noticia sobre la apertura de una subsede es una entrada; la subsede operativa es una ficha `subsede` relacionada por tema o enlaces editoriales.
- Una noticia sobre la firma de un convenio es una entrada; el convenio PDF es un `documento` de tipo Convenio.
- Una edición anual de diplomatura es una `capacitacion`; no se sobreescribe la edición anterior.
- Un PDF es `documento`; un video, app o micrositio es `recurso`.
- Una campaña puede agrupar noticias y recursos mediante taxonomías compartidas y enlaces editoriales, sin duplicar contenidos.
- Los campos “estado” permiten mantener archivos finalizados, pausados o cerrados de forma explícita.

## Presentación futura

El tema podrá consultar CPT, taxonomías y metadatos para construir listados, filtros, mapas y fichas. `ddna-core` no genera tarjetas, colores ni templates de frontend.
