# Auditoría técnica y funcional del sitio actual de la DDNA Córdoba

**Sitio auditado:** <https://ddna.cba.gov.ar/>  
**Fecha de auditoría:** 11 de agosto de 2026  
**Alcance:** inventario previo al rebuild; sin scraping masivo, importación ni creación de WordPress.

## 1. Alcance, método y limitaciones

La portada pública devuelve `403 Request blocked` desde CloudFront a los clientes automatizados utilizados para esta auditoría. Los buscadores tampoco devolvieron un índice útil del dominio. Por esa razón, el inventario se construyó mediante una revisión acotada de:

- la respuesta del sitio en vivo y sus endpoints habituales;
- el sitemap de Yoast y sus sitemaps hijos archivados el 3 de abril de 2025;
- una captura de la portada y catorce páginas estructurales cercanas a abril de 2025;
- resultados públicos de fuentes institucionales complementarias, sólo para contrastar función institucional y actividad.

La evidencia integral más reciente disponible en esta revisión es de abril de 2025. Por lo tanto:

- “confirmado” significa visible en la portada o en los sitemaps/capturas revisados;
- los datos operativos (autoridades, teléfonos, correos, domicilios, horarios y estado de subsedes/capacitaciones) deben validarse con la institución antes de publicar el nuevo sitio;
- el sitemap es un inventario de URLs indexables, no garantiza que todo su contenido siga vigente;
- no se descargaron los documentos ni se auditó exhaustivamente la biblioteca de medios.

## 2. Resumen ejecutivo de la arquitectura actual

El sitio es un WordPress institucional que combina cuatro capas:

1. **Información institucional:** quiénes somos, marco normativo, convenios, subsedes y contacto.
2. **Servicios y líneas de acción:** asistencia, programas, mediación, protección digital, participación y monitoreo de medios.
3. **Biblioteca documental:** informes anuales, guías, comunicados/pronunciamientos y folletería.
4. **Actualidad y formación:** novedades y páginas de diplomaturas/seminarios.

La portada funciona como landing visual con accesos por imágenes, cinco novedades recientes, enlaces a micrositios/servicios externos, WhatsApp y redes. La arquitectura editorial es poco normalizada: conviven páginas nuevas con sufijo `-2`, versiones antiguas aún indexadas, documentos alojados tanto localmente como en Google Drive/Firebase, y gran cantidad de información incrustada en placas gráficas.

## 3. Menú principal y submenús

Navegación confirmada en la captura de portada:

- **Inicio** — `/`
- **Defensoría**
  - Quienes somos — `/quienes-somos-2/`
  - Normativas — `/normativas-2/`
  - Convenios — `/convenios-2/`
  - Subsedes — `/sub-sedes-2/`
- **Documentos**
  - Informes Anuales — `/informes-anuales-2/`
  - Guías para la Prevención — `/guias-para-la-prevencion-2/`
  - Guías para una Crianza Cuidada — `/crianza-cuidada-2/`
  - Comunicados y Pronunciamientos — `/comunicados-y-pronunciamientos-2/`
  - Folletería Digital — `/folleteria-digital-2/`
- **Asistencia** — `/asistencia-2/`
- **Programas** — `/programas-2/`
- **Capacitaciones**
  - Diplomaturas
    - Diplomatura Abogado del niño — en la portada auditada enlazaba a `/diplomatura-abogado-nnya-edicion-2025/`
    - Diplomatura NNyA mundo digital — `/diplomatura-nnya-en-el-mundo-digital/`
  - Seminario “Abordaje de las violencias hacia NNyA” — URL larga de post detallada más abajo
- **Novedades** — `/category/novedades/`
- **Contacto** — `/contacto-2/`

Problemas observados: “Defensoría”, “Documentos”, “Capacitaciones” y “Diplomaturas” actúan como agrupadores; existen slugs poco semánticos con `-2`; la edición anual de una diplomatura está fijada en el menú; y contenidos relevantes dependen de botones-imagen sin texto alternativo descriptivo.

