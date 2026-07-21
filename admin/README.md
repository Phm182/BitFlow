# Panel administrativo BitFlow

## Instalación

1. Hacé un backup de la base.
2. Para una instalación existente, importá `sql/admin_migration.sql` desde phpMyAdmin.
   Para una base nueva, importá `sql/bitflow.sql`.
3. Revisá las credenciales de `inc/funciones/bd.php`.
4. Cambiá `$admin_setup_key` por una clave larga y única. Si queda vacía (`''`), el setup no solicitará clave.
5. Abrí `/admin/`. Si todavía no hay administradores, el sistema te enviará a `/admin/setup.php`.
6. Creá el primer usuario con una contraseña de al menos 12 caracteres e iniciá sesión.

El panel también verifica y crea su esquema al abrirse, para facilitar instalaciones en hosting compartido. La migración SQL se entrega para despliegues controlados y auditoría.

## Funciones incluidas

- Login por usuario o email, sesiones seguras y logout por POST.
- CSRF en todos los formularios administrativos.
- Bloqueo por 15 minutos después de 5 intentos fallidos por identificador e IP.
- Dashboard con totales y auditoría reciente.
- Contactos con búsqueda, filtros, paginación, edición, estados, notas, archivo, eliminación y CSV.
- Exportación protegida contra fórmulas maliciosas de planillas.
- Protección Apache para `inc/`, `sql/`, templates y archivos sensibles.

## Producción

- Usá HTTPS para activar la cookie `Secure`.
- No subas dumps, backups ni credenciales públicas.
- Reemplazá la clave de instalación de ejemplo antes de publicar.
- Conservá `display_errors=Off` y `log_errors=On` en producción.

