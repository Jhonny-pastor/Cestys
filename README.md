# CESTYS Backend (Laravel API)

API REST para autenticacion, catalogo, carrito, ordenes, pago simulado y matriculas.

## Requisitos

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Extensiones PHP comunes de Laravel (`pdo`, `mbstring`, `openssl`, `tokenizer`, `json`)

## Instalacion

1. Entrar al proyecto backend:
```bash
cd backend
```

2. Instalar dependencias:
```bash
composer install
```

3. Crear archivo de entorno:
```bash
cp .env.example .env
```

4. Configurar `.env` (base de datos y JWT):
- `APP_URL=http://127.0.0.1:8000`
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=nombre_bd`
- `DB_USERNAME=usuario_bd`
- `DB_PASSWORD=clave_bd`

5. Generar clave de app:
```bash
php artisan key:generate
```

6. Generar JWT secret:
```bash
php artisan jwt:secret
```

7. Ejecutar migraciones:
```bash
php artisan migrate
```

8. Levantar servidor:
```bash
php artisan serve
```

## Endpoints principales

Base URL:
- `http://127.0.0.1:8000/api/v1`

Auth:
- `POST /auth/register`
- `POST /auth/login`

Carrito (requiere JWT):
- `GET /me/cart`
- `POST /me/cart/items` (body: `cursoId`)
- `DELETE /me/cart/items/{cursoId}`
- `DELETE /me/cart`

Ordenes (requiere JWT):
- `POST /orders` (crea orden `PENDING`)
- `GET /me/orders`
- `GET /orders/{id}/status`
- `POST /orders/{id}/retry`

Pago simulado:
- `POST /payments/webhook` (body: `orderId`, `status`, `paymentId`)
- Al enviar `status=PAID`, actualiza orden a `PAID` y crea matriculas automaticamente.

Admin (requiere JWT admin):
- `POST /admin/categories`
- `POST /admin/courses`
- `POST /admin/modules`
- `POST /admin/topics`

## Tests

Ejecutar todos:
```bash
php artisan test
```

Tests relevantes:
```bash
php artisan test --filter=AuthTest
php artisan test --filter=AdminCatalogStoreTest
php artisan test --filter=CheckoutFlowTest
php artisan test --filter=CartAndPaymentTest
```

## Flujo rapido de compra (manual)

1. Login y obtener token JWT.
2. Agregar curso al carrito: `POST /me/cart/items`.
3. Crear orden: `POST /orders` (estado `PENDING`).
4. Simular pago: `POST /payments/webhook` con `status=PAID`.
5. Verificar matricula creada en tabla `matricula`.