## 4. Páginas institucionales

### Quienes somos

`/quienes-somos-2/` define a la DDNA como organismo estatal autónomo de derechos humanos. Expone misión, visión, funciones, servicios, autoridad y CV. Las funciones incluyen asistencia/orientación, recepción de consultas, mediación, asistencia jurídica a NNyA con discapacidad, promoción e investigación, supervisión del sistema de protección y participación de NNyA.

Recursos vinculados detectados:

- `/wp-content/uploads/2024/07/CV_Amelia_López.pdf`
- `/wp-content/uploads/2025/03/Dossier-Institucional-2025.pdf`

La mención a Amelia López y toda fecha de mandato requieren validación editorial al momento de migrar.

### Normativas

`/normativas-2/` agrupa Convenciones, leyes nacionales, leyes provinciales y Código Civil. PDFs confirmados:

- `/wp-content/uploads/2021/06/Convención.pdf`
- `/wp-content/uploads/2016/08/LEY-23.849.pdf`
- `/wp-content/uploads/2016/08/LEY-26.061-1.pdf`
- `/wp-content/uploads/2016/08/LEY-9.944.pdf`
- `/wp-content/uploads/2016/08/LEY-9.396.pdf`

### Convenios

Página actual `/convenios-2/`, con antecedente `/convenios/`. También existen posts de firmas de convenios. La página agregadora debería preservarse; cada convenio formal merece datos estructurados (contraparte, fecha, objeto, vigencia y archivo), mientras la noticia de la firma puede seguir como post relacionado.

### Subsedes

Página actual `/sub-sedes-2/`. Las doce tarjetas visibles en la captura auditada corresponden a:

- Río Cuarto
- Laboulaye
- Laguna Larga
- Villa María
- Cosquín
- Río Tercero
- Cruz del Eje
- Colonia Caroya
- Malvinas Argentinas
- Villa Cura Brochero
- San Francisco
- Las Varillas
- Sierras Chicas

La lista contiene trece nombres pero sólo doce enlaces de mapa: Cosquín no exhibió un enlace de Google Maps en el HTML revisado. Cada ubicación está publicada principalmente como placa gráfica, lo que impide buscar/copiar datos y perjudica accesibilidad. Antes de migrar hay que confirmar cuáles están activas, el nombre oficial de cada subsede, dirección, localidad/departamento, horarios, teléfono, correo, responsable, coordenadas y cobertura territorial.

Históricos relevantes: `/delegaciones-interior/`, `/subsedes/` y `/subsedes-interior/`, además de posts sobre aperturas/reaperturas (Villa María, Villa Cura Brochero, San Francisco, Cruz del Eje, Salsipuedes, Bell Ville y Justiniano Posse). No debe asumirse que una noticia de apertura equivale a una sede actualmente operativa.

### Contacto

La página vigente del menú es `/contacto-2/`; existe `/contacto/`. Gran parte del contenido parece estar resuelto como imagen. En el pie se confirmaron dos enlaces de WhatsApp: `3514020503` y `3512398953`, más Facebook, Instagram, X/Twitter y YouTube.

La página de asistencia publica los siguientes datos, sujetos a validación:

- Dámaso Larrañaga 94, barrio Nueva Córdoba, Córdoba, Argentina;
- (351) 428-8888;
- (351) 402-0503;
- (351) 800-6053;
- `consulta.defensoría@cba.gov.ar` (revisar técnicamente el carácter acentuado);
- `casosasistencia@gmail.com`.

## 5. Secciones de documentos y recursos descargables

### Informes anuales

`/informes-anuales-2/` enlaza informes 2016–2023. Se confirmaron:

