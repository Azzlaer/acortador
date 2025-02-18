# Acortador de URLs

## Descripción
Este es un acortador de URLs que permite a los usuarios generar enlaces cortos y administrar sus enlaces mediante un panel de control. También incluye autenticación y estadísticas básicas.

## Características
- Acortar URLs de manera rápida y sencilla.
- Panel de control para administrar enlaces.
- Autenticación de usuarios con soporte para 2FA.
- Estadísticas básicas de uso.
- Seguridad mejorada con validación de entradas.

## Requisitos
- PHP 7.4 o superior.
- Servidor web (Apache, Nginx, etc.).
- MySQL/MariaDB para almacenamiento de enlaces.
- Composer para gestionar dependencias.

## Instalación
1. **Clonar el repositorio:**
   ```sh
   git clone https://github.com/Azzlaer/acortador.git
   cd acortador
   ```
2. **Instalar dependencias:**
   ```sh
   composer install
   ```
3. **Configurar la base de datos:**
   - Crear una base de datos en MySQL.
   - Importar el esquema de la base de datos desde `database.sql`.
   - Configurar el archivo `.env` con las credenciales de la base de datos.

4. **Configurar el servidor web:**
   - Asegurar que el servidor apunte al directorio `public/`.
   - Habilitar `mod_rewrite` en Apache si es necesario.

5. **Ejecutar el proyecto:**
   ```sh
   php -S localhost:8000 -t public
   ```

## Uso
- Accede a `http://localhost:8000` para comenzar a acortar URLs.
- Inicia sesión para administrar tus enlaces y ver estadísticas.

## Seguridad
- Se recomienda utilizar HTTPS para proteger la información de los usuarios.
- Validación estricta de URLs para evitar enlaces maliciosos.
- Protección contra inyecciones SQL y ataques XSS.

## Contribuir
Si deseas mejorar el proyecto, por favor sigue estos pasos:
1. Haz un fork del repositorio.
2. Crea una nueva rama con tu mejora o corrección.
3. Realiza un Pull Request con una descripción detallada de los cambios.

## Licencia
Este proyecto está licenciado bajo la MIT License. Puedes ver más detalles en el archivo `LICENSE`.
