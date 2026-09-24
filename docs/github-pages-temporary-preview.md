# Preview temporal DDNA — GitHub Pages

Fecha: 2026-09-14. Duración prevista: aproximadamente una semana. Esta preview estática NO reemplaza al WordPress local ni al sitio productivo. No se configura Hostinger, DNS ni dominio DDNA.

## Arquitectura

WordPress local `http://localhost:8080/` → exportación anónima por HTTP → `github-preview/` versionada → push manual a main → GitHub Actions publica exclusivamente esa carpeta.

GitHub Actions no accede a localhost, no inicia WordPress, no importa SQL ni necesita credenciales externas. Publica la última exportación commiteada, no cambios locales sin exportar. WordPress y sus volúmenes permanecen intactos.

## Auditoría de origen y alcance del export

- Remoto: `git@github.com:LautyUCC/PrensaDDNA.git`; rama principal actual main.
- WordPress activo en 8080, MariaDB en red Docker; tema ddna-theme y plugin DDNA Core.
- HTML público de Home, nueve páginas finales nuevas y tres actualizadas, cuatro novedades actuales, archivo Novedades y rutas de navegación pública necesarias. También se capturan rutas estructurales vacías que están enlazadas actualmente; no se completan con contenido ficticio.
- JS existente: navigation, home-panels, carousel, hero-video y territory; no se cambia el código del tema ni del plugin.
- Assets: solo recursos referenciados en HTML/srcset y dependencias CSS locales; no se copia WordPress Core completo ni la biblioteca de medios entera.
- Exportación acotada a origen loopback y como máximo 80 páginas. No consulta producción ni descarga recursos externos. Subir el límite requiere revisión deliberada.
- El hero pesa 7.519.916 bytes: se incluye una copia exacta sin recomprimir o modificar el original.
- Elementor no está instalado en el estado real actual. Si existe en otra instalación, se captura su HTML final y assets referenciados, no el editor; widgets que dependan de AJAX/REST no son compatibles con esta demo.
- No exportar boilerplate Hello world/Sample Page, author archives, wp-admin, wp-login, REST, XML-RPC, feeds, configuraciones, SQL, logs, secretos o datos privados.

## Generar y actualizar

Desde la raíz del proyecto en PowerShell:

```powershell
docker compose up -d db wordpress
powershell -NoProfile -ExecutionPolicy Bypass -File scripts/update-github-preview.ps1
```

Este es el comando que debes ejecutar ANTES de cada push que deba mostrar cambios nuevos. Comprueba HTTP, exporta y valida enlaces/assets básicos, ausencia de localhost/secretos y tamaño. No hace commit ni push. Si el servidor estático propio ya corre, se recrea para montar la nueva carpeta; no toca WordPress ni MariaDB.

Build directo: `scripts/build-github-preview.ps1`. Validación independiente:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts/verify-github-preview.ps1
```

Se genera en una carpeta de trabajo dentro de backups (ignorada). Solo tras una validación correcta reemplaza github-preview; la anterior se mueve a backups, no se elimina. El reemplazo se limita a la carpeta generada con su marcador de build. Si falla, se conserva la preview anterior y se informa el error.

Para otro repo/ruta pública puedes configurar:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts/update-github-preview.ps1 -LocalUrl http://localhost:8080 -BasePath /OtroRepositorio/ -PublicOrigin https://usuario.github.io
```

Por defecto se deducen owner/repo de origin. El script no requiere dominio escrito en múltiples archivos. El manifiesto de contenido final sirve como semilla adicional si existe; siempre se captura HTML renderizado actual, no se reconstruye desde ese JSON.

