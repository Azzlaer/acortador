# Acortador de URLs Mejorado

## 📌 Descripción
Este es un sistema de acortamiento de URLs con medidas de seguridad avanzadas, validación de enlaces, protección contra abuso y autenticación de usuarios. Incluye un panel de control para la gestión de enlaces y estadísticas de uso.

## 🚀 Características
- ✅ **Acortar URLs** de manera rápida y sencilla.
- 🔒 **Validación estricta de URLs** para evitar enlaces maliciosos.
- 📊 **Estadísticas básicas** sobre el uso de los enlaces.
- 👤 **Autenticación de usuarios** con soporte para **2FA**.
- 🔄 **Redirección segura** de URLs acortadas.
- ⏳ **Límite de acortamiento por usuario/IP** para evitar abuso.
- 🤖 **Protección contra bots** con integración de reCAPTCHA.

## 🛠️ Requisitos
- PHP 7.4 o superior.
- Servidor web (Apache, Nginx, etc.).
- MySQL/MariaDB para almacenamiento de enlaces.
- Composer para gestionar dependencias.

## 📥 Instalación
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

## 🔹 Estructura del Proyecto
📂 **acortador**
   - 📁 `public/` → Carpeta pública del proyecto.
   - 📁 `includes/` → Contiene archivos auxiliares como validaciones y conexiones.
   - 📄 `validar_url.php` → Función para validar URLs antes de acortarlas.
   - 📄 `redireccionar.php` → Redirección segura a la URL original.
   - 📄 `limite_acortamiento.php` → Control de límite de URLs acortadas por IP.
   - 📄 `.env` → Configuración de la base de datos.

## 🔐 Seguridad Implementada
- **Validación de URLs:** Solo se aceptan URLs válidas y accesibles.
- **Protección contra inyección SQL:** Uso de sentencias preparadas en todas las consultas a la base de datos.
- **Protección contra XSS:** Sanitización de todas las entradas y salidas de datos.
- **Límite de uso por usuario/IP:** Máximo de 5 URLs acortadas por IP cada hora.
- **Redirección segura:** Evita redirecciones a sitios maliciosos o inexistentes.
- **Integración de reCAPTCHA:** Evita el uso de bots para acortar URLs de forma masiva.

## 📊 Uso del Sistema
- Accede a `http://localhost:8000` para comenzar a acortar URLs.
- Inicia sesión para administrar tus enlaces y ver estadísticas.
- Redirección automática al ingresar una URL acortada.

## 🤝 Contribuir
Si deseas mejorar el proyecto, sigue estos pasos:
1. Haz un fork del repositorio.
2. Crea una nueva rama con tu mejora o corrección.
3. Realiza un Pull Request con una descripción detallada de los cambios.

## 📜 Licencia
Este proyecto está licenciado bajo la **MIT License**. Puedes ver más detalles en el archivo `LICENSE`.

---
**🔹 Desarrollado por:** [Azzlaer](https://github.com/Azzlaer) 🚀