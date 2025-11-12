# Sistema de Gestión de Usuarios y Pedidos - Laravel Query Builder y ORM

## Descripción del Proyecto

Este proyecto es un sistema de gestión de usuarios y pedidos desarrollado en Laravel, diseñado para demostrar el uso avanzado de **Query Builder** y **Eloquent ORM** para realizar consultas SQL complejas. El sistema implementa una arquitectura MVC estándar de Laravel con énfasis en la capa de acceso a datos, permitiendo realizar operaciones como filtrado, agregación, ordenamiento, joins y agrupación de datos.

### Objetivos

- Demostrar el uso de Query Builder y Eloquent ORM en Laravel
- Implementar consultas SQL avanzadas (JOIN, WHERE, ORDER BY, GROUP BY, agregaciones)
- Establecer relaciones entre modelos (hasMany, belongsTo)
- Aplicar buenas prácticas en el desarrollo con Laravel
- Proporcionar ejemplos prácticos de consultas comunes en aplicaciones web

---

## Requisitos del Sistema

Para ejecutar este proyecto, necesitas tener instalado:

- **PHP**: 8.1 o superior
- **Composer**: Gestor de dependencias de PHP
- **MySQL**: 8.0 o superior (o MariaDB equivalente)
- **Servidor Web**: Apache, Nginx, o el servidor integrado de PHP
- **Git**: Para clonar el repositorio

### Extensiones PHP Requeridas

- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath

---

## Instalación

Sigue estos pasos para instalar y configurar el proyecto en tu entorno local:

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/laravel-query-builder-orm.git
cd laravel-query-builder-orm
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar Variables de Entorno

Copia el archivo de ejemplo `.env.example` y renómbralo a `.env`:

```bash
copy .env.example .env
```

Edita el archivo `.env` y configura los datos de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_querybuilder
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

### 4. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 5. Crear la Base de Datos

Crea una base de datos MySQL con el nombre especificado en tu archivo `.env`:

```sql
CREATE DATABASE laravel_querybuilder CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar Migraciones

```bash
php artisan migrate
```

### 7. Ejecutar Seeders (Datos de Prueba)

```bash
php artisan db:seed
```

O ejecutar seeders específicos:

```bash
php artisan db:seed --class=UsuarioSeeder
php artisan db:seed --class=PedidoSeeder
```

### 8. Iniciar el Servidor de Desarrollo

```bash
php artisan serve
```

El proyecto estará disponible en: `http://localhost:8000`

---

## Estructura de la Base de Datos

### Diagrama de Relaciones

```
┌─────────────────────┐         ┌─────────────────────┐
│      usuarios       │         │       pedidos       │
├─────────────────────┤         ├─────────────────────┤
│ id (PK)             │◄────────│ id (PK)             │
│ nombre              │    1:N  │ producto            │
│ correo (UNIQUE)     │         │ cantidad            │
│ telefono            │         │ total               │
│ created_at          │         │ id_usuario (FK)     │
│ updated_at          │         │ created_at          │
└─────────────────────┘         │ updated_at          │
                                └─────────────────────┘
```

### Tabla: usuarios

| Campo      | Tipo         | Restricciones | Descripción                    |
|------------|--------------|---------------|--------------------------------|
| id         | BIGINT       | PK, AUTO_INC  | Identificador único            |
| nombre     | VARCHAR(255) | NOT NULL      | Nombre completo del usuario    |
| correo     | VARCHAR(255) | NOT NULL, UNIQUE | Correo electrónico único    |
| telefono   | VARCHAR(20)  | NULLABLE      | Número de teléfono             |
| created_at | TIMESTAMP    | NULLABLE      | Fecha de creación              |
| updated_at | TIMESTAMP    | NULLABLE      | Fecha de última actualización  |

### Tabla: pedidos

