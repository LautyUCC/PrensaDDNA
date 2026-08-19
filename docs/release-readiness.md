# Release readiness del rebuild DDNA

## Veredicto ejecutivo

**Estado del entregable actual: READY para cierre de la etapa local y preparacion de staging.**

**Estado para reemplazar el sitio historico o publicar en `ddna.cba.gov.ar`: BLOCKED.**

El alcance terminado es la nueva infraestructura WordPress, el codigo propio y la experiencia Home. No constituye una reconstruccion integral del sitio anterior. Los bloqueos de publicacion no son fallas de la Home: dependen de decisiones editoriales, institucionales, SEO y operativas que todavia no fueron tomadas.

## Matriz general

| Area | Elemento | Estado | Evidencia / condicion |
|---|---|---|---|
| Infraestructura | Docker local | READY | Compose valido; servicios activos. |
| Infraestructura | WordPress | READY | WordPress 7.0.2 instalado y responde HTTP 200. |
| Infraestructura | Base de datos | READY | MariaDB saludable; `wp db check` completo. |
| Infraestructura | WP-CLI | READY | Disponible mediante perfil `tools`. |
| Infraestructura | Git y exclusiones | READY | `.env`, dumps, uploads, backups y logs ignorados. |
| Infraestructura | Configuracion portable | READY | Soporta local/staging/production sin dominio definitivo hardcodeado. |
| Infraestructura | Staging Hostinger real | NEEDS REVIEW | Procedimiento listo; faltan URL temporal, credenciales, plan/servicios y autorizacion para desplegar. |
| WordPress | Tema `ddna-theme` | READY | Activo, PHP valido y frontend funcional. |
| WordPress | Plugin `ddna-core` | READY | Activo; CPT, taxonomias, campos y opciones registrados. |
| WordPress | Navegacion | READY | Menu principal y accesos rapidos son ubicaciones editables. |
| WordPress | Contenido dinamico | READY | Programas/Campanas por CPT; Novedades por posts. |
| Home | Header y navegacion | READY | Renderizados, responsive y operables por teclado. |
| Home | Hero | READY | Imagen administrable, logo personalizado y H1 institucional. |
| Home | Accesos rapidos | READY | Menu administrable con seis iconos seleccionables. |
| Home | Programas | READY | Consulta dinamica, destacados, orden y cantidad administrable. |
| Home | Campanas | READY | Consulta dinamica, destacados, orden y cantidad administrable. |
| Home | Novedades | READY | Cuatro posts locales; imagen, titulo, extracto y permalink dinamicos. |
| Home | Footer | READY | Datos institucionales centralizados y enlaces accesibles. |
| Calidad | Responsive | READY | Auditado de 320 a 1920 px sin scroll horizontal accidental. |
| Calidad | Accesibilidad tecnica | READY | Landmarks, headings, alt, teclado, foco, menu y carruseles revisados. |
| Calidad | Validacion con usuarios/lector real | NEEDS REVIEW | Recomendable antes de publicar; no bloquea el cierre local. |
| Calidad | Performance del codigo propio | READY | Imagenes responsivas/lazy, scripts defer, consultas y CSS revisados. |
| Calidad | Performance desde Hostinger | NEEDS REVIEW | Requiere staging real, latencia, cache, compresion y Lighthouse. |
| Calidad | Seguridad del codigo propio | READY | Escape, sanitizacion, nonces, permisos, secretos y debug revisados. |
| Calidad | Hardening del hosting | NEEDS REVIEW | TLS, cabeceras, permisos, backups, SMTP y logs dependen de Hostinger. |
| Calidad | SEO tecnico basico | READY | Title, description, canonical, OG, sitemap, robots y permalinks. |
| Administracion | Menu editable | READY | Agregar, quitar, reordenar y anidar desde WordPress. |
| Administracion | Programas administrables | READY | Titulo, imagen, extracto, enlace, destacado y orden. |
| Administracion | Campanas administrables | READY | Titulo, imagen, informacion, enlace, destacado y orden. |
| Administracion | Novedades administrables | READY | Entradas nativas con categoria Novedades. |
| Administracion | Datos institucionales | READY | Fuente unica en Ajustes > Datos institucionales. |
| Contenido | Contenido final aprobado de la Home | NEEDS REVIEW | El entorno contiene muestras locales; autoridades deben validar textos, imagenes, enlaces y contactos. |
| Migracion | Paginas internas historicas | BLOCKED | Fuera de alcance; no hay inventario aprobado de destino. |
| Migracion | Posts/documentos/PDFs historicos | BLOCKED | No se decidio que migrar, archivar o retirar. |
| Migracion | Matriz de URLs | BLOCKED | Falta decidir conservacion, reemplazos y destinos equivalentes. |
| Migracion | Redirecciones 301 | BLOCKED | No autorizadas hasta aprobar la matriz URL origen-destino. |
| Migracion | Reemplazo de produccion | BLOCKED | Sin autorizacion, ventana, backup, rollback ni aprobacion final. |