- Informe 2023 — `/wp-content/uploads/2024/04/Informe-Defensoría-2023.pdf`
- Informe 2022 — `/wp-content/uploads/2023/03/Informe-2022.pdf`
- Informe 2021 — `/wp-content/uploads/2022/04/Informe-Anual-2021.pdf`
- Informe 2020 — `/wp-content/uploads/2021/03/DDL-2020-02.pdf`
- un informe alojado en `/informe/INFORME DIGITAL FINAL.pdf` (año por validar)
- Informe 2018 — `/wp-content/uploads/2021/01/INFORME-DIGITAL-2018.pdf`
- un informe externo en Firebase (año por validar)
- Informe 2016 — `/wp-content/uploads/2018/04/Informe-2016.pdf`

También existen páginas históricas `/informe-anual-2016/`, `/informe-2020/`, `/informe-2020-anexos/`, `/informe-2021/`, `/informe-2022/` e `/informe-anual-2023/`. Es indispensable verificar 2017, 2019 y todo informe posterior a 2023, anexos y versiones completas.

### Guías para la prevención

`/guias-para-la-prevencion-2/` contiene al menos:

- Guías de buenas prácticas — `/wp-content/uploads/2023/11/Guías-de-Buenas-Prácticas.pdf`
- Guía de juegos en línea — `/wp-content/uploads/2024/09/Guía-Juegos-en-Línea.pdf`
- Guía para la prevención del abuso y el acoso sexual hacia NNyA — `/wp-content/uploads/2023/11/Guía-para-la-prevención-del-Abuso-y-el-Acoso-Sexual-hacia-NNyA-2.pdf`
- Revista/guía sobre bullying — `/wp-content/uploads/2023/11/RevistaBULLYING-2022.pdf`

Persisten páginas históricas específicas: `/guia-sobre-bullying/`, `/guia-para-la-prevencion-del-abuso-sexual/`, `/guia-de-juegos-en-linea/`, `/guia-para-la-navegacion-segura/` y `/guia-para-adultos/`.

### Guías para una Crianza Cuidada

`/crianza-cuidada-2/` presenta ocho recursos mediante botones gráficos. Todos apuntan a archivos de Google Drive, sin título legible en el HTML. Deben inventariarse manualmente sus nombres, edición, tema, público, formato, peso, propietario y permisos; luego conviene alojar copias institucionales estables y accesibles.

### Comunicados y pronunciamientos

`/comunicados-y-pronunciamientos-2/` contiene al menos nueve PDFs, entre ellos:

- comunicado conjunto a medios de prensa;
- recomendación conjunta de defensorías;
- pronunciamiento por 40 años de democracia;
- pronunciamiento conjunto de enero de 2024;
- pronunciamiento sobre Ley Bases;
- justicia penal juvenil;
- observación sobre baja de edad de imputabilidad;
- Observación N.º 5 “Adultizar no es ampliar derechos”;
- comunicado conjunto de marzo de 2025.

Existen además posts individuales de comunicados y pronunciamientos. Esta duplicación debe resolverse relacionando la ficha documental con la publicación periodística, sin borrar ninguna URL histórica.

### Folletería digital

`/folleteria-digital-2/` declara permitir ver y descargar folletos, guías y materiales. Se detectaron trece botones-imagen hacia Google Drive, sin títulos accesibles en el HTML. Existe `/folleteria/`. Requiere inventario manual archivo por archivo antes de migrar.

### Otros recursos destacados en portada

- Informe NNyA Mundo Digital — `/wp-content/uploads/2024/07/Informe_NNyA_MundoDigital.pdf`
- otra variante desde Programas — `/wp-content/uploads/2024/07/Informe-NNyA-MundoDigital-2022-2023.pdf`
- playlist de YouTube;
- formulario de Google;
- app DDnApp en Google Play;
- micrositios externos `ddnaprotecciondigital.com`, `monitoreoddna.com`, `hayotraforma.com.ar` e `ixcongresomundialdeinfancia.com`.

Las dos variantes del informe de mundo digital deben compararse para evitar duplicar o perder versiones.

## 6. Programas

`/programas-2/` describe las siguientes líneas, hoy mezcladas en una sola página extensa:

- **Participación y promoción de derechos**
  - Va con Vos: espacio de escucha y talleres (alcohol/sustancias, bullying, emociones y sexualidad responsable).
  - Foros de participación de NNyA.
  - Escuela de Facilitadores de Participación Ciudadana.
  - Activá Participación Adolescente.
  - acompañamiento y formación para adultos.
- **Protección Digital**
  - ciudadanía y uso seguro/responsable;
  - talleres para NNyA;
  - vías de prevención/denuncia;
  - contacto `ddnaprotecciondigital@gmail.com`;
  - guías e informes relacionados.
- **Centro de Mediación y Gestión Pacífica de Conflictos**
  - servicio gratuito y confidencial para NNyA y personas responsables;
  - bullying, ciberbullying y conflictos de convivencia.
- **Los chicos y los medios**
  - monitoreo anual de medios;
  - observaciones ante vulneraciones;
  - capacitaciones en buenas prácticas periodísticas;
  - Adolescentes Comunicando.

Programas/campañas históricos que siguen indexados y deben evaluarse individualmente: Sistema Integral de Monitoreo de Derechos con Mirada de Chic@s, Centro de Mediación, Libres sin Alcohol, Va con Vos, Tu Palabra Vale, Cuidando los Cuidadores, Dado de la Paz, Protección Digital, Foro Permanente de Niños y Niñas con Discapacidad, Hay Otra Forma, DDnApp, Conocé tus Derechos, Cosas de Chicos y MUNA. La noticia histórica también menciona Programa Encontrarte y Programa de Empoderamiento de NNyA 2021–2024.

## 7. Capacitaciones

Las capacitaciones no son una colección homogénea: el menú enlaza páginas estáticas y posts, y una edición anual aparece directamente en navegación.

Elementos confirmados:

- Diplomatura Universitaria en Derechos de la Niñez y Adolescencia / Abogada-o de NNyA, con páginas/posts para 2019, 2021, 2022, 2023, 2024 y 2025.
- Diplomatura en Niñeces y Adolescencias en el Mundo Digital; la página revisada conserva datos de edición 2023 (modalidad, arancel, cronograma, docentes y evaluación), aun cuando seguía enlazada desde el menú de 2025.
- Seminario internacional “Abordajes de las violencias hacia niñas, niños y adolescentes: desafíos y aportes frente a la complejidad”.
- Capacitación virtual y múltiples jornadas, conversatorios, talleres y seminarios publicados como posts.

Riesgo principal: datos vencidos (fechas, precios, inscripción, contactos) presentados como contenido permanente. Cada edición debe tener estado (`próxima`, `inscripción abierta`, `en curso`, `finalizada`), fechas y enlace propio; la landing general no debe quedar atada a un año.

## 8. Novedades/noticias y contenido que parece WordPress post

La sección `/category/novedades/` es un archivo de posts nativos. La portada mostraba cinco entradas recientes, con imagen y título:

- Firma de Convenio de Cooperación Mutua con la Universidad Siglo 21.
- Detrás del Humo: un stream hecho por jóvenes para jóvenes.
- Firma de convenio para subsedes en Bell Ville y Justiniano Posse.
- Firma de convenio para subsede en Salsipuedes.
- Comunicado de la Defensora ante la Resolución General 1023/2024 de la CNV.

El sitemap de posts contiene publicaciones desde 2016 hasta marzo de 2025: noticias institucionales, efemérides, campañas, aperturas de sedes, invitaciones y crónicas de capacitaciones, comunicados, videos y convenios. Son señales técnicas de posts nativos:

- inclusión en `post-sitemap.xml`;
- archivo de categoría y feed RSS (`/feed/`);
- archivo de autor `/author/laura-piragine/`;
- paginación `/page/2/`;
- taxonomías de formato `/type/aside/` y `/type/video/`.

Hay slugs de baja calidad o prueba (`/asdasd/`), duplicados numerados (`...-2/`, `...-3/`) y un conflicto notable: `/dado-de-la-paz/` aparece históricamente como página y como post. Ninguno debe eliminarse sin comprobar ID, canonical, tráfico y enlaces entrantes.

