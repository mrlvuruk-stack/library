<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

# Library Management System

A feature-rich, modern web application designed for school, college, and community libraries. Built using **Laravel 8**, **Livewire**, and **Bootstrap**, this application provides a sleek admin interface to manage books, authors, publishers, students, and book issuances with ease.

[![PHP Version](https://img.shields.io/badge/php-%5E7.3%20%7C%20%5E8.0-blue.svg)](https://www.php.net/)
[![Laravel Framework](https://img.shields.io/badge/laravel-v8.75-red.svg)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/livewire-v2.8-purple.svg)](https://laravel-livewire.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## Key Features

- 📊 **Dynamic Dashboard**: Real-time stats on total books, active issuances, publishers, and authors.
- 📚 **Book Cataloging**: Manage books, quantities, custom types, and search books dynamically.
- 🧑‍🎓 **Student Management**: Register students with branch, category, contact information, and profile photo upload.
- 📝 **Book Issuance & Tracking**: Record when books are issued and returned, track due dates, and manage late returns.
- 📝 **Dynamic Filtering & Livewire**: Real-time searching and pagination across all lists without page reloads.
- 📈 **Detailed Reports**: Generate daily, monthly, and overdue reports in a clean tabular view.
- ⚙️ **System Settings**: Customize the library name, logo, address, and email settings.

---

## Getting Started (Local Development)

Follow these steps to set up the project locally:

### Prerequisites
- PHP (>= 7.3 or 8.x)
- Composer
- Node.js & NPM
- MySQL Server

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/mrlvuruk-stack/library.git
   cd library
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Assets**
   ```bash
   npm install
   ```

4. **Compile Assets**
   ```bash
   npm run dev
   ```

5. **Configure Environment File**
   Copy the example environment file and configure your database details:
   - *Windows (PowerShell):*
     ```powershell
     copy .env.example .env
     ```
   - *Linux/macOS:*
     ```bash
     cp .env.example .env
     ```
   Open `.env` and set your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.

6. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

7. **Migrate and Seed Database**
   Run the migrations and seed default data (creates admin login):
   ```bash
   php artisan migrate:fresh --seed
   ```

8. **Start Local Development Server**
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://127.0.0.1:8000`.

---

## Default Admin Credentials

Use the following details to log in to the dashboard:
- **Username**: `mrlv`
- **Password**: `password`

---

## Deployment (Railway & Cloudflare)

This project is configured to easily deploy to **Railway** (using a MySQL service) and proxy through **Cloudflare** for DNS, SSL encryption, and caching.

For step-by-step setup guides, refer to:
- Codebase Proxy configuration inside `app/Http/Middleware/TrustProxies.php`.
- SSL security set to **Full** or **Full (strict)** in your Cloudflare panel.

---

## Database Schema (ERD)

Below is the entity-relationship diagram representing the database design of the Library Management System:

```mermaid
erDiagram
    User {
        int id PK
        varchar name
        varchar username
        varchar email
        varchar password
        varchar avatar
        datetime created_at
        datetime updated_at
    }
    Student {
        int id PK
        varchar name
        varchar age
        varchar gender
        varchar email
        varchar phone
        varchar address
        varchar class
        varchar branch
        varchar photo
        varchar category
        datetime created_at
        datetime updated_at
    }
    Author {
        int id PK
        varchar name
        datetime created_at
        datetime updated_at
    }
    Publisher {
        int id PK
        varchar name
        datetime created_at
        datetime updated_at
    }
    Category {
        int id PK
        varchar name
        datetime created_at
        datetime updated_at
    }
    Book {
        int id PK
        varchar name
        int category_id FK
        int author_id FK
        int publisher_id FK
        varchar status
        int quantity
        varchar type
        datetime created_at
        datetime updated_at
    }
    BookIssue {
        int id PK
        int student_id FK
        int book_id FK
        datetime issue_date
        datetime return_date
        varchar issue_status
        datetime return_day
        datetime created_at
        datetime updated_at
    }
    Setting {
        int id PK
        varchar library_name
        varchar logo
        varchar address
        varchar email
        datetime created_at
        datetime updated_at
    }

    Book ||--o{ BookIssue : "has"
    Student ||--o{ BookIssue : "issues"
    Category ||--o{ Book : "categorizes"
    Author ||--o{ Book : "writes"
    Publisher ||--o{ Book : "publishes"
```

---

## Deployment & System Architecture

Here is the operational architecture diagram showing the data flow from clients through Cloudflare and Railway to the Laravel application container and MySQL:

```mermaid
graph TD
    Client["Browser / User"]
    CF["Cloudflare CDN / WAF / DNS"]
    RLB["Railway Load Balancer"]
    App["Laravel App Container"]
    MySQL["MySQL Database"]

    Client -->|"HTTPS / SSL - User Domain"| CF
    CF -->|"Proxied Traffic / SSL - star.up.railway.app"| RLB
    RLB --> App
    App --> MySQL
```

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
