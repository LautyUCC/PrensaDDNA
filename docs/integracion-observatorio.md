# Integración local del Observatorio

## Arquitectura

El rebuild WordPress y el Dashboard son repositorios hermanos e independientes:

- WordPress: `D:\DDNA rebuild` (WordPress, MariaDB y WP-CLI).
- Dashboard: `D:\ddna-dashboard` (Next.js 16, API Routes y Supabase remoto).

La integración agrega `compose.observatorio.yaml` al repositorio WordPress. El
servicio `dashboard` construye el Dockerfile existente del segundo repositorio,
usa el puerto de host `127.0.0.1:3001` y su propia red
`ddna-observatorio-local`. No monta ni declara volúmenes y no depende de
WordPress. Supabase sigue siendo un servicio externo: no se inicia una base
local ni se aplican migraciones, seeds o ETL.

La ruta pública definitiva es un único dominio: `https://ddna.com.ar/` sirve
WordPress y `https://ddna.com.ar/observatorio/` sirve Next.js. El Dashboard
construye con `NEXT_PUBLIC_BASE_PATH=/observatorio`, que configura
`basePath` en Next.js. `Link` y router conservan su prefijo; las llamadas de
API y los assets HTML directos usan el helper `dashboardPath()` para evitar
que una URL absoluta consulte la raíz de WordPress.

El commit inspeccionado del Dashboard es `773208edefc8a6f600dc9fa56f55ffd4a60d0850`
(`fix(supabase): add is_admin() helper; rewrite self-referential RBAC policies`).

## Variables

WordPress define `DDNA_OBSERVATORIO_URL` desde el entorno. Localmente su valor
por defecto es `http://localhost:3001/observatorio/`; en producción se
configura como `https://ddna.com.ar/observatorio/`. No se escribe esa URL en
los templates.

El Dashboard lee `D:\ddna-dashboard\.env.local`, ignorado por Git. Las variables
posibles son `NEXT_PUBLIC_SUPABASE_URL`, `NEXT_PUBLIC_SUPABASE_ANON_KEY`,
`SUPABASE_SERVICE_ROLE_KEY`, `OPENAI_API_KEY` e `INTERNAL_API_SECRET`. El archivo
local inicial no contiene valores. Sin las dos variables públicas de Supabase,
el Dashboard se ejecuta en modo fallback/placeholder; no confirma datos reales.

En la validaciÃ³n local sin credenciales, el healthcheck informa estado
`degraded` y las rutas de indicadores muestran un estado explÃ­cito de datos no
configurados. No se presentan mÃ©tricas de ejemplo como datos institucionales.
La ruta de fuentes conserva su fallback propio identificado como tal. Ninguna
de estas pruebas confirma datos reales. La navegaciÃ³n que requiere una sesiÃ³n
o datos reales debe volver a validarse con credenciales autorizadas y datos
disponibles.

## Comandos locales

```powershell
# WordPress y MariaDB solamente (desde D:\DDNA rebuild)
docker compose up -d db wordpress

# Dashboard solamente (desde D:\DDNA rebuild)
docker compose -f compose.observatorio.yaml up -d dashboard

# Tras configurar el Dashboard en su .env.local ignorado por Git, reconstruir
# para incorporar solamente sus variables NEXT_PUBLIC_* al bundle del navegador.
docker compose --env-file ../ddna-dashboard/.env.local -f compose.observatorio.yaml up -d --build dashboard

# Ambos, conservando la infraestructura existente de WordPress
docker compose -f compose.yaml -f compose.observatorio.yaml up -d

# Logs y reinicios independientes
docker compose logs --tail=100 wordpress db
docker compose -f compose.observatorio.yaml logs --tail=100 dashboard
docker compose restart wordpress
docker compose -f compose.observatorio.yaml restart dashboard

# Detener sin borrar datos ni volúmenes
docker compose -f compose.observatorio.yaml stop dashboard
docker compose stop wordpress db
```

URLs locales: WordPress `http://localhost:8080`; Dashboard
`http://localhost:3001/observatorio/`; health
`http://localhost:3001/observatorio/api/health`.

## Actualización y diagnóstico

Para WordPress, revisar los cambios y actualizar su repositorio con el proceso
habitual. Para el Dashboard, entrar a `D:\ddna-dashboard`, revisar `git status`,
traer cambios sin descartar modificaciones locales y reconstruir solo el
servicio Dashboard:

```powershell
git -C D:\ddna-dashboard pull --ff-only origin main
docker compose -f D:\DDNA rebuild\compose.observatorio.yaml up -d --build dashboard
```

Comprobar `docker compose ... ps`, `... logs dashboard` y
`/observatorio/api/health`. Si
Supabase aparece como `degraded`, revisar únicamente las variables locales y la
disponibilidad del proyecto remoto; no ejecutar migraciones, ETL ni seeds como
parte del arranque.

## Seguridad, aislamiento y rollback

El Dockerfile del Dashboard ya ejecuta Next.js como usuario no root y tiene
healthcheck. Sus áreas administrativas y rutas de escritura conservan sus
controles propios; esta integración no crea SSO ni modifica autenticación,
RLS, Supabase, Vercel o DNS.

Para revertir la integración local, detener y eliminar solo el contenedor y la
red del Dashboard:

```powershell
docker compose -f compose.observatorio.yaml down
```

No usar `--volumes`: esta integración no necesita ni crea volúmenes. Quitar los
archivos aditivos `compose.observatorio.yaml` y la configuración de URL del tema
restaura la navegación anterior tras una revisión Git normal.

## Futuro Hostinger VPS

Como referencia para la configuraciÃ³n que se audite en el VPS, una regla
equivalente en Nginx debe preservar el prefijo al hacer proxy (sin barra final
en `proxy_pass`):

```nginx
location = /observatorio { return 308 /observatorio/; }
location ^~ /observatorio/ {
    proxy_pass http://dashboard:3000;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
}
location / { proxy_pass http://wordpress:80; }
```

Es una referencia, no una instrucciÃ³n de despliegue: debe adaptarse al proxy
existente, su red Docker y sus puertos una vez que exista acceso autorizado.

Cuando exista acceso autorizado al VPS, un único reverse proxy deberá priorizar
`/observatorio/` hacia Next.js y enviar el resto de `ddna.com.ar` a WordPress.
El proxy debe preservar el prefijo: Next.js ya se construye con ese `basePath`.
Falta inspeccionar el proxy real de Hostinger, sus puertos, la versión del
Dashboard y Supabase ya desplegados, los secretos de runtime, el dominio/callback
de Supabase, cabeceras de proxy, monitoreo y rollback remoto. Esta configuración
local no crea DNS, certificados, proxy ni despliegue remoto.

## AutomatizaciÃ³n pendiente

No se creÃ³ una GitHub Action ni se hizo push. Antes de automatizar `main` hacia
Hostinger se deben verificar el acceso SSH autorizado, directorios de ambos
repositorios, usuario y permisos de Docker, estrategia de build, secretos de
runtime, proxy real, healthchecks, backup y un rollback probado.
