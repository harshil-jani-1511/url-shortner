# URL Shortener

A URL shortener built with Laravel 11. Users can create short links and see how many times each one was clicked. What a user can see and do depends on their role.

## Features

- Login with Laravel Breeze (no public registration, accounts are created through invitation links)
- Three roles: Super Admin, Admin and Member
- Super Admin creates clients and sees the URLs of every company
- Admin creates short URLs and sees the URLs of their own company and invites team members
- Member creates short URLs and sees only their own
- Short URLs redirect to the original link and every visit is counted as a click
- Dashboard with URL and click totals
- Date filters: today, last week, last month, this month and custom range
- CSV export of the URL list (follows the selected date filter)

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL
- Node.js and npm

## Local Setup

### 1. Clone the repository

```bash
git clone https://github.com/harshil-jani-1511/url-shortner.git
cd url-shortner
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment file

PowerShell:

```powershell
Copy-Item .env.example .env
```

Linux / macOS:

```bash
cp .env.example .env
```

Then generate the app key:

```bash
php artisan key:generate
```

### 4. Database

Create an empty MySQL database named `urlshorter` and check these values in `.env` (change the username and password if your MySQL setup is different):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=urlshorter
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrations and seeder

```bash
php artisan migrate --seed
```

The seeder creates the Super Admin account from these values in `.env`:

```env
SUPER_ADMIN_NAME="Super Admin"
SUPER_ADMIN_EMAIL="superadmin@urlshortner.com"
SUPER_ADMIN_PASSWORD="SuperAdmin#123!"
```

You can change them before running the seeder.

### 6. Build assets and start the server

```bash
npm run build
php artisan serve
```

Open http://127.0.0.1:8000, it redirects to the login page.

## How to Test

Mail is set to `log`, so no emails are sent. The invitation link is shown on the page after you submit the form. Copy it and open it in a private window.

1. Log in as the Super Admin using the credentials above.
2. Create a client (company name and admin email) and copy the invitation link.
3. Open the link, set a name and password, and log in as that Admin.
4. As the Admin, invite a Member from the team invite page and accept that link the same way.
5. Log in as the Member and create a short URL.
6. Open the short URL (`http://127.0.0.1:8000/s/{shortCode}`) a few times and check the click count on the dashboard.
7. Try the date filter and the CSV export from the dashboard, and compare what each role can see.

Invitation links expire after 7 days.

## User Roles

| Role | Access |
|---|---|
| Super Admin | Create clients, view and export URLs of all companies |
| Admin | Create, View and export URLs of their company, invite team members |
| Member | Create short URLs, view and export their own URLs |

## Project Structure

The main project directories are organized as follows:

## Project Structure

The main project directories are organized as follows:

```text
url-shortner/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── ClientController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── InvitationController.php
│   │   │   ├── ShortUrlController.php
│   │   │   ├── SuperAdminUrlController.php
│   │   │   ├── TeamController.php
│   │   │   ├── TeamInvitationController.php
│   │   │   └── UrlController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── Company.php
│   │   ├── Invitation.php
│   │   ├── Url.php
│   │   ├── UrlClick.php
│   │   └── User.php
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── app/
│       ├── auth/
│       ├── dashboard/
│       ├── invitations/
│       ├── layouts/
│       ├── super-admin/
│       └── team/
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

## AI Tools Used

- **ChatGPT**
  - UI: took suggestions for the layout and UI improvements. I made the CSS adjustments myself.
  - Debugging: used it to understand errors and get ideas for fixing them.
  - Commands and logic: referred to it for some Laravel/Artisan commands and implementation ideas.
- **Claude**
  - Helped draft this README.

I reviewed every suggestion before using it, and I tested the application myself.
