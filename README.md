# Flash Sale API

A RESTful API built with Laravel 12 to simulate an online store flash sale. The application prevents overselling by handling concurrent purchase requests using database transactions and pessimistic locking (`lockForUpdate()`), ensuring inventory never becomes negative.

---

## Features

- RESTful API with JSON responses
- Product listing and product detail endpoints
- Order checkout endpoint
- Inventory protection against overselling
- Race condition handling during flash sales
- Functional command-line concurrency test
- MySQL database
- Service Layer architecture

---

## Tech Stack

- PHP 8.2+
- Laravel 12
- MySQL
- Guzzle HTTP Client

---

## Project Structure

```text
app
├── Console
│   └── Commands
├── Http
│   ├── Controllers
│   └── Requests
├── Models
└── Services
```

---

## Requirements

- PHP >= 8.2
- Composer
- MySQL 8.0+

---

## Installation

Clone the repository.

```bash
git clone https://github.com/fadhlan36/flash-sale-api.git
cd flash-sale-api
```

Install project dependencies.

```bash
composer install
```

Copy the environment file.

```bash
cp .env.example .env
```

Generate the application key.

```bash
php artisan key:generate
```

Create a MySQL database (e.g. `flash_sale_api`), then import the SQL file located at:

```text
database/sql/flash_sale.sql
```

Update the database configuration in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=(your_database_name)
DB_USERNAME=root
DB_PASSWORD=
```

> **Note:** Replace `(your_database_name)` with the name of the database you created.

Start the development server.

```bash
php artisan serve
```

> **Note**
>
> The provided SQL file already contains the required database schema and sample product data. No additional migration or seeding is required.

---

## Available Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | Retrieve all products |
| GET | `/api/products/{id}` | Retrieve product details |
| POST | `/api/orders` | Create a new order |

---

## API Examples

### Get All Products

```http
GET /api/products
```

**Response**

```json
{
    "message": "Products retrieved successfully.",
    "data": [
        {
            "id": 1,
            "name": "Mechanical Keyboard",
            "price": "500000.00",
            "stock": 10
        }
    ]
}
```

---

### Get Product Detail

```http
GET /api/products/1
```

---

### Create Order

```http
POST /api/orders
```

**Request Body**

```json
{
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```

**Successful Response**

```json
{
    "message": "Order created successfully.",
    "data": {
        "id": 1,
        "total_price": "1000000.00",
        "status": "completed"
    }
}
```

**Error Response**

```json
{
    "message": "Insufficient stock for Mechanical Keyboard"
}
```

---

## Race Condition Handling

To prevent overselling during flash sales, the checkout process uses:

- Database transactions
- Pessimistic locking with `lockForUpdate()`
- Atomic inventory updates

This ensures that multiple concurrent requests cannot reduce product inventory below zero.

---

## Functional Concurrency Test

Run the following command to simulate concurrent purchase requests.

```bash
php artisan flash-sale:test
```

Or specify the number of requests.

```bash
php artisan flash-sale:test --requests=100
```

Example output:

```text
Sending 100 concurrent requests...

Finished

+---------+--------+
| Success | Failed |
+---------+--------+
|    5    |   95   |
+---------+--------+
```

---

## HTTP Status Codes

| Status | Description |
|--------|-------------|
| 200 | Success |
| 201 | Resource Created |
| 404 | Product Not Found |
| 409 | Insufficient Stock |
| 422 | Validation Error |

---

## Design Decisions

- Business logic is separated into a dedicated Service Layer.
- Request validation is handled using Laravel Form Requests.
- Inventory updates are protected using database transactions and pessimistic locking (`lockForUpdate()`).
- All API responses are returned in JSON format with appropriate HTTP status codes.

---

## Author

**Fadhlan Faidh**

GitHub: https://github.com/fadhlan36
