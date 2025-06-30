# 📚 Retrolector - Biblioteca Digital

**Retrolector** es una plataforma web completa de biblioteca digital desarrollada en Laravel que permite a los usuarios explorar, comprar, prestar y gestionar libros digitales y físicos.

## 🌟 Características Principales

### 👥 Sistema de Usuarios
- **Registro y Autenticación**: Sistema completo de registro y login para usuarios
- **Roles de Usuario**: 
  - **Cliente**: Puede explorar, comprar, prestar y gestionar libros
  - **Administrador**: Gestión completa del sistema
- **Perfiles de Usuario**: Dashboard personalizado con historial y preferencias
- **Sistema de Favoritos**: Los usuarios pueden marcar libros como favoritos

### 📖 Gestión de Libros
- **Catálogo Completo**: Exploración de libros con filtros por categoría y autor
- **Información Detallada**: Sinopsis, autor, categoría, precio, disponibilidad
- **Búsqueda Avanzada**: Búsqueda por título, autor, categoría
- **Imágenes de Portada**: Visualización de portadas de libros

### 💰 Sistema de Compras
- **Compra Física**: Opción para comprar libros físicos con envío
- **Compra Virtual**: Descarga de PDFs de libros digitales
- **Métodos de Pago**: Yape, transferencia bancaria, efectivo
- **Información de Envío**: Gestión de direcciones y costos de envío

### 📚 Sistema de Préstamos
- **Préstamos Digitales**: Lectura de libros en el navegador
- **Lector Digital**: Interfaz de lectura con controles de tema y fuente
- **Gestión de Préstamos**: Historial de préstamos activos y completados
- **Sistema de Devoluciones**: Control automático de fechas de devolución

### 🔔 Sistema de Notificaciones
- **Notificaciones en Tiempo Real**: Alertas sobre préstamos, reservas y novedades
- **Gestión de Notificaciones**: Panel para ver y gestionar notificaciones

### 🌍 Multilingüe y Temas
- **Idiomas**: Español e Inglés con sistema de traducción completo
- **Temas**: Modo claro y oscuro con transiciones suaves
- **Interfaz Adaptativa**: Colores y estilos que se adaptan al tema seleccionado

### 📊 Panel de Administración
- **Gestión de Libros**: CRUD completo para libros
- **Gestión de Usuarios**: Administración de cuentas de usuario
- **Gestión de Autores**: Administración de autores
- **Gestión de Categorías**: Administración de categorías de libros
- **Estadísticas**: Dashboard con métricas del sistema

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templates, CSS3, JavaScript
- **Base de Datos**: MySQL
- **Autenticación**: Laravel Breeze
- **Estilos**: CSS personalizado con variables para temas
- **Interactividad**: JavaScript vanilla y AJAX

## 📋 Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- MySQL 5.7 o superior
- Node.js y NPM (para compilar assets)
- Servidor web (Apache/Nginx)

## 🚀 Instalación

### 1. Clonar el Repositorio
```bash
git clone [url-del-repositorio]
cd biblioteca/retrolector
```

### 2. Instalar Dependencias
```bash
composer install
npm install
```

### 3. Configurar Variables de Entorno
```bash
cp .env.example .env
```

