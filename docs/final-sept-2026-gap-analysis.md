# Brechas — contenidos finales septiembre 2026

Fecha de auditoría: 2026-09-13. Alcance: WordPress local `ddna-rebuild` únicamente.

## Protección del estado previo

- **PENDIENTE:** el árbol Git contiene modificaciones y archivos sin confirmar anteriores a esta actualización. No se creó un checkpoint para no atribuir ni confirmar cambios cuya autoría no puede determinarse con seguridad.
- **OK:** Docker, WordPress, `ddna-theme`, `ddna-core`, Elementor y los CPT institucionales están operativos localmente.
- **OK:** no se inspeccionó, copió ni modificó producción, DNS, Hostinger ni contenido histórico externo.

## Portada y paneles

| Elemento | Estado actual | Estado final requerido | Estado | Acción prevista |
| --- | --- | --- | --- | --- |
| Seis accesos | Existen con hashes y controlador accesible | Textos y bajadas finales | MODIFICAR | Actualizar etiquetas y descripciones, conservando interacción actual. |
| Necesito Ayuda | Contenido temporal y datos de contacto parcialmente distintos | Texto final completo y canales centralizados | MODIFICAR | Reemplazar contenido visible, reutilizar configuración institucional. |
| Quiero Saber | Incluye informes y comunicados; materiales con notas antiguas visibles | 4 guías de prevención, 8 de crianza, materiales pendientes y recursos didácticos | MODIFICAR | Retirar bloques no aprobados del frontend; conservar archivos/CPT; añadir enlace YouTube seguro. |
| Quiero Conocer | Tres programas temporales, cuatro campañas, capacitación temporal | Seis programas, tres campañas y una diplomatura final | MODIFICAR | Actualizar contenido demostrativo local y consultas de portada sin borrar históricos. |
| Observatorio | Estructura y mensaje de preparación | Nombre final y contenido pendiente | MODIFICAR | Ajustar nomenclatura y mantener estado vacío explícito. |
| Territorio | Mapa y seis sedes locales | Conservar implementación; título/bajada final | MODIFICAR / PENDIENTE | Actualizar bajada. No cambiar sedes sin verificación con planilla. |
| Actualidad | Solo muestra Novedades | Agenda, Novedades y Prensa/Comunicados | MODIFICAR | Conservar novedades dinámicas y crear subsecciones preparadas sin URLs inventadas. |

## Institucional y contacto

| Elemento | Estado actual | Estado final requerido | Estado | Acción prevista |
| --- | --- | --- | --- | --- |
| La Defensoría | Acordeones existentes con texto anterior | Textos finales de quiénes somos, hacemos, misión, visión y objetivos | MODIFICAR | Reemplazar solamente el contenido visible. |
| Dossier institucional | Botón visual sin URL | Mantener control, URL pendiente | OK / PENDIENTE | Conservar sin destino inventado. |
| Informes de Gestión | No está en la barra | Existe conceptualmente; ubicación visual no definida | PENDIENTE | No inventar ubicación ni informes; registrar para decisión posterior. |
| Normativas | Tarjetas temporales de convenciones y leyes | Estructura sin listado final | MODIFICAR / PENDIENTE | Retirar tarjetas temporales del frontend, sin borrar documentos. |
| Convenios | Tabla temporal con 15 filas | Lista exacta de 12 registros | MODIFICAR | Sustituir filas visibles por la lista aprobada. |
| Contacto | Una fuente de datos, línea fija y dirección distintas; segundo correo hardcodeado | Seis datos finales centralizados | MODIFICAR | Ampliar la configuración institucional y consumirla en Home y footer. |

## Programas, campañas y capacitaciones

