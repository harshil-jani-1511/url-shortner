# URL Shortener

A role-based URL shortening application built with Laravel. Users can generate short URLs, track link clicks, and manage URL records through role-specific dashboards.

## Features

### Authentication and User Management
- User registration and login using Laravel Breeze.
- Role-based access control.
- Invitation-based team member registration.
- Separate dashboards for Super Admin, Admin, and Member.

### URL Management
- Generate unique short URLs.
- Redirect short URLs to their original destinations.
- View generated URLs with pagination.
- Track total clicks for each URL.
- Filter URL records by date range.
- Export URL reports as CSV files.

### Role-Based Access

| Role | Permissions |
|---|---|
| Super Admin | Manage clients and view URL records across companies. |
| Admin | Manage company URLs, invite team members, and view company reports. |
| Member | Generate and manage their own URLs and view their click statistics. |

### Reporting
- Dashboard statistics for generated URLs and clicks.
- Date filters: Today, Last Week, Last Month, This Month, and Custom Range.
- CSV export based on selected date filters.

## Technology Stack

- **Backend:** PHP 8.2+, Laravel 11
- **Frontend:** Blade, HTML, CSS, JavaScript, Tailwind CSS
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Build Tools:** Vite, npm
- **Version Control:** Git

## Installation and Setup

### 1. Clone the repository

```bash
git clone YOUR_REPOSITORY_URL
cd url-shortner
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment

Copy the example environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Configure the database

Create a MySQL database and update the database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=urlshorter
DB_USERNAME=root
DB_PASSWORD=
```

Update the credentials according to your local MySQL configuration.

### 5. Run migrations

```bash
php artisan migrate
```

If seeders are provided and you want to populate the database:

```bash
php artisan db:seed
```

### 6. Install frontend dependencies

```bash
npm install
npm run build
```

### 7. Start the application

```bash
php artisan serve
```

Open the application:

http://127.0.0.1:8000

## URL Redirection

Generated short URLs use the following format:

```text
http://127.0.0.1:8000/s/{shortCode}
```

When a short URL is accessed, the application redirects the visitor to its original URL and records the click.

## Project Structure

```text
app/
    Http/
        Controllers/
        Middleware/
    Models/

database/
    migrations/
    seeders/

resources/
    views/
        auth/
        dashboard/
        app/
        team/

routes/
    web.php

tests/
    Feature/
    Unit/
```

## Security and Access Control

- Authentication-protected application routes.
- Role-based authorization.
- Company-level data isolation for Admin users.
- Member-level URL ownership restrictions.
- Server-side request validation.

## License

This project was developed as a Laravel application assignment.
