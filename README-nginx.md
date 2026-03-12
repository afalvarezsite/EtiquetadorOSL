# Guía de instalación del Etiquetador OSL (Nginx)

> [!WARNING]  
> Esta guía ha sido probada en sistemas basados en Debian (Ubuntu 24.04 LTS).

## Instalación

### Paso 1: Actualizar el sistema

```bash
sudo apt update && sudo apt upgrade -y
```

### Paso 2: Instalar Nginx

```bash
sudo apt install nginx -y
```

### Paso 3: Instalar MariaDB (MySQL)

```bash
sudo apt install mariadb-server -y
sudo mysql_secure_installation
```

### Paso 4: Instalar PHP 8.2 FPM y extensiones

```bash
sudo apt install php8.2-fpm php8.2-mysql php8.2-gd php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip -y
```

### Paso 5: Instalar Python y dependencias de PDF

```bash
sudo apt install python3 python3-pip python3-venv -y
# Configurar entorno virtual para scripts de etiquetado
sudo python3 -m venv /opt/venv-etiquetador
sudo /opt/venv-etiquetador/bin/pip install pymupdf fillpdf
```

---

## Configuración del Proyecto

### 1. Descargar el código

```bash
cd /var/www
sudo git clone https://github.com/Adriansolier322/EtiquetadorOSL.git etiquetador
sudo chown -R www-data:www-data /var/www/etiquetador
```

### 2. Configurar el bloque de servidor de Nginx

Crea un nuevo archivo de configuración:

```bash
sudo nano /etc/nginx/sites-available/etiquetador
```

Pega el siguiente contenido:

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/etiquetador/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log  /var/log/nginx/etiquetador_error.log;
    access_log /var/log/nginx/etiquetador_access.log;
}
```

Habilita el sitio y reinicia Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/etiquetador /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
```

### 3. Configuración de la Base de Datos

Accede a MariaDB:

```bash
sudo mariadb
```

Ejecuta las siguientes consultas:

```sql
CREATE DATABASE etiquetador;
CREATE USER 'etiquetador_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON etiquetador.* TO 'etiquetador_user'@'localhost';
FLUSH PRIVILEGES;
USE etiquetador;
SOURCE /var/www/etiquetador/init.sql;
EXIT;
```

### 4. Configurar variables de entorno

Edita el archivo de configuración de la aplicación:

```bash
sudo nano /var/www/etiquetador/config/config.php
```

Asegúrate de que los datos de la base de datos coincidan.

---

## Permisos Finales

```bash
sudo chown -R www-data:www-data /var/www/etiquetador
sudo chmod -R 775 /var/www/etiquetador
```

> [!IMPORTANT]
> **Credenciales por defecto:**
>
> - **Usuario:** admin
> - **Contraseña:** admin123
>
> *Recuerda cambiar la contraseña inmediatamente desde el panel de administración.*