## 9. Información de asistencia

`/asistencia-2/` explica un servicio gratuito durante todo el año, brindado por un equipo interdisciplinario de abogacía, trabajo social y psicología. Recibe consultas por teléfono, presencialmente, nota, agenda, correo, formulario web, app y redes; hace seguimiento cuando corresponde y auditorías/supervisión de instituciones públicas y privadas.

Temas publicados:

- acceso a derechos y trámites;
- violencia, maltrato y presunción de abuso;
- grooming y vulneraciones en redes;
- bullying/cyberbullying;
- derecho a ser escuchado;
- conflictos familiares, comunicación, parentalidad, tutela y delegación;
- DNI;
- atención/insumos de organismos;
- consumo de sustancias;
- obras sociales;
- acceso a educación y salud.

Por criticidad, la nueva arquitectura debe mostrar un CTA persistente “Necesito ayuda”, distinguir orientación de emergencia/denuncia y validar qué organismo/canal corresponde fuera de horario. No deben publicarse formularios que recojan datos sensibles sin evaluación legal, de seguridad y privacidad.

## 10. Taxonomías y tipos técnicos visibles

Confirmados por el sitemap Yoast de abril de 2025:

- categorías de posts: `novedades` y `sin-categoria`;
- formatos: `aside` y `video`;
- CPT/eventos legado bajo `/events/event/`;
- taxonomía de eventos: categoría `eventos`, etiqueta `caraffa` y lugar `museo-caraffa-plaza-espana-cordoba`;
- dos eventos de prueba/legado de agosto de 2016;
- páginas de calendario `/my-calendar/` y `/calendar/`.

La implementación mostraba WordPress, Yoast SEO, Contact Form 7, Event Organiser, tema Hueman y, en capturas históricas, WordPress 5.2.2. Esto es evidencia histórica, no una determinación de versiones actuales. Los endpoints de eventos parecen abandonados y no deberían migrarse automáticamente como arquitectura vigente.

## 11. Inventario de URLs y slugs relevantes

### URLs actuales prioritarias a preservar

Mantener exactamente o redirigir uno-a-uno sólo si hay una mejora acordada:

`/`, `/quienes-somos-2/`, `/normativas-2/`, `/convenios-2/`, `/sub-sedes-2/`, `/informes-anuales-2/`, `/guias-para-la-prevencion-2/`, `/crianza-cuidada-2/`, `/comunicados-y-pronunciamientos-2/`, `/folleteria-digital-2/`, `/asistencia-2/`, `/programas-2/`, `/category/novedades/`, `/contacto-2/`, las tres URLs de capacitación enlazadas por el menú y todas las URLs de posts del sitemap.

También deben conservarse las URLs físicas de PDFs e imágenes referenciadas externamente. Cambiar el permalink de la página no resuelve enlaces directos históricos a `/wp-content/uploads/...`.

### URLs antiguas/duplicadas candidatas a 301

La asignación final debe basarse en export completo, estado HTTP, canonical y equivalencia editorial. Mapa inicial:

| Origen histórico | Destino recomendado si el contenido es equivalente |
|---|---|
| `/institucional/`, `/defensoria/`, `/la-defensora/` | `/quienes-somos-2/` o nuevas páginas institucionales específicas |
| `/normativas/`, `/normativas/principales/`, `/normativas/adicionales/` | `/normativas-2/` o fichas/documentos equivalentes |
| `/convenios/` | `/convenios-2/` |
| `/delegaciones-interior/`, `/subsedes/`, `/subsedes-interior/` | `/sub-sedes-2/` o subsede individual equivalente |
| `/folleteria/` | `/folleteria-digital-2/` |
| `/informe-anual-2016/`, `/informe-2020/`, `/informe-2020-anexos/`, `/informe-2021/`, `/informe-2022/`, `/informe-anual-2023/` | informe individual correspondiente, no siempre la portada genérica |
| `/programas/` y páginas hijas | landing o ficha del programa equivalente |
| `/asesoramiento/`, `/asesoramiento-y-denuncias/` | `/asistencia-2/` sólo si alcance y canales coinciden |
| `/contacto/` | `/contacto-2/` |
| `/links-amigos/`, `/links-amigos-2/` | nueva página de enlaces o 410 si se documenta que no tiene sustituto |

