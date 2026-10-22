# Correcciones de Prensa — revisión local 22/10/2026

Solo localhost. Sin commit, push, PR, merge, VPS ni cambios productivos. Docker existente permanece activo en http://localhost:8080.

## Cambios

- Normativas: eliminado únicamente el recurso de trabajo “NORMATIVAS - VAN NORMATIVAS” y su enlace. El recurso general restante ocupa la fila completa, sin huecos. H1 y acceso institucional: “Normativas”. Los otros 22 enlaces y las categorías están intactos.
- Header: header.php y navigation/primary.php reutilizan siempre el dropdown aprobado. Antes la condición institucional enviaba Comunicados, singles, páginas genéricas, búsqueda, archivos y 404 a la navegación horizontal. Ahora todos los templates públicos comparten logo y MENÚ. single.php cubre todas las novedades actuales y futuras.
- Se conservaron inactivos el fallback de inc/template-tags.php, los filtros de inc/navigation.php y los estilos de compatibilidad: ningún template público invoca la rama horizontal. wp-admin y Observatorio no se modificaron.
- Comunicados: H1 “Comunicados y Pronunciamientos”; text-transform pasa de uppercase a none, conservando familia, peso, tamaño y jerarquía. No se escribió su título ni cuerpo en DB.

## Territorio

Se reutiliza el mismo enlace, clase, target="_blank" y rel="noopener noreferrer". Se conservan cuatro destinos existentes y se agregan nueve búsquedas por dirección. Bell Ville y Capilla del Monte quedan sin enlace por instrucción expresa del usuario, hasta disponer de dirección. No se inventaron coordenadas ni se modificaron las direcciones.

