# Especificación visual de la Home DDNA

**Fuente:** `Home WEB NUEVA DDNA 2026-02.pdf`  
**Fecha de análisis:** 11 de agosto de 2026  
**Estado:** especificación previa a implementación; no constituye la Home implementada.

## 1. Alcance y método

El original es una única mesa de trabajo creada en Adobe Illustrator 29.8 y exportada como PDF/X-4. Mide **1900 × 5291,5 puntos** y representa una Home desktop larga. No es un PDF etiquetado ni contiene variantes responsive.

La revisión combinó:

- render completo a 1900 px de ancho;
- recortes de header/hero, Programas, Campañas, Novedades y footer;
- extracción de texto y familias tipográficas incrustadas;
- muestreo de colores planos del render;
- mediciones relativas sobre la mesa de 1900 px.

Las medidas de esta especificación son estimaciones de implementación, no coordenadas de producción. Deben verificarse contra los assets originales de Illustrator y fuentes licenciadas antes de cerrar el sistema visual.

### Convenciones de certeza

- **A — Visible:** aparece inequívocamente en el PDF.
- **B — Inferido:** comportamiento necesario para convertir la propuesta desktop en una interfaz responsive y accesible.
- **C — No determinado:** el PDF no contiene evidencia suficiente; requiere decisión o material adicional.

## 2. Estructura general de la Home

Orden vertical visible:

1. Header con navegación horizontal.
2. Hero fotográfico con identidad DDNA superpuesta.
3. Seis accesos rápidos.
4. Sección Programas, presentada como carrusel.
5. Sección Campañas, presentada como carrusel de composiciones gráficas.
6. Sección Novedades, presentada como carrusel de cards.
7. Footer institucional naranja.

No se observan breadcrumbs, buscador, CTA dentro del hero, bloque de asistencia antes del footer, testimonios, newsletter ni otros elementos. No deben incorporarse a esta Home sin una decisión posterior.

## 3. Design tokens estimados

### 3.1 Colores

| Token propuesto | Valor estimado | Uso visible | Certeza |
|---|---:|---|---|
| `--color-canvas` | `#E6E1DE` | Header, fondo general y fondo interior de secciones | A, muestreo directo |
| `--color-accent` | `#FF8C00` | Campañas, botones “Leer más”, iconos rápidos, footer y detalles | Definición institucional vigente |
| `--color-ink` | `#050506` | Texto, contornos, iconos y flechas | A, muestreo directo |
| `--color-surface` | `#FFFFFF` | Accesos rápidos y placas de texto sobre Programas | A |
| `--color-overlay` | negro, aproximadamente 55–70% | Oscurecimiento uniforme sobre fotografía del hero | A en presencia; opacidad estimada |
| `--color-logo-blue` | por extraer del archivo de marca | Pastilla “Provincia de Córdoba” y marca DDNA | A en la marca; C en valor exacto |
| `--color-focus` | por definir con contraste AA | Foco de teclado | B; no aparece en PDF |

Los colores azul, verde, magenta y naranja dentro de la marca DDNA deben provenir del archivo oficial, no recrearse desde una captura. Los colores de fotografías y miniaturas no forman parte de la paleta estructural.

### 3.2 Tipografía

Familias detectadas dentro del PDF:

- **Montserrat:** Light, Medium y SemiBold; los metadatos de Illustrator también mencionan Regular y Black.
- **Forma DJR Banner:** Bold.
- **Fieldwork Geo:** Light.

Asignación visual estimada:

| Rol | Familia/peso probable | Tamaño desktop estimado |
|---|---|---:|
| Navegación principal | Montserrat SemiBold | 24–28 px sobre mesa de 1900 px |
| Títulos de sección | Forma DJR Banner Bold o Montserrat Black | 96–112 px |
| Título de card Programa | Forma DJR Banner Bold / peso negro | 48–58 px |
| Descripción de Programa | Montserrat Light | 40–48 px |
| Títulos de Campaña | Forma DJR Banner Bold / Montserrat Black | 38–50 px |
| Listas de Campaña | Montserrat Light/Regular | 26–34 px |
| Título de Novedad | Montserrat SemiBold | 24–30 px |
| Extracto de Novedad | Montserrat Light | 24–28 px |
| Botón | Montserrat Medium/SemiBold, mayúsculas | 17–20 px |
| Footer | Montserrat Medium/SemiBold | 18–25 px |
| Logotipo/lockup | arte oficial; Fieldwork Geo Light aparece incrustada | No componer como texto web |

Los tamaños anteriores expresan la proporción del layout fuente. Para CSS deben convertirse a una escala fluida, no copiarse como píxeles rígidos.

Escala propuesta para implementación:

```css
--font-body: "Montserrat", sans-serif;
--font-display: "Forma DJR Banner", "Montserrat", sans-serif;
--step--1: clamp(0.875rem, 0.82rem + 0.2vw, 1rem);
--step-0: clamp(1rem, 0.94rem + 0.25vw, 1.125rem);
--step-1: clamp(1.25rem, 1.08rem + 0.65vw, 1.625rem);
--step-2: clamp(1.75rem, 1.42rem + 1.2vw, 2.5rem);
--step-3: clamp(2.5rem, 1.8rem + 2.4vw, 4.5rem);
--step-4: clamp(3.25rem, 2.1rem + 4vw, 6.5rem);
```

La escala es **B — inferida**. La licencia, formato web (`woff2`) y disponibilidad de Forma DJR Banner y Fieldwork Geo son **C — no determinados**.

### 3.3 Contenedores y anchos

Sobre la mesa de 1900 px:

- Los bloques de Programas, Campañas y Novedades comienzan aproximadamente a **55 px** y terminan alrededor de **1810–1845 px**.
- El ancho útil equivale aproximadamente a **92–94% del viewport**.
- El hero ocupa el **100% del ancho**.
- El footer ocupa el **100% del ancho**.
- Los accesos rápidos usan casi todo el ancho, con margen lateral aproximado de **55 px**.

Tokens propuestos:

```css
--container-wide: 112rem;       /* techo aproximado para desktop grande */
--container-reading: 48rem;
--gutter-inline: clamp(1rem, 3vw, 3.5rem);
--section-gap: clamp(3.5rem, 7vw, 8rem);
```

El máximo exacto y el comportamiento por encima de 1900 px son **C**. Se recomienda limitar el contenido y permitir que el fondo continúe, evitando cards excesivamente anchas.

### 3.4 Bordes, radios y sombras

- Contornos negros, uniformes y sin sombra en accesos, grandes secciones y cards de Novedades.
- Grosor visual estimado: **2–3 px** en la mesa original.
- Accesos rápidos: radio aproximado **28–34 px**.
- Contenedores de sección: radio exterior aproximado **55–65 px**.
- Cards de Programa: radio exterior aproximado **65–75 px**, con un segundo contorno naranja interior.
- Cards/placas de Campaña: radio aproximado **28–34 px**.
- Cards de Novedades: radio aproximado **30–36 px**.
- Botones “Leer más”: forma píldora, radio total (`999px`).
- No se observan sombras arrojadas estructurales. Las cards de Programa tienen una banda/silueta gris lateral que puede ser profundidad, borde o parte del arte.

La forma exacta de radios y grosores debe medirse en el archivo vectorial (**C**). Como base responsive se propone:

```css
--border-width: 2px;
--radius-sm: 1rem;
--radius-md: 1.75rem;
--radius-lg: clamp(2rem, 4vw, 4rem);
--radius-pill: 999px;
```

## 4. Header y navegación

### A — Visible

- Barra horizontal de fondo gris cálido.
- Altura aproximada de 123 px sobre la mesa de 1900 px.
- Ocho enlaces distribuidos horizontalmente: Inicio, Defensoría, Documentos, Asistencia, Programas, Capacitaciones, Novedades y Contacto.
- Texto negro, peso alto, sin iconos ni logo visible en el header.
- No hay borde, sombra, indicador de página activa ni botón hamburguesa en la variante desktop.
- La navegación se percibe centrada/distribuida, con márgenes amplios.

