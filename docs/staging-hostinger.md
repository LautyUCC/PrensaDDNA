# Preparacion de staging DDNA en Hostinger

## Objetivo

El staging debe vivir en una URL temporal independiente. No debe reemplazar, apuntar ni modificar `ddna.cba.gov.ar`; el sitio productivo actual continua funcionando hasta una instruccion explicita y un plan de migracion aprobado.

| Entorno | `WP_ENVIRONMENT_TYPE` | Indexacion | Debug | URL |
|---|---|---|---|---|
| Local | `local` | `noindex` automatico | opcional, nunca visible | `WP_URL` local |
| Staging | `staging` | `noindex` y acceso protegido | log temporal | URL HTTPS temporal |
| Production | `production` | segun Ajustes > Lectura | desactivado | dominio definitivo aprobado |

No hay dominios de staging o produccion hardcodeados en `ddna-theme`, `ddna-core` o la configuracion portable. Las URLs salen de WordPress o `WP_URL`.

## Codigo propio que debe desplegarse

- `app/themes/ddna-theme/` a `wp-content/themes/ddna-theme/`.
- `app/plugins/ddna-core/` a `wp-content/plugins/ddna-core/`.
- `config/wp-config.environment.php` a una ubicacion privada legible por `wp-config.php`.
- Una inclusion minima en `wp-config.php`.
- Reglas necesarias de HTTPS, acceso y cron configuradas en Hostinger.
- `app/mu-plugins/` solo cuando contenga MU plugins reales; hoy contiene solo `.gitkeep`.

No son runtime de Hostinger: `compose.yaml`, `docker/`, `scripts/`, `docs/` y las plantillas `.env.*.example`. Los scripts son seeds de desarrollo y no deben ejecutarse automaticamente en staging.

## Archivos que NO deben versionarse

- `.env`, `.env.local` y cualquier `.env.*` real.
- Credenciales de base, SMTP, SFTP/SSH, tokens y salts.
- `wp-config.php` real con secretos.
- WordPress Core como parte del repositorio de codigo propio.
- `wp-content/uploads/`, dumps SQL, backups, caches y logs.
- Datos locales y volumenes Docker.
- Dependencias o builds temporales no requeridos.

Base y uploads son datos persistentes de cada entorno; se trasladan mediante backup controlado, no Git.

## Variables

Plantillas versionadas:

- `.env.example`: Docker local.
- `.env.staging.example`: referencia para staging.
- `.env.production.example`: referencia para production.

Variables principales:

- `WP_ENVIRONMENT_TYPE`: `local`, `staging` o `production`.
- `WP_URL`: URL absoluta del entorno, sin slash final.
- `WP_DEBUG`, `WP_DEBUG_LOG`.
- `WP_FORCE_HTTPS`.
- `WP_DISABLE_CRON`.
- `WP_TABLE_PREFIX`.
- `DDNA_DB_NAME`, `DDNA_DB_USER`, `DDNA_DB_PASSWORD`, `DDNA_DB_HOST`.
- Ocho claves y salts `DDNA_*` indicadas en las plantillas.

Si Hostinger no permite variables persistentes, definirlas una sola vez en un archivo privado fuera del document root y cargarlo antes de `config/wp-config.environment.php`. Ese archivo nunca debe entrar en Git ni ser descargable por HTTP.

## Integracion con wp-config.php

Hostinger puede generar su propio `wp-config.php`; no debe reemplazarse ciegamente. Antes de:

```php
require_once ABSPATH . 'wp-settings.php';
```

incluir:

```php
require_once __DIR__ . '/wp-config.environment.php';
```

Si se guarda fuera de `public_html`, adaptar esa unica ruta. La configuracion comprueba `defined()` antes de definir constantes, por lo que respeta valores administrados por el hosting.

`$table_prefix` es una variable especial de WordPress. La configuracion portable lo toma de `WP_TABLE_PREFIX` si Hostinger no lo definio antes. No debe cambiarse luego de crear las tablas.

## Procedimiento paso a paso

### 1. Crear un staging aislado

1. Crear desde hPanel un subdominio o dominio temporal que no sea `ddna.cba.gov.ar`.
2. Crear base y usuario exclusivos de staging.
3. No compartir base, uploads ni configuracion con produccion.
4. Activar proteccion por contrasena HTTP desde hPanel si esta disponible.
5. Mantener `WP_ENVIRONMENT_TYPE=staging`; el tema agrega `noindex, nofollow` fuera de production.

La proteccion HTTP es preferible a depender solo de robots, porque robots no controla el acceso.

### 2. HTTPS y URL

1. Activar SSL para la URL temporal.
2. Confirmar redireccion HTTP a HTTPS unicamente en staging.
3. Establecer `WP_URL=https://URL-TEMPORAL`.
4. Establecer `WP_FORCE_HTTPS=1`.
5. La configuracion reconoce `HTTP_X_FORWARDED_PROTO=https` si Hostinger termina TLS en un proxy.
6. Verificar login, cookies, REST, sitemap y assets sin mixed content.

No cambiar DNS ni opciones del sitio productivo.

### 3. WordPress y base

1. Crear una instalacion WordPress estable nueva.
2. Usar PHP soportado; el proyecto requiere 8.1+ y se prueba con 8.3.
3. Crear una base con credenciales aleatorias y privilegios solo sobre esa base.
4. Generar salts exclusivos para staging.
5. Mantener `utf8mb4`.
6. Conservar el prefijo de tablas durante la vida del entorno.