Si se decide limpiar los slugs actuales (`quienes-somos`, `subsedes`, etc.), las URLs `-2` deben recibir 301 directos, nunca cadenas de redirecciones. Normalizar también HTTP→HTTPS, `www`→sin `www` y slash final con una sola regla canónica.

### URLs técnicas/legado a decidir

`/my-calendar/`, `/calendar/`, `/events/...`, `/type/aside/`, `/type/video/`, `/category/sin-categoria/`, `/author/laura-piragine/`, `/comments/feed/`, `/asdasd/`. Si no hay contenido útil ni enlaces/tráfico, usar 410 puede ser más correcto que redirigir todo a Inicio. La decisión requiere evidencia de Analytics/Search Console y rastreo completo.

## 12. Clasificación editorial recomendada

| Contenido | Modelo nuevo | Motivo |
|---|---|---|
| Inicio, quiénes somos, asistencia, contacto, política de privacidad/accesibilidad | Páginas estáticas editables | Contenido estable y único |
| Novedades, crónicas, efemérides, anuncios | Posts nativos | Flujo cronológico, RSS, autores y categorías |
| Programas | CPT `programa` | Ficha, estado, público, responsables, recursos y noticias relacionadas |
| Capacitaciones | CPT `capacitacion` | Ediciones, fechas, modalidad, sede, cupos, inscripción y estado |
| Subsedes | CPT `subsede` | Dirección, mapa, canales, horario, responsable, cobertura y estado |
| Documentos | CPT `documento` | Archivo, tipo, tema, año, organismo, versión, accesibilidad y relacionados |
| Convenios | CPT `convenio` o subtipo de documento | Contraparte, firma, vigencia, objeto y archivo |
| Informes anuales, guías, comunicados, normativa y folletería | CPT `documento` con taxonomía `tipo_documento` | Evita landings manuales y permite filtros |
| Campañas/micrositios | CPT `campana` sólo si habrá varias activas | Agrupa recursos, programas y noticias sin confundirlos con posts |
| Eventos | No migrar el CPT legado automáticamente | El calendario 2016 parece abandonado; crear CPT sólo si existe necesidad actual |

Taxonomías recomendadas: `tipo_documento`, `tema` (violencias, participación, mundo digital, crianza, comunicación, salud, educación, etc.), `publico` (NNyA, familias, docentes, profesionales, medios, municipios), `anio`, y opcionalmente `territorio`. Evitar usar categorías como sustituto de estructura y evitar una taxonomía por cada atributo.

## 13. Arquitectura de información propuesta

- **Inicio**
- **Necesito ayuda**
  - Cómo podemos ayudarte
  - Canales y horarios
  - Situaciones urgentes y otros organismos
  - Preguntas frecuentes
- **La Defensoría**
  - Qué es y qué hace
  - Autoridades y equipo
  - Marco normativo
  - Convenios
  - Transparencia / informes anuales
- **Programas**
  - listado filtrable
  - ficha individual y recursos/noticias relacionados
- **Capacitaciones**
  - próximas / inscripción abierta
  - archivo de ediciones finalizadas
  - ficha individual
- **Subsedes**
  - mapa/listado accesible
  - ficha por subsede
- **Biblioteca**
  - Informes anuales
  - Guías de prevención
  - Crianza cuidada
  - Comunicados y pronunciamientos
  - Normativa
  - Folletería
- **Novedades**
- **Contacto**

Inicio debería priorizar: ayuda/contacto, búsqueda, programas vigentes, subsede más cercana, biblioteca y novedades. Los datos críticos deben ser texto HTML, no imágenes. La búsqueda debe indexar metadatos y, cuando sea posible, texto de PDFs accesibles.

