# Estado actual del proyecto DDNA

Fecha de auditoría: 12 de agosto de 2026  
Entorno auditado: local (`http://localhost:8080`)  
Alcance: inspección técnica y documental del repositorio y del WordPress local. No se modificaron funcionalidades, diseño, contenido ni producción.

## Resumen ejecutivo

El rebuild cuenta con una infraestructura local operativa, un tema personalizado activo, un plugin institucional activo y una Home funcional, responsive y administrable. La implementación actual sirve para demostración local y constituye una base técnica razonable para ampliar el sitio.

El entregable no equivale todavía al reemplazo integral del sitio histórico. Las páginas internas definitivas, la migración de documentos e imágenes, el inventario final de URLs y las redirecciones permanecen pendientes de decisiones editoriales y de migración. Tampoco se realizó un despliegue en staging o producción.

La auditoría confirmó:

- WordPress 7.0.2 responde por HTTP con estado 200.
- MariaDB está saludable y todas las tablas revisadas responden `OK`.
- `ddna-theme` 0.2.0 y `ddna-core` 0.1.0 están activos.
- No se observaron errores PHP críticos, errores de acceso a la base ni fallos de InnoDB en las últimas 200 líneas de logs.
- El repositorio Git está inicializado, pero la rama actual todavía no tiene commits y todos los archivos aparecen sin seguimiento. Esto debe resolverse antes de preparar una entrega o staging.

## 1. Objetivo actual del proyecto

El objetivo confirmado es entregar una nueva experiencia de Home para DDNA, apoyada en una instalación WordPress independiente y preparada para futuras ampliaciones. Incluye infraestructura, tema, funcionalidades institucionales, administración de los contenidos principales, responsive, accesibilidad, rendimiento, seguridad y SEO técnico básico.

No es objetivo actual reconstruir ni reemplazar el sitio histórico completo. El sitio productivo `ddna.cba.gov.ar` se mantiene únicamente como referencia y no debe modificarse. La regla vigente en `AGENTS.md` prohíbe migraciones masivas, eliminación o reemplazo de sus contenidos sin instrucción explícita.

## 2. Arquitectura técnica implementada

### Infraestructura

- Docker Compose coordina WordPress, MariaDB y WP-CLI.
- WordPress usa la imagen `wordpress:7.0.2-php8.3-apache`.
- La base usa `mariadb:11.4.12` y dispone de healthcheck.
- La Home local se publica en `http://localhost:8080` mediante el puerto host 8080.
- MariaDB utiliza el puerto 3306 solamente dentro de la red de Docker; no está publicado al host.
- WP-CLI se ejecuta como servicio bajo demanda y comparte Core, uploads y código propio con WordPress.
- Hay persistencia separada para base de datos, Core y uploads.
- El tema, el plugin y los mu-plugins se montan desde el repositorio, separados del Core.
- La configuración admite variables para `LOCAL`, `STAGING` y `PRODUCTION`, sin fijar dominios en los templates.
- `.env` está ignorado; existen ejemplos específicos para los tres entornos.

### Estructura relevante del repositorio

```text
DDNA rebuild/
├── app/
│   ├── mu-plugins/
│   ├── plugins/
│   │   └── ddna-core/
│   │       ├── assets/js/admin-media.js
│   │       ├── inc/
│   │       │   ├── admin.php
│   │       │   ├── content-types.php
│   │       │   ├── home-management.php
│   │       │   ├── institutional-settings.php
│   │       │   ├── meta-fields.php
│   │       │   └── taxonomies.php
│   │       └── ddna-core.php
│   └── themes/
│       └── ddna-theme/
│           ├── assets/
│           │   ├── css/
│           │   ├── images/
│           │   └── js/
│           ├── inc/
│           ├── template-parts/
│           ├── 404.php
│           ├── archive.php
│           ├── footer.php
│           ├── front-page.php
│           ├── functions.php
│           ├── header.php
│           ├── index.php
│           ├── page.php
│           ├── single.php
│           └── style.css
├── config/
│   └── wp-config.environment.php
├── docker/
├── docs/
├── scripts/
├── .env.example
├── .env.staging.example
├── .env.production.example
├── .gitignore
├── AGENTS.md
├── compose.yaml
└── README.md
```