## Infraestructura

### READY

- `compose.yaml` define WordPress, MariaDB persistente y WP-CLI.
- MariaDB esta activa y saludable; todas las tablas pasan `wp db check`.
- WordPress 7.0.2 esta instalado; la Home responde 200.
- Core, base y uploads viven en volumenes; tema y plugin estan separados del Core.
- `.env.example` documenta local y las plantillas staging/production no contienen secretos.
- `config/wp-config.environment.php` centraliza URL, base, HTTPS, cron, debug y entorno.
- `.gitignore` protege secretos y artefactos operativos.

### NEEDS REVIEW

- Crear el staging temporal en Hostinger.
- Confirmar PHP, base, SSH/WP-CLI, SSL, cron, cache y limites del plan contratado.
- Definir URL temporal, credenciales, salts y mecanismo de variables privadas.
- Probar backup/restauracion, correo y permisos en el hosting real.

No se realizo ningun despliegue.

## WordPress y administracion

### READY

- `ddna-theme` 0.2.0 y `ddna-core` 0.1.0 estan activos.
- Permalinks amigables: `/%postname%/`.
- Ubicaciones registradas: menu principal, accesos rapidos y footer.
- Existen 3 Programas, 4 Campanas y 4 Novedades locales para validacion.
- Los datos institucionales estan configurados en una opcion central.
- Las cantidades predeterminadas de carrusel son administrables.
- Formularios administrativos usan Settings API o nonce/capability checks propios.

### NEEDS REVIEW

- Aprobar roles y responsabilidades editoriales reales.
- Validar que las muestras locales puedan conservarse, reemplazarse o descartarse en staging.
- Confirmar logo, fotografia Hero, contactos, redes y enlaces definitivos.

## Home

### READY

Estan implementados y renderizados:

1. Header.
2. Navegacion principal y submenus.
3. Hero e identidad institucional.
4. Seis accesos rapidos.
5. Programas dinamicos.
6. Campanas dinamicas.
7. Novedades dinamicas.
8. Footer institucional.

La Home conserva el alcance grafico del PDF sin depender de page builders. No se han reconstruido las paginas internas.

## Calidad

### READY

- Responsive auditado en 13 anchos entre 320 y 1920 px.
- Un solo H1, tres H2 de seccion y H3 de tarjetas.
- Todas las imagenes actuales tienen `alt`.
- Menu mobile y carruseles funcionan con teclado y Escape cuando corresponde.
- Foco visible y `prefers-reduced-motion` implementados.
- Imagenes con dimensiones, `srcset`, `sizes`, lazy loading y object-fit adecuados.
- JavaScript propio pequeno, diferido y sin librerias pesadas.
- Consultas Home sin paginacion SQL ni caches de taxonomia innecesarias.
- Salidas, inputs, permisos, nonces, REST meta, secretos y debug auditados.
- SEO basico: title, description, canonical unico, OG, sitemap sin usuarios y robots.
- Local recibe `noindex, nofollow` por no ser production.

