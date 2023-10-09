# Grupo
**Adair Gondán, Sergio Lusa Coria, Javier Arambarri Calvo**

**Asignatura:** Sistemas de Gestión de Seguridad de Sistemas de Información (SGSSI)
**Curso:** 2023-2024
**Grado:** Ingeniería Informática de Gestión y Sistemas de Información

# Desplegar proyecto mediante Docker
Se necesita Linux, Apache, MariaDB (MySQL) y PHP 7.2 en Docker Compose. A continuación se indica cómo desplegar el sistema en Docker.

# Resumen:
Para arrancar por primera vez el sistema, construiremos la imagen:
```bash
$ docker build -t="web"
```

Una vez la imagen haya sido creada (sólo es necesario crearla una vez), para arrancar el sistema completo, ejecutar en la carpeta en la que están los archivos de Docker y el proyecto:
```bash
$ docker-compose up -d #-d devuelve el prompt de la terminal.
```

Para detener el despliegue:
```bash
$ docker-compose down #o Ctrl+C
```

# Instalación Docker

Instalar docker:
```bash
$ sudo apt install docker.io
```

Docker necesita privilegios de root. Si deseas evitar el uso de sudo, ejecutar:
```bash
$ sudo groupadd docker
$ sudo usermod -aG docker $USER
$ docker run hello-world
```

Reiniciar el sistema, y ejecutar:
```bash
$ docker run hello-world
```

## Instalación Docker-Compose

docker-compose nos permitirá desplegar el sistema (HTML, PHP, MariaDB...), para instalarlo:
```bash
$ sudo apt install docker-compose
```

## Configuración del sistema para su despliegue
### Crear imagen
La imagen se crea a partir de un DockerFile, proporcionado por el profesor:
La imagen construida a partir del Dockerfile proporcionado por el profesor:
```bash
FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli
```

### Definición de los servicios
Los servicios de los que se compone el sistema se describen en el archivo docker-compose.yml. En este proyecto serán tres:
#### web
Contiene un servidor web Apache y contiene nuestra aplicación PHP definida en /app. Se conecta al servicio db y redirige el puerto 81 del host al puerto 80 del container, que es donde se ejecuta Apache.
#### db
Imagen que provee la base de datos MariaDB, contenida en /mysql.
#### phpmyadmin
Imagen oficial de PHPMyAdmin, se conecta al servicio db y se usa para administrar la base de datos, accediendo directamente al puerto 8890. 
#### docker-compose.yml
```
services:
  web:
    build: .
    environment:
      - ALLOW_OVERRIDE=true
    ports:
      - "81:80"
    links:
      - db
    volumes:
      - ./app:/var/www/html/

  db:
    image: mariadb:10.8.2
    restart: always
    volumes:
      - ./mysql:/var/lib/mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_USER: admin
      MYSQL_PASSWORD: test
      MYSQL_DATABASE: database
    ports:
      - "8889:3306"

  phpmyadmin:
    image: phpmyadmin/phpmyadmin:latest
    links:
      - db
    ports:
      - 8890:80
    environment:
      MYSQL_USER: admin
      MYSQL_PASSWORD: test
      MYSQL_DATABASE: database
```

### Construir la imagen
```bash
$ docker build -t="web"
```