### Git y configuración

La estructura está preparada para Git y la exclusión de secretos y archivos generados está configurada. Sin embargo, no existe todavía un commit inicial. Antes de staging se necesita revisar el conjunto de archivos, confirmar que no haya datos locales o secretos y crear una línea base versionada.

## 3. Tema personalizado `ddna-theme`

El tema contiene las plantillas mínimas profesionales solicitadas y mantiene presentación separada de la lógica institucional. Está activo en la instalación local.

### Capacidades y convenciones

- Soporte para `title-tag`, imágenes destacadas, HTML5, logo personalizado, responsive embeds y alineación amplia.
- Uso correcto de `wp_head()`, `wp_footer()`, `body_class()` y `language_attributes()`.
- Menús registrados: principal, accesos rápidos y footer.
- Tamaños de imagen propios para cards de programas y novedades.
- CSS modular por settings, base, layout, componentes y utilidades.
- Tokens mediante custom properties para colores, tipografía, espacios, radios, sombras y contenedores.
- JavaScript vanilla y diferido para navegación y carruseles; no hay page builders ni librerías visuales pesadas.
- Componentes reutilizables para programas, campañas, novedades, controles, iconos, header, footer y secciones.
- Plantillas genéricas de página, single, archivo y 404 disponibles como base, no como páginas internas definitivas.

### Organización visual

`front-page.php` funciona como orquestador y delega cada bloque en `template-parts/sections/`. La Home no está concentrada en un único archivo. Los estilos específicos se cargan modularmente y los scripts de carrusel se limitan a la portada.

## 4. Plugin institucional `ddna-core`

El plugin concentra el modelo de contenido y la administración institucional independientemente del tema. No incorpora presentación visual pública.

### Tipos de contenido

- `programa`, con archivo y slug `programas`.
- `campana`, con archivo y slug `campanas`.
- `documento`, con archivo y slug `biblioteca`.
- `capacitacion`, con archivo y slug `capacitaciones`.
- `subsede`, con archivo y slug `subsedes`.
- `recurso`, con archivo y slug `recursos`.

Todos son públicos, compatibles con REST y administrables. Incluyen, según el caso, título, editor, extracto, imagen destacada, revisiones, autor, campos personalizados y orden de página.

### Taxonomías

- Temas DDNA.
- Públicos destinatarios.
- Tipos de documento.
- Tipos de capacitación.

### Metadatos y administración

- Programas: estado, fechas, contacto, URL externa, destacado y orden de Home.
- Campañas: estado, fechas, CTA, URL externa, destacado y orden de Home.
- Documentos: archivo, URL, año, fecha, versión, organismo emisor y accesibilidad.
- Capacitaciones: estado, fechas, modalidad, lugar, duración, inscripción, contacto y costo.
- Subsedes: estado, domicilio, localidad, departamento, CP, teléfonos, WhatsApp, correo, horarios, mapa, coordenadas, cobertura y responsable.
- Recursos: tipo, archivo, URL, texto de CTA y destacado.

Los metaboxes son nativos y el selector de medios reutiliza la biblioteca de WordPress. La activación registra términos iniciales y actualiza reglas de enlaces permanentes.

### Configuración transversal

- `Apariencia > Portada DDNA`: cantidades de programas, campañas y novedades.
- `Ajustes > Datos institucionales`: teléfonos de asistencia, domicilio, teléfono, correo y redes sociales.
- Menús: selección de icono para los seis accesos rápidos mediante una lista controlada.

## 5. Estado de la Home, sección por sección

### Header y navegación

