# Diferencias — contenidos finales septiembre 2026

Fecha: 2026-09-14. Entorno exclusivamente LOCAL. Fuente: `DDNA_CONTENIDOS_FINAL_SEPT_2026_Codex.md`, leída íntegramente; la solicitud adjunta autoriza la implementación. Checkpoint: `fcf4b52`; árbol inicial limpio. No se migrará producción ni se borrarán registros históricos.

| Elemento | Clasificación inicial | Actual → requerido / acción prevista | Componente | Estado final |
| --- | --- | --- | --- | --- |
| Docker, tema y DDNA Core | OK | WordPress instalado, tema y plugin activos; reutilizar | compose, app | DONE |
| Elementor | PENDIENTE | No instalado, contrario a la descripción de la solicitud. No instalar ni simular integración; conservar the_content | plugin list, single.php | NOT APPLICABLE |
| Hero, header, hashes, controles | OK | Conservar video, identidad, navegación editable y seis IDs de panel | hero, navigation.js, home-panels.js | DONE |
| Portada | MODIFICAR | Bajadas antiguas y Observatorio abreviado → seis nombres y bajadas exactos | quick-access.php | DONE |
| La Defensoría | MODIFICAR | Página preparada → contenido aprobado completo y dossier sin URL inventada | page, contenido WordPress | PARTIAL |
| Informes de Gestión | PENDIENTE | Sin listado final; preparar apartado institucional dentro de La Defensoría, sin decidir un quinto botón inferior | página defensoria | PENDING |
| Normativas | CREAR / PENDIENTE | Acceso a taxonomía → página preparada sin lista temporal | institutional-navigation, página normativas | PARTIAL |
| Convenios | CREAR | Acceso a taxonomía → página con los 12 nombres aprobados | página convenios, menú | DONE |
| Contacto y footer | MODIFICAR | Fijo 428-8888, domicilio incompleto y segundo correo disperso → 428 8881, domicilio y correos centrales aprobados | institutional-settings, partial contacto | DONE |
| Necesito Ayuda | MODIFICAR | Texto anterior hardcodeado → todos los textos finales administrables; contacto reutilizable | panel-necesito-ayuda, página asistencia | DONE |
| Guías prevención/crianza | OK / PENDIENTE | Hay 4 y 8 etiquetas/iconos; conservar cantidades. Fuente nueva no define títulos ni URLs; registrar validación pendiente, no importar PDFs | panel-quiero-saber | PARTIAL |
| Informes/comunicados en Quiero Saber | ELIMINAR | Retirar bloques del panel, sin eliminar documentos, adjuntos o noticias | panel-quiero-saber | DONE |
| Materiales gráficos | MODIFICAR / PENDIENTE | Sublista temporal → acceso único preparado; URL sin definir | panel-quiero-saber | PARTIAL |
| Recursos didácticos | CREAR | Enlace externo exacto a playlist aprobada | panel-quiero-saber | DONE |
| Programas | MODIFICAR | Tres programas demo → seis aprobados. Quitar destacado a antiguos, no borrar; conservar CPT y carrusel | programs, script actualización | DONE |
| Participación NNyA | CREAR | Página WordPress propia con todo el texto y dos Drive | página programas-participacion-nnya | DONE |
| Formación adultos | CREAR | Página propia con texto aprobado | página acompanamiento-formacion-adultos | DONE |
| Cooperación / MUNA | CREAR | Página propia, contenido completo y 20 municipios en cuatro cohortes | página cooperacion-internacional-interinstitucional | DONE |
| Dossiers de programas | PENDIENTE | Va con Vos, Entre Pantallas y Desarrollo Integral sin destino final; tarjetas sin enlace falso | program-card, metadatos | PENDING |
| Centro de Mediación | ELIMINAR | Retirar referencias temporales públicas de paneles; no eliminar archivos | panel-quiero-saber, conocer | DONE |
| Campañas | MODIFICAR | Cuatro campañas demo → tres finales, iconos oficiales; retirar destacado a otras, no borrar | campaigns, campaign-card | PARTIAL |
| Capacitaciones | MODIFICAR | Dos diplomaturas y seminario temporales → diplomatura IA y categoría Seminarios vacía | trainings, CPT | PARTIAL |
| Territorio | MODIFICAR | Conservar seis pins, componentes y coordenadas. Planilla accesible; Cosquín agrega Catamarca 554. Sin importación masiva; MUNA desde cohortes aprobadas | panel-territorio | DONE |
| Observatorio | MODIFICAR / PENDIENTE | Consulta genérica a publicaciones → nombre completo de portada y estructura sin indicadores inventados | panel-observatorio | PARTIAL |
| Actualidad | MODIFICAR | Solo novedades → Agenda, Novedades y Prensa | panel-actualidad | DONE |
| Novedades | OK | Cuatro posts de novedades, categoría, fecha, extracto y permalink; conservar todos los posts, archivo y búsqueda existentes | latest-news, archive, single | DONE |
| Agenda / medios | CREAR / PENDIENTE | Páginas preparadas, sin eventos o notas ficticios | páginas agenda, medios | PARTIAL |
| Comunicados | CREAR / PENDIENTE | Tres títulos finales por 2025/2022; no aparecen en el inventario local. Resolver solo coincidencias reales, si no sin href inventado | página comunicados, partial títulos | PARTIAL |
| Documentación y repetibilidad | CREAR | Manifest de contenido aprobado, script LOCAL con backup previo e idempotencia; bootstrap nuevo no reintroduce demos | content, scripts, docs | DONE |
| Responsive / accesibilidad / logs | PENDIENTE | Verificar ocho anchos y teclado, HTML, PHP/JS y logs antes de cierre | pruebas | DONE |

## Criterios y límites

- No se borrarán posts, páginas, archivos ni CPT. Exclusión de portada mediante destacado; datos anteriores respaldados antes de escritura local.
- El archivo fuente prevalece sobre PDFs anteriores. Se preservan slugs existentes de páginas y posts.
- Informe de Gestión queda como subapartado institucional provisional: el documento no determina si corresponde otro botón inferior.
- El documento no identifica títulos individuales de las doce guías; mantener los labels existentes no equivale a aprobar sus PDFs.
- Notas editoriales de materiales y columnas internas de planilla no se muestran al público.
- No habrá push ni despliegue en esta tarea.

## Cierre de implementación

DONE: código y contenido definidos implementados. PARTIAL: estructura funcional con recursos/contenidos pendientes. PENDING: decisión o recurso no entregado. NOT APPLICABLE: Elementor ausente en el estado real. Detalle en `final-sept-2026-implementation-report.md` y `final-sept-2026-pending.md`.

Se corrigió además el desplazamiento mobile de pins al abrir popups mediante un lienzo independiente, sin alterar coordenadas. Menú Home conectado a Accesos rápidos de WordPress; formularios propios verificados, PHP válido y pruebas Chrome en ocho viewports sin overflow. Se preserva historia y no se migra producción.
