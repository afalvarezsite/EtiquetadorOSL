# Guía de instalación del Etiquetador OSL (Apache)

> [!WARNING]  
> Esta guía ha sido probada en sistemas basados en Debian (Ubuntu 24.04 LTS). Los pasos pueden variar ligeramente en otras distribuciones.

## Instalación

### Paso 1: Actualizar el sistema

```bash
sudo apt update && sudo apt upgrade -y
```

### Paso 2: Instalar Apache

```bash
sudo apt install apache2 -y
```

**Verificación:** Visita `http://localhost` en tu navegador. Deberías ver la página por defecto de Apache.

### Paso 3: Instalar MariaDB (MySQL)

```bash
sudo apt install mariadb-server -y
sudo mysql_secure_installation
```

### Paso 4: Instalar PHP 8.2 y extensiones

```bash
sudo apt install php8.2 libapache2-mod-php8.2 php8.2-mysql php8.2-gd php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip -y
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

### 2. Configurar el VirtualHost de Apache

Crea un nuevo archivo de configuración:

```bash
sudo nano /etc/apache2/sites-available/etiquetador.conf
```

Pega el siguiente contenido (ajustando si es necesario):

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/etiquetador/public

    <Directory /var/www/etiquetador/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        
        # Habilitar reescritura para el enrutador
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [L]
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/etiquetador_error.log
    CustomLog ${APACHE_LOG_DIR}/etiquetador_access.log combined
</VirtualHost>
```

Habilita el sitio y módulos necesarios:

```bash
sudo a2enmod rewrite
sudo a2dissite 000-default.conf
sudo a2ensite etiquetador.conf
sudo systemctl restart apache2
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

Asegúrate de que los datos de `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` coincidan con los pasos anteriores.

---

## Permisos Finales

Es crucial que el servidor web tenga permisos de escritura en las carpetas de logs o temporales si las hubiera:

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
