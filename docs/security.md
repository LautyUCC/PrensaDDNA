# Auditoría de seguridad del código personalizado DDNA

## Alcance

La auditoría cubre `ddna-theme`, `ddna-core`, Docker Compose, `.env.example` y reglas propias de Git. No se modificó WordPress Core, no se inspeccionó ni alteró producción y no se instalaron herramientas o plugins de seguridad.

Esta revisión reduce riesgos evidentes del código actual, pero no sustituye el hardening del hosting, actualizaciones, backups, control de cuentas ni una prueba de penetración previa al lanzamiento.

## Salida HTML

Se revisaron los contextos de escape del tema:

- texto plano y atributos: `esc_html()`, `esc_html_e()`, `esc_attr()` y `esc_attr_e()`;
- URLs de enlaces e imágenes: `esc_url()`;
- extractos con HTML editorial permitido: `wp_kses_post()`;
- clases: `sanitize_html_class()` seguido de `esc_attr()` en el template;
- HTML generado por APIs de WordPress, como imágenes destacadas, contenido, menús y paginación, se conserva mediante sus funciones nativas.

Se hizo explícito el escape de títulos, permalinks y extractos en las tarjetas de Programas, Campañas y Novedades. Los fragmentos SVG inline proceden exclusivamente de arrays constantes del tema y se eligen contra claves permitidas; no incorporan entrada editorial.

`the_content()`, descripciones de archivo y otras salidas editoriales de plantillas interiores mantienen el filtrado nativo de WordPress. Aplicar `esc_html()` a esos contenidos rompería bloques y marcado permitido.

## Entradas y sanitización

`ddna-core` aplica sanitización por tipo:

- texto: `sanitize_text_field()`;
- texto multilínea: `sanitize_textarea_field()`;
- correo: `sanitize_email()`;
- URL persistida: `esc_url_raw()`;
- IDs: `absint()`;
- claves de iconos: `sanitize_key()` y allowlist;
- booleanos: conversión explícita;
- números: validación numérica;
- fechas: formato ISO, componentes numéricos y `checkdate()`.

Los arrays de opciones institucionales, campos de CPT e iconos de menú ahora rechazan estructuras inesperadas antes de pasarlas a sanitizadores de valores escalares. Las cantidades de portada se limitan entre 1 y 24.

## Nonces y permisos

### Metaboxes institucionales

- El formulario genera `wp_nonce_field()`.
- El guardado verifica `wp_verify_nonce()`.
- Se evita el guardado durante autosave.
- Se exige `current_user_can( 'edit_post', $post_id )`.
- El post type se contrasta contra los grupos de campos registrados.

### Settings API

Las pantallas de Portada y Datos institucionales usan `register_setting()`, `settings_fields()` y `options.php`. WordPress gestiona nonce y autorización del guardado. Las callbacks de pantalla vuelven a comprobar:

- `edit_theme_options` para Portada DDNA;
- `manage_options` para Datos institucionales.

### Elementos de menú

El icono de acceso rápido se guarda dentro del flujo nativo `wp_update_nav_menu_item`, protegido por el nonce de la pantalla de Menús de WordPress. El callback exige además `edit_theme_options` y una clave incluida en la allowlist.

### REST

No existen endpoints REST personalizados. Los CPT, taxonomías y metadatos utilizan la REST API nativa. Cada metadato registrado incluye `auth_callback` y exige permiso `edit_post` sobre el objeto concreto para escritura.

### AJAX y parámetros públicos

No existen handlers AJAX propios, acciones `admin_post` ni lecturas propias de `$_GET`, `$_REQUEST`, cookies o archivos. Los únicos `$_POST` pertenecen a formularios administrativos identificados anteriormente.

## Enlaces externos

- Las URLs administrables se sanitizan al guardar mediante `esc_url_raw()` y al renderizar mediante `esc_url()`.
- Las redes sociales abren en una pestaña nueva y usan `rel="noopener noreferrer"`.
- Programas y Campañas no fuerzan `target="_blank"`; los enlaces externos conservan el comportamiento normal del navegador.
- Teléfonos y correos se construyen con esquemas `tel:`/`mailto:` y salida escapada.

## Secretos y repositorio

- `.env`, `.env.local`, dumps SQL, backups, uploads, caches y logs están ignorados por Git.
- La comprobación de archivos versionados no encontró `.env`, dumps ni `debug.log` rastreados.
- `.env.example` contiene solamente valores de ejemplo y contraseñas deliberadamente no válidas para producción.
- No se encontraron API keys, tokens, contraseñas o credenciales hardcodeadas en `ddna-theme` o `ddna-core`.

Antes de cualquier despliegue se deben reemplazar todos los valores `change-*`, usar contraseñas únicas y no copiar el `.env` local al repositorio.

## Debug y exposición de errores

- `WP_DEBUG_DISPLAY` permanece desactivado.
- PHP `display_errors` se fuerza a `0` en la configuración propia.
- `WP_DEBUG_LOG` ahora sigue `WP_DEBUG` en lugar de quedar siempre activo.
- `.env.example` propone `WP_DEBUG=0` como valor seguro por defecto.
- `DISALLOW_FILE_EDIT` impide editar PHP desde el panel de WordPress.
- No se encontraron `var_dump`, `print_r`, `error_log`, `eval` ni ejecución de comandos en el código personalizado.

En desarrollo puede habilitarse `WP_DEBUG=1` localmente. En staging público o producción debe permanecer en `0`, y los logs deben almacenarse fuera de rutas públicas cuando el proveedor lo permita.

## Hallazgos no modificados

- Los CPT usan capacidades de entradas (`capability_type => post`). Esto no es una vulnerabilidad por sí mismo, pero si futuros roles deben administrar sólo ciertos contenidos conviene definir capacidades por CPT.
- La seguridad TLS, cabeceras HTTP, WAF, permisos del filesystem, rotación de secretos y backups dependen del despliegue en Hostinger y quedan fuera de esta auditoría de código.
- No se agregaron nonces a acciones de sólo lectura ni ARIA/HTML relacionado con seguridad.

## Recomendaciones de despliegue

1. Mantener WordPress, PHP, MariaDB y dependencias actualizadas.
2. Usar HTTPS obligatorio y revisar cabeceras en staging.
3. Desactivar debug y visualización de errores.
4. Utilizar credenciales distintas para base de datos y administración.
5. Aplicar mínimo privilegio a cuentas editoriales.
6. Confirmar backups automáticos y restauración probada.
7. Revisar permisos de archivos y bloquear ejecución PHP en uploads si el hosting lo permite.
