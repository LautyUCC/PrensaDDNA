# Ajustes adicionales — revisión local del 7/10/2026

URL: http://localhost:8080/ . Rama: `feature/home-footer-comunicados-octubre`.
HEAD conservado: `2a8409a840b8b86475731c2d73646751df68da8f`.
Sin commit, push, merge ni deploy. Trabajo anterior y recursos del usuario preservados.

## Resultado

- Contacto: teléfonos destacados y jerarquía responsive. Destinos tel/mail y los accesos existentes conservados.
- Convenios: ocultada exclusivamente la card de **Secretaría de Fortalecimiento Vecinal, Cultura y Deportes**, logo `convenio-92.png`, última de las 13 originales. Se conservan los 13 registros y todos los archivos; se muestran 12, terminando en UNICEF.
- Necesito ayuda: cuerpo, resúmenes y títulos mayores; negritas y botón redondeado de Adolescencia conservados.
- Diplomatura IA: tipografía mayor en el acceso desplegable del Home y en su ficha local. Destino UCASAL y contenido conservados. El estilo interior está acotado al slug de esa Diplomatura.
- Observatorio: acceso sin subrayado; `/observatorio/` y foco visible de 3px conservados. Dashboard sin modificaciones.
- Territorio: “Conocé los 20 municipios y sus cohortes”, mismo destino.
- Comunicados: acceso único a la página existente `/comunicados/`, título “Comunicados y pronunciamientos”. Ocho entradas y sus enlaces intactos. `/defensoria-en-los-medios/` conserva su archivo histórico y título original; no se borró ni reorganizó contenido histórico.
- Volver arriba: negro con flecha blanca; funciona y conserva posición/foco responsive.
- Volver al menú: casita en Defensoría, Informes Anuales (plantilla propia), Normativa, Convenios y Contacto; además alias histórico Normativas. Usa `home_url('/#menu-principal-home')`; el ancla recibe foco al volver.
- Video: loop nativo silencioso y playsinline, sin botón de pausa ni listeners para controles inexistentes. Preferencia de movimiento reducido pausa el video; se respetan bloqueos de autoplay.

## Mapa pendiente

El candidato `RECURSOS GRÁFICOS - WEB DDNA 2026/Mapa Cordoba.png` es idéntico al activo `app/themes/ddna-theme/assets/images/territorio/mapa-cordoba.png`.
SHA256 de ambos: `1029fadd78eede4bda384e50f79bc16cb08c9008efbfbf4d11ae84dcedd135c9`.
No se reemplazó ni generó un mapa. Falta la ubicación inequívoca del nuevo mapa de Fede.

## Archivos de este lote

Dentro de `app/themes/ddna-theme/`:

- `assets/css/components/additional-adjustments.css` (nuevo): tamaños, Contacto, Observatorio y casita.
- `assets/css/components/back-to-top.css`: colores; componente nuevo del lote anterior.
- `assets/js/hero-video.js`: preferencias de movimiento, sin control visible.
- `inc/editorial-content.php`: presentación Convenios y acceso Comunicados.
- `inc/enqueue.php`: nueva hoja CSS después de los estilos editoriales.
- `page.php`, `single.php`: clases acotadas de Contacto/Ayuda/Diplomatura.
- `page-informes-anuales.php`, `template-parts/content/content-page.php`: acceso de regreso.
- `template-parts/components/return-home-menu.php` (nuevo).
- `template-parts/home/institutional-navigation.php`: ancla estable.
- `template-parts/home/panel-territorio.php`: Conocé.
- `template-parts/sections/hero.php`: video sin botón.
- `template-parts/sections/quick-access.php`: clase del acceso Observatorio.

Otros: `scripts/apply-additional-adjustments-octubre-2026.php` (nuevo) y este reporte. Se retiraron las reglas CSS del control de pausa agregado en la revisión anterior; `hero.css` queda igual al HEAD actual. Los demás cambios existentes del lote anterior y recursos ajenos permanecen pendientes, sin stage.

## Contenido local fuera de Git y actualización futura

Se modificó únicamente el título editorial de la página local ID 70; slug `comunicados` y contenido `[ddna_statements]` intactos. WordPress puede actualizar metadatos/revisiones asociados. Se añadió la opción de recibo `ddna_additional_adjustments_octubre_receipt` con el valor anterior para rollback selectivo. La carga de ocho comunicados del lote anterior continúa en la opción local `ddna_final_statements`; su manifiesto y script existentes se preservan.

El script nuevo no exporta ni reemplaza la base: resuelve la página por slug, exige que exista publicada y que su título sea el auditado. Por defecto dry-run; modos `DDNA_ADDITIONAL_MODE=apply` o `rollback`. En producción exige aprobación específica en `DDNA_ADDITIONAL_APPROVAL` y origen exacto en `DDNA_ADDITIONAL_TARGET_ORIGIN`; todavía no se ejecutó allí. Rollback restaura solo el título si el recibo y título actual coinciden. No toca comunicados, convenios, uploads ni historial de medios.

Ejemplos SOLO para este entorno local:

```sh
docker compose --profile tools run --rm cli eval-file /var/www/html/scripts/apply-additional-adjustments-octubre-2026.php
docker compose --profile tools run --rm -e DDNA_ADDITIONAL_MODE=apply cli eval-file /var/www/html/scripts/apply-additional-adjustments-octubre-2026.php
docker compose --profile tools run --rm -e DDNA_ADDITIONAL_MODE=rollback cli eval-file /var/www/html/scripts/apply-additional-adjustments-octubre-2026.php
```

## Validaciones y evidencia

Evidencia fuera del repo: `/Users/lautyvallino/Projects/DDNA/wordpress-additional-review-20261007/`.
Capturas desktop/mobile de Contacto, Ayuda, Diplomatura, Territorio, Comunicados, Convenios y Home. Capturas de 1440px y 390px; Contacto probado además a 320px. Sin overflow en las vistas verificadas; 12 logos cargan correctamente. Los cinco accesos interiores se verificaron en navegador (house-checks.json). Click real de regreso llega al ancla y le da foco. Observatorio sin subrayado y outline de teclado 3px. Volver arriba vuelve a scrollY=0.

Video activo y en loop durante revisión de paneles; duración 24.448s. Sin control visible, con muted y atributo playsinline. Prueba aislada de preferencia reducida y sus cambios, ausencia de video y rechazo de autoplay: OK. La preferencia reducida se verificó en lógica aislada, no como emulación de sistema en esta segunda ronda.

Sintaxis de los 53 PHP del tema y del script nuevo: OK. Todos los JS del tema: OK. `git diff --check`: OK. Idempotencia local sin escrituras, rollback y reaplicación del título: OK. Comparación de opciones confirma 13 convenios y ocho comunicados idénticos a las evidencias anteriores. Sin errores/warnings propios del sitio en consola observada; avisos de MetaMask identificados por origen chrome-extension (console.json). No se promete la disponibilidad de páginas externas ni del Dashboard en este entorno local.

**Limitación de accesibilidad:** al retirar el control solicitado, el movimiento automático prolongado no ofrece un mecanismo accesible de pausa para todas las personas. Respetar movimiento reducido no sustituye ese control. No se agregó otro botón.

VPS, MariaDB productiva, uploads productivos, Dashboard, Supabase, Caddy, DNS, HTTPS y CI/CD no modificados. Publicación pendiente de aprobación explícita.