### B — Responsive inferido

- Desktop grande: mantener los ocho elementos en una línea.
- Notebook: reducir gaps y tamaño dentro del rango tipográfico; si el conjunto deja de caber, colapsar antes de producir dos líneas.
- Tablet y mobile: botón de menú accesible que controle un panel vertical. Mantener el orden exacto del menú.
- El menú debe soportar submenús de WordPress aunque el PDF sólo muestre el primer nivel.
- Área táctil mínima de 44 × 44 px, foco visible, `aria-expanded`, cierre con Escape y gestión correcta de teclado.
- No hacer sticky por defecto: el PDF no lo indica.

### C — No determinado

- Comportamiento sticky o transparente.
- Estados hover, focus, active y submenús.
- Altura exacta en cada breakpoint.
- Si “Inicio” debe ocultarse visualmente en mobile.

### Fuente WordPress

`wp_nav_menu()` en la ubicación `primary`. Los textos, orden, jerarquía y destinos deben ser administrables. No codificar los ocho enlaces en el template.

## 5. Hero, imagen principal e identidad

### A — Visible

- Hero inmediatamente debajo del header, a ancho completo.
- Altura aproximada de **955–960 px** sobre la mesa desktop; relación visual cercana a 2:1 en ancho/alto.
- Fotografía de adolescentes en aula realizando un gesto grupal con las manos.
- Recorte horizontal `cover`; las personas ocupan ambos extremos y centro.
- Capa negra intensa sobre toda la imagen para asegurar lectura.
- Lockup de identidad centrado: sigla DDNA multicolor en marco naranja, nombre completo manuscrito blanco y pastilla azul “Provincia de Córdoba”.
- El lockup ocupa aproximadamente la mitad del ancho del hero.
- No hay título HTML adicional, bajada, botón ni indicadores de slider.

### B — Responsive inferido

- Usar la imagen como medio responsive con `object-fit: cover` y un punto focal administrable/definido para conservar rostros y manos.
- Mantener el lockup centrado y con ancho fluido; no recomponer sus letras con fuentes web.
- Desktop grande: altura máxima controlada, evitando crecer indefinidamente.
- Notebook: conservar relación visual y reducir ligeramente altura.
- Tablet: recorte más vertical; revisar foco para no cortar rostros.
- Mobile: altura aproximada de 55–70vh, con recorte específico si existe asset móvil. El lockup debe ocupar aproximadamente 80–90% del ancho útil.
- Proveer texto alternativo adecuado para la fotografía; el logo enlazado debe tener nombre accesible.

### C — No determinado

- Archivo fuente y derechos de la fotografía.
- Punto focal exacto y existencia de variantes mobile.
- Opacidad exacta del overlay.
- Si el lockup es el logo oficial único o una variante horizontal.
- Si el hero puede cambiar desde administración o es institucional permanente.

### Fuente WordPress

- Recomendación: imagen destacada de la página configurada como Inicio para el fondo del hero.
- `custom-logo` para el lockup oficial si ese es el logo principal aprobado. En este diseño tendría sentido renderizarlo dentro del hero, no necesariamente en el header.
- Si footer y hero requieren variantes distintas, `ddna-core` necesitará posteriormente ajustes institucionales con selectores de Medios; no duplicar archivos dentro del template.
- El overlay y la composición son responsabilidad del tema.

## 6. Accesos rápidos

### A — Visible

Seis accesos en una sola fila:

1. Asesoramiento y Consultas.
2. Talleres Interactivos.
3. Datos sobre la Niñez y la Adolescencia.
4. Recursos Didácticos.
5. Subsedes.
6. Mapeo de Instituciones que trabajan con NNyA.

Cada acceso es una card blanca vertical con:

- contorno negro;
- esquinas redondeadas;
- icono lineal naranja centrado;
- etiqueta negra, centrada, semibold y en varias líneas;
- proporciones aproximadamente cuadradas/verticales;
- sin sombra ni descripción.

Medición aproximada desktop: seis cards de **245–265 px** de ancho, **275–300 px** de alto, separadas por **30–40 px**.

### B — Responsive inferido

- Desktop grande: 6 columnas.
- Notebook: 3 columnas × 2 filas si seis cards ya no conservan tamaño legible.
- Tablet: 3 × 2 o 2 × 3 según ancho real y longitud de etiquetas.
- Mobile: 2 columnas; una columna sólo en anchos muy estrechos.
- Toda la card debe ser el enlace, con foco visible y área táctil completa.
- Los iconos deben ser SVG con `currentColor`, decorativos cuando el texto ya expresa el destino.

### C — No determinado

- Destinos exactos.
- Assets vectoriales originales y familia iconográfica.
- Estados hover/focus/pressed.
- Si el orden puede cambiar.

### Fuente WordPress

Ubicación de menú administrable `quick_access`. Cada ítem necesita título, URL, orden e icono. El icono puede resolverse posteriormente mediante un campo de Media/SVG controlado en el ítem de menú o una colección administrable equivalente. No usar seis URLs hardcodeadas.

## 7. Patrón común de secciones enmarcadas

### A — Visible

Programas, Campañas y Novedades comparten un contenedor singular:

- gran marco negro redondeado sobre el mismo fondo gris cálido del canvas;
- título muy grande, negro y alineado a la izquierda;
- el título ocupa una pestaña superior integrada al contorno;
- el borde desciende a la derecha del título mediante una curva y continúa horizontalmente hasta el extremo derecho;
- amplio padding interior;
- flechas negras fuera del contenido, pero dentro de los laterales del marco.

Este “marco con pestaña” es un rasgo central de identidad y debe construirse como componente reutilizable, no dibujarse distinto en cada sección.

### B — Responsive inferido

- Mantener el título y el gesto de pestaña mientras haya espacio.
- Reducir progresivamente título, radio, indentación y padding.
- En mobile, preservar la lectura del marco sin forzar una pestaña demasiado ancha; se admite una simplificación geométrica del mismo gesto, no un estilo nuevo.
- El título debe ser un encabezado HTML, no texto dentro de SVG.

### C — No determinado

- Geometría vectorial exacta del notch/pestaña.
- Si debe resolverse con CSS, máscara, SVG decorativo o pseudo-elementos.
- Grosor exacto del trazo.

## 8. Programas

### A — Visible

- Título “Programas”.
- Tres cards visibles en una fila.
- Flechas triangulares negras izquierda/derecha.
- Cards fotográficas verticales, de ancho aproximado **510–525 px** y alto **690–710 px**.
- Esquinas muy redondeadas.
- Contorno naranja fino inset siguiendo el radio.
- Una segunda silueta gris/desfasada aparece detrás o al costado.
- Texto superpuesto cerca de la parte inferior, en una placa blanca irregular y escalonada.
- Título negro muy pesado y descripción negra más liviana.
- Ejemplos visibles: Entre Pantallas, Detrás del Humo y Va con Vos.
- No hay botones “ver programa”; la card parece ser el enlace completo.

### B — Responsive inferido

- Desktop grande: 3 cards visibles.
- Notebook: 3 cards si conservan legibilidad; pasar a 2 cuando el texto o la imagen queden comprimidos.
- Tablet: 2 cards visibles.
- Mobile: 1 card visible por avance.
- Usar imagen destacada con proporción consistente y `object-fit: cover`.
- Limitar el largo de título/bajada o diseñar la placa para crecer; no incrustar el texto dentro de la imagen.
- Carrusel operable con teclado, controles etiquetados y sin autoplay predeterminado.
- Si hay tres o menos programas y todos caben, los controles pueden deshabilitarse/ocultarse sin alterar el layout.