| Elemento | Estado actual | Estado final requerido | Estado | Acción prevista |
| --- | --- | --- | --- | --- |
| Programas | Entre Pantallas, Detrás del Humo y Va con Vos | Seis programas finales | MODIFICAR / CREAR | Actualizar registros locales y crear los tres programas nuevos con contenido aprobado. |
| Páginas de programas | No existen las tres solicitadas | Participación, Formación Adultos y Cooperación | CREAR | Usar singles nativos del CPT `programa` como páginas propias con contenido final. |
| Dossiers de programas | No hay URLs definitivas | Va con Vos, Entre Pantallas y Desarrollo Integral pendientes | PENDIENTE | Mantener tarjetas sin destino ficticio y registrar los pendientes. |
| Centro de Mediación | Figura entre materiales y contenido temporal | No debe aparecer en Quiero Conocer | ELIMINAR | Retirar del frontend de programas; no borrar datos históricos. |
| Campañas | Cuatro campañas temporales | Tres campañas finales | MODIFICAR / ELIMINAR | Actualizar tres registros destacados y retirar la cuarta del frontend. |
| Capacitaciones | No hay CPT publicado; plantilla lista diplomaturas anteriores y seminario temporal | Una diplomatura final y categoría Seminarios sin contenido | MODIFICAR / CREAR | Crear capacitación local aprobada, mostrar estado pendiente para seminarios. |

## Novedades y arquitectura editorial

| Elemento | Estado actual | Estado final requerido | Estado | Acción prevista |
| --- | --- | --- | --- | --- |
| Novedades | Posts nativos, Home con cuatro, archivo, búsqueda y paginación | Mantener arquitectura actual | OK | No reemplazar ni borrar posts. |
| Agenda | No existe | Sub-sección sin eventos definidos | CREAR / PENDIENTE | Crear estado de preparación sin eventos ficticios. |
| Prensa | No existe | Medios y Comunicados por año | CREAR / PENDIENTE | Crear estructura; títulos de comunicados sin links hasta recibir URLs. |
| Comunicados | Contenido temporal dentro de Quiero Saber | Tres títulos por año en Prensa | MODIFICAR / PENDIENTE | Mover a la estructura visible de Prensa como texto no enlazado mientras faltan URLs. |

## Datos externos y pendientes editoriales

| Elemento | Estado | Acción |
| --- | --- | --- |
| Google Sheets de Territorio | PENDIENTE | No alterar sedes hasta contrastar la planilla o recibir datos aprobados. |
| Materiales gráficos descargables | PENDIENTE URL | Mostrar control preparado sin destino. Las notas de actualización quedan fuera del frontend. |
| Recursos didácticos | CREAR | Usar la URL YouTube provista con `target="_blank"` y `rel="noopener noreferrer"`. |
| Informes de Gestión, Normativas, Agenda, Medios y dossiers | PENDIENTE | Conservar estructuras preparadas sin inventar contenido, documentos o destinos. |

## Riesgos y decisiones aplicadas

1. Los cambios de contenidos se limitan al entorno local y al frontend del rebuild.
2. Los registros locales que dejan de ser parte de la Home se conservan y se desdestacan; no se eliminan físicamente.
3. Los enlaces inexistentes se presentan como estados pendientes no interactivos; nunca como `#` ni URLs inventadas.
4. La inconsistencia del nombre del Observatorio se resuelve usando el nombre completo de portada: “Observatorio de Niñez, Adolescencia, Familia y Comunidad”.

## Ejecución local y validación

- **IMPLEMENTADO:** se actualizaron los seis accesos de la Home, Necesito Ayuda, Quiero Saber, Quiero Conocer, Observatorio, Territorio y Actualidad con el contenido final disponible.
- **IMPLEMENTADO:** se actualizaron La Defensoría, la tabla de Convenios, los datos de contacto centralizados y la navegación institucional desplegable.
- **IMPLEMENTADO:** se crearon localmente las tres páginas aprobadas de programas y se destacaron los seis programas finales. Los programas con dossier pendiente permanecen sin enlace ficticio.
- **IMPLEMENTADO:** se destacaron únicamente las tres campañas aprobadas. La campaña temporal restante se conserva en la base local, pero ya no aparece en la Home.
- **IMPLEMENTADO:** se configuró la diplomatura aprobada y el estado explícito pendiente para Seminarios, Agenda, Medios, materiales gráficos, normativas, informes y dossiers sin URL.
- **VERIFICADO:** Home, Prensa, Novedades y la página de Programas de Participación responden HTTP 200; `ddna-theme` y `ddna-core` están activos; MariaDB está saludable; WordPress permanece activo e `init` finalizó con código 0.
- **NO REALIZADO:** no se modificó producción, staging, DNS, Hostinger, contenido histórico externo ni volúmenes Docker.
