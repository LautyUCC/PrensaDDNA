# Auditoría de rendimiento del nuevo sitio DDNA

## Alcance y criterio

La auditoría cubre exclusivamente la Home del entorno local y el código propio de `ddna-theme`/`ddna-core`. No se consultó ni modificó producción, no se descargaron recursos del sitio anterior y no se instalaron plugins de caché.

Se priorizaron optimizaciones claras y mantenibles. No se minificaron manualmente los archivos fuente ni se introdujo un proceso de build obligatorio para Hostinger.

## Resumen de correcciones

- Se eliminó la cadena serial de dependencias entre todas las hojas CSS.
- La Home dejó de solicitar los estilos exclusivos de plantillas interiores.
- Los scripts propios usan la estrategia nativa `defer` de WordPress.
- Se registraron tamaños de imagen específicos para tarjetas.
- Programas y Novedades solicitan esos tamaños en lugar de variantes genéricas.
- Las imágenes inferiores declaran explícitamente lazy loading y decodificación asíncrona.
- Las tres consultas de contenidos de Home evitan cálculos de paginación y caché de taxonomías innecesarios.
- Se generaron únicamente derivados faltantes de los medios locales, preservando sus originales.

## Imágenes

### Estado encontrado

- Hero: WebP, 2560 × 1707, aproximadamente 337 KB. Es el principal candidato a LCP.
- Logo horizontal: PNG, 984 × 255, aproximadamente 159 KB. Se usa en Hero y footer, pero el navegador reutiliza la misma URL desde caché.
- Imágenes de Programas: WebP locales entre 1024 × 768 y 1400 × 788.
- Imágenes de Novedades: WebP entre 512 y 1200 px de ancho.
- WordPress genera `srcset` y `sizes` para Hero, logo, Programas y Novedades.
- Las reglas CSS usan `object-fit: cover`, evitando deformación al mezclar proporciones.

### Correcciones

- Hero conserva `loading="eager"`, `fetchpriority="high"`, `sizes="100vw"` y dimensiones intrínsecas. No debe usar lazy loading por estar above-the-fold.
- Se registró `ddna-program-card` con máximo 900 × 900 y crop.
- Se registró `ddna-news-card` con 768 × 480 y crop.
- Programas y Novedades solicitan los nuevos tamaños y mantienen `srcset`/`sizes` responsivos.
- Las imágenes inferiores usan `loading="lazy"` y `decoding="async"`.
- Se regeneraron sólo los derivados faltantes de los siete adjuntos locales utilizados en tarjetas. No se reemplazaron ni recomprimieron los originales.

Los medios futuros obtendrán estas variantes al cargarse normalmente desde WordPress. Si una instalación desplegada ya contiene medios anteriores, deberá regenerar sólo los thumbnails faltantes durante su preparación, nunca sobrescribir originales.

### Pendientes razonables

- El PNG del logo es relativamente pesado para una identidad plana. Debe solicitarse el SVG o WebP oficial antes de reemplazarlo; no se realizó una conversión destructiva o visualmente incierta.
- El Hero es aceptable para su resolución, aunque una dirección de arte mobile podría reducir bytes si se aprueba otro recorte institucional.

## CSS

### Problema encontrado

La arquitectura fuente es modular y mantenible, pero cada módulo se cargaba como una hoja independiente dependiente de la anterior. Esto producía 22 solicitudes CSS y una cascada bloqueante completamente serial para alrededor de 30 KB de CSS propio.

### Correcciones

- Se quitó la hoja `style.css` del frontend: conserva los metadatos requeridos por WordPress, pero no contiene estilos de ejecución.
- Todos los módulos dependen solamente de `tokens.css`, eliminando la cadena serial.
- La Home no carga `cards.css`, `site-shell.css` ni `main.css`, utilizados por plantillas interiores provisionales.
- La Home queda en 18 hojas CSS y aproximadamente 27.7 KB sin compresión HTTP en local.

Se conservaron los archivos modulares para evitar un bundle manual difícil de mantener. En producción, HTTP/2, compresión Brotli/Gzip y caché del servidor reducirán el coste; un bundle generado puede evaluarse más adelante si existe un pipeline de despliegue confiable.

## JavaScript

- No hay jQuery, sliders externos ni librerías de terceros en la Home.
- `navigation.js` pesa aproximadamente 2.9 KB.
- `carousel.js` pesa aproximadamente 1.6 KB y sólo se carga en la portada.
- Ambos scripts se cargan en footer y ahora usan la estrategia nativa `defer`.
- No se encontró código duplicado material.
- El carrusel usa `ResizeObserver` con fallback, listeners pasivos para scroll y respeta `prefers-reduced-motion`.

No se minificaron los fuentes: el ahorro potencial es pequeño frente a la pérdida de legibilidad sin un proceso automático.

## Fuentes

- No se descargan fuentes externas ni se bloquea el render esperando Google Fonts.
- El sistema declara Montserrat y Forma DJR Banner si están instaladas, con fallbacks de sistema.
- No existen archivos WOFF/WOFF2 dentro del tema ni solicitudes de pesos innecesarios.

Esto prioriza rendimiento y privacidad, pero puede producir diferencias tipográficas entre dispositivos. Si se reciben archivos oficiales y licencias de webfont, la estrategia recomendada es alojar WOFF2 localmente, limitarse a los pesos realmente usados y precargar sólo el archivo crítico.

## WordPress y consultas

La Home ejecuta una consulta independiente y necesaria por fuente editorial:

1. Programas destacados.
2. Campañas destacadas.
3. Entradas de la categoría Novedades.

No se repite ninguna de esas consultas dentro de sus loops. Cada consulta:

- limita `posts_per_page` con la opción administrable;
- consulta sólo contenido publicado;
- usa `no_found_rows => true` porque no hay paginación numerada;
- desactiva `update_post_term_cache` cuando las tarjetas no leen taxonomías;
- mantiene la caché de metadatos, necesaria para enlaces externos y datos destacados.

No se unificaron artificialmente los tres `WP_Query`, porque pertenecen a post types y criterios distintos; hacerlo complicaría el orden editorial y no eliminaría de forma fiable las consultas SQL necesarias.

## Recursos nativos de WordPress

La Home no carga jQuery, bloques de frontend externos ni plugins visuales. WordPress mantiene enlaces de descubrimiento REST/oEmbed en el `<head>`; son bytes de HTML y no solicitudes bloqueantes de render, por lo que no se eliminaron sin una necesidad operativa.

## Verificación

- Frontend HTTP 200.
- Apariencia y estructura de las secciones conservadas.
- Hero mantiene prioridad alta; imágenes inferiores permanecen responsivas y lazy.
- Los dos scripts renderizan con `defer`.
- PHP sin errores de sintaxis.
- CSS sin llaves desbalanceadas.
- Sin errores fatales o warnings de aplicación en logs posteriores a los cambios.

## Recomendaciones para despliegue

- Habilitar caché de página/servidor y compresión en Hostinger una vez definido el entorno final.
- Configurar expiración larga para assets versionados.
- Confirmar HTTP/2 o HTTP/3.
- Ejecutar Lighthouse/WebPageTest contra staging con latencia real antes del lanzamiento.
- Monitorizar LCP del Hero y peso total de imágenes editoriales; los administradores deben evitar subir originales desproporcionadamente grandes.
