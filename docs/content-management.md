# Administración de contenidos de la Home DDNA

## Criterio general

La Home consume contenido publicado en WordPress. El personal administrador no necesita editar PHP, CSS ni JavaScript. La presentación permanece en `ddna-theme`; los tipos de contenido, campos y opciones institucionales permanecen en `ddna-core` para no depender del tema activo.

No se instalaron ACF, Elementor ni otros plugins de campos. La implementación utiliza menús, entradas, imagen destacada, extracto, orden nativo, logo personalizado y páginas de opciones construidas con las APIs de WordPress.

## Header y navegación

Ruta: **Apariencia → Menús** (o **Apariencia → Editor → Navegación**, si WordPress muestra esa interfaz).

- La ubicación **Menú principal** controla el header.
- Se pueden agregar, quitar y reordenar páginas, contenidos o enlaces personalizados.
- Arrastrar un elemento debajo y hacia la derecha de otro crea un submenú.
- El tema admite hasta tres niveles y genera botones accesibles para abrir submenús en desktop y mobile.
- Conviene revisar el resultado responsive al agregar muchos elementos de primer nivel.

## Identidad y Hero

Rutas:

- **Apariencia → Personalizar → Identidad del sitio**: logo institucional mediante `custom-logo`.
- **Páginas → página asignada como portada → Imagen destacada**: fotografía del Hero.

El mismo logo configurado se utiliza en la identidad del Hero y el footer. Si no hay logo o imagen destacada, el tema conserva recursos visuales de respaldo. El texto institucional accesible del Hero forma parte del tema porque representa el nombre legal estable del organismo; no está incrustado dentro de la fotografía.

## Accesos rápidos

Ruta: **Apariencia → Menús**, ubicación **Accesos rápidos de la portada**.

Cada tarjeta corresponde a un elemento del menú y permite administrar:

- título visible;
- URL, página o contenido de destino;
- orden mediante arrastrar y soltar;
- icono mediante el selector **Icono de acceso rápido** incluido en cada elemento.

Los seis iconos previstos son Asesoramiento, Talleres, Datos, Recursos, Subsedes y Mapeo. Si no se selecciona uno, se utiliza el icono genérico. Se conserva compatibilidad con las antiguas clases CSS `icon-*`, pero ya no es necesario editarlas.

Mantener seis elementos preserva la composición aprobada; WordPress permite cambiar la cantidad, aunque debe revisarse visualmente si se altera.

## Programas

Ruta: **Programas**.

Cada Programa administra:

- **Título**: título nativo;
- **Imagen**: imagen destacada;
- **Descripción**: extracto;
- **Contenido completo**: editor de WordPress;
- **Enlace**: campo **Sitio o micrositio**; si queda vacío, enlaza al permalink del Programa;
- **Aparición en portada**: marcar **Destacar en listados**;
- **Orden**: campo **Orden en la portada** dentro de Información institucional; los números menores aparecen primero y los empates se resuelven mostrando primero la publicación más reciente.

La Home sólo consulta Programas publicados y marcados para destacar. La cantidad máxima cargada se administra en **Apariencia → Portada DDNA**.

## Campañas

Ruta: **Campañas**.

Cada Campaña administra:

- título nativo;
- información breve mediante extracto;
- contenido completo mediante el editor;
- imagen destacada;
- URL mediante **URL de campaña o acción**, con fallback al permalink;
- estado, fechas y texto de llamada a la acción;
- aparición en portada mediante **Destacar en listados**;
- orden mediante **Orden en la portada**, con fecha descendente como desempate.

La imagen destacada queda disponible para archivos, fichas y futuras variantes. La tarjeta actual de la Home no la muestra porque el diseño aprobado utiliza el símbolo lineal y fondo naranja; incorporarla cambiaría el diseño.

La cantidad máxima cargada se administra en **Apariencia → Portada DDNA**.

## Novedades

Ruta: **Entradas**.

Las Novedades utilizan entradas nativas de WordPress. La Home toma automáticamente las entradas publicadas de la categoría con slug `novedades`; si esa categoría no existe, utiliza las entradas publicadas más recientes.

Cada tarjeta utiliza:

- imagen destacada;
- título;
- extracto;
- permalink nativo;
- fecha de publicación como criterio cronológico.

La fecha se conserva en WordPress y determina el orden, pero no se imprime en la tarjeta porque no aparece en la propuesta visual aprobada. La cantidad máxima cargada se administra en **Apariencia → Portada DDNA**. La cantidad simultáneamente visible cambia responsivamente: cuatro en desktop, tres o dos en anchos intermedios y una tarjeta principal en mobile.

## Footer y datos institucionales

Ruta: **Ajustes → Datos institucionales**.

Esta pantalla es la fuente única para:

- nombre y teléfono de la línea de asistencia;
- nombre y teléfono de la línea de adolescencia;
- dirección;
- teléfono general;
- correo institucional;
- Facebook, Instagram, X, YouTube y Google Play.

El footer genera automáticamente enlaces `tel:` y `mailto:` y omite redes o datos vacíos. No deben duplicarse estos valores en templates.

## Configuración de la portada

Ruta: **Apariencia → Portada DDNA**.

Permite definir, entre 1 y 24, cuántos Programas, Campañas y Novedades carga cada carrusel. Esta cifra no fuerza cuántas tarjetas caben en una fila: esa presentación continúa adaptándose al dispositivo.

Valores predeterminados:

- Programas: 9;
- Campañas: 12;
- Novedades: 12.

## Flujo editorial recomendado

1. Completar título, extracto, imagen y enlace cuando corresponda.
2. Publicar o programar el contenido.
3. En Programas y Campañas, activar **Destacar en listados** para incluirlo en la Home.
4. Ajustar **Orden en la portada** si se necesita una posición editorial concreta.
5. En Novedades, asignar la categoría **Novedades**.
6. Verificar la portada en desktop y mobile antes de dar por terminado el cambio.

## Alcance y permisos

Los usuarios necesitan permisos para editar el tipo de contenido correspondiente. Las opciones de portada requieren `edit_theme_options`; los datos institucionales requieren `manage_options`. No se modificaron contenidos históricos, URLs productivas, páginas internas ni el modelo de migración.
