# 🏷️ EtiquetadorOSL

![EtiquetadorOSL Header](https://via.placeholder.com/1200x400?text=EtiquetadorOSL+Logo+Header)

**EtiquetadorOSL** es una herramienta *Open Source* diseñada para la gestión y etiquetado eficiente de equipos informáticos (sobremesa y portátiles). Permite documentar especificaciones técnicas y generar etiquetas listas para imprimir, además de ofrecer un panel de control para la gestión de inventario y usuarios.

---

## Características Principales

- **Gestión de Componentes:** Registro detallado de CPU, RAM (DDR2-DDR5), Discos (HDD, SSD, NVMe) y Gráficas.
- **Generación de Etiquetas:** Formateo automático de especificaciones para impresión física.
- **Dashboard Administrativo:** Estadísticas en tiempo real del inventario (Bluetooth, WiFi, tipos de modelos).
- **Seguridad Integrada:** Autenticación de doble factor (2FA) vía correo electrónico.
- **Gestión de Usuarios:** Control total sobre registros, roles y credenciales.

![Funcionalidades Preview](https://via.placeholder.com/800x450?text=Vista+Previa+de+la+Plataforma)

---

## Stack Tecnológico

El proyecto está construido sobre tecnologías robustas y :

- **Frontend:** HTML5, CSS3, JavaScript (Vanilla).
- **Backend:** PHP.
- **Base de Datos:** MariaDB.
- **Servidores Web:** Soporte nativo para **Nginx** y **Apache**.
- **Containerización:** Docker & Docker Compose.

---

## Instalación Rápida

La forma recomendada de desplegar EtiquetadorOSL es mediante **Docker Compose**.

### Prerrequisitos

- Docker y Docker Compose instalados.

### Despliegue con Docker

Ejecuta el perfil que mejor se adapte a tus necesidades de servidor web:

**Para usar Nginx:**

- **HTTPS:** `https://localhost` (Puerto 443)

```bash
docker compose --profile nginx up -d
```

**Para usar Apache:**

- **HTTPS:** `https://localhost:8443`

```bash
docker compose --profile apache up -d
```

> [!TIP]
> **Política de reinicio:** Por defecto, los contenedores usan `unless-stopped`. Para desarrollo, puedes usar `RESTART_POLICY=no`:
>
> ```bash
> RESTART_POLICY=no docker compose --profile apache up -d
> ```
>
> **PowerShell (Windows):** Si usas PowerShell, el comando para definir la variable es:
>
> ```powershell
> $env:RESTART_POLICY="no"; docker compose --profile apache up -d
> ```

---

## Guía de Uso

1. **Acceso:** Entra en la URL correspondiente (localhost:8080 o localhost:8081).
2. **Registro/Login:** Regístrate si es tu primera vez o inicia sesión.
3. **2FA:** Introduce el código enviado a tu correo electrónico.
4. **Etiquetado:** Rellena las características del equipo:
   - Tipo de placa (BIOS/UEFI).
   - CPU, Memoria, Disco y Gráfica.
5. **Administración:** Accede al panel para gestionar el inventario global.

![Dashboard Preview](https://via.placeholder.com/800x450?text=Dashboard+de+Administración)

---

## Administración y Seguridad

El acceso al "Panel administración" requiere credenciales específicas.

> [!WARNING]
> **Credenciales de Administrador por defecto:**
>
> - **Usuario:** admin
> - **Contraseña:** admin123
>
> *¡Por favor, es imperativo cambiar esta contraseña inmediatamente después del primer inicio de sesión!*

---

## Instalación Manual (Sin Docker)

Si prefieres una instalación tradicional en Linux (Ubuntu/Debian):

- 📖 [Guía para Apache](https://github.com/Adriansolier322/EtiquetadorOSL/blob/main/README-Apache.md)
- 📖 [Guía para Nginx](https://github.com/Adriansolier322/EtiquetadorOSL/blob/main/README-nginx.md)

---

## Contribuciones

¡Las contribuciones son lo que hacen a la comunidad open source un lugar increíble! Si quieres contribuir:

1. Abre un **Issue** para discutir cambios importantes.
2. Haz un **Fork** del proyecto.
3. Crea tu rama (`git checkout -b feature/AmazingFeature`).
4. Haz un **Pull Request**.

---

## Licencia

Distribuido bajo la Licencia **GPL-3.0**. Consulta el archivo [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html) para más información.