| Campo      | Tipo          | Restricciones | Descripción                    |
|------------|---------------|---------------|--------------------------------|
| id         | BIGINT        | PK, AUTO_INC  | Identificador único            |
| producto   | VARCHAR(255)  | NOT NULL      | Nombre del producto            |
| cantidad   | INTEGER       | NOT NULL      | Cantidad de productos          |
| total      | DECIMAL(10,2) | NOT NULL      | Total del pedido               |
| id_usuario | BIGINT        | FK, NOT NULL  | Referencia al usuario          |
| created_at | TIMESTAMP     | NULLABLE      | Fecha de creación              |
| updated_at | TIMESTAMP     | NULLABLE      | Fecha de última actualización  |

**Relación**: Un usuario puede tener muchos pedidos (1:N). La tabla `pedidos` tiene una clave foránea `id_usuario` que referencia a `usuarios.id` con `ON DELETE CASCADE`.

---

## Consultas Implementadas

El sistema incluye 9 consultas SQL avanzadas implementadas en el controlador `ConsultasController`. Cada consulta demuestra diferentes técnicas de Query Builder y Eloquent ORM.

### 1. Pedidos del Usuario con ID 2

**Descripción**: Recupera todos los pedidos asociados al usuario con ID 2.

**Técnica**: Eloquent ORM con `where()`

**URL**: `GET /consultas/pedidos-usuario-2`

**Ejemplo de Salida**:
```json
[
  {
    "id": 1,
    "producto": "Laptop Dell",
    "cantidad": 1,
    "total": "250.00",
    "id_usuario": 2,
    "created_at": "2024-11-12T10:30:00.000000Z",
    "updated_at": "2024-11-12T10:30:00.000000Z"
  },
  {
    "id": 2,
    "producto": "Mouse Logitech",
    "cantidad": 2,
    "total": "50.00",
    "id_usuario": 2,
    "created_at": "2024-11-12T10:31:00.000000Z",
    "updated_at": "2024-11-12T10:31:00.000000Z"
  }
]
```

---

### 2. Pedidos con Información de Usuarios (JOIN)

**Descripción**: Muestra todos los pedidos con información completa de los usuarios asociados mediante un JOIN.

**Técnica**: Query Builder con `join()` y `select()`

**URL**: `GET /consultas/pedidos-con-usuarios`

**Ejemplo de Salida**:
```json
[
  {
    "producto": "Laptop Dell",
    "cantidad": 1,
    "total": "250.00",
    "nombre_usuario": "María López",
    "correo_usuario": "maria@example.com"
  },
  {
    "producto": "Mouse Logitech",
    "cantidad": 2,
    "total": "50.00",
    "nombre_usuario": "María López",
    "correo_usuario": "maria@example.com"
  }
]
```

---

### 3. Pedidos entre $100 y $250

**Descripción**: Filtra pedidos cuyo total se encuentra entre $100 y $250 (inclusive).

**Técnica**: Eloquent ORM con `whereBetween()`

**URL**: `GET /consultas/pedidos-rango-precio`

**Ejemplo de Salida**:
```json
[
  {
    "id": 1,
    "producto": "Laptop Dell",
    "cantidad": 1,
    "total": "250.00",
    "id_usuario": 2,
    "created_at": "2024-11-12T10:30:00.000000Z",
    "updated_at": "2024-11-12T10:30:00.000000Z"
  },
  {
    "id": 3,
    "producto": "Teclado Mecánico",
    "cantidad": 1,
    "total": "150.00",
    "id_usuario": 1,
    "created_at": "2024-11-12T10:32:00.000000Z",
    "updated_at": "2024-11-12T10:32:00.000000Z"
  }
]
```

---

### 4. Usuarios que Comienzan con "R"

**Descripción**: Busca todos los usuarios cuyo nombre comienza con la letra "R".

**Técnica**: Eloquent ORM con operador `LIKE`

**URL**: `GET /consultas/usuarios-con-r`

**Ejemplo de Salida**:
```json
[
  {
    "id": 1,
    "nombre": "Roberto García",
    "correo": "roberto@example.com",
    "telefono": "2452-0101",
    "created_at": "2024-11-12T10:25:00.000000Z",
    "updated_at": "2024-11-12T10:25:00.000000Z"
  },
  {
    "id": 5,
    "nombre": "Ricardo Fernández",
    "correo": "ricardo@example.com",
    "telefono": "2147-0105",
    "created_at": "2024-11-12T10:29:00.000000Z",
    "updated_at": "2024-11-12T10:29:00.000000Z"
  }
]
```