Editar `.env` con tu configuración:
```env
APP_NAME=Retrolector
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=retrolector
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 5. Ejecutar Migraciones
```bash
php artisan migrate
```

### 6. Ejecutar Seeders (Datos de Prueba)
```bash
php artisan db:seed
```

### 7. Compilar Assets
```bash
npm run dev
```

### 8. Crear Enlace Simbólico de Storage
```bash
php artisan storage:link
```

### 9. Configurar Permisos (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

## 👤 Cuentas de Prueba

### Administrador
- **Email**: admin@retrolector.com
- **Contraseña**: admin123

### Usuario Cliente
- **Email**: usuario@retrolector.com
- **Contraseña**: usuario123

## 📁 Estructura del Proyecto

```
retrolector/
├── app/
│   ├── Console/Commands/
│   │   └── MarkOverdueLoans.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── AuthController.php
│   │   │   ├── AutorController.php
│   │   │   ├── CategoriaController.php
│   │   │   ├── CompraController.php
│   │   │   ├── FavoritoController.php
│   │   │   ├── HomeController.php
│   │   │   ├── LibroController.php
│   │   │   ├── NotificacionController.php
│   │   │   ├── PrestamoController.php
│   │   │   ├── ReservaController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── Authenticate.php
│   │       ├── ClienteMiddleware.php
│   │       ├── SetLocale.php
│   │       └── SetTheme.php
│   └── Models/
│       ├── Autor.php
│       ├── Categoria.php
│       ├── Compra.php
│       ├── Favorito.php
│       ├── Libro.php
│       ├── Notificacion.php
│       ├── Prestamo.php
│       ├── Reserva.php
│       └── Usuario.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── books/
│   │   ├── user/
│   │   └── layouts/
│   ├── css/
│   └── js/
└── routes/
    └── web.php
```

## 🎯 Funcionalidades Detalladas

### Sistema de Autenticación
- Registro de usuarios con validación
- Login con email y contraseña
- Middleware de autenticación para rutas protegidas
- Middleware de roles (Admin/Cliente)
- Recuperación de contraseña

### Gestión de Libros
- **Crear**: Formulario completo para agregar nuevos libros
- **Leer**: Vista detallada con información completa
- **Actualizar**: Edición de información de libros
- **Eliminar**: Eliminación segura de libros
- **Búsqueda**: Filtros por título, autor, categoría
- **Paginación**: Navegación por páginas

### Sistema de Compras
- **Tipos de Compra**:
  - Física: Envío a domicilio
  - Virtual: Descarga de PDF
- **Proceso de Compra**:
  1. Selección de tipo de compra
  2. Información de contacto y envío
  3. Método de pago
  4. Confirmación y procesamiento
- **Historial**: Vista de compras realizadas

### Sistema de Préstamos
- **Préstamo Digital**: Lectura en navegador
- **Lector Avanzado**:
  - Controles de fuente (tamaño, familia)
  - Temas de lectura (claro/oscuro)
  - Navegación por capítulos
  - Marcadores de posición
- **Gestión de Préstamos**:
  - Fechas de inicio y fin
  - Estado (activo, completado, vencido)
  - Devoluciones automáticas

### Sistema de Favoritos
- **Agregar/Quitar**: Toggle de favoritos con AJAX
- **Lista de Favoritos**: Vista paginada de libros favoritos
- **Animaciones**: Efectos visuales al agregar/quitar
- **Persistencia**: Guardado en base de datos

### Panel de Administración
- **Dashboard**: Estadísticas generales del sistema
- **Gestión de Usuarios**: Lista, edición, eliminación
- **Gestión de Libros**: CRUD completo
- **Gestión de Autores**: Administración de autores
- **Gestión de Categorías**: Administración de categorías

### Sistema de Notificaciones
- **Tipos de Notificación**:
  - Nuevos libros disponibles
  - Préstamos próximos a vencer
  - Confirmaciones de compra
  - Actualizaciones del sistema
- **Gestión**: Marcar como leídas, eliminar

### Multilingüe
- **Idiomas Soportados**: Español, Inglés
- **Archivos de Traducción**: Organizados por idioma
- **Cambio de Idioma**: Selector en la interfaz
- **Persistencia**: Preferencia guardada en sesión

### Sistema de Temas
- **Temas Disponibles**: Claro, Oscuro
- **Variables CSS**: Colores, fondos, bordes
- **Transiciones**: Cambios suaves entre temas
- **Persistencia**: Tema guardado en sesión

## 🔧 Comandos Útiles

### Desarrollo
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets
npm run dev

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Revertir migraciones
php artisan migrate:rollback

# Ejecutar seeders
php artisan db:seed

# Crear migración
php artisan make:migration nombre_migracion
```