## Probar por HTTP, no file://

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts/serve-github-preview.ps1
```

Preview local: **http://localhost:4173/PrensaDDNA/**

El contenedor ddna-github-preview (Nginx) monta solo github-preview en modo lectura; escucha únicamente en loopback. La ruta bajo el repo reproduce GitHub Pages, no se prueba como si estuviera en raíz. Un servidor fuera de esa ruta no reproduce correctamente este build. No se monta la raíz del repositorio ni se expone .env.

Para detener únicamente el servidor de preview: `docker stop ddna-github-preview`. WordPress puede seguir corriendo.

## Versionar y publicar manualmente

github-preview SÍ se versiona temporalmente: Actions no tiene acceso a la base local. No la utilices como fuente del tema ni edites sus HTML a mano; esas ediciones se reemplazan en el próximo build.

Después de revisar los archivos:

```powershell
git status --short
git add github-preview .github/workflows/deploy-github-preview.yml scripts/build-github-preview.ps1 scripts/update-github-preview.ps1 scripts/verify-github-preview.ps1 scripts/serve-github-preview.ps1 docs/github-pages-temporary-preview.md
git commit -m "feat: agrega preview temporal de DDNA para GitHub Pages"
git push origin main
```

README también puede incluirse si quieres versionar las instrucciones. Hay cambios anteriores de la actualización editorial local todavía sin commit; revisarlos y versionarlos separadamente si deseas que otro equipo reconstruya ese WordPress. No usar git add indiscriminadamente ni subir .env, backups o dumps. La preview es autocontenida y el workflow publica únicamente su carpeta aunque haya más código propio en el repo.

## Activar GitHub Pages

1. En GitHub → LautyUCC/PrensaDDNA → Settings → Pages.
2. Build and deployment → Source: **GitHub Actions**.
3. Verificar que Actions esté permitido por la configuración del repositorio/organización y que main pueda desplegar al environment github-pages.
4. Hacer el push manual, o ejecutar el workflow mediante Actions → Deploy temporary DDNA preview → Run workflow.
5. Comprobar ejecución verde y la URL del environment/deployment.

URL esperada: **https://lautyucc.github.io/PrensaDDNA/**. Todavía no se activó Pages ni se hizo push en esta tarea; no se garantiza un deployment público hasta completar estos pasos. En repos privados comprobar disponibilidad del plan; no cambiar visibilidad automáticamente.

El workflow usa Actions oficiales: checkout v6, configure-pages v5, upload-pages-artifact v4 y deploy-pages v4; contents read, pages write e id-token write; concurrency github-pages. Se contrastó con la [documentación oficial de custom workflows de GitHub Pages](https://docs.github.com/en/pages/getting-started-with-github-pages/using-custom-workflows-with-github-pages). No usa rama gh-pages ni claves externas. Cada push a main publica el artifact estático commiteado.

## Rutas y límites

- href/src/srcset/poster, dependencias url() de CSS y URLs locales quedan bajo la base /PrensaDDNA/. No hay referencias necesarias a localhost o 127.0.0.1 en el build.
- Archivo local /category/novedades/ se exporta como /novedades/ y todos los enlaces correspondientes se adaptan; no se cambia el permalink local ni se crean 301 productivas.
- Se recorre paginación que esté enlazada en el HTML público; actualmente hay cuatro novedades, sin páginas adicionales de archivo visibles. No se inventan páginas de paginación.
- Google Drive, YouTube, mapas, tel y mailto se conservan como externos. Disponibilidad de servicios externos depende de sus permisos y conexión.
- Búsquedas/formularios server-side quedan deshabilitados únicamente en HTML generado, con aviso; no aparentan aceptar envíos. Links administrativos/endpoints y boilerplate no se publican.
- No funcionan PHP, DB, wp-admin, login, edición, comentarios/envíos, AJAX/REST WordPress, búsqueda dinámica o editor Elementor. No se replica WordPress con JS.
- Sí funcionan HTML público, navegación exportada, menú responsive, accordions, seis paneles/hashes, carruseles, pins/popups/teclado, CSS, fuentes, imágenes, video y enlaces externos.
- Solo información públicamente renderizada sin sesión; no se exportan registros de usuarios ni perfiles. Nombres/bylines ya públicos dentro de noticias pueden aparecer como parte del HTML editorial.
- La preview conserva noindex/nofollow y robots Disallow. Esto desalienta indexación pero NO proporciona privacidad o control de acceso: cualquiera con la URL puede ver/compartir el contenido. No publicar datos confidenciales.
- Las carpetas wp-content/wp-includes del build solo contienen assets públicos referenciados, no PHP ni instalaciones WordPress.

## Validación

Build inicial final: 24 rutas públicas, 95 assets, 122 archivos y aproximadamente 10,43 MiB (incluido video). Marcador preview-build.json contiene solo rutas, fecha, URL pública y contadores, sin URL local ni secretos.

- .nojekyll, Home y /novedades/ presentes; referencias internas/srcset/CSS verificadas contra archivos reales, sin loops locales ni credenciales.
- YAML parseado y rama, permisos y artifact revisados. No se probó deployment remoto porque Source Pages y push quedan bajo control del usuario.
- HTTP local bajo /PrensaDDNA/: Chrome comprobó Home, doce páginas finales y todos sus paneles en 320/375/430/768/1024/1366/1440/1920 sin overflow ni errores JS. Tab, Shift+Tab, Enter, Espacio, Escape, foco y hover pasaron en 375/1440 (hover solo desktop). Script existente `test-final-sept-2026.ps1` admite `-BaseUrl http://localhost:4173/PrensaDDNA -DebugPort 9225`.
- WordPress conserva su base, usuarios, contenidos, tema/plugin y puerto 8080. Exportar no ejecuta scripts de actualización editorial.

## Retirar cuando se autorice Hostinger

1. Guardar feedback y decidir si conservar snapshot/tag de la demo.
2. Deshabilitar/eliminar publicación en Settings → Pages (Unpublish si está disponible).
3. Retirar el workflow para evitar nuevos deploys.
4. Retirar github-preview y scripts exclusivos de la demo mediante un commit explícito, solo después de autorización; no eliminar tema, plugin, documentos o uploads de WordPress.
5. Detener/eliminar el contenedor propio de preview si no se necesita.
6. Seguir el procedimiento de staging/producción únicamente cuando se autorice. Esta preview no es un paquete de migración a Hostinger ni contiene su DB.