- **Estado:** funcional y dinámico.
- **Fuente:** menú nativo de WordPress asignado a la ubicación principal.
- **Administrable:** elementos, enlaces, jerarquías, orden y submenús.
- **Plantilla:** `header.php` y `template-parts/navigation/primary.php`.
- **Comportamiento:** navegación desktop y menú móvil con botón hamburguesa, control de estado y cierre por Escape.
- **Observación:** la identidad visual principal se concentra en Hero según el diseño; el header no duplica innecesariamente el lockup.

### Hero

- **Estado:** funcional.
- **Fuente:** imagen destacada de la página configurada como portada y logo personalizado; existen recursos fallback del tema.
- **Dinámico:** imagen principal y logo.
- **Estático de estructura:** composición institucional y denominación legal usada como H1.
- **Plantilla:** `template-parts/sections/hero.php`.
- **Observación:** la imagen above-the-fold se carga prioritariamente y conserva composición responsive mediante `object-fit` y `object-position`.

### Accesos rápidos

- **Estado:** funcional y dinámico.
- **Fuente:** menú WordPress asignado a `quick_access`.
- **Administrable:** título, URL, orden e icono de cada acceso.
- **Plantilla:** `template-parts/sections/quick-access.php` y componente de icono.
- **Contenido previsto:** Asesoramiento y Consultas, Talleres Interactivos, Datos sobre la Niñez y la Adolescencia, Recursos Didácticos, Subsedes y Mapeo de Instituciones.
- **Observación:** la solución mantiene la edición simple para seis elementos, sin crear un CPT innecesario.

### Programas

- **Estado:** funcional y dinámico.
- **Fuente:** CPT `programa`.
- **Administrable:** título, imagen destacada, extracto, enlace externo o permalink, destacado y orden.
- **Consulta:** solamente publicados y destacados, ordenados por orden de menú y luego fecha; cantidad configurable.
- **Plantilla:** `template-parts/sections/programs.php` y `template-parts/components/program-card.php`.
- **Contenido local actual:** 3 programas publicados.
- **Observación:** cards y carrusel toleran distintas cantidades y ausencia de imagen mediante fallback.

### Campañas

- **Estado:** funcional y dinámico.
- **Fuente:** CPT `campana`.
- **Administrable:** título, imagen, extracto/información, CTA, enlace, destacado y orden.
- **Consulta:** campañas publicadas y destacadas; cantidad configurable.
- **Plantilla:** `template-parts/sections/campaigns.php` y componente de card.
- **Contenido local actual:** 4 campañas publicadas.
- **Observación:** el componente actual prioriza la composición gráfica del PDF; aunque el modelo admite imagen destacada, la variante visible no necesita mostrarla en todos los casos.

### Novedades

- **Estado:** funcional y automático.
- **Fuente:** entradas nativas de WordPress, preferentemente categoría `novedades`; si la categoría no existe, usa las últimas entradas.
- **Administrable:** imagen destacada, título, extracto, contenido, fecha, categoría y permalink.
- **Consulta:** cronológica, sin consultas de conteo innecesarias; cantidad configurable.
- **Plantilla:** `template-parts/sections/latest-news.php` y `template-parts/components/news-card.php`.
- **Contenido local actual:** 4 novedades de demostración más la entrada predeterminada `Hello world!` fuera de la selección editorial principal.
- **Observación:** la fecha participa del orden cronológico, aunque no se muestra en la card actual porque no forma parte necesaria de la composición aprobada.

### Footer

- **Estado:** funcional y centralizado.
- **Fuente:** logo personalizado, nombre del sitio y opción institucional única.
- **Administrable:** teléfonos, etiquetas de líneas de asistencia, domicilio, teléfono general, correo y redes.
- **Plantilla:** `template-parts/footer/site-footer.php`.
- **Comportamiento:** enlaces `tel:`, `mailto:` y redes con nombres accesibles; año dinámico.
- **Observación:** existe una ubicación de menú de footer registrada como preparación, pero la composición definitiva actual no la utiliza.

