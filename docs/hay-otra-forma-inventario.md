# Inventario — Hay Otra Forma

Auditoría local realizada el 24 de septiembre de 2026. Se revisaron recursivamente `H:\hayotraforma` (16.613 archivos, 609,93 MB) y `H:\2025-01\Hay Otra Forma` (11.860 archivos, 613,25 MB), sin escribir en ninguno de los dos orígenes.

`H:\hayotraforma` contiene un WordPress local, sus uploads, respaldos y `rebuild-static/`, que incluye las tres vistas HTML y una selección editorial documentada. `H:\2025-01\Hay Otra Forma` contiene una copia de WordPress y un SQL; sus 2.332 uploads coinciden en ruta y tamaño con los de la primera ubicación. No se detectaron PDFs o documentos de campaña; los MP4 hallados pertenecen al plugin de video, no a contenido editorial de Hay Otra Forma.

| Recurso | Carpeta de origen | Tipo | Campaña correspondiente | Uso previsto |
| --- | --- | --- | --- | --- |
| `logo-hay-otra-forma-01.png` | `H:\hayotraforma\rebuild-static\assets\img` y uploads `2022/04` en ambos orígenes | PNG | Común | Logo oficial en ambas páginas y tarjetas |
| `Web-Hay-Otra-Forma-Edicion*.png` | uploads `2023/05` en ambos orígenes | PNG | Común | Placas de elección; referencia, no duplicadas en páginas |
| `flia-03.png`, `flia-02.png` | uploads `2022/04` en ambos orígenes | PNG | Maltrato | Hero e identidad visual |
| `tip-01.png`, `tip-02.png`, `tip-05.png`, `tip-06.png` | uploads `2022/04` en ambos orígenes | PNG | Maltrato | Recomendaciones de crianza |
| `hay-otra-forma.jpg`, `como-prevenir.jpg`, `una-infancia.jpg`, `como-detectamos.jpg` | uploads `2023/03` en ambos orígenes | JPG | Maltrato | Miniaturas de videos |
| `img-que-es-4.png`, `guia-bullying.png`, `hay-bullying-cuando.png`, `1-2-3-4.png` | uploads `2022/06` en ambos orígenes | PNG | Bullying | Hero y bloque informativo |
| `bullying-en-numeros.png`, `roles.png`, `img-acosado.png`, `agresor.png`, `img-espectador.png` | uploads `2022/06` en ambos orígenes | PNG | Bullying | Estadísticas y roles |
| `H1.png`, `H2.png`, `H3.png` | uploads `2022/06` en ambos orígenes | PNG | Bullying | Descargas para difusión |
| `campana.jpg`, `que-hacemos.jpg`, `adultos.jpg`, `consecuencias.jpg`, `factores.jpg` | uploads `2023/03` en ambos orígenes | JPG | Bullying | Miniaturas de videos |
| Variantes `frase-*`, `*-hover`, QR, tamaños intermedios y assets de plugins | Ambos orígenes | PNG/JPG/CSS/JS | Destino no claro | No importados: son duplicados, variantes de hover o dependencias antiguas |

Se copiaron solo los recursos de uso previsto a `app/themes/ddna-theme/assets/images/hay-otra-forma/{shared,maltrato,bullying}/`. El hash SHA-256 del logo seleccionado coincide entre `rebuild-static`, los uploads de `H:\hayotraforma` y los de `H:\2025-01`.
