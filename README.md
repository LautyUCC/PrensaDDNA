# DDNA Córdoba — rebuild de WordPress

Entorno local independiente para el rebuild del sitio institucional de la Defensoría de los Derechos de Niñas, Niños y Adolescentes de la Provincia de Córdoba.

Este proyecto no se conecta ni modifica el sitio productivo `ddna.cba.gov.ar`. La auditoría de referencia está en [`docs/current-site-audit.md`](docs/current-site-audit.md).

El modelo editorial implementado por `ddna-core` está documentado en [`docs/content-model.md`](docs/content-model.md).

## Requisitos

- Docker Desktop con Docker Compose v2
- Git
- 2 GB de memoria disponible como mínimo

## Estructura

```text
.
├── app/
│   ├── mu-plugins/          # extensiones obligatorias propias
│   ├── plugins/ddna-core/   # funcionalidad institucional propia
│   └── themes/ddna-theme/   # tema institucional propio
├── docs/                    # documentación y auditorías
├── .env                     # secretos locales; no se versiona
├── .env.example             # plantilla de configuración
└── compose.yaml             # WordPress, MariaDB y WP-CLI
```

WordPress Core, la base de datos y los uploads se guardan en volúmenes Docker persistentes. Sólo el código personalizado bajo `app/` se monta desde el repositorio, por lo que permanece separado del Core y puede desplegarse en un hosting WordPress convencional como Hostinger.

## Inicio rápido

1. Copiar `.env.example` como `.env` y reemplazar las contraseñas de ejemplo.
2. Iniciar los servicios:

   ```sh
   docker compose up -d
   ```

3. Esperar a que la base esté saludable:

   ```sh
   docker compose ps
   ```

4. Abrir <http://localhost:8080>. En la instalación inicial preparada para este repositorio, los datos de acceso local se encuentran en las variables `WP_ADMIN_*` de `.env`.

Si se cambia `WP_PORT`, también se debe actualizar `WP_URL`.

## Comandos habituales

```sh
# Estado y logs
docker compose ps
docker compose logs --tail=100 db wordpress

# Información de WP-CLI
docker compose --profile tools run --rm cli --info

# Comprobar acceso a la base desde WP-CLI
docker compose --profile tools run --rm cli db check

# Consultar la versión después de instalar WordPress
docker compose --profile tools run --rm cli core version

# Comprobar que WordPress está instalado
docker compose --profile tools run --rm cli core is-installed

# Detener sin borrar datos
docker compose down
```

Para borrar volúmenes y reiniciar desde cero se puede usar `docker compose down --volumes`, pero esa operación elimina de forma irreversible la base, el Core local y los uploads.

## Persistencia y copias

Los volúmenes son `db_data`, `wordpress_core` y `wordpress_uploads`, con el prefijo del proyecto definido por `COMPOSE_PROJECT_NAME`. Antes de actualizar versiones o migrar, exportar la base y los uploads.

Ejemplo de export de base, una vez instalado WordPress:

```sh
docker compose --profile tools run --rm cli db export - > ddna-local.sql
```

Los dumps y backups están ignorados por Git y deben almacenarse de manera segura.

## Compatibilidad de despliegue

El contenido y el código personalizado no deben depender de nombres de contenedor, rutas de Windows ni `localhost`. Esos valores sólo configuran el entorno local. En Hostinger se desplegarán el tema, los plugins propios, uploads y una exportación de base mediante el procedimiento que se defina para producción.

No se incluyen Elementor, Divi ni otros page builders.

La preparacion de los entornos `local`, `staging` y `production`, incluida la integracion portable con `wp-config.php`, esta documentada en [`docs/staging-hostinger.md`](docs/staging-hostinger.md). No usar `compose.yaml` como configuracion del hosting compartido.