---

### 5. Contar Pedidos del Usuario con ID 5

**Descripción**: Cuenta el número total de pedidos realizados por el usuario con ID 5.

**Técnica**: Eloquent ORM con función de agregación `count()`

**URL**: `GET /consultas/contar-pedidos-usuario-5`

**Ejemplo de Salida**:
```json
{
  "usuario_id": 5,
  "total_pedidos": 1
}
```

---

### 6. Pedidos Ordenados por Total Descendente

**Descripción**: Lista todos los pedidos con información de usuarios, ordenados de mayor a menor precio.

**Técnica**: Eloquent ORM con `with()` (eager loading) y `orderBy()`

**URL**: `GET /consultas/pedidos-ordenados-desc`

**Ejemplo de Salida**:
```json
[
  {
    "id": 4,
    "producto": "Monitor Samsung",
    "cantidad": 1,
    "total": "300.00",
    "id_usuario": 5,
    "created_at": "2024-11-12T10:33:00.000000Z",
    "updated_at": "2024-11-12T10:33:00.000000Z",
    "usuario": {
      "id": 5,
      "nombre": "Ricardo Fernández",
      "correo": "ricardo@example.com",
      "telefono": "2147-0105",
      "created_at": "2024-11-12T10:29:00.000000Z",
      "updated_at": "2024-11-12T10:29:00.000000Z"
    }
  },
  {
    "id": 1,
    "producto": "Laptop Dell",
    "cantidad": 1,
    "total": "250.00",
    "id_usuario": 2,
    "created_at": "2024-11-12T10:30:00.000000Z",
    "updated_at": "2024-11-12T10:30:00.000000Z",
    "usuario": {
      "id": 2,
      "nombre": "María López",
      "correo": "maria@example.com",
      "telefono": "7458-0102",
      "created_at": "2024-11-12T10:26:00.000000Z",
      "updated_at": "2024-11-12T10:26:00.000000Z"
    }
  }
]
```

---

### 7. Suma Total de Todos los Pedidos

**Descripción**: Calcula el valor total de todos los pedidos en el sistema.

**Técnica**: Eloquent ORM con función de agregación `sum()`

**URL**: `GET /consultas/suma-total-pedidos`

**Ejemplo de Salida**:
```json
{
  "suma_total": "1045.00",
  "mensaje": "Suma total de todos los pedidos en el sistema"
}
```

---

### 8. Pedido Más Económico con Usuario

**Descripción**: Encuentra el pedido con el valor total más bajo, incluyendo información del usuario.

**Técnica**: Eloquent ORM con `with()`, `orderBy()` y `first()`

**URL**: `GET /consultas/pedido-mas-economico`

**Ejemplo de Salida**:
```json
{
  "id": 2,
  "producto": "Mouse Logitech",
  "cantidad": 2,
  "total": "50.00",
  "id_usuario": 2,
  "created_at": "2024-11-12T10:31:00.000000Z",
  "updated_at": "2024-11-12T10:31:00.000000Z",
  "usuario": {
    "id": 2,
    "nombre": "María López",
    "correo": "maria@example.com",
    "telefono": "7458-0102",
    "created_at": "2024-11-12T10:26:00.000000Z",
    "updated_at": "2024-11-12T10:26:00.000000Z"
  }
}
```

---

### 9. Pedidos Agrupados por Usuario

**Descripción**: Agrupa todos los pedidos organizados por usuario, mostrando el nombre del usuario y sus pedidos.

**Técnica**: Query Builder con `join()`, `select()`, `orderBy()` y `groupBy()` de colecciones

**URL**: `GET /consultas/pedidos-agrupados`

