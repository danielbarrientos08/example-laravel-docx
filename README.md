# EDICIÓN CONVERSIÓN DE DOCX A PDF
Generar un archivo pdf a partir de una plantilla word (.docx)
# Dependencias
Instale libreoffice en /Aplicaciones (Mac), con su administrador de paquetes favorito (Linux) o con el msi (Windows).
## Despliegue

1. Clonar el proyecto
    ```sh
    git clone .....
    ```
2. Versiones usadas para el desarrollo:
    - PHP Version 8.1.*
    - [Laravel version 9.52.*](https://laravel.com/docs/9.x)
3. Ingresar al directorio del proyecto y realizar los comandos de ejecucion Laravel 
    ```sh
    composer install //Instalar dependencias
    cp .env.example .env   //crear archivo de variables de entorno
    php artisan key:generate   //generar APP_KEY
    php artisan serve --port=8000
    ```

4. Acceder en el puerto definido en el comando anterior : http://localhost:8000/

> Es importarte tener instalado libreoffice en el equipo.