### C — No determinado

- Cantidad total, loop, autoplay, transición, swipe y paso del carrusel.
- Forma exacta de la placa blanca.
- Comportamiento con títulos mucho más largos.
- Si los tres programas están seleccionados manualmente o por fecha.

### Fuente WordPress

CPT `programa` de `ddna-core`:

- título;
- extracto breve;
- imagen destacada;
- estado;
- campo `_ddna_featured` para selección inicial;
- permalink a la ficha.

Orden recomendado: selección destacada explícita y luego orden editorial/fecha. Si se requiere orden manual estable, agregar posteriormente `menu_order` o un campo de prioridad en el plugin, no en el tema.

## 9. Campañas

### A — Visible

- Título “Campañas”.
- Flechas triangulares laterales.
- Composición de cuatro placas naranja en grilla 2 × 2.
- Dos columnas de ancho similar; separación central y vertical estrecha.
- Cada placa tiene radio medio, icono/identidad negra y texto negro/blanco.
- Las cuatro composiciones visibles son:
  - Hay Otra Forma;
  - Guías para la Prevención;
  - Guías para una Crianza Cuidada;
  - La vida es un viaje único, acompañalos a vivir sin adicciones.
- No hay imágenes fotográficas ni botones visibles.
- La estructura interna varía: icono a izquierda, títulos, bullets y, en un caso, gran titular centrado.

### B — Responsive inferido

- Desktop y notebook amplio: mantener 2 × 2.
- Tablet: una columna de placas o slides con una composición por fila, según cantidad de texto.
- Mobile: una campaña por slide/avance; evitar escalar una placa desktop completa hasta volver ilegibles sus listas.
- Títulos y bullets deben ser HTML administrable, no una imagen plana, siempre que los assets originales lo permitan.
- El carrusel debe compartir comportamiento y controles con Programas y Novedades.

### C — No determinado

- Si las cuatro placas son cuatro campañas independientes o una única diapositiva editorial con cuatro enlaces.
- Número total de campañas y agrupación por slide.
- Qué partes son logos oficiales que deben mantenerse como imagen.
- Destinos, prioridad, loop y transición.
- Reglas para campañas sin icono o con foto.

### Fuente WordPress

CPT `campana`:

- título y extracto/contenido;
- imagen destacada o icono/identidad aprobada;
- estado y período;
- CTA/URL;
- `_ddna_featured`.

Las listas (“Navegación Segura”, “Juegos en Línea”, etc.) pueden ser contenido editorial o enlaces a `documento`/`recurso`. No deben codificarse en el template. La implementación debe resolver si la Home lista campañas individuales o una selección editorial agrupada.

## 10. Novedades

### A — Visible

- Título “Novedades”.
- Cuatro cards visibles en una fila.
- Flechas triangulares izquierda/derecha.
- Cards con contorno negro, esquinas redondeadas y fondo del mismo gris cálido.
- Imagen superior horizontal, con márgenes internos.
- Título negro semibold, extracto gris/negro liviano y botón naranja “LEER MÁS” alineado hacia el extremo inferior derecho.
- Las cards mantienen igual altura aunque el contenido tenga longitudes distintas.
- No se muestran fecha, categoría ni autor.

Medición aproximada desktop: card de **395–405 px** de ancho; gap visual cercano a **8–16 px**; imagen con relación cercana a 3:2/16:10.

### B — Responsive inferido

- Desktop grande: 4 cards visibles.
- Notebook: 3 cards.
- Tablet: 2 cards.
- Mobile: 1 card.
- Igualar altura con grid/flex y empujar el CTA al fondo.
- Aplicar line clamp sólo si se acuerda; el PDF sugiere truncado con puntos suspensivos en extractos.
- Imagen destacada obligatoria o fallback institucional definido.
- Carrusel manual, con swipe táctil y teclado; no autoplay.
- “Leer más” debe conservar un nombre accesible que incluya el título de la noticia aunque visualmente mantenga la etiqueta breve.