### Artisan
```bash
# Crear controlador
php artisan make:controller NombreController

# Crear modelo
php artisan make:model NombreModelo

# Crear middleware
php artisan make:middleware NombreMiddleware
```

## 🐛 Solución de Problemas

### Problemas Comunes

#### 1. Error de Permisos
```bash
chmod -R 775 storage bootstrap/cache
```

#### 2. Error de Clave de Aplicación
```bash
php artisan key:generate
```

#### 3. Error de Base de Datos
- Verificar credenciales en `.env`
- Ejecutar `php artisan migrate:fresh`

#### 4. Assets No Cargados
```bash
npm install
npm run dev
```

#### 5. Error de Storage
```bash
php artisan storage:link
```

## 📊 Estadísticas del Proyecto

- **Líneas de Código**: ~15,000
- **Controladores**: 13
- **Modelos**: 9
- **Vistas**: 25+
- **Rutas**: 50+
- **Migraciones**: 13
- **Funcionalidades**: 8 módulos principales
- **Idiomas**: 2 (Español, Inglés)
- **Temas**: 2 (Claro, Oscuro)

## 🎨 Características de Diseño

### Interfaz de Usuario
- **Diseño Responsivo**: Adaptable a móviles, tablets y desktop
- **Temas Dinámicos**: Cambio automático entre modo claro y oscuro
- **Animaciones Suaves**: Transiciones y efectos visuales
- **Navegación Intuitiva**: Menús organizados y accesibles

### Experiencia de Usuario
- **Carga Rápida**: Optimización de assets y consultas
- **Feedback Visual**: Confirmaciones y mensajes de estado
- **Accesibilidad**: Contraste adecuado y navegación por teclado
- **Personalización**: Preferencias guardadas por usuario

## 📱 Funcionalidades Móviles

### Optimización Móvil
- **Diseño Adaptativo**: Interfaz optimizada para pantallas táctiles
- **Navegación Touch**: Botones y enlaces optimizados para móvil
- **Lector Móvil**: Experiencia de lectura optimizada para dispositivos móviles
- **Gestos**: Navegación por gestos en el lector digital

## 🔒 Seguridad

### Medidas de Seguridad Implementadas
- **Autenticación Segura**: Hashing de contraseñas con bcrypt
- **Middleware de Protección**: Rutas protegidas por roles
- **Validación de Datos**: Validación en frontend y backend
- **CSRF Protection**: Protección contra ataques CSRF
- **Sanitización**: Limpieza de datos de entrada

## 📈 Escalabilidad

### Arquitectura Escalable
- **MVC Pattern**: Separación clara de responsabilidades
- **Base de Datos Optimizada**: Índices y consultas eficientes
- **Caché**: Sistema de caché para mejorar rendimiento
- **Modular**: Estructura modular para fácil expansión

## 🎯 Casos de Uso

### Para Usuarios Clientes
1. **Registro y Login**: Crear cuenta y acceder al sistema
2. **Explorar Catálogo**: Buscar y filtrar libros por categorías
3. **Ver Detalles**: Información completa de libros
4. **Agregar Favoritos**: Marcar libros como favoritos
5. **Comprar Libros**: Proceso de compra física o virtual
6. **Prestar Libros**: Lectura digital en el navegador
7. **Gestionar Perfil**: Ver historial y preferencias
8. **Recibir Notificaciones**: Alertas sobre novedades

### Para Administradores
1. **Dashboard**: Estadísticas y métricas del sistema
2. **Gestión de Libros**: Agregar, editar, eliminar libros
3. **Gestión de Usuarios**: Administrar cuentas de usuarios
4. **Gestión de Autores**: Mantener base de datos de autores
5. **Gestión de Categorías**: Organizar libros por categorías
6. **Monitoreo**: Supervisar préstamos y compras

## 🗄️ Base de Datos

### Estructura de la Base de Datos

La aplicación utiliza MySQL como sistema de gestión de base de datos, con una estructura optimizada para una biblioteca digital.

#### Tablas Principales

