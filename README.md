<p align="center"><a href="https://www.facebook.com/GiosalonAngienails" target="_blank"><img src="https://github.com/VictorChn/Proyecto_Final/blob/master/public/img/logo.png?raw=true" width="400" style="border-radius: 100%;" alt="Gio Salon & Angie Nails"></a></p>

## Acerca del Proyecto

Este proyecto es una aplicación web para una **estetica**, donde se podrán realizar citas, reagendarlas y cancelarlas, así como agregar los servicios con los que cuente dicha estetica y de igual manera agregar estilistas, desarrollada mediante el framework de backend **Laravel**, junto con otras dependencias:

- **TailwindCSS**, para los estilos y diseño.
- **Jetstream**, para el sistema de autenticación y perfiles.
- **Sanctum**, para la autenticación de API básica.
- **Livewire**, para reactividad en el frontend y la creación de componentes dinamicos.
- **Spatie / Laravel Permission**, para la administración de roles y permisos.

Ademas de utilizar una base de datos en **MySQL** con el sistema de **XAMPP**.

## Comandos para su ejecución en un nuevo entorno

Si buscas iniciar este proyecto en un nuevo equipo, es necesario realizar los siguientes pasos dentro de la carpeta raíz del proyecto:

Primero, para instalar todas las librerías de PHP necesarias, ejecuta:

```bash
composer install
```

### 1. Configuración del archivo de entorno `.env`

Antes de generar claves o migrar la base de datos, es obligatorio crear tu archivo `.env` local copiando el archivo de ejemplo:

- **En Windows (PowerShell):**
    ```powershell
    Copy-Item .env.example .env
    ```
- **En macOS / Linux / Git Bash / CMD:**
    ```bash
    cp .env.example .env
    ```

Luego, genera una clave de seguridad de la aplicación con el comando:

```bash
php artisan key:generate
```

### 2. Preparación de la Base de Datos

Antes de ejecutar las migraciones, debes abrir **XAMPP**, iniciar **MySQL** y crear una base de datos vacía llamada:

```sql
gios_salon
```

Una vez creada, ejecuta el comando para crear las tablas y poblar la base de datos con los datos de prueba:

```bash
php artisan migrate --seed
```

### 3. Instalación de Frontend y Ejecución

Para instalar todas las dependencias del Frontend (Node.js) ejecuta:

```bash
npm install
```

Ahora para iniciar el servidor de manera local necesitamos ejecutar el siguiente comando:

```bash
php artisan serve
```

Mientras que para cargar todos los estilos CSS en una terminal distinta necesitamos usar el comando de:

```bash
npm run dev
```

> [!TIP]
> Puedes automatizar todo esto mediante el comando `composer run setup` y posteriormente usar `composer run dev` para iniciar el servidor.

## Credenciales de prueba

En la aplicación se cuenta con 3 roles los cuales son:

- Administrador
- Estilista
- Cliente

Además de tener credenciales de prueba para cada uno siendo estos:

### Administrador

```bash
Email: "kualexander69@gmail.com"
Password: "gio%Angie*"
```

### Estilista

```bash
Email: "warrior3011232@gmail.com"
Password: "D18e?ai#4k"
```

### Cliente

```bash
Email: "s.p.a.r.c.k.0.1.1.9@gmail.com"
Password: "Bn12_Al0"
```

## Modelo DER de la Base de datos

A continuación se muestra una representación visual de las tablas de la base de datos:

<p align="center"><img src="https://github.com/VictorChn/Proyecto_Final/blob/master/public/img/Diagrama%20de%20Entidad-Relaci%C3%B3n%20(DER).png?raw=true" width="1200" alt="Diagrama DER"></p>

## Recomendaciones Generales

Tambien tenemos como cuestiones a considerar que para poder instalar todas las dependencias, ejecutar el servidor y todo lo que se ha mencionado anteriormente, necesitamos tener instalados:

- [PHP](https://www.php.net). Para esta cuestión necesitamos que sea la versión 8.3 o Superior
- [Composer](https://getcomposer.org). El cual es el gestor de paquetes para PHP.
- [Node.js](https://nodejs.org/en/download/). El cual es el gestor de paquetes para JavaScript.
- [XAMPP](https://www.apachefriends.org/index.html). El cual funciona como servidor para PHP, asi como incluir la base de datos MySQL.

### Configuración del Envio de Correos

De igual manera para poder hacer las pruebas de envios de correo es necesario que al ejecutar todos los comandos de setup, se va a generar un archivo .env, en este es necesario configurar el correo electrónico que realizará el envio de correos.

Para este proyecto se recomienda usar Gmail. Esta configuración se encuentra en las lineas **50 - 56**:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación-de-google
MAIL_ENCRYPTION=smtps
MAIL_FROM_ADDRESS="tu-correo@gmail.com"
```

En su mayoría ya está la configuración, lo único que faltaría agregar es el correo desde el que se enviarán y la contraseña de aplicación.

> [!IMPORTANT]
> En `MAIL_PASSWORD` **no debes poner la contraseña tradicional** con la que ingresas a tu correo. Debes generar una **"Contraseña de Aplicación"** desde la configuración de seguridad de tu cuenta de Google. Si necesitas más información sobre cómo generar esta contraseña, haz clic [aquí](https://youtu.be/_BlFAbaMhuY?si=AA7Cm7M79ltutcNz).

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