### C — No determinado

- Cantidad total de posts cargados.
- Criterio exacto: últimos publicados, categoría Novedades o selección destacada.
- Duración/animación/loop del carrusel.
- Regla exacta de recorte de títulos y extractos.

### Fuente WordPress

Entradas nativas (`post`), preferentemente categoría `novedades`:

- imagen destacada;
- título;
- extracto manual, con fallback controlado;
- permalink;
- fecha disponible aunque el diseño no la muestre.

Consulta recomendada: publicaciones `publish` de la categoría Novedades, orden descendente por fecha. Una futura opción editorial puede permitir fijar/destacar posts sin duplicarlos.

## 11. Carruseles y controles

### A — Visible

- Programas, Campañas y Novedades presentan un triángulo negro a cada lado.
- No se observan dots, contador, barra de progreso, labels ni botones con fondo.
- Los controles están visualmente centrados en el eje vertical del contenido desplazable, no del título.

### B — Responsive inferido

- Implementar un único componente de carrusel reutilizable y progresivamente mejorado.
- El contenido debe permanecer accesible como lista/grid si JavaScript falla.
- Botones reales (`button`), nombres “Anterior”/“Siguiente”, foco visible y tamaño táctil mínimo 44 px.
- No autoplay: reduce carga cognitiva y evita inventar una conducta ausente.
- Soportar swipe/scroll-snap en touch y flechas en desktop.
- Deshabilitar controles al inicio/final si no hay loop.
- Respetar `prefers-reduced-motion`.
- Mantener DOM semántico y lectura lineal independiente de la posición visual.

### C — No determinado

- Si existe loop infinito.
- Número de elementos por avance.
- Easing/duración.
- Si las flechas son SVG oficiales o triángulos CSS.
- Si habrá anuncios de cambio para lectores de pantalla.

No añadir dots hasta que diseño los apruebe.

## 12. Footer

### A — Visible

- Franja naranja `#FF8C00` a ancho completo, de aproximadamente **327 px** de alto en la mesa original.
- Columna izquierda: logo DDNA vertical negro.
- Centro-izquierda: dos botones/píldoras delineadas en negro con icono WhatsApp:
  - Línea Asistencia — 351 4020503;
  - Línea Adolescencia — 351 2398953.
- Derecha: “Seguinos en”, fila de iconos de redes y apps, domicilio, correo, teléfono y marcas del Gobierno de Córdoba.
- Iconos visibles: Facebook, Instagram, X, YouTube y Google Play; el PDF también parece incluir una marca/icono adicional junto a los logos provinciales.
- Todo el texto e iconografía estructural es negro sobre naranja.

### B — Responsive inferido

- Desktop: conservar tres zonas principales y alineación horizontal.
- Notebook: reducir gaps manteniendo logo, líneas y contacto claramente separados.
- Tablet: grid de dos columnas; contacto/redes puede ocupar una fila completa.
- Mobile: una columna, orden recomendado según criticidad: líneas de atención, contacto, redes y marcas. El logo puede encabezar o cerrar según validación institucional.
- Teléfonos, WhatsApp, correo y mapa/dirección deben ser enlaces accionables.
- SVG de redes con labels accesibles; no depender sólo del icono.

### C — No determinado

- Vigencia de teléfonos, correo y domicilio.
- URLs sociales y de Google Play.
- Archivos oficiales de DDNA/Gobierno y reglas de tamaño/área de protección.
- Si las líneas funcionan 24/7.
- Orden mobile aprobado.

### Fuente WordPress

- Menú `footer` para enlaces institucionales si se incorporan.
- Menú/configuración administrable para redes.
- Opciones institucionales en `ddna-core` para teléfonos, WhatsApp, correo, domicilio y logos secundarios; no codificar estos datos en `footer.php`.
- `custom-logo` puede alimentar el logo DDNA si la misma variante sirve para hero y footer. Si no, usar selectores de Medios separados.