##### 👥 **usuarios**
- **Propósito**: Almacena información de todos los usuarios del sistema
- **Campos Clave**:
  - `id`: Identificador único del usuario
  - `nombre`: Nombre completo del usuario
  - `email`: Correo electrónico (único)
  - `password`: Contraseña hasheada
  - `rol`: Tipo de usuario (admin/cliente)
  - `direccion`: Dirección de envío
  - `telefono`: Número de contacto
  - `fecha_registro`: Fecha de creación de la cuenta
- **Funcionalidad**: Autenticación, perfiles de usuario, gestión de roles

##### 📚 **libros**
- **Propósito**: Catálogo completo de libros disponibles
- **Campos Clave**:
  - `id`: Identificador único del libro
  - `titulo`: Título del libro
  - `autor_id`: Referencia al autor (clave foránea)
  - `categoria_id`: Referencia a la categoría (clave foránea)
  - `sinopsis`: Descripción del libro
  - `precio`: Precio en soles peruanos
  - `stock`: Cantidad disponible
  - `imagen`: Ruta de la imagen de portada
  - `pdf_path`: Ruta del archivo PDF (para libros digitales)
  - `estado`: Estado del libro (disponible/agotado)
- **Funcionalidad**: Catálogo, búsquedas, gestión de inventario

##### 👨‍💼 **autors**
- **Propósito**: Información de autores de libros
- **Campos Clave**:
  - `id`: Identificador único del autor
  - `nombre`: Nombre completo del autor
  - `biografia`: Información biográfica
  - `nacionalidad`: País de origen
- **Funcionalidad**: Organización de libros por autor, información biográfica

##### 📂 **categorias**
- **Propósito**: Clasificación de libros por género/tema
- **Campos Clave**:
  - `id`: Identificador único de la categoría
  - `nombre`: Nombre de la categoría
  - `descripcion`: Descripción de la categoría
- **Funcionalidad**: Filtrado de libros, organización del catálogo

##### 💰 **compras**
- **Propósito**: Registro de todas las compras realizadas
- **Campos Clave**:
  - `id`: Identificador único de la compra
  - `usuario_id`: Usuario que realizó la compra
  - `libro_id`: Libro comprado
  - `tipo_compra`: Física o virtual
  - `precio`: Precio pagado
  - `metodo_pago`: Método de pago utilizado
  - `estado`: Estado de la compra
  - `fecha_compra`: Fecha de la transacción
  - `direccion_envio`: Dirección de envío (para compras físicas)
- **Funcionalidad**: Historial de compras, seguimiento de transacciones

##### 📖 **prestamos**
- **Propósito**: Gestión de préstamos digitales de libros
- **Campos Clave**:
  - `id`: Identificador único del préstamo
  - `usuario_id`: Usuario que solicita el préstamo
  - `libro_id`: Libro prestado
  - `fecha_inicio`: Fecha de inicio del préstamo
  - `fecha_fin`: Fecha de vencimiento
  - `estado`: Estado del préstamo (activo/completado/vencido)
  - `progreso_lectura`: Porcentaje de lectura completado
- **Funcionalidad**: Control de préstamos, fechas de devolución, progreso de lectura

##### ❤️ **favoritos**
- **Propósito**: Libros marcados como favoritos por usuarios
- **Campos Clave**:
  - `id`: Identificador único del favorito
  - `usuario_id`: Usuario que marcó como favorito
  - `libro_id`: Libro marcado como favorito
  - `fecha_agregado`: Fecha cuando se agregó a favoritos
- **Funcionalidad**: Lista de favoritos, recomendaciones personalizadas

##### 🔔 **notificacions**
- **Propósito**: Sistema de notificaciones para usuarios
- **Campos Clave**:
  - `id`: Identificador único de la notificación
  - `usuario_id`: Usuario destinatario
  - `titulo`: Título de la notificación
  - `mensaje`: Contenido de la notificación
  - `tipo`: Tipo de notificación
  - `leida`: Estado de lectura
  - `fecha_creacion`: Fecha de creación
