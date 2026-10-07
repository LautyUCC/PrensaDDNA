> Actualización: la revisión [ajustes adicionales](ajustes-adicionales-octubre-2026.md) reemplaza el control visible de pausa, los colores del botón flotante y el nombre del acceso de prensa descritos aquí. Este documento conserva la evidencia del lote anterior.

# Home, footer y comunicados — revisión LOCAL del 7/10/2026

Rama `feature/home-footer-comunicados-octubre`, desde `2a8409a840b8b86475731c2d73646751df68da8f` (última recuperación funcional/campañas de la rama anterior). Main no modificado. Sin commit, push, PR, merge, SSH al VPS ni deploy en esta etapa. Los recursos gráficos y el documento CI/CD que ya estaban modificados/untracked se conservan y quedan fuera de este lote.

Revisión: http://localhost:8080/ ; comunicados: http://localhost:8080/comunicados/ . Home → NECESITO AYUDA → ¿En qué podemos ayudarte? → Derecho a ser escuchado.

## Resultado

- Un único botón Home “Volver arriba”, siempre disponible, teclado Enter/Espacio, nombre accesible, foco visible y foco devuelto a main. Scroll suave o instantáneo según movimiento reducido; sin listeners de scroll ni timers. Desktop abajo/derecha; mobile y tablet, fijo en el espacio central de la barra superior, con área44px y logo/menú separados. Esta adaptación evita tapar tarjetas, textos y controles.
- Video nativo en loop, autoplay/muted/playsinline. **Causa comprobada anterior:** loop=false, ended=true, paused=true y currentTime=duration=24.448s. No había pausa por scroll, IntersectionObserver ni temporizadores del video; el script solo pausaba con prefers-reduced-motion. Se conserva esa preferencia y se agrega un control explícito Pausar/Reproducir, sin forzar reanudación al navegar tras una pausa voluntaria. No hay audio automático, listeners repetidos, intervalos ni requests de recuperación artificiales.
- Footer: Instagram y Facebook conservados; X y YouTube usan los logos existentes social41/42 y enlaces canónicos del pedido. Se retiró WhatsApp de la lista social. Se conservan las líneas de contacto y los canales de otras secciones; los pictogramas de las líneas no son un enlace social WhatsApp. Targets52px, imagen37.6px (antes44/26.4px), espaciado/wrapping, etiquetas y noopener/noreferrer.
- Adolescencia: mismo shortcode, texto, negritas y destino **tel:+543512398953**. Solo se agrega presentación de enlace destacado redondeado; no se inventó WhatsApp. A320px envuelve el rótulo completo sin overflow.
- Comunicados: misma opción `ddna_final_statements` y shortcode `[ddna_statements]`; ocho entradas canónicas, años2025→2024→2023→2022, sin URLs visibles adicionales. El renderer usa URL explícita por entrada; el fallback de los tres enlaces antiguos se asocia por título en lugar de índice, para no mezclar destinos al ordenar/agregar entradas.

## Archivos del lote (14)

1. `app/themes/ddna-theme/assets/css/components/back-to-top.css` (nuevo)
2. `app/themes/ddna-theme/assets/js/back-to-top.js` (nuevo)
3. `app/themes/ddna-theme/footer.php`
4. `app/themes/ddna-theme/inc/enqueue.php`
5. `app/themes/ddna-theme/template-parts/sections/hero.php`
6. `app/themes/ddna-theme/assets/js/hero-video.js`
7. `app/themes/ddna-theme/assets/css/components/hero.css`
8. `app/themes/ddna-theme/template-parts/footer/site-footer.php`
9. `app/themes/ddna-theme/assets/css/components/footer.css`
10. `app/themes/ddna-theme/inc/editorial-content.php`
11. `app/themes/ddna-theme/assets/css/components/editorial-feedback.css`
12. `content/comunicados-octubre-2026.json` (nuevo)
13. `scripts/apply-comunicados-octubre-2026.php` (nuevo)
14. Este documento.

No hay cambios CI/CD, Docker/Compose, Core, Elementor, plugins ni recursos binarios nuevos. Los logos ya existen.

## Contenido local fuera de Git

La DB local pasó de tres comunicados a ocho. El script cambia únicamente `ddna_final_statements` y guarda `ddna_comunicados_octubre_2026_receipt` con before/after/fecha. No modifica páginas, posts, enlaces editoriales, adjuntos ni uploads. Las ocho entradas y el procedimiento sí están representados en el manifiesto/script versionables; el estado efectivo de DB y su receipt no se versionan.

Modo predeterminado dry-run. Aplicación local:

```sh
docker compose --profile tools run --rm cli eval-file /var/www/html/scripts/apply-comunicados-octubre-2026.php
docker compose --profile tools run --rm -e DDNA_COMUNICADOS_MODE=apply cli eval-file /var/www/html/scripts/apply-comunicados-octubre-2026.php
```

El script identifica entradas por título normalizado o ID Drive, corrige las ocho y deduplica solo esas identidades; preserva todos los demás comunicados/campos y ordena años. Segunda aplicación: cero escrituras. Rollback:

```sh
docker compose --profile tools run --rm -e DDNA_COMUNICADOS_MODE=rollback cli eval-file /var/www/html/scripts/apply-comunicados-octubre-2026.php
```

Se probó ese rollback real **local**, devolviendo exactamente las tres entradas anteriores, y se reaplicó para dejar las ocho disponibles para revisión. Si hay ediciones posteriores, rollback se bloquea para no pisarlas. No se usó ni restauró un dump SQL.