## 6. Capacidades actuales de administración

Un administrador puede, sin editar PHP, CSS o JavaScript:

- Modificar, agregar, quitar, jerarquizar y ordenar el menú principal.
- Gestionar submenús y el menú de accesos rápidos.
- Cambiar el logo desde el personalizador/configuración nativa.
- Crear y editar programas, campañas, novedades y sus imágenes.
- Marcar programas y campañas para Home y controlar su orden.
- Configurar cuántos programas, campañas y posts muestra la portada.
- Editar teléfonos, correo, dirección y redes desde una única pantalla.
- Preparar documentos, capacitaciones, subsedes y recursos como contenidos estructurados cuando se autorice esa etapa.

La administración está basada en WordPress nativo y código propio. No se instalaron ACF, Elementor, Divi ni plugins equivalentes.

## 7. Estado responsive

La Home emplea grid, flexbox, `clamp()`, `minmax()` y dimensiones fluidas. Las auditorías previas cubrieron 320, 360, 375, 390, 430, 600, 768, 820, 1024, 1280, 1366, 1440 y 1920 píxeles.

Estado observado/documentado:

- Menú móvil, logo, submenús y hamburger se adaptan sin depender de hover.
- Hero cambia altura y encuadre sin deformar la imagen.
- Accesos rápidos reorganizan columnas y conservan objetivos táctiles.
- Programas, campañas y novedades cambian su cantidad visible y conservan controles utilizables.
- Footer colapsa columnas de forma legible.
- No hay un problema conocido de scroll horizontal accidental en los viewports auditados.

Pendiente razonable antes de producción: validación final en dispositivos físicos y navegadores objetivo con contenido editorial definitivo.

## 8. Accesibilidad

### Implementado

- Landmarks `header`, `nav`, `main`, `section`, `article` y `footer`.
- Un único H1 lógico en Hero; H2 para secciones y H3 para cards.
- Enlace para saltar al contenido.
- Texto alternativo o tratamiento decorativo según el uso de cada imagen.
- Navegación completa por teclado y estados de foco visibles.
- Menú móvil con botón real, `aria-expanded`, asociación con el panel y cierre mediante Escape.
- Carruseles con botones reales, nombres accesibles y soporte de teclado; no dependen exclusivamente de swipe.
- Enlaces comprensibles, incluidos teléfonos, correo y redes.
- Respeto de `prefers-reduced-motion` para movimiento no esencial.
- Uso limitado de ARIA donde HTML semántico no cubre el comportamiento.

### Pendiente de revisión humana

- Prueba completa con lectores de pantalla en Windows, Android e iOS.
- Validación con usuarios reales, incluidas personas con discapacidad.
- Revisión de textos alternativos y claridad editorial cuando se cargue contenido definitivo.
- Repetición de contrastes si la identidad o los colores oficiales cambian.

## 9. Rendimiento

### Estado actual

- Imágenes de contenido servidas mediante funciones WordPress con `srcset` y `sizes`.
- Imágenes de cards con tamaños registrados y `object-fit: cover`.
- Hero con carga eager y prioridad alta; imágenes fuera del primer viewport con lazy loading.
- Assets propios en WebP cuando corresponde; no se destruyeron originales.
- CSS modular y específico por componentes, sin framework externo.
- JavaScript pequeño, vanilla y diferido; no hay dependencia de jQuery para la Home.
- No se descargan fuentes web externas: se usan fallbacks de sistema.
- Consultas de Home separadas por sección, sin repetir la misma consulta y con optimizaciones como `no_found_rows` cuando corresponde.
- No se instaló un plugin de caché, de acuerdo con el alcance.

### Pendiente antes de producción

- Medición Lighthouse/WebPageTest sobre staging con HTTPS, latencia y contenido reales.
- Política de caché, compresión y CDN según capacidades del plan Hostinger.
- Optimización editorial de imágenes definitivas antes de subirlas.

## 10. Seguridad

### Estado actual del código propio

