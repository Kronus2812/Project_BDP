# Sistema BDP - Control de Acceso Aeroportuario

## Descripción

Sistema de gestión y control de acceso para personal de seguridad de Avianca en aeropuertos. Permite registrar vuelos, controlar entradas y salidas de personal, y consultar registros históricos mediante dashboards interactivos.

**Contexto:** Desarrollado para Securitas como herramienta de control operacional del personal asignado a vuelos de Avianca.

**Enfoque de aprendizaje:** Backend con PHP, bases de datos MySQL, autenticación de usuarios y arquitectura MVC básica.

---

## Funcionalidades Principales

### Sistema de Autenticación
- Login seguro con hashing de contraseñas (password_hash)
- Sesión persistente con validación por roles
- Logout y control de acceso a páginas protegidas

### Módulo de Vuelos

- Registro de vuelos con información detallada:
  - Número de vuelo
  - Origen y destino
  - Horarios de salida y llegada
  - Estado del vuelo
  - Aeronave asignada
- Dashboard de consulta de vuelos registrados
- Búsqueda y filtrado de vuelos

### Control de Entradas

- Registro de ingreso de personal al área segura
- Captura de datos:
  - Información del empleado
  - Hora de entrada
  - Vuelo asignado
  - Fotografía (carga de imagen)
- Dashboard con historial de entradas
- Búsqueda por fecha, empleado o vuelo

### Control de Salidas

- Registro de egreso de personal
- Vinculación automática con entrada existente
- Cálculo de tiempo total en instalaciones
- Dashboard con historial de salidas
- Reportes de personal actualmente en instalaciones

### Dashboards Administrativos

- Vista general de operaciones del día
- Estadísticas de entradas/salidas
- Personal en instalaciones en tiempo real
- Historial de vuelos y asignaciones

---

## Arquitectura Técnica

### Stack Backend

**PHP 7.4+**
- Procesamiento de formularios
- Lógica de negocio
- Interacción con base de datos
- Manejo de sesiones

**MySQL**
- Almacenamiento relacional
- Tablas principales:
  - `usuarios` (autenticación)
  - `vuelos` (información de vuelos)
  - `entradas` (registros de acceso)
  - `salidas` (registros de egreso)
  - `personal` (datos de empleados)

### Stack Frontend

**HTML5 + CSS3**
- Formularios semánticos
- Diseño responsive
- Múltiples hojas de estilo por módulo

**JavaScript Vanilla**
- Validación de formularios client-side
- Interactividad en dashboards
- Carga dinámica de imágenes

### Estructura de Archivos

```
Project_BDP/
├── Backend/
│   ├── conexion.php           # Configuración BD
│   └── queries/                # Consultas SQL
├── images/                   # Imágenes de personal
├── registro-turnos/          # Módulo adicional
├── login.php                 # Autenticación
├── dashboard.php             # Dashboard principal
├── vuelos.html               # Registro de vuelos
├── entrada.html              # Registro de entradas
├── salida.html               # Registro de salidas
├── registrar_vuelo.php       # Procesa vuelos
├── registrar_entrada.php     # Procesa entradas
├── registrar_salida.php      # Procesa salidas
├── *_dashboard.php           # Vistas de consulta
└── *.css                     # Estilos por módulo
```

---

## Instalación y Configuración

### Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP: mysqli, gd (para imágenes)

### Instalación Local

1. **Clonar repositorio**

```bash
git clone https://github.com/Kronus2812/Project_BDP.git
cd Project_BDP
```

2. **Configurar base de datos**

```sql
CREATE DATABASE bdp_system;
USE bdp_system;

-- Importar estructura de tablas
SOURCE Backend/database.sql;
```

3. **Configurar conexión**

Editar `Backend/conexion.php`:

```php
<?php
$host = 'localhost';
$usuario = 'tu_usuario';
$password = 'tu_password';
$base_datos = 'bdp_system';

$conexion = mysqli_connect($host, $usuario, $password, $base_datos);
?>
```

4. **Crear usuario administrador**

Ejecutar `generar_hash.php` para crear hash de contraseña, luego:

```sql
INSERT INTO usuarios (username, password, rol) 
VALUES ('admin', '$2y$10$hash_generado', 'admin');
```

5. **Configurar servidor**

Apuntar document root a la carpeta del proyecto o usar servidor embebido:

```bash
php -S localhost:8000
```

6. **Acceder**

Abrir navegador: `http://localhost:8000/login.php`

---

## Uso del Sistema

### Flujo de Trabajo Típico

1. **Login**
   - Ingresar credenciales en `login.php`
   - Sistema valida y crea sesión

2. **Registrar Vuelo**
   - Acceder a `vuelos.html`
   - Completar formulario con datos del vuelo
   - Sistema guarda y asigna ID único

3. **Registrar Entrada de Personal**
   - Acceder a `entrada.html`
   - Seleccionar vuelo asignado
   - Ingresar datos del empleado
   - Cargar fotografía (opcional)
   - Sistema registra hora automáticamente

4. **Registrar Salida**
   - Acceder a `salida.html`
   - Buscar entrada activa del empleado
   - Confirmar salida
   - Sistema calcula tiempo total

