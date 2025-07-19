
# API RESTful - Gestión de Usuarios y Transacciones Financieras

Este proyecto implementa una API RESTful desarrollada en **Laravel 11 + PHP 8+ + MySQL**, enfocada en la gestión de usuarios y transacciones financieras, cumpliendo con múltiples requerimientos funcionales y técnicos.

---

## ✅ Requerimientos funcionales

### 👤 Gestión de Usuarios
- [x] CRUD completo de usuarios (`nombre`, `email`, `saldo_inicial`)
- [x] Validación de campos y reglas personalizadas

### 💸 Gestión de Transacciones
- [x] Registro de transferencias entre usuarios
- [x] Validación de saldo suficiente en el emisor
- [x] Límite diario de transferencia: **máximo 5.000 USD**
- [ ] Prevención de transacciones duplicadas (mismo monto entre mismos usuarios y timestamp)
- [x] Personalización de mensajes de error

### 🔐 Autenticación y Seguridad
- [x] Laravel Sanctum como método de autenticación
- [x] Middleware protegiendo rutas sensibles

### 🗄️ Base de Datos
- [x] Migraciones completas
- [x] Valores por defecto
- [ ] Uso de `CHECK` constraints (si el motor lo permite)
- [x] Claves foráneas y restricciones únicas

### 📤 Exportación CSV
- [x] Endpoint para exportar las transacciones (`api/transacciones/export`)
- [x] Archivo CSV generado con delimitador **punto y coma (;)**

---

## 🧠 Requerimientos técnicos adicionales

### 🚀 Optimización de Consultas
- [x] Endpoint con reporte por usuario emisor (`api/reportes/totales`)
- [ ] Total transferido
- [ ] Promedio por usuario

### 🧪 Testing
- [ ] Pruebas unitarias mínimas para validaciones personalizadas
  - ❌ No implementadas

### 📚 Documentación
- [ ] Esbozo con Swagger o Laravel Scribe
  - ❌ No implementado

---

## 🔍 Caso Práctico de Análisis

**Problema:** El sistema permite transferencias diarias por sobre el límite de 5.000 USD

### ✅ Estrategia aplicada
- Se agregó una validación en el `TransaccionController` para calcular el total transferido por el emisor **el día actual**, y evitar superar el límite.

### 🛠️ Cómo se identificaría el error
- Revisar el controlador de transacciones
- Validar si hay una consulta a la suma diaria de transacciones
- Confirmar que `created_at` se compara contra la fecha del día (con Carbon)


---

## Instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/camellodetroya/bipay.git
cd nombre-repo
```

2. **Instalar dependencias**
composer install

3. **Copiar .env y configurar**
cp .env.example .env
php artisan key:generate

4. **Configurar base de datos en .env**
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=NOMBRE_BDD
DB_USERNAME=USUARIO_BDD
DB_PASSWORD=PASSWORD_BDD

5. **Correr migraciones**
php artisan migrate

## Autentificación
POST /api/login: Inicio de sesión (devuelve token)
POST /api/logout: Cierre de sesión

Nota: Debes incluir el token en el header de tus peticiones autenticadas:
Authorization: Bearer TU_TOKEN_AQUI

## EndPoint

Usuarios
| Método | Ruta                 | Descripción               |
| ------ | -------------------- | ------------------------- |
| GET    | `/api/usuarios`      | Listar todos los usuarios |
| GET    | `/api/usuarios/{id}` | Ver un usuario específico |
| POST   | `/api/usuarios`      | Crear usuario             |
| PUT    | `/api/usuarios/{id}` | Actualizar usuario        |
| DELETE | `/api/usuarios/{id}` | Eliminar usuario          |

Transacciones
| Método | Ruta                        | Descripción                  |
| ------ | --------------------------- | ---------------------------- |
| POST   | `/api/transacciones`        | Crear nueva transacción      |
| GET    | `/api/transacciones/export` | Exportar transacciones a CSV |