## 13. Iconografía e imágenes

### A — Visible

- Estilo lineal, trazo naranja para accesos rápidos.
- Estilo lineal negro para campañas y WhatsApp.
- Flechas como triángulos negros simples.
- Logos/identidades especiales: DDNA, Hay Otra Forma y Gobierno de Córdoba.
- Fotografías con recorte editorial en hero, Programas y Novedades.

### B — Responsive inferido

- Usar SVG optimizado para iconos y marcas cuando exista original vectorial.
- Iconos decorativos con `aria-hidden="true"`; enlaces sociales con nombre accesible.
- Imágenes WordPress con `srcset`, `sizes`, dimensiones explícitas y carga diferida fuera del hero.
- El hero debe priorizar LCP y no usar lazy-load.
- Definir puntos focales y relaciones de aspecto por componente.

### C — No determinado

- Set de iconos original, licencias y archivos fuente.
- Nombres alternativos aprobados.
- Política de fallback cuando falta imagen.

## 14. Espaciado y composición

### A — Visible

- El diseño usa una gran cantidad de aire vertical.
- Accesos rápidos quedan separados del hero y de Programas por espacios amplios.
- Las secciones enmarcadas están separadas entre sí por aproximadamente 80–100 px sobre la mesa.
- Padding exterior de cada marco cercano a 55–90 px.
- El título invade/define el borde superior y queda separado del contenido.
- Campañas usa gaps menores que Programas; Novedades compacta cuatro cards.

### B — Tokens de espaciado inferidos

```css
--space-1: 0.25rem;
--space-2: 0.5rem;
--space-3: 0.75rem;
--space-4: 1rem;
--space-5: 1.5rem;
--space-6: 2rem;
--space-7: 3rem;
--space-8: 4rem;
--space-9: clamp(4rem, 7vw, 8rem);
```

- Mantener ritmo vertical, pero reducirlo proporcionalmente en mobile.
- No escalar toda la mesa como una imagen: grids, tipografía y padding deben refluír.
- Evitar anchos de línea excesivos en extractos y footer.

### C — No determinado

- Baseline grid y valores exactos del archivo Illustrator.
- Si todas las secciones usan exactamente el mismo padding.

## 15. Breakpoints propuestos

Los breakpoints responden a quiebres de contenido, no a dispositivos específicos:

| Rango | Estrategia |
|---|---|
| `>= 1600px` — desktop grande | Contenedor amplio limitado; menú completo; accesos 6; Programas 3; Campañas 2×2; Novedades 4 |
| `1200–1599px` — desktop/notebook grande | Misma composición con escala fluida; comprobar que menú y tres Programas conserven legibilidad |
| `1024–1199px` — notebook | Colapsar navegación si no cabe; accesos 3×2; Programas 2–3; Campañas 2 columnas; Novedades 3 |
| `768–1023px` — tablet | Menú móvil; accesos 3×2 o 2×3; Programas 2; Campañas 1 columna; Novedades 2; footer 2 columnas |
| `< 768px` — mobile | Menú móvil; hero recortado; accesos 2 columnas; un Programa/Campaña/Novedad por avance; footer apilado |
| `< 360px` — mobile estrecho | Accesos pueden pasar a 1 columna si el texto no cabe; gutters mínimos de 16 px |

Los números de items visibles son **B — inferidos**. Deben probarse con contenido real, zoom al 200%, traducciones y títulos largos.

## 16. Matriz responsive por componente

| Componente | Desktop grande | Notebook | Tablet | Mobile |
|---|---|---|---|---|
| Header | 8 enlaces en línea | línea o colapso por ajuste | menú colapsado | menú colapsado |
| Hero | ancho completo, lockup ~50% | lockup ~55–60% | lockup ~70% | lockup ~85%, crop vertical |
| Accesos | 6 columnas | 3×2 | 3×2 o 2×3 | 2 columnas / 1 muy estrecho |
| Programas | 3 visibles | 2–3 | 2 | 1 |
| Campañas | grilla 2×2 | grilla 2×2 | 1 columna | 1 por avance |
| Novedades | 4 visibles | 3 | 2 | 1 |
| Footer | 3 zonas horizontales | 2–3 zonas | 2 columnas | 1 columna |