### 4. Configurar el entorno

1. Copiar `config/wp-config.environment.php` a la ubicacion acordada.
2. Incluirlo antes de `wp-settings.php`.
3. Proveer variables equivalentes a `.env.staging.example` sin versionar valores.
4. Usar inicialmente:

   ```text
   WP_ENVIRONMENT_TYPE=staging
   WP_DEBUG=0
   WP_DEBUG_LOG=1
   WP_FORCE_HTTPS=1
   WP_DISABLE_CRON=1
   ```

5. Tras estabilizar staging, cambiar `WP_DEBUG_LOG=0` y retirar de forma segura el log.

### 5. Desplegar codigo propio

1. Subir `ddna-theme` y `ddna-core` a sus directorios.
2. No subir Core local, volumenes Docker, `.env`, dumps o caches.
3. Activar primero `ddna-core` y despues `ddna-theme`.
4. Guardar una vez Ajustes > Enlaces permanentes o ejecutar `wp rewrite flush`.
5. No ejecutar seeds de `scripts/` sin una decision explicita sobre contenido de staging.

### 6. Base y contenido

No existe autorizacion para migrar contenido historico. Debe elegirse expresamente entre una instalacion vacia, una copia controlada del nuevo sitio local o datos de prueba descartables.

Si se importa el nuevo sitio local:

1. Exportar solo esa base, nunca produccion por inferencia.
2. Importar en la base exclusiva de staging.
3. Ejecutar primero un reemplazo serializado en seco:

   ```sh
   wp search-replace 'URL_LOCAL' 'URL_HTTPS_STAGING' --all-tables-with-prefix --precise --skip-columns=guid --dry-run
   wp search-replace 'URL_LOCAL' 'URL_HTTPS_STAGING' --all-tables-with-prefix --precise --skip-columns=guid
   ```

4. No modificar GUIDs.
5. Confirmar `home` y `siteurl`, aunque `WP_URL` los fije en runtime.

### 7. Uploads

- `wp-content/uploads/` es persistente y escribible; no va en Git.
- Copiar solo medios del nuevo sitio autorizados.
- No descargar ni sincronizar masivamente el sitio anterior.
- Mantener estructura anual/mensual y metadatos consistentes.
- No regenerar originales; se pueden generar thumbnails faltantes.

### 8. Permisos

Referencia sujeta a Hostinger:

- directorios `755`;
- archivos `644`;
- configuracion privada `600` o `640` si es compatible;
- propietario/grupo del usuario PHP/SFTP;
- uploads escribible por PHP;
- nunca `777`.

`DISALLOW_FILE_EDIT` permanece activo.

### 9. Cron

1. Establecer `WP_DISABLE_CRON=1`.
2. Crear en hPanel una tarea cada 5 a 15 minutos:

   ```sh
   php /ruta/al/staging/wp-cron.php >/dev/null 2>&1
   ```

3. Ajustar la ruta al hosting y verificar con `wp cron event list` y `wp cron event run --due-now`.
4. No mantener simultaneamente cron real y cron por visitas.

### 10. Correo

El proyecto no configura SMTP ni contiene credenciales.

1. Crear remitente autorizado para staging.
2. Configurar SMTP con la herramienta aprobada cuando exista necesidad real.
3. Guardar credenciales fuera de Git.
4. Enviar pruebas solo a casillas controladas.
5. Configurar SPF, DKIM y DMARC para el remitente.
6. Probar recuperacion de contrasena y correo transaccional.

No se instala todavia un plugin SMTP porque proveedor y credenciales no estan definidos.

### 11. Cache

1. Identificar la cache de servidor/CDN incluida en Hostinger.
2. No activar dos caches de pagina a la vez.
3. Purgar cache tras desplegar tema/plugin.
4. Excluir admin, login, previews y usuarios autenticados.
5. Mantener assets versionados y habilitar compresion/cache larga cuando staging sea estable.

No se instala todavia un plugin de cache.

### 12. Debug y logs

- Nunca mostrar errores en pantalla.
- Usar debug/log solo durante una ventana controlada en staging.
- Production: `WP_DEBUG=0`, `WP_DEBUG_LOG=0`.
- Proteger o mover logs fuera del document root y rotarlos.
- No registrar contrasenas, tokens ni cuerpos completos de formularios.

### 13. Checklist de validacion

1. `wp core version`, `wp core verify-checksums`, `wp db check`.
2. Estado activo de `ddna-core` y `ddna-theme`.
3. HTTPS, login y cookies seguras.
4. Home HTTP 200 y ausencia de mixed content.
5. Enlaces permanentes, REST y sitemap.
6. `noindex, nofollow` y proteccion HTTP.
7. Uploads y thumbnails.
8. Cron real.
9. Correo a casilla de prueba.
10. Logs sin errores.
11. Responsive, accesibilidad y rendimiento en la URL real.
12. Backup de base/uploads y restauracion probada.

## Promocion futura a production

No convertir staging en production simplemente cambiando DNS. Antes deben existir:

- aprobacion de contenido y alcance;
- estrategia de URLs y redirecciones;
- plan para documentos y contenido historico;
- backup y rollback del productivo;
- ventana coordinada;
- variables y salts exclusivos de production;
- revision de indexacion, sitemap y Search Console.

`WP_ENVIRONMENT_TYPE=production` elimina el `noindex` automatico, por lo que solo debe configurarse al aprobar el lanzamiento y revisar la visibilidad de WordPress.