| Subsede | Dirección actual | URL mapa | Estado |
|---|---|---|---|
| Córdoba Capital | Dámaso Larrañaga 94, Nueva Córdoba, Córdoba Capital. | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=D%C3%A1maso%20Larra%C3%B1aga%2094%2C%20Nueva%20C%C3%B3rdoba%2C%20C%C3%B3rdoba%20Capital.%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Colonia Caroya | José Alfredo Nanini 4195 | [Abrir mapa](https://maps.app.goo.gl/2R8wnViTg8zMsXXE8) | Conservado |
| Sierras Chicas — Salsipuedes | Sarmiento esquina 25 de Mayo, Bº El Pueblito, Salsipuedes | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Sarmiento%20esquina%2025%20de%20Mayo%2C%20B%C2%BA%20El%20Pueblito%2C%20Salsipuedes%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Cosquín | Catamarca 554 esq. Santa Fé | [Abrir mapa](https://maps.app.goo.gl/6npxL6ySjViyZu7z7) | Conservado |
| Capilla del Monte | Sin dirección disponible | — | Sin enlace, autorizado por usuario |
| Cruz del Eje | General Paz 168 | [Abrir mapa](https://maps.app.goo.gl/E9wVDLZa2dyqLDvn6) | Conservado |
| Río Tercero | Hilario Cuadros 433 | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Hilario%20Cuadros%20433%2C%20R%C3%ADo%20Tercero%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Laguna Larga | Juan Domingo Perón esquina Entre Rios - Barrio Arturo Illia | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Juan%20Domingo%20Per%C3%B3n%20esquina%20Entre%20Rios%20-%20Barrio%20Arturo%20Illia%2C%20Laguna%20Larga%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Las Varillas | España Nº 51 | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Espa%C3%B1a%20N%C2%BA%2051%2C%20Las%20Varillas%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| San Francisco | Av. Garibaldi esq. Suipacha | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Av.%20Garibaldi%20esq.%20Suipacha%2C%20San%20Francisco%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Villa Cura Brochero | Av. Cura Gaucho 52 | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Av.%20Cura%20Gaucho%2052%2C%20Villa%20Cura%20Brochero%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Río Cuarto | C. Caseros 1020 (Centro Cívico) | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=C.%20Caseros%201020%20%28Centro%20C%C3%ADvico%29%2C%20R%C3%ADo%20Cuarto%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |
| Bell Ville | Sin dirección disponible | — | Sin enlace, autorizado por usuario |
| Justiniano Posse | 9 de Julio y Belgrano | [Abrir mapa](https://maps.app.goo.gl/o78SrNxFpaE1Cw9v8) | Conservado |
| Laboulaye | España 186 | [Abrir mapa](https://www.google.com/maps/search/?api=1&query=Espa%C3%B1a%20186%2C%20Laboulaye%2C%20C%C3%B3rdoba%2C%20Argentina) | Agregado por dirección |

## Archivos modificados

- `app/themes/ddna-theme/assets/css/components/editorial-feedback.css`
- `app/themes/ddna-theme/assets/data/normativas.json`
- `app/themes/ddna-theme/header.php`
- `app/themes/ddna-theme/inc/editorial-content.php`
- `app/themes/ddna-theme/inc/territory-venues.php`
- `app/themes/ddna-theme/page-normativa.php`
- `app/themes/ddna-theme/template-parts/content/content-page.php`
- `app/themes/ddna-theme/template-parts/home/institutional-navigation.php`
- `app/themes/ddna-theme/template-parts/home/panel-territorio.php`
- `app/themes/ddna-theme/template-parts/navigation/primary.php`

## Git

- Branch: `feature/correcciones-prensa-octubre`.
- HEAD y origin/main inicial: `c409126cebf7e1319dfd279f7822d63dde40cbd2`.
- Índice vacío; ningún commit nuevo.
- Diez archivos de theme modificados por este lote, más este informe nuevo.
- Los 438 paths previamente modificados/eliminados/no rastreados se preservaron exactamente; hashes y ausencias coinciden con el inventario inicial.

## Validación

- PHP syntax: ocho archivos modificados OK; JS navigation.js syntax OK; git diff --check OK.
- Playwright/Chrome: 375, 430, 768, 1024 y 1440 px. Menú en Home, Normativas, Comunicados, dos novedades, Programas, Contacto, búsqueda y 404: apertura, seis opciones, Escape, clic exterior y límites de viewport OK.
- 248 respuestas públicas (230 novedades + 18 páginas): header actual presente, variante compacta vieja ausente.
- Acordeones de Normativas y Comunicados: todos los grupos abren y cierran con Enter/Espacio en cinco anchos.
- Territorio: 15 popups probados en cinco anchos; 13 mapas con URL/target/rel correctos; dos ausencias autorizadas. Sin overflow de enlace ni horizontal de página. Laguna Larga usa la dirección vigente.
- Capturas Normativas, Comunicados y Territorio revisadas; títulos y mapas completos, sin cortes.
- Datos de Normativas comparados con HEAD: igualdad exacta salvo recurso eliminado. URL eliminada ausente del HTML.
- test-october-news-presentation.php OK: orden histórico, búsquedas y paginación preservados.
- verify-october-news.php OK: ocho identidades, fechas, portadas, galerías, enlaces e imágenes inline.
- test-historical-content.py OK: 219 fuentes históricas íntegras.
- Snapshot final: posts, postmeta, terms, term_taxonomy, term_relationships y opciones institucionales idénticos; las 230 novedades conservan fecha, contenido y orden.
- Evidencias/scripts/capturas: `/Users/lautyvallino/Projects/DDNA/correcciones-prensa-local-20261022`.

## URLs de revisión

- Normativas: http://localhost:8080/normativa/
- Territorio: http://localhost:8080/#territorio
- Comunicados: http://localhost:8080/comunicados/
- Realizaremos la charla abierta “La mirada que sostiene: hablemos sobre la escucha activa, la validación emocional y el derecho a ser mirado y oído”: http://localhost:8080/realizaremos-la-charla-abierta-la-mirada-que-sostiene-hablemos-sobre-la-escucha-activa-la-validacion-emocional-y-el-derecho-a-ser-mirado-y-oido/
- Programa de Protección Digital: Entre Pantallas, más herramientas para cuidar a chicas y chicos en los entornos digitales: http://localhost:8080/programa-de-proteccion-digital-entre-pantallas-mas-herramientas-para-cuidar-a-chicas-y-chicos-en-los-entornos-digitales/