## 17. Fuentes de contenido WordPress

| Área visual | Fuente administrable | Campos mínimos |
|---|---|---|
| Navegación | Menú `primary` | título, URL, padre, orden |
| Hero | Página definida como Inicio | imagen destacada; logo desde `custom-logo`; futuro punto focal/overlay si se requiere |
| Accesos rápidos | Menú `quick_access` propuesto | título, URL, orden, icono administrable |
| Programas | CPT `programa` | título, extracto, imagen destacada, estado, destacado, enlace |
| Campañas | CPT `campana` | título, contenido breve, identidad/icono, estado, fechas, CTA, destacado |
| Novedades | `post` + categoría `novedades` | título, extracto, imagen destacada, fecha, permalink |
| Footer institucional | Opciones de `ddna-core` a diseñar | líneas, WhatsApp, dirección, correo, teléfono, redes, logos |
| Menú de footer | Menú `footer` | enlaces y orden |

Los componentes deben mostrar contenido publicado y vigente, omitir elementos sin información crítica y ofrecer fallbacks controlados. El tema no debe contener nombres de programas, campañas, posts, teléfonos ni URLs concretas.

## 18. Requisitos funcionales y de accesibilidad derivados

Aunque no son visibles en una lámina estática, son necesarios para una aplicación pública:

- HTML semántico con un único `h1` y jerarquía ordenada.
- Skip link y landmarks (`header`, `nav`, `main`, `section`, `footer`).
- Contraste WCAG AA; verificar especialmente blanco sobre naranja y textos livianos.
- Foco visible que no dependa sólo de color.
- Operación completa por teclado de navegación y carruseles.
- Objetivos táctiles mínimos de 44 × 44 px.
- Zoom al 200% sin pérdida ni solapamiento.
- `prefers-reduced-motion` y ausencia de autoplay.
- Alt text editorial para fotos; logos e iconos tratados según función.
- No convertir textos de campañas, contacto o cards en imágenes.
- Carga responsive de imágenes y prevención de layout shift.

## 19. Información que debe solicitarse antes de implementar

1. Archivo Illustrator o export de medidas/especificaciones.
2. Logos oficiales DDNA y Gobierno de Córdoba en SVG, con variantes y manual de marca.
3. Set original de iconos de accesos/campañas/redes.
4. Fuentes web y licencias de Forma DJR Banner y Fieldwork Geo; confirmación de pesos Montserrat.
5. Fotografía original del hero, derechos, alt text y eventual recorte mobile.
6. Confirmación de destinos de los seis accesos rápidos.
7. Reglas editoriales de selección y orden para Programas y Campañas.
8. Definición de agrupación del carrusel de Campañas.
9. Comportamiento de carruseles: loop, paso y límites; se recomienda sin autoplay.
10. Estados hover/focus/active aprobados.
11. Vigencia de teléfonos, correo, domicilio y redes.
12. Definición de qué variante de logo alimenta `custom-logo`.
13. Confirmación de fallback para imágenes faltantes.
14. Validación de la geometría responsive del marco con pestaña.

## 20. Criterio de fidelidad para la futura implementación

La implementación deberá preservar:

- la secuencia de bloques;
- el fondo gris cálido, acento naranja y contornos negros;
- el hero fotográfico oscuro con lockup centrado;
- las seis cards de acceso;
- el marco con pestaña de las tres secciones;
- la personalidad diferenciada de cards de Programas, Campañas y Novedades;
- el footer naranja con líneas de asistencia e información institucional.

La fidelidad no significa fijar una mesa de 1900 px ni publicar texto dentro de imágenes. El resultado debe refluír, mantener jerarquía y conservar acceso editorial desde WordPress.
