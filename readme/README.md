# 📚 Blog Histórico - Documentación General

## 📋 Tabla de Contenidos

1. [Descripción del Proyecto](#descripción-del-proyecto)
2. [Estructura de Archivos](#estructura-de-archivos)
3. [Base de Datos](#base-de-datos)
4. [Funcionalidades Principales](#funcionalidades-principales)
5. [Archivos PHP](#archivos-php)
6. [Estilos y JavaScript](#estilos-y-javascript)
7. [Instalación y Configuración](#instalación-y-configuración)

---

## 📖 Descripción del Proyecto

El Blog Histórico es una aplicación web que permite a los usuarios publicar, leer y gestionar artículos sobre eventos históricos. Incluye un sistema de autenticación, roles de usuario, gestión de contenido e interacciones sociales como los likes.

### 🎯 Características Principales

- **Gestión de Artículos**: Crear, leer, actualizar y eliminar artículos
- **Sistema de Autenticación**: Registro, login y gestión de sesiones
- **Roles de Usuario**: Usuarios regulares y administradores
- **Sistema de Likes**: Interacción con los artículos
- **Panel Administrativo**: Gestión completa del contenido y usuarios
- **Diseño Responsivo**: Interfaz moderna con Bootstrap 5
- **Interactividad**: Animaciones y efectos con JavaScript

---

## 📁 Estructura de Archivos

```
BLOG HISTORIA/
├── index.php              # Página principal
├── articulo.php           # Vista completa de artículos
├── login.php              # Formulario de login
├── register.php           # Formulario de registro
├── crear.php              # Crear nuevos artículos
├── admin.php              # Panel de administración
├── editar.php             # Editar artículos
├── logout.php             # Cerrar sesión
├── css/
│   └── estilos.css        # Estilos personalizados
├── js/
│   └── interacciones.js   # JavaScript interactivo
├── uploads/              # Imágenes de artículos
├── documentación/
│   └── README.md          # Esta documentación
└── bdhistoria.sql        # Estructura de la base de datos
```

---

## 🗄️ Base de Datos

### Estructura General

La base de datos `bdhistoria` contiene 3 tablas principales:

#### 1. **articulos**
Almacena toda la información de los artículos del blog.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT AUTO_INCREMENT | Identificador único |
| titulo | VARCHAR(255) | Título del artículo |
| contenido | TEXT | Contenido completo |
| imagen | VARCHAR(255) | Ruta de la imagen |
| fecha_publicacion | TIMESTAMP | Fecha de publicación |
| likes | INT DEFAULT 0 | Contador de likes |
| categoria | VARCHAR(30) | Categoría del artículo |
| estado | TINYINT DEFAULT 1 | 1=Activo, 0=Inactivo |

#### 2. **usuarios**
Gestiona la información de los usuarios registrados.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(100) | Nombre del usuario |
| email | VARCHAR(150) | Email único del usuario |
| password | VARCHAR(255) | Contraseña almacenada de forma segura |
| rol | ENUM('admin','usuario') | Rol del usuario |

#### 3. **likes**
Registra las interacciones de los usuarios con los artículos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT AUTO_INCREMENT | Identificador único |
| usuario_id | INT | ID del usuario |
| articulo_id | INT | ID del artículo |
| fecha | TIMESTAMP | Fecha de la interacción |

---

## ⚙️ Funcionalidades Principales

### 🔐 Sistema de Autenticación
- **Registro**: Nuevo usuarios con validación de email
- **Login**: Verificación de credenciales de forma segura
- **Sesiones**: Manejo seguro de sesiones PHP
- **Roles**: Diferenciación entre admin y usuario

### 📰 Gestión de Artículos
- **Crear**: Formulario con título, contenido, categoría e imagen
- **Leer**: Vista previa en inicio, vista completa con interacciones
- **Actualizar**: Edición de artículos existentes (solo admin)
- **Eliminar**: Borrado de artículos (solo admin)

### ❤️ Sistema de Interacción
- **Interacciones**: Usuarios registrados pueden interactuar con artículos
- **Contador**: Actualización automática del contador
- **Control**: Evita múltiples interacciones del mismo usuario

### 🛠️ Panel Administrativo
- **Dashboard**: Estadísticas en tiempo real
- **Gestión de Usuarios**: Ver, editar roles y eliminar usuarios
- **Gestión de Artículos**: Activar/desactivar, editar y eliminar
- **Control de Estados**: Publicar/despublicar artículos

---

## 📄 Archivos PHP

### 🏠 index.php
**Propósito**: Página principal con vista previa de artículos.

**Funcionalidades**:
- Muestra artículos activos ordenados por fecha
- Navegación dinámica según estado de sesión
- Cards con información básica de artículos
- Enlaces a lectura completa

**Bloques principales**:
1. Inicialización del entorno
2. Consulta de contenidos publicados
3. Renderizado de HTML con Bootstrap
4. Iteración de contenidos con PHP
5. Navegación contextual

---

### 📄 articulo.php
**Propósito**: Vista completa de un artículo específico.

**Funcionalidades**:
- Muestra artículo completo con imagen
- Sistema de interacciones con artículos
- Validación de usuario
- Metadatos completos del artículo

**Bloques principales**:
1. Validación de ID y obtención de artículo
2. Verificación de interacción del usuario actual
3. Procesamiento de POST para interacciones
4. Renderizado completo del contenido

---

### 🔑 login.php
**Propósito**: Formulario de autenticación de usuarios.

**Funcionalidades**:
- Formulario de login con email y contraseña
- Validación de credenciales
- Creación de sesión de usuario
- Redirección según rol

**Bloques principales**:
1. Procesamiento de formulario POST
2. Consulta de usuario por email
3. Verificación de credenciales
4. Creación de variables de sesión

---

### 📝 register.php
**Propósito**: Registro de nuevos usuarios.

**Funcionalidades**:
- Formulario de registro completo
- Validación de datos (email único, contraseña)
- Almacenamiento seguro de contraseña
- Creación automática de sesión

**Bloques principales**:
1. Procesamiento del formulario
2. Validaciones de entrada
3. Verificación de email existente
4. Inserción en base de datos

---

### ➕ crear.php
**Propósito**: Creación de nuevos artículos.

**Funcionalidades**:
- Formulario completo para artículos
- Subida de imágenes
- Validación de campos
- Guardado en base de datos

**Bloques principales**:
1. Verificación de sesión requerida
2. Procesamiento de formulario
3. Manejo de subida de archivos
4. Inserción en base de datos

---

### 🛠️ admin.php
**Propósito**: Panel de administración completo.

**Funcionalidades**:
- Estadísticas generales
- Gestión de artículos (CRUD)
- Gestión de usuarios (cambio de rol, eliminación)
- Activación/desactivación de contenido

**Bloques principales**:
1. Verificación de privilegios de administración
2. Procesamiento de operaciones de gestión
3. Consultas de contenido y usuarios
4. Presentación de datos administrativos

---

### ✏️ editar.php
**Propósito**: Edición de artículos existentes.

**Funcionalidades**:
- Carga de datos existentes
- Actualización de todos los campos
- Mantenimiento o cambio de imagen
- Control de estado del artículo

**Bloques principales**:
1. Obtención de artículo a editar
2. Procesamiento del formulario
3. Manejo de imagen (mantener/cambiar)
4. Actualización en base de datos

---

### 🚪 logout.php
**Propósito**: Cierre de sesión de usuario.

**Funcionalidades**:
- Destrucción completa de sesión
- Redirección a página principal

---

## 🎨 Estilos y JavaScript

### 📁 css/estilos.css
**Propósito**: Estilos personalizados que complementan Bootstrap.

**Secciones principales**:
1. **Variables CSS**: Definición de colores consistentes
2. **Estilos Base**: Fuentes, fondos, layout general
3. **Hero Sections**: Encabezados con gradientes
4. **Cards y Componentes**: Estilos personalizados
5. **Formularios**: Diseño de inputs y botones
6. **Animaciones**: Transiciones y efectos hover
7. **Responsive**: Media queries para dispositivos

**Características destacadas**:
- Gradientes modernos y consistentes
- Efectos hover sutiles
- Diseño responsivo
- Sombras y bordes redondeados

---

### 📁 js/interacciones.js
**Propósito**: Mejoras interactivas y animaciones.

**Funciones principales**:
1. **Animación de tarjetas**: Fade-in progresivo
2. **Efecto typing**: Animación de texto en título
3. **Parallax**: Efecto en hero section
4. **Validación de formularios**: En tiempo real
5. **Contadores animados**: Para estadísticas
6. **Efecto ripple**: En botones al hacer clic
7. **Partículas flotantes**: Animación decorativa

**Características**:
- Código modular y organizado
- Event listeners eficientes
- Animaciones suaves con CSS
- Mejora progresiva

---

## 🚀 Instalación y Configuración

### 📋 Requisitos Previos
- PHP 8.0 o superior
- MySQL 8.0 o MariaDB
- Servidor web (Apache recomendado)
- Extensión PDO para MySQL habilitada

### ⚙️ Pasos de Instalación

#### 1. Configuración de Base de Datos
```sql
-- Importar el archivo bdhistoria.sql
-- O crear manualmente las tablas:
-- - articulos
-- - usuarios  
-- - likes
```

#### 2. Configuración de la Aplicación
Configurar los parámetros de conexión según tu entorno.

#### 3. Permisos de Directorios
```bash
# Dar permisos de escritura a uploads
chmod 755 uploads/
# O en Windows: Asegurar acceso de escritura
```

#### 4. Configuración del Servidor
- Colocar archivos en document root
- Configurar virtual host si es necesario
- Verificar que mod_rewrite esté activo

### 🔧 Configuración Adicional

#### Personalización
- Modificar colores en `:root` de estilos.css
- Cambiar textos y branding en archivos HTML
- Ajustar categorías según necesidad

---

## 📝 Notas Finales

### 🎯 Mejoras Futuras
- Sistema de comentarios en artículos
- Búsqueda y filtrado avanzado
- Perfiles de usuario personalizados
- Sistema de notificaciones
- Exportación de artículos
- Integración con redes sociales

### 🐛 Troubleshooting Común

#### Problemas de Conexión
- Verificar configuración de conexión
- Confirmar que el servicio de base de datos esté activo
- Revisar permisos de acceso a la base de datos

#### Problemas de Upload
- Verificar permisos del directorio `uploads/`
- Comprobar límites de PHP (upload_max_filesize)
- Validar extensiones permitidas

#### Problemas de Sesión
- Confirmar que `session_start()` esté al inicio
- Verificar configuración de PHP para sesiones
- Limpiar cookies si hay problemas

### 📞 Soporte
Para problemas técnicos:
1. Revisar logs de errores de PHP
2. Verificar configuración del servidor
3. Consultar esta documentación
4. Revisar archivos de ejemplo proporcionados

---

## 📄 Licencia

Este proyecto está desarrollado con fines educativos y puede ser modificado y distribuido según las necesidades del usuario.

---

**Blog Histórico v1.0**  
*Desarrollado con PHP, MySQL, Bootstrap y JavaScript*  
*Documentación completa para desarrollo y mantenimiento*