## 14. Qué preservar durante la migración

No debe perderse:

- el archivo completo de posts 2016–actualidad, con fechas, autores, categorías, imágenes, galerías y embeds;
- todos los informes anuales y anexos, aunque falten en la landing actual;
- normativa, convenios, comunicados y pronunciamientos por su valor legal/institucional;
- guías, materiales de crianza, protección digital y folletería;
- historial de programas, campañas y participación de NNyA, incluso si pasan a estado “finalizado/archivo”;
- noticias de aperturas de subsedes y convenios, separadas del estado operativo actual;
- metadatos SEO, títulos, descripciones, canonical, Open Graph y alt text aprovechable;
- slugs, fechas, IDs, adjuntos y relaciones entre páginas y archivos;
- enlaces externos, videos, formularios y app, luego de comprobar vigencia;
- imágenes que contienen texto, conservándolas como evidencia hasta transcribirlas a HTML accesible;
- feeds y URLs con enlaces entrantes relevantes.

## 15. Riesgos de migración

1. **Inventario incompleto por bloqueo del sitio en vivo.** Mitigar con export de base/media, WP-CLI, sitemaps actuales, Search Console y Analytics antes de construir el mapa definitivo.
2. **Duplicados y slugs `-2`.** Riesgo de canibalización, 404 y cadenas 301; resolver con matriz URL origen→destino uno-a-uno.
3. **Documentos externos.** Google Drive, Firebase y micrositios pueden cambiar permisos o desaparecer; obtener originales y definir custodia institucional.
4. **Contenido en imágenes.** Direcciones, títulos y materiales no son accesibles, buscables ni mantenibles; transcribir y conservar la placa como adjunto.
5. **PDFs faltantes o duplicados.** Comparar hash, tamaño, fecha, versión y año; no deduplicar sólo por nombre.
6. **Información operativa vencida.** Validar autoridades, subsedes, teléfonos, correos, horarios, programas y capacitaciones.
7. **Datos sensibles.** Formularios de asistencia requieren minimización, consentimiento, retención, cifrado, roles y protocolo de derivación; no migrar envíos históricos sin autorización.
8. **Eventos/pruebas heredados.** No arrastrar calendarios 2016, `sin-categoria` o `/asdasd/` sin decisión editorial.
9. **Dependencias externas y embeds.** Comprobar HTTPS, disponibilidad, privacidad y accesibilidad.
10. **Pérdida de archivo histórico.** Una campaña finalizada no es contenido descartable; debe quedar fechada y marcada como archivo.

## 16. Plan previo a la implementación

Antes de crear el nuevo WordPress:

1. obtener export XML/SQL y copia de `uploads` en entorno controlado;
2. exportar listado de posts, páginas, adjuntos, CPT/taxonomías, menús y redirects;
3. cruzar URLs con Search Console, Analytics, logs y backlinks;
4. verificar HTTP/canonical de cada URL y generar matriz de migración;
5. inventariar todos los PDFs/Drive con propietario, versión, año, accesibilidad y checksum;
6. validar contenido operativo con responsables institucionales;
7. decidir qué programas/capacitaciones/subsedes están activos, finalizados o cerrados;
8. definir política de archivo, privacidad, accesibilidad y formularios;
9. aprobar la arquitectura y los modelos de contenido;
10. recién entonces iniciar implementación e importación de prueba.

## 17. Conclusión

El valor principal del sitio no está sólo en sus páginas actuales sino en un archivo institucional de casi una década: noticias, informes, pronunciamientos, campañas, materiales pedagógicos y evidencia territorial. El rebuild debe convertir las páginas gráficas y listados manuales en contenido estructurado, conservar los posts como memoria cronológica y mantener una biblioteca documental central. La prioridad funcional debe ser que una niña, niño, adolescente, familiar o profesional encuentre ayuda y una subsede rápidamente, mientras que la prioridad técnica debe ser una migración URL-a-URL verificable, sin pérdidas ni redirecciones genéricas.