- Salidas escapadas con funciones contextuales (`esc_html`, `esc_attr`, `esc_url`) y HTML controlado con `wp_kses_post` donde corresponde.
- Inputs administrativos sanitizados según tipo.
- Metaboxes protegidos con nonces, permisos, control de autosave y `current_user_can()`.
- Metadatos REST con autorización para editar el post.
- No existen endpoints AJAX o REST personalizados adicionales que amplíen superficie de ataque.
- Enlaces externos con nueva pestaña incorporan `noopener noreferrer`.
- No se hallaron credenciales hardcodeadas en el código propio; los secretos se esperan en `.env`, que está ignorado.
- La edición de archivos desde WordPress está deshabilitada.
- Debug y visualización de errores son configurables por entorno y no deben mostrarse en producción.
- WordPress Core no fue modificado.

### Pendiente antes de staging/producción

- Rotar y definir secretos exclusivos por entorno.
- Confirmar permisos de archivos del hosting y acceso administrativo mínimo necesario.
- Configurar backups, actualizaciones, correo transaccional y monitoreo.
- Verificar cabeceras HTTP, TLS y configuración efectiva del servidor Hostinger.

## 11. Estado del contenido

### Contenido local disponible

- 3 programas publicados: Entre Pantallas, Detrás del Humo y Va con Vos.
- 4 campañas publicadas: Hay Otra Forma, Guías para la Prevención, Guías para una Crianza Cuidada y La vida es un viaje único.
- 4 novedades de demostración asociadas al flujo editorial de novedades.
- Página Inicio configurada como portada.
- Páginas provisionales: Defensoría, Quiénes somos, Asistencia, Contacto y Mapeo de Instituciones.
- Contenido predeterminado de WordPress todavía presente: `Hello world!` y `Sample Page`.

Este contenido permite comprobar componentes dinámicos, proporciones, títulos y extractos. No debe confundirse con una carga editorial aprobada para producción.

### Contenido que no fue migrado

- Archivo histórico completo de noticias.
- Documentos y PDFs institucionales históricos.
- Imágenes y biblioteca multimedia del sitio anterior.
- Capacitaciones históricas.
- Subsedes con datos definitivos.
- Recursos descargables históricos.
- Páginas institucionales internas definitivas.
- Taxonomías, metadatos y relaciones históricas.

No hay contenidos publicados localmente en los CPT documento, capacitación, subsede o recurso. Esta ausencia es intencional y responde al alcance confirmado.

## 12. Hitos ya completados

1. Auditoría técnica y funcional del sitio público actual, sin scraping masivo.
2. Creación de infraestructura local independiente con Docker, WordPress, MariaDB y WP-CLI.
3. Creación y activación del tema personalizado.
4. Diseño e implementación del modelo institucional en `ddna-core`.
5. Análisis del PDF oficial y documentación de especificación visual.
6. Implementación del sistema global de diseño.
7. Implementación de header, navegación y Hero.
8. Implementación dinámica de accesos rápidos, programas, campañas y novedades.
9. Implementación y centralización del footer institucional.
10. Documentación formal del alcance y protección del sitio histórico.
11. Auditorías visual, de administrabilidad y de contenido dinámico.
12. Auditorías responsive y de accesibilidad.
13. Auditorías de rendimiento y seguridad del código propio.
14. SEO técnico básico y separación de decisiones de migración pendientes.
15. Preparación documental y de configuración para futuro staging en Hostinger.
16. Preparación y verificación de la demostración local.

## 13. Clasificación general

### COMPLETADO

- Infraestructura Docker local y persistencia.
- WordPress local conectado a MariaDB.
- Tema `ddna-theme` y plugin `ddna-core` activos.
- Home con header, navegación, Hero, accesos rápidos, programas, campañas, novedades y footer.
- Administración de menú, logo, contenidos principales y datos institucionales.
- Base responsive, accesible y optimizada del código propio.
- SEO técnico básico independiente de la migración.
- Documentación del proyecto, alcance, administración, accesibilidad, rendimiento, seguridad, SEO y staging.