**Ejemplo de Salida**:
```json
{
  "María López": [
    {
      "nombre": "María López",
      "producto": "Laptop Dell",
      "cantidad": 1,
      "total": "250.00"
    },
    {
      "nombre": "María López",
      "producto": "Mouse Logitech",
      "cantidad": 2,
      "total": "50.00"
    },
    {
      "nombre": "María López",
      "producto": "Auriculares Bluetooth",
      "cantidad": 1,
      "total": "120.00"
    }
  ],
  "Ricardo Fernández": [
    {
      "nombre": "Ricardo Fernández",
      "producto": "Monitor Samsung",
      "cantidad": 1,
      "total": "300.00"
    }
  ]
}
```

---

## Índice de Consultas

Para ver todas las consultas disponibles con sus URLs, accede a:

**URL**: `GET /consultas`

**Ejemplo de Salida**:
```json
{
  "mensaje": "Sistema de Consultas - Laravel Query Builder y ORM",
  "total_consultas": 9,
  "consultas": [
    {
      "id": 1,
      "nombre": "Pedidos del usuario con ID 2",
      "descripcion": "Recupera todos los pedidos asociados al usuario con ID 2",
      "url": "http://localhost:8000/consultas/pedidos-usuario-2"
    },
    {
      "id": 2,
      "nombre": "Pedidos con información de usuarios",
      "descripcion": "Muestra pedidos con datos completos de los usuarios (JOIN)",
      "url": "http://localhost:8000/consultas/pedidos-con-usuarios"
    }
  ]
}
```

---

## Tecnologías Utilizadas

- **Laravel**: 11.x - Framework PHP para desarrollo web
- **PHP**: 8.1+ - Lenguaje de programación
- **MySQL**: 8.0+ - Sistema de gestión de base de datos
- **Composer**: Gestor de dependencias de PHP
- **Eloquent ORM**: Sistema de mapeo objeto-relacional de Laravel
- **Query Builder**: Constructor de consultas SQL de Laravel

### Características de Laravel Utilizadas

- **Migraciones**: Para definir la estructura de la base de datos
- **Seeders**: Para insertar datos de prueba
- **Modelos Eloquent**: Para representar entidades (Usuario, Pedido)
- **Relaciones**: hasMany, belongsTo
- **Controladores**: Para organizar la lógica de consultas
- **Rutas**: Para definir endpoints de la API
- **Query Builder**: Para consultas SQL complejas
- **Eager Loading**: Para optimizar consultas con relaciones

---

## Estructura del Proyecto

```
laravel-query-builder-orm/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ConsultasController.php    # Controlador con todas las consultas
│   └── Models/
│       ├── Usuario.php                    # Modelo Usuario con relación hasMany
│       └── Pedido.php                     # Modelo Pedido con relación belongsTo
├── database/
│   ├── migrations/
│   │   ├── 2025_11_12_222543_create_usuarios_table.php
│   │   └── 2025_11_12_222554_create_pedidos_table.php
│   └── seeders/
│       ├── UsuarioSeeder.php              # Seeder con 5 usuarios
│       └── PedidoSeeder.php               # Seeder con 7 pedidos
├── routes/
│   └── web.php                            # Definición de rutas
├── .env                                   # Configuración de entorno
├── .env.example                           # Ejemplo de configuración
└── README.md                              # Este archivo
```

---

## Uso del Sistema

### Probar las Consultas

Puedes probar las consultas de varias formas:

#### 1. Navegador Web

Simplemente abre tu navegador y visita las URLs:

```
http://localhost:8000/consultas
http://localhost:8000/consultas/pedidos-usuario-2
http://localhost:8000/consultas/pedidos-con-usuarios
```

#### 2. Postman o Thunder Client

Importa las siguientes URLs como colección:

- GET `http://localhost:8000/consultas`
- GET `http://localhost:8000/consultas/pedidos-usuario-2`
- GET `http://localhost:8000/consultas/pedidos-con-usuarios`
- GET `http://localhost:8000/consultas/pedidos-rango-precio`
- GET `http://localhost:8000/consultas/usuarios-con-r`
- GET `http://localhost:8000/consultas/contar-pedidos-usuario-5`
- GET `http://localhost:8000/consultas/pedidos-ordenados-desc`
- GET `http://localhost:8000/consultas/suma-total-pedidos`
- GET `http://localhost:8000/consultas/pedido-mas-economico`
- GET `http://localhost:8000/consultas/pedidos-agrupados`