5. **Consultar Dashboards**
   - `dashboard.php`: Vista general
   - `vuelos_dashboard_completo.php`: Vuelos registrados
   - `entradas_dashboard.php`: Historial de accesos
   - `salidas_dashboard.php`: Historial de egresos

---

## Seguridad Implementada

### Autenticación y Sesiones

- Hashing de contraseñas con `password_hash()` (bcrypt)
- Validación de sesión en cada página protegida
- Tokens CSRF (recomendado implementar)

### Base de Datos

- Prepared statements para prevenir SQL injection
- Sanitización de inputs con `mysqli_real_escape_string()`
- Validación de tipos de datos

### Archivos

- Validación de tipo y tamaño de imágenes
- Almacenamiento fuera de webroot (recomendado)
- Nombres de archivo sanitizados

### Mejoras Recomendadas

- Implementar HTTPS en producción
- Agregar rate limiting en login
- Implementar logs de auditoría
- Añadir tokens CSRF en formularios
- Usar PDO en lugar de mysqli

---

## Modelo de Base de Datos

### Tabla: usuarios

```sql
id INT PRIMARY KEY AUTO_INCREMENT
username VARCHAR(50) UNIQUE
password VARCHAR(255)  -- Hash bcrypt
rol ENUM('admin', 'operador')
creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
```

### Tabla: vuelos

```sql
id INT PRIMARY KEY AUTO_INCREMENT
numero_vuelo VARCHAR(20)
origen VARCHAR(100)
destino VARCHAR(100)
hora_salida DATETIME
hora_llegada DATETIME
estado ENUM('programado', 'en_vuelo', 'aterrizado', 'cancelado')
aeronave VARCHAR(50)
registrado_por INT  -- FK a usuarios
registrado_en TIMESTAMP
```

### Tabla: entradas

```sql
id INT PRIMARY KEY AUTO_INCREMENT
vuelo_id INT  -- FK a vuelos
empleado_nombre VARCHAR(100)
empleado_cedula VARCHAR(20)
hora_entrada DATETIME
fotografia VARCHAR(255)  -- Ruta archivo
observaciones TEXT
registrado_por INT  -- FK a usuarios
```

### Tabla: salidas

```sql
id INT PRIMARY KEY AUTO_INCREMENT
entrada_id INT  -- FK a entradas
hora_salida DATETIME
tiempo_total INT  -- Minutos
observaciones TEXT
registrado_por INT  -- FK a usuarios
```

---

## Casos de Uso

### Escenario 1: Turno de Mañana

El supervisor de seguridad inicia sesión a las 5:00 AM. Registra 3 vuelos programados para la mañana. A medida que el personal llega, registra sus entradas vinculadas al vuelo correspondiente. El dashboard muestra en tiempo real quién está en instalaciones.

### Escenario 2: Auditoría Mensual

El gerente de operaciones necesita un reporte de actividad del mes. Accede a los dashboards, filtra por rango de fechas, y exporta los datos para análisis. Puede ver patrones de asignación, tiempos promedio de permanencia, y vuelos más frecuentes.

### Escenario 3: Verificación de Acceso

Un empleado reporta que no se registró su salida del día anterior. El operador busca en el dashboard de entradas, confirma que hay una entrada sin salida asociada, y la completa manualmente con la hora aproximada basada en el vuelo.

---

## Roadmap y Mejoras Futuras

### Corto Plazo

- Exportación de reportes a PDF/Excel
- Búsqueda avanzada con múltiples filtros
- Notificaciones de personal sin salida registrada
- Dashboard con gráficos estadísticos

### Mediano Plazo

- API REST para integración con otros sistemas
- App móvil para registro rápido
- Sistema de alertas automáticas
- Integración con cámaras de seguridad

### Largo Plazo

- Migración a framework moderno (Laravel/Symfony)
- Reconocimiento facial automático
- Módulo de nómina integrado
- Analytics predictivo de asignaciones

---

## Aprendizajes del Proyecto

### Habilidades Técnicas Desarrolladas

- Diseño de esquemas relacionales
- Implementación de autenticación segura
- Manejo de archivos y carga de imágenes
- Arquitectura MVC básica
- Queries SQL complejas con JOINs
- Validación de datos front y backend

### Desafíos Resueltos

- Vinculación correcta entre entradas y salidas
- Manejo de zonas horarias en registros
- Prevención de duplicados en registros
- Optimización de queries para dashboards
- Gestión de sesiones en múltiples páginas

---

## Contribución

Este es un proyecto educativo. Si encuentras bugs o tienes sugerencias:

1. Fork el repositorio
2. Crea un branch (`git checkout -b feature/mejora`)
3. Commit tus cambios (`git commit -m 'Agregar feature'`)
4. Push al branch (`git push origin feature/mejora`)
5. Abre un Pull Request

---

## Stack Tecnológico

- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3
- JavaScript Vanilla
- Apache/Nginx

---

## Desarrollador

**Kronus2812**

Stack: Frontend, Backend, Python, JavaScript, SQL, PHP, React, CSS, HTML

Repositorio: [github.com/Kronus2812/Project_BDP](https://github.com/Kronus2812/Project_BDP)

---

## Licencia

MIT License - Proyecto educativo para propósitos de aprendizaje.