### FUNCIONAL PERO REQUIERE REVISIÓN

- Contenido de demostración y datos institucionales antes de publicación.
- Validación visual final con el equipo responsable del PDF.
- Pruebas manuales en dispositivos físicos, lectores de pantalla y navegadores objetivo.
- Plantillas genéricas internas: funcionan como WordPress básico, pero no constituyen el diseño definitivo de páginas internas.
- Configuración de staging: está preparada y documentada, pero todavía no se probó en Hostinger.
- Estado Git: repositorio inicializado, pero sin commit base ni archivos rastreados.

### PENDIENTE

- Definir el inventario editorial definitivo de la Home.
- Retirar o conservar conscientemente el contenido predeterminado de WordPress antes de staging público.
- Crear commit inicial, estrategia de ramas y respaldo remoto.
- Desplegar y validar staging en un dominio temporal con HTTPS.
- Definir backups, cron, correo, caché y logs efectivos del hosting.
- Completar pruebas de aceptación editorial, accesibilidad y rendimiento en staging.
- Decidir el alcance real de una eventual fase de páginas internas.

### FUERA DEL ALCANCE ACTUAL

- Modificar o reemplazar `ddna.cba.gov.ar`.
- Migrar masivamente páginas, documentos, imágenes o noticias históricas.
- Reconstruir todas las páginas internas.
- Eliminar contenido del sitio anterior.
- Crear redirecciones 301 definitivas.
- Cambiar URLs productivas.
- Implementar page builders.
- Desplegar a producción.

## 14. Próximos pasos recomendados

### Para demo local

1. Mantener `docker compose up -d` y comprobar `http://localhost:8080`.
2. Validar que el contenido visible sea apropiado para terceros y esté claramente identificado como demostración.
3. Probar menú, carruseles y enlaces en el navegador que se usará durante la presentación.
4. Confirmar acceso administrativo local sin compartir credenciales fuera del equipo autorizado.

### Para staging

1. Revisar archivos no rastreados y crear un commit inicial sin `.env`, uploads, base de datos ni Core.
2. Crear base de datos y credenciales exclusivas de staging.
3. Elegir dominio temporal, habilitar HTTPS y configurar las variables del entorno.
4. Desplegar únicamente código propio y configuración necesaria siguiendo `docs/staging-hostinger.md`.
5. Transferir de forma controlada la base y los uploads estrictamente necesarios para la demo.
6. Verificar URLs, permisos, cron, correo, logs, caché y backups.

### Decisiones de contenido y migración

1. Aprobar qué contenidos locales son definitivos y cuáles son solamente muestras.
2. Definir qué páginas antiguas se conservan, reemplazan, fusionan o descartan.
3. Elaborar inventario validado de URLs, documentos, PDFs e imágenes antes de importar.
4. Definir responsables editoriales y criterios de vigencia, privacidad y accesibilidad.
5. Solo después, diseñar un mapa de redirecciones 301 y un plan de migración reversible.

### Antes de producción

1. Completar y aprobar staging.
2. Congelar alcance, contenido, URLs y ventana de publicación.
3. Crear backups verificados del sitio productivo y del nuevo staging.
4. Ejecutar pruebas funcionales, responsive, accesibilidad, seguridad, SEO y rendimiento con datos finales.
5. Preparar plan de reversión, monitoreo y responsables de la salida.
6. Autorizar explícitamente cualquier migración o cambio productivo; sin esa autorización, producción no debe tocarse.

## Conclusión

El proyecto está listo para una demostración local y técnicamente preparado para iniciar una fase controlada de staging. No está listo para reemplazar el sitio histórico ni para producción porque faltan decisiones de contenido y migración, validación real en hosting y una línea base versionada en Git. La arquitectura actual permite continuar sin rehacer la Home y mantiene correctamente separados Core, presentación y lógica institucional.