#### 3. cURL (Línea de Comandos)

```bash
curl http://localhost:8000/consultas/pedidos-usuario-2
curl http://localhost:8000/consultas/suma-total-pedidos
```

#### 4. Artisan Tinker

Para probar consultas directamente en la consola:

```bash
php artisan tinker
```

Luego ejecuta:

```php
>>> use App\Models\Pedido;
>>> use App\Models\Usuario;
>>> Pedido::where('id_usuario', 2)->get();
>>> Usuario::where('nombre', 'LIKE', 'R%')->get();
>>> Pedido::sum('total');
```

---

## Datos de Prueba

El sistema incluye datos de prueba insertados mediante seeders:

### Usuarios (5 registros)

| ID | Nombre              | Correo                  | Teléfono   |
|----|---------------------|-------------------------|------------|
| 1  | Roberto García      | roberto@example.com     | 2452-0101  |
| 2  | María López         | maria@example.com       | 7458-0102  |
| 3  | Carlos Martínez     | carlos@example.com      | 2365-0103  |
| 4  | Ana Rodríguez       | ana@example.com         | 7496-0104  |
| 5  | Ricardo Fernández   | ricardo@example.com     | 2147-0105  |

### Pedidos (7 registros)

| ID | Producto                | Cantidad | Total   | Usuario |
|----|-------------------------|----------|---------|---------|
| 1  | Laptop Dell             | 1        | $250.00 | María   |
| 2  | Mouse Logitech          | 2        | $50.00  | María   |
| 3  | Teclado Mecánico        | 1        | $150.00 | Roberto |
| 4  | Monitor Samsung         | 1        | $300.00 | Ricardo |
| 5  | Webcam HD               | 1        | $80.00  | Carlos  |
| 6  | Auriculares Bluetooth   | 1        | $120.00 | María   |
| 7  | Disco Duro Externo      | 1        | $95.00  | Ana     |

---

## Comandos Útiles

```bash
# Ver estado de las migraciones
php artisan migrate:status

# Revertir la última migración
php artisan migrate:rollback

# Revertir todas las migraciones y volver a ejecutarlas
php artisan migrate:fresh

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ver todas las rutas
php artisan route:list

# Iniciar servidor de desarrollo
php artisan serve

# Iniciar servidor en puerto específico
php artisan serve --port=8080
```

---

## Notas Adicionales

### Diferencias entre Query Builder y Eloquent ORM

**Query Builder**:
- Más cercano a SQL puro
- Mayor control sobre las consultas
- Mejor rendimiento en consultas complejas
- Retorna objetos `stdClass` o arrays

**Eloquent ORM**:
- Sintaxis más expresiva y legible
- Manejo automático de relaciones
- Retorna instancias de modelos
- Incluye características como timestamps automáticos, soft deletes, etc.

### Buenas Prácticas Implementadas

- ✅ Uso de migraciones para control de versiones de la base de datos
- ✅ Seeders para datos de prueba reproducibles
- ✅ Relaciones Eloquent para mantener integridad referencial
- ✅ Comentarios explicativos en cada método del controlador
- ✅ Nombres descriptivos para métodos y variables
- ✅ Respuestas JSON consistentes
- ✅ Uso de eager loading para evitar problema N+1
- ✅ Validación de integridad referencial con claves foráneas

---

## Licencia

Este proyecto es de código abierto y está disponible bajo la [Licencia MIT](https://opensource.org/licenses/MIT).

---

## Autor

Proyecto desarrollado como demostración de Query Builder y Eloquent ORM en Laravel.

---

## Soporte

Si encuentras algún problema o tienes preguntas:

1. Verifica que todos los requisitos del sistema estén instalados
2. Asegúrate de que la base de datos esté configurada correctamente
3. Revisa que las migraciones y seeders se hayan ejecutado sin errores
4. Consulta los logs de Laravel en `storage/logs/laravel.log`

---

**¡Gracias por usar este proyecto! 🚀**
