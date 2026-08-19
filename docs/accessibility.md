# Accesibilidad de la Home DDNA

## Alcance

La auditoría cubre la Home, su header, navegación, Hero, accesos rápidos, carruseles de Programas, Campañas y Novedades, y footer. No incluye las plantillas interiores ni implica una certificación formal de conformidad. El objetivo técnico es aproximarse a WCAG 2.2 nivel AA con HTML semántico, operación por teclado, alternativas textuales y contraste suficiente.

## Semántica y encabezados

- Existe un `header` global, navegación principal etiquetada, un único `main` con destino para el enlace de salto y un `footer` global.
- Hero, Programas, Campañas y Novedades son `section`.
- Las tarjetas de contenidos administrables son `article`.
- La Home tiene un único `h1`: el nombre institucional, disponible para tecnologías de asistencia aunque la identidad visual se represente con el logo.
- Programas, Campañas y Novedades utilizan `h2`; cada contenido utiliza `h3`.
- Accesos rápidos es una navegación complementaria y no introduce un heading artificial.

## Imágenes e identidad

- La fotografía del Hero es informativa y utiliza el texto alternativo administrado en la Biblioteca de Medios.
- Las imágenes destacadas de Programas y Novedades utilizan el `alt` del adjunto.
- Los iconos de accesos, redes y controles son decorativos y se ocultan mediante `aria-hidden`; el enlace o botón conserva texto accesible.
- El logo dentro de enlaces utiliza `alt=""` para evitar repetir el nombre, mientras el enlace recibe el nombre accesible **Ir al inicio**.
- El texto del logo no sustituye el `h1` institucional.

Recomendación editorial: toda imagen destacada nueva debe recibir un `alt` que describa su información o contexto, sin comenzar con “imagen de”. Si es puramente decorativa debe utilizar texto alternativo vacío.

## Navegación y teclado

- El enlace **Saltar al contenido** aparece al recibir foco.
- Enlaces, botones del menú, submenús y carruseles pueden recorrerse con `Tab` y `Shift+Tab`.
- Los botones nativos se activan con `Enter` o barra espaciadora.
- El botón mobile expone `aria-expanded`, `aria-controls` y un nombre que cambia entre **Abrir menú principal** y **Cerrar menú principal**.
- Cada botón de submenú controla un `id` real y actualiza `aria-expanded`.
- `Escape` cierra primero el submenú activo y devuelve el foco a su botón; si no hay uno abierto, cierra el menú mobile y devuelve el foco al hamburger.
- Fuera de mobile, los submenús también se muestran mediante `:focus-within`.

No se implementa un patrón ARIA `menubar`: la navegación de un sitio web es una lista de enlaces, no un menú de aplicación.

## Carruseles

- Cada carrusel tiene nombre y `aria-roledescription="carrusel"` sobre un grupo.
- Los controles son elementos `button` reales agrupados y etiquetados específicamente como Programas, Campañas o Novedades.
- `aria-controls` apunta a la pista correspondiente.
- Los botones anterior/siguiente permiten operar sin gestos táctiles.
- La pista puede recibir foco y responde a flechas izquierda/derecha.
- Los botones imposibles de ejecutar se deshabilitan nativamente.
- El contenido sigue siendo una secuencia HTML legible si JavaScript no está disponible.

Los carruseles no cambian automáticamente, por lo que no necesitan pausa. No se anuncian todos los cambios como regiones `live`, evitando mensajes excesivos durante el desplazamiento.

## Foco y contraste

- Todo elemento interactivo utiliza `:focus-visible`.
- El indicador combina contorno negro de 3 px con halo blanco, visible sobre fondos claros, oscuros y naranja sin depender de un solo color.
- Los controles interactivos mantienen un objetivo mínimo aproximado de 44 × 44 px.
- Texto negro sobre fondo blanco, gris claro y naranja institucional supera holgadamente la relación AA para texto normal.
- Texto blanco se utiliza sobre el overlay oscuro del Hero. La capa oscura mantiene la legibilidad de la identidad frente a variaciones fotográficas.
- Los enlaces textuales se distinguen mediante subrayado, contexto o forma de botón; el color no es el único indicador.

## Movimiento

La consulta `prefers-reduced-motion: reduce`:

- desactiva el scroll suave global;
- reduce transiciones y animaciones no esenciales;
- hace que los botones del carrusel desplacen de forma inmediata en lugar de solicitar animación suave.

## Formularios

La Home actual no contiene formularios. Las reglas de labels, errores, `required` y `autocomplete` deberán auditarse cuando se incorpore un formulario real; no se agregó ARIA preventivo a elementos inexistentes.

## Criterios editoriales pendientes de operación

- Mantener nombres de menú y enlaces comprensibles fuera de contexto.
- Evitar enlaces genéricos repetidos salvo que su `aria-label` identifique el contenido, como ocurre con **Leer más sobre [título]**.
- Completar texto alternativo en Biblioteca de Medios.
- Conservar una sola categoría semántica de heading por tarjeta; no elegir headings por tamaño visual.
- Probar cambios editoriales importantes con teclado y, cuando sea posible, NVDA o VoiceOver.

## Limitaciones

- El contraste del texto que forme parte de un archivo de logo depende del recurso institucional aprobado.
- El contenido futuro puede introducir textos alternativos deficientes, nombres de enlace ambiguos o títulos excesivos; la estructura técnica no sustituye una revisión editorial.
- Antes de producción se recomienda una prueba manual con lector de pantalla y usuarios, además de validadores automáticos.
