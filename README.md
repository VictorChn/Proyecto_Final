<p align="center"><a href="https://www.facebook.com/GiosalonAngienails" target="_blank"><img src="https://github.com/VictorChn/Proyecto_Final/blob/master/public/img/logo.png?raw=true" width="400" style="border-radius: 100%;" alt="Gio Salon & Angie Nails"></a></p>


## Acerca del Proyecto
Este proyecto es una aplicación web para una estetica, donde se podrán realizar citas, reagendarlas y cancelarlas, así como agregar los servicios con los que cuente dicha estetica y de igual manera agregar estilistas,  desarrollada mediante el framework de backend, Laravel, junto con otras dependencias:
- TailwindCSS
- Jetstream
- Sanctum
- Livewire
- Spatie / Laravel Permission

Ademas de utilizar una base de datos en MySQL con el sistema de XAMPP.

## Comandos para su ejecución en un nuevo entorno
En este caso si se busca iniciar este proyecto en un nuevo equipo es necesario que ejecute los siguientes comandos en una terminar que este dentro de la carpeta raíz del proyecto.

Primero para instalar todas las librerias de PHP necesarias deberá de ejecutar el comando:
```bash
composer install
```

Y para instalar todas las dependencias del Frontend (Node.js) necesitamos ejecutar:
```bash
npm install
```

Del mismo modo para poder tener la base de datos lista y poder tener información para poder ejecutar pruebas necesitamos ejecutar el comando:
```bash
php artisan migrate --seed
```

## Credenciales de prueba
En la aplicación se cuenta con 3 roles los cuales son:
- Administrador
- Estilista
- Cliente

Además de tener credenciales de prueba para cada uno siendo estos:

```bash
Rol: "Administrador"
Email: "kualexander69@gmail.com"
Password: "gio%Angie*"
```

```bash
Rol: "Estilista"
Email: "warrior3011232@gmail.com"
Password: "D18e?ai#4k"
```


```bash
Rol: "Cliente"
Email: "s.p.a.r.c.k.0.1.1.9@gmail.com"
Password: "Bn12_Al0"
```


## Modelo DER de la Base de datos


## Recomendaciones Generales


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
