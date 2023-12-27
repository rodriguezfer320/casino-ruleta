CONFIGURACIÓN PARA EJECUTAR EL PROGRAMA

1. Bajar el proyecto del repositorio de github, ejecutando el siguiente comando:
	
    git clone https://github.com/rodriguezfer320/casino-ruleta.git

2. En la raíz del proyecto duplique el archivo .env.example y cámbiale el nombre a .env

3. En el archivo .env, sustituya los datos indicados en las variables correspondientes:
	
    DB_DATABASE=casino_ruleta
    DB_USERNAME=laravel
    DB_PASSWORD=root

4. En la carpeta principal del proyecto, ejecuté el siguiente comando:
	
    docker compose up

5. Luego, ejecuté el siguiente comando para ingresar al contenedor laravel (proyecto):
	
    docker exec -it laravel bash

6. Por último, en la consola del contenedor laravel ejecute los siguientes comandos:

php artisan key:generate
php artisan migrate // La base de datos tarda un poco en iniciar, esperar hasta que esta cargue bien para ejecutarlo
                    // para saber si la base de datos ya cargo, solo basta con acceder a la pagina del proyecto en el navegador
                    // si esta no lanza una exception, quiere decir que esta lista