## Evidencias y validación

Directorio local fuera del repo: `/Users/lautyvallino/Projects/DDNA/wordpress-home-review-20261007/`.

- Chrome desktop1512 y responsive390/320/768: sin overflow horizontal en vistas comprobadas. Botón accesible, retorno y foco main, menús/acordeones, Actualidad→Comunicados y Territorio verificados. Ocho títulos completos/enlaces correctos en navegador, sin duplicados; el botón flotante existe solo en Home.
- `video-loops.json`: siete muestras desde12:47:22.092Z hasta12:49:34.491Z (132.399s), duración24.448s, **más de cinco vueltas naturales**, paused=false/ended=false mientras se desplazaba entre secciones; no se adelantó currentTime ni se aceleró playbackRate. Se observan retornos a tiempo bajo tras completar el clip. `manual-pause.json`: pausa9.573427s mantenida al menos39s tras navegar a Quiero conocer.
- `motion-validation.log`: ramas reduced-motion y pausa manual/reanudación, muted y scroll instantáneo con pruebas JS aisladas. No se cambió la preferencia global del usuario para probarlo.
- `statements-validation.log`: otros comunicados/campos preservados, duplicados canónicos eliminados, idempotencia, rollback exacto y rechazo ante edición posterior, con opciones simuladas sin DB real. También se compararon before/rollback del WordPress local real.
- `drive-access.json`: las ocho páginas y sus archivos se consultaron **sin cookies/autenticación**, y los ocho devolvieron cabecera `%PDF-` en lectura acotada. No se asumió acceso público por HTTP200, no se importaron/guardaron documentos y se conservan todos los enlaces pedidos. El visor de la herramienta web no pudo leer Drive; la comprobación HTTP anónima sí funcionó. Permisos podrían cambiar después.
- `php-all.log`:69 archivos PHP (app y scripts) sin errores; JS syntax y git diff --check pasan.
- `console-final.json`: sin errores/warnings nuevos del sitio; warnings preexistentes de la extensión MetaMask de Chrome, ajena al theme, permanecen.
- Capturas: desktop-home/desktop-footer/desktop-ayuda/desktop-comunicados y mobile-home/mobile-footer/mobile-ayuda/mobile-comunicados.jpg. Las capturas responsive son Chrome en Mac, no prueba sobre un iPhone/Android físico.

Limitaciones: los navegadores pueden bloquear autoplay, suspender videos/pestañas en segundo plano o en ahorro de energía; no se combate esa política con timers/reintentos. Movimiento reducido comienza en pausa; reproducción puede iniciarse deliberadamente con el control. Sin garantía de continuidad en background/iOS. Las campañas que muestran “Enlace pendiente” en la DB local son configuración previa: este lote no cambia sus destinos ni altera su metadata.

## Publicación y rollback propuestos — NO ejecutados

1. Esperar aprobación explícita del resultado; después commit/push/PR únicamente de los14 archivos del lote, excluyendo recursos pendientes ajenos. Revisar la integración de la rama funcional actual para no perder Campañas ni mezclar el PR de preparación CI/CD. No habilitar CI/CD por este pedido.
2. Cumplir la coordinación DevOps del deploy controlado, registrar revisión/imagen activa real y las15 identidades de contenedores/HTTP mediante lectura. No suponer que el checkout equivale a la imagen activa. Confirmar receptor/procedimiento existente en ese momento; no instalar el receptor nuevo como efecto lateral de esta publicación.
3. Antes de aplicar contenido, respaldar **solo** opción comunicados y receipt, con custodia protegida; conservar imagen anterior y su override. Construir imagen WordPress con código aprobado manteniendo Core7.0.2, Elementor4.2.4 y config externa. Reemplazar exclusivamente servicio wordpress usando los Compose reales y override que conserve la imagen Dashboard activa; --no-deps/--no-build/--pull never. Preservar redes/mounts/volúmenes y uploads. No copiar DB local ni ejecutar bootstrap/importadores generales.
4. Copiar únicamente manifiesto+script aprobado a la carpeta del release; ejecutar WP-CLI del entorno real con variables `DDNA_COMUNICADOS_MODE=dry-run`, `DDNA_COMUNICADOS_APPROVAL='APROBADO PARA PUBLICAR COMUNICADOS'` y `DDNA_COMUNICADOS_TARGET_ORIGIN=http://179.199.132.207`, **solo tras aprobación y revalidación del origen**. Si el preflight/backup son correctos, el mismo comando con modo apply. El entrypoint de WP-CLI depende del Compose/release que esté efectivamente activo y se concretará al revalidarlo, sin activar referencias históricas.
5. Verificar home/wp-login, video, footer, Adolescencia, ocho comunicados/links y comparación de IDs/StartedAt/imagen de todos los otros servicios. Sin operaciones de datos fuera de esas dos opciones.
6. Ante fallo: revertir únicamente imagen WordPress al ID anterior. Si ya se aplicaron comunicados, primero ejecutar el script del release con modo rollback y las mismas guardas; exige que la opción siga exactamente igual a after. Con ediciones posteriores, detener la reversión de contenido y revisar diferencias, preservándolas. No restaurar DB, uploads ni infraestructura para revertir este lote.

Este procedimiento es una propuesta para ejecución posterior autorizada; no se hizo push, merge, deploy ni contacto con DevOps.
