### API LARAVEL 8

En este proyecto veremos las siguientes características o features:

- JWT Authetication.
- CRUD Usuarios.
- CRUD Items.
- Testing Driven Development.
- Recuperación de contraseña.
- Busqueda de pr By SKU or Name
## Pasos para montar un entorno

- Usamos Docker para crear los servicios del entorno como Mysql, PhpMyAdmin y PHP. [Docker Doc](https://docs.docker.com/compose/) 
- Variables de entorno. Debes crear tu archivo (.env) y configurar las siguientes variables de entorno: App Key, App Url, Servidor Base de datos y Servidor SMTP para correos.
- Instalación  vendors o dependencias con "composer install".
- PHP artisan migrate (para crear la estructura de la BDD).
- PHP artisan db:seed para crear algunos datos con que probar.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