### NEEDS REVIEW

- Auditoria manual con usuarios/lector de pantalla antes de lanzamiento.
- Lighthouse/WebPageTest en staging con red real.
- Hardening de Hostinger: TLS, cabeceras, backups, permisos, cache, logs y SMTP.
- Imagen Open Graph oficial 1200 x 630 y datos estructurados, cuando se confirmen datos de produccion.

## Documentacion

Todos los documentos solicitados existen y son utilizables:

| Documento | Estado | Funcion |
|---|---|---|
| `README.md` | READY | Puesta en marcha local y estructura. |
| `AGENTS.md` | READY | Regla de proteccion del sitio existente. |
| `docs/project-scope.md` | READY | Alcance vigente y exclusiones. |
| `docs/current-site-audit.md` | READY | Inventario de referencia, no autorizacion de migracion. |
| `docs/design-specification.md` | READY | Especificacion visual/responsive. |
| `docs/content-management.md` | READY | Guia de administracion. |
| `docs/accessibility.md` | READY | Decisiones y limites de accesibilidad. |
| `docs/performance.md` | READY | Auditoria y optimizaciones propias. |
| `docs/security.md` | READY | Auditoria de codigo/configuracion. |
| `docs/seo-migration-pending.md` | READY | SEO implementado vs. decisiones pendientes. |
| `docs/staging-hostinger.md` | READY | Procedimiento sin ejecutar de staging. |

## Bloqueos causados por la migracion historica

Los siguientes elementos estan **BLOCKED** hasta recibir decisiones y autorizacion explicitas:

### 1. Alcance editorial

No se sabe si la migracion sera total, parcial o selectiva, ni que paginas, noticias, Programas, Campanas, Capacitaciones, Subsedes y recursos se conservaran.

### 2. URLs y SEO de migracion

No existe una matriz aprobada URL origen -> URL destino. Por lo tanto no pueden definirse:

- slugs definitivos de contenidos equivalentes;
- redirecciones 301;
- canonicals cruzados;
- respuestas 404/410 planificadas;
- consolidaciones de paginas duplicadas.

### 3. Documentos y medios

No se decidio que PDFs y adjuntos permanecen, que versiones son vigentes, que URLs de archivo deben preservarse ni que derechos/metadatos existen.

### 4. Contenido historico

No hay autorizacion para importar posts, paginas, autores, formularios, eventos, categorias, comentarios, imagenes o documentos. Tampoco esta definida la fecha de corte ni el archivo historico.

### 5. Produccion

No estan aprobados dominio/ventana de cambio, congelamiento editorial, backups, rollback, DNS, cache/CDN, Search Console ni responsables de validacion. El sitio actual no debe tocarse.

## Criterio de salida de esta etapa

Esta etapa puede considerarse cerrada cuando se acepta que el entregable es:

- infraestructura WordPress nueva;
- tema y plugin propios;
- nueva Home responsive y administrable;
- documentacion tecnica y preparacion de staging.

No puede considerarse listo el reemplazo integral de `ddna.cba.gov.ar` hasta resolver todos los items BLOCKED y completar las revisiones de staging.

## Evidencia de verificacion final

- Docker: WordPress activo y MariaDB saludable.
- WordPress: 7.0.2, HTTP 200.
- Base: todas las tablas OK.
- Tema/plugin: activos.
- Home: ocho componentes principales presentes.
- HTML: H1=1, H2=3, H3=11; imagenes sin `alt`=0.
- SEO: canonical=1, description=1, siete propiedades Open Graph.
- Robots/sitemap: HTTP 200; usuarios excluidos; local no indexable.
- PHP: todos los archivos del tema/plugin sin errores de sintaxis.
- CSS: sin llaves desbalanceadas.
- Logs: sin errores fatales o warnings recientes.
- Git: `.env` ignorado y diff sin errores de whitespace.
