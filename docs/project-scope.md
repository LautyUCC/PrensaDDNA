# Alcance vigente del proyecto DDNA

**Estado:** confirmado  
**Fecha de formalización:** 11 de agosto de 2026  
**Aplicación:** este documento rige el trabajo del rebuild hasta que exista una instrucción explícita que modifique su alcance.

## 1. Objetivo de la etapa actual

La etapa vigente consiste en construir una nueva base técnica y visual para DDNA en un WordPress independiente. El sitio productivo actual se utiliza únicamente como referencia de arquitectura y como antecedente para una posible migración futura.

Esta etapa no constituye una migración completa ni autoriza a copiar, reemplazar, eliminar o reorganizar masivamente el contenido histórico.

## 2. Incluido actualmente

El alcance confirmado comprende:

1. Infraestructura local nueva e independiente basada en Docker.
2. WordPress estable, base de datos persistente y WP-CLI.
3. Tema personalizado `ddna-theme`, separado de WordPress Core y sin page builders.
4. Plugin institucional `ddna-core`, responsable del modelo y la configuración independientes de la presentación.
5. Sistema de diseño global derivado de `Home WEB NUEVA DDNA 2026-02.pdf`.
6. Nueva Home y sus componentes actuales:
   - header;
   - navegación principal administrable;
   - hero institucional;
   - accesos rápidos administrables;
   - Programas dinámicos;
   - Campañas dinámicas;
   - Novedades obtenidas de posts;
   - footer institucional administrable.
7. Comportamiento responsive para desktop, notebook, tablet y mobile.
8. Accesibilidad básica de navegación, menús, controles, enlaces, foco y carruseles.
9. Estructuras técnicas preparatorias para futuras ampliaciones:
   - Custom Post Types y taxonomías institucionales;
   - configuración centralizada de contacto y redes;
   - ubicaciones de menús;
   - componentes reutilizables;
   - scripts idempotentes de preparación del entorno local.
10. Contenido demostrativo local necesario para verificar la Home. Este material es una muestra editorial de desarrollo y no representa una importación del archivo histórico.

## 3. Fuera de alcance

Hasta nueva instrucción quedan expresamente fuera de alcance:

- migrar automáticamente páginas internas del sitio anterior;
- reconstruir todas las páginas internas;
- importar entradas, autores, comentarios, categorías o archivos históricos;
- copiar masivamente imágenes, documentos, PDFs, galerías o adjuntos;
- descargar o replicar de forma masiva la biblioteca de medios productiva;
- trasladar formularios o sus envíos históricos;
- migrar eventos, calendarios, usuarios o configuraciones heredadas;
- sustituir contenidos del sitio productivo;
- eliminar, editar o despublicar contenido productivo;
- modificar URLs, slugs, enlaces permanentes o canonicals del sitio existente;
- crear o publicar redirecciones 301 definitivas;
- ejecutar importaciones XML, SQL o mediante scraping sobre el nuevo entorno;
- sincronizar bases de datos o directorios de medios entre producción y desarrollo;
- desplegar el rebuild como reemplazo del sitio actual;
- considerar los contenidos demostrativos locales como contenido final aprobado.

## 4. Elementos del sitio anterior que no deben tocarse

No se debe realizar ninguna operación de escritura sobre `https://ddna.cba.gov.ar/` ni sobre su infraestructura asociada.

En particular, deben permanecer intactos:

- la base de datos productiva;
- WordPress Core, tema y plugins productivos;
- páginas, posts, CPT y taxonomías existentes;
- usuarios, autores, roles y permisos;
- biblioteca de medios y archivos físicos;
- PDFs y enlaces directos publicados;
- formularios y datos recibidos;
- menús y opciones de WordPress;
- slugs, permalinks, canonicals y sitemaps;
- reglas del servidor, DNS, SSL, CDN y cachés;
- redirecciones existentes;
- Analytics, Search Console y otras integraciones;
- micrositios, servicios externos y cuentas sociales vinculadas.

El sitio anterior puede consultarse de forma acotada y no destructiva cuando una tarea lo requiera, pero esa consulta no amplía el alcance ni autoriza una migración.

## 5. Pendiente de decisión

Todavía no se ha decidido:

- si se realizará una migración total, parcial o selectiva;
- qué páginas internas se reconstruirán y en qué orden;
- qué contenidos históricos deben migrarse, archivarse o permanecer sólo en el sitio anterior;
- cuál será la fecha de corte editorial;
- qué URLs conservarán su slug y cuáles podrían cambiar;
- el mapa definitivo de redirecciones;
- qué documentos externos deberán pasar a custodia institucional;
- qué imágenes y PDFs poseen originales, permisos y metadatos suficientes;
- qué contenidos están vigentes, vencidos, duplicados o requieren revisión;
- cómo se tratarán autores, fechas, categorías y relaciones históricas;
- si se migrarán formularios, y bajo qué política de privacidad y retención;
- cuál será el procedimiento de despliegue y reemplazo en Hostinger;
- quién aprobará contenido, información operativa y cambios SEO;
- cuál será el plan de reversión y conservación del sitio anterior.

## 6. Decisiones previas a una eventual migración

Antes de autorizar cualquier migración deberán resolverse y documentarse, como mínimo:

1. **Alcance editorial:** inventario aprobado de contenidos incluidos y excluidos.
2. **Fuente de verdad:** export controlado de base de datos y medios, proporcionado por responsables autorizados.
3. **Propiedad y vigencia:** responsable institucional que valide textos, autoridades, teléfonos, sedes, programas y capacitaciones.
4. **Mapa de contenidos:** correspondencia individual entre contenido de origen y modelo de destino.
5. **Estrategia de URLs:** matriz URL origen → destino con status, canonical y justificación.
6. **Preservación SEO:** datos de Search Console, Analytics, sitemaps, enlaces entrantes y páginas con tráfico.
7. **Documentos y medios:** política de versiones, deduplicación, accesibilidad, derechos y custodia.
8. **Datos personales:** tratamiento autorizado para formularios, usuarios y cualquier información sensible.
9. **Archivo histórico:** reglas para conservar noticias, campañas, eventos y documentos no vigentes.
10. **Método de migración:** entorno de ensayo, scripts repetibles, logs, validación y control de duplicados.
11. **Control de calidad:** pruebas de contenido, enlaces, archivos, accesibilidad, SEO y rendimiento.
12. **Despliegue:** ventana de publicación, backups, responsables, congelamiento editorial y rollback.
13. **Aprobación explícita:** instrucción escrita que autorice la ejecución y delimite los sistemas afectados.

## 7. Regla de control de cambios

Toda ampliación que incorpore migración histórica, escritura sobre producción, eliminación de contenido, cambios de URLs o redirecciones definitivas requiere una instrucción explícita nueva.

La ausencia de una prohibición en una tarea futura no debe interpretarse como autorización. Ante una duda, debe preservarse el estado actual y solicitarse confirmación.

## 8. Referencias

### Publicación controlada aprobada — 7 de octubre de 2026

El usuario respondió `APROBADO PARA PUBLICAR` luego de las dos rondas de revisión local. Autoriza preparar/publicar el lote editorial y visual aprobado en el WordPress del VPS de revisión (http://179.199.132.207), con manifiesto de nueve guías y cinco normativas e importación WP-CLI acotada; se preservan contenido histórico, uploads anteriores y demás servicios. No autoriza tocar https://ddna.cba.gov.ar/, CI/CD, Dashboard, Supabase, Caddy, DNS ni sustituir/restaurar bases de datos. El respaldo previo es obligatorio para la operación controlada. Los iconos faltantes de las dos campañas se informaron antes de la aprobación.

### Feedback de Prensa — octubre de 2026, exclusivamente local

La solicitud explícita de este lote autoriza los ajustes de presentación y contenidos institucionales, contacto, guías, convenios, programas, campañas, territorio y novedades detallados en `feedback-prensa-octubre.md`. Incluye importar en Media Library local nueve guías provistas y los cinco PDFs enlazados en la fuente canónica de Normativa, con manifiesto y WP-CLI reproducibles. Los informes y tres dossiers ya presentes en los recursos del repositorio se usan como fixtures de revisión en una DB local independiente; no se copió una DB ni una biblioteca productiva.

Se trabaja en `feature/feedback-prensa-octubre`, desde `9945d18e38d3c2abbef920f13602c0304a142b7d`, sin commit, push, PR, merge, acceso al VPS o deploy. La frase `APROBADO PARA PUBLICAR` habilitará preparar una publicación controlada futura; esta solicitud no autoriza ejecutarla ahora ni modificar infraestructura, Dashboard o datos productivos.

### Ampliación local autorizada — 14 de septiembre de 2026

La solicitud explícita de actualización global con `DDNA_CONTENIDOS_FINAL_SEPT_2026_Codex.md` amplía la etapa local: contenidos finales de Home/institucional, nueve páginas nuevas (incluidas tres páginas de programas), convenios, estructura de Agenda/Prensa/Comunicados y contacto final. Se conserva el lenguaje visual; páginas/CPT existentes se reutilizan sin importar historia productiva.

Esta ampliación NO autoriza migración histórica completa, importación masiva, eliminación de archivos, escritura en producción, cambios de DNS/URLs productivas, redirecciones definitivas o despliegue. Los antiguos mockups solo siguen rigiendo el lenguaje visual cuando son compatibles con el contenido final. El WordPress local real no tiene Elementor: no se instaló ningún page builder.

Consultar `final-sept-2026-gap-analysis.md`, `final-sept-2026-implementation-report.md` y `final-sept-2026-pending.md` para el estado vigente y las limitaciones.

- `docs/current-site-audit.md`: inventario de referencia; no es una orden de migración.
- `docs/design-specification.md`: especificación visual de la Home.
- `docs/content-model.md`: preparación del modelo administrable; no obliga a importar contenido histórico.
- `Home WEB NUEVA DDNA 2026-02.pdf`: referencia gráfica oficial de la Home.

### Reconciliación histórica autorizada — 9 de octubre de 2026

La solicitud explícita autoriza recorrer íntegramente el archivo oficial, reconciliar e importar su contenido y medios exclusivamente en WordPress local, conservar solo las nuevas 1–3 y excluir 4–13 sin destruir sus fuentes. El usuario confirmó 2026-10-09 como fecha de publicación de las tres nuevas. Trabajo en `feature/novedades-reconciliation`: no commit/push/PR/merge/VPS. Solo la respuesta exacta `LISTO PARA PUSH` autoriza commit y push de la feature, sin PR ni despliegue.

### Publicación de la reconciliación — 9 de octubre de 2026

El usuario autorizó explícitamente `LISTO PARA PUSH Y PUBLICAR EN VPS`. Amplía la fase anterior: commit y push de `feature/novedades-reconciliation` y despliegue controlado en el VPS de revisión `http://179.199.132.207`, incluyendo los 219 históricos, las tres nuevas con fecha confirmada, siete retiros a papelera y un borrador REVIEW. No autoriza PR/merge, DNS, SSL/TLS, el sitio anterior ni otros servicios. Respaldo DB/uploads previo; reutilizar imagen/configuración vigente y reemplazar únicamente WordPress. Resolver identidades por source_id, nunca por IDs locales; preservar medios anteriores y contenido ajeno.