- **Funcionalidad**: Alertas, notificaciones en tiempo real

##### 📅 **reservas**
- **Propósito**: Sistema de reservas para libros no disponibles
- **Campos Clave**:
  - `id`: Identificador único de la reserva
  - `usuario_id`: Usuario que realiza la reserva
  - `libro_id`: Libro reservado
  - `fecha_reserva`: Fecha de la reserva
  - `estado`: Estado de la reserva
- **Funcionalidad**: Lista de espera, notificaciones de disponibilidad

### Relaciones entre Tablas

#### Relaciones Principales
- **usuarios** ↔ **compras**: Un usuario puede tener múltiples compras
- **usuarios** ↔ **prestamos**: Un usuario puede tener múltiples préstamos
- **usuarios** ↔ **favoritos**: Un usuario puede tener múltiples favoritos
- **usuarios** ↔ **notificacions**: Un usuario puede tener múltiples notificaciones
- **libros** ↔ **autors**: Un autor puede tener múltiples libros
- **libros** ↔ **categorias**: Una categoría puede tener múltiples libros
- **libros** ↔ **compras**: Un libro puede ser comprado múltiples veces
- **libros** ↔ **prestamos**: Un libro puede ser prestado múltiples veces

#### Integridad Referencial
- **Claves Foráneas**: Todas las relaciones están protegidas con claves foráneas
- **Cascada**: Eliminación en cascada para mantener integridad
- **Índices**: Optimización de consultas con índices en campos clave

### Funcionalidades de la Base de Datos

#### 🔍 **Búsquedas Optimizadas**
- **Búsqueda por Título**: Índices en campos de texto
- **Filtros por Categoría**: Consultas optimizadas por categoría
- **Búsqueda por Autor**: Relaciones eficientes con tabla de autores
- **Búsqueda Avanzada**: Combinación de múltiples criterios

#### 📊 **Estadísticas y Reportes**
- **Libros Más Populares**: Consultas agregadas para estadísticas
- **Usuarios Más Activos**: Análisis de actividad de usuarios
- **Ventas por Período**: Reportes de ventas temporales
- **Préstamos Vencidos**: Consultas para gestión de devoluciones

#### 🔄 **Transacciones**
- **Compras**: Transacciones atómicas para garantizar integridad
- **Préstamos**: Control de concurrencia para libros únicos
- **Favoritos**: Operaciones seguras de agregar/quitar

#### 🛡️ **Seguridad de Datos**
- **Hashing de Contraseñas**: Almacenamiento seguro de credenciales
- **Validación de Datos**: Restricciones a nivel de base de datos
- **Backup Automático**: Sistema de respaldo de datos
- **Logs de Actividad**: Registro de acciones importantes

### Migraciones y Seeders

#### 📋 **Migraciones**
- **13 Migraciones**: Estructura completa de la base de datos
- **Versionado**: Control de versiones de esquema
- **Rollback**: Capacidad de revertir cambios
- **Integridad**: Validación de estructura en cada migración

#### 🌱 **Seeders**
- **Datos de Prueba**: Información inicial para desarrollo
- **Usuarios de Prueba**: Cuentas de administrador y cliente
- **Libros de Ejemplo**: Catálogo inicial con libros de muestra
- **Autores y Categorías**: Datos de referencia completos

### Optimización y Rendimiento

#### ⚡ **Optimizaciones Implementadas**
- **Índices Estratégicos**: En campos de búsqueda frecuente
- **Consultas Optimizadas**: Uso de Eloquent ORM eficientemente
- **Caché de Consultas**: Reducción de carga en base de datos
- **Paginación**: Carga progresiva de resultados

#### 📈 **Escalabilidad**
- **Arquitectura Modular**: Fácil expansión de funcionalidades
- **Normalización**: Estructura normalizada para evitar redundancia
- **Particionamiento**: Preparado para grandes volúmenes de datos
- **Replicación**: Preparado para configuración master-slave

