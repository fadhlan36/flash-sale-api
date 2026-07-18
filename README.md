# Flash Sale API

A RESTful API built with Laravel 12 to simulate an online store flash sale. This project ensures inventory consistency during concurrent purchase requests by using database transactions and row-level locking.

## Features

- RESTful API with JSON responses
- Product listing and detail endpoints
- Order checkout endpoint
- Prevents negative inventory
- Handles race conditions during flash sales
- Functional command-line concurrency test
- MySQL database
- Laravel Service Layer architecture

---

## Tech Stack

- PHP 8.2+
- Laravel 12
- MySQL
- Guzzle HTTP Client

---

## Project Structure

```
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

## Installation

Clone the repository

```bash
git clone https://github.com/fadhlan36/flash-sale-api.git
cd flash-sale-api
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your database in the `.env` file.

Run database migration (or import the provided SQL schema if applicable).

Seed sample data

```bash
php artisan db:seed
```

Start the development server

```bash
php artisan serve
```

---

## API Endpoints

### Get All Products

```
GET /api/products
```

Response

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

```
GET /api/products/{id}
```

---

### Create Order

```
POST /api/orders
```

Request Body

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

Successful Response

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

---

## Race Condition Handling

This project prevents overselling during flash sales by using:

- Database Transactions
- `lockForUpdate()` row locking
- Atomic inventory updates

Even when multiple users purchase the same product simultaneously, inventory will never become negative.

---

## Functional Concurrency Test

Run the following command to simulate concurrent requests:

```bash
php artisan flash-sale:test
```

Or specify the number of requests:

```bash
php artisan flash-sale:test --requests=100
```

Example output:

```
Sending 100 concurrent requests...

Finished

+---------+--------+
| Success | Failed |
+---------+--------+
|    5    |   95   |
+---------+--------+

Final Stock : 0

PASS
```

---

## HTTP Status Codes

| Status Code | Description        |
| ----------- | ------------------ |
| 200         | Success            |
| 201         | Order Created      |
| 404         | Product Not Found  |
| 409         | Insufficient Stock |
| 422         | Validation Error   |

---

## Design Decisions

- Business logic is separated into a dedicated Service Layer.
- Request validation is handled using Laravel Form Requests.
- Inventory updates are protected using transactions and pessimistic locking (`lockForUpdate()`).
- API responses use JSON with appropriate HTTP status codes.

---

## Author

**Fadhlan Faidh**

GitHub: https://github.com/fadhlan36
