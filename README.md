# MStaff 2.0

Staff and Employee Management System — a full-stack application for managing employees, departments, positions, projects, and task assignments within an organization.

## Tech Stack

### Backend
- **PHP 8.2+** / **Laravel 12**
- **Laravel Sanctum** — token-based API authentication
- **MySQL** — primary database
- **Eloquent ORM**

### Frontend
- **Vue 3** / **TypeScript**
- **Vue Router** / **Pinia**
- **Vite**
- **Electron** — desktop application support

## Features

- **Authentication** — login via email/password with Bearer token (Sanctum)
- **Role-based access control** — three roles: `admin`, `manager`, `line_worker`
- **User management** (admin) — create, edit, delete users with strong password policies
- **Employee management** (admin) — link users to departments, positions, and statuses; track employment dates and work format (office/remote/hybrid)
- **Department & Position management** (admin) — CRUD for organizational structure
- **Employee status dictionaries** (admin) — configurable employee statuses
- **Project management** (admin, manager) — create and manage projects with priority, status, dates, and team member assignment
- **Task assignments** (admin, manager) — create tasks linked to projects with priority, status, and assignee tracking
- **Assignment comments & attachments** — comment on assignments and upload files (schema-level)

## Project Structure

```
MStaff-2.0/
├── backend/                # Laravel 12 API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/    # Auth, Users, Employees, Departments,
│   │   │   │                   # Positions, Dictionaries, Projects, Assignments
│   │   │   ├── Middleware/     # CheckRole middleware
│   │   │   ├── Requests/      # Form request validators
│   │   │   └── Resources/     # API resource transformers
│   │   └── Models/            # Eloquent models (9 total)
│   ├── database/
│   │   ├── migrations/        # 21 migration files
│   │   └── seeders/
│   ├── routes/
│   │   └── api.php            # All API route definitions
│   └── composer.json
└── frontend/               # Vue 3 + TypeScript SPA
    ├── src/
    │   ├── App.vue
    │   ├── main.ts
    │   ├── router/
    │   └── stores/
    ├── package.json
    └── vite.config.ts
```

## API Endpoints

### Authentication — `/api/auth/`

| Method | Endpoint              | Access |
|--------|-----------------------|--------|
| POST   | `/auth/login`         | Guest  |
| POST   | `/auth/password/forgot` | Guest |
| POST   | `/auth/password/reset`  | Guest |
| GET    | `/auth/user`          | Authenticated |
| POST   | `/auth/logout`        | Authenticated |

### Admin — `/api/admin/` (admin only)

| Method | Endpoint                                      | Resource          |
|--------|-----------------------------------------------|-------------------|
| GET    | `/admin/users/list`                           | Users             |
| POST   | `/admin/users/create`                         | Users             |
| PUT    | `/admin/users/edit/{id}`                      | Users             |
| DELETE | `/admin/users/delete/{id}`                    | Users             |
| GET    | `/admin/employee/list`                        | Employees         |
| POST   | `/admin/employee/create`                      | Employees         |
| PATCH  | `/admin/employee/edit/{id}`                   | Employees         |
| DELETE | `/admin/employee/delete/{id}`                 | Employees         |
| GET    | `/admin/departments/list`                     | Departments       |
| POST   | `/admin/departments/create`                   | Departments       |
| PUT    | `/admin/departments/edit/{id}`                | Departments       |
| DELETE | `/admin/departments/delete/{id}`              | Departments       |
| GET    | `/admin/positions/list`                       | Positions         |
| POST   | `/admin/positions/create`                     | Positions         |
| PUT    | `/admin/positions/edit/{id}`                  | Positions         |
| DELETE | `/admin/positions/delete/{id}`                | Positions         |
| GET    | `/admin/dictionaries/employee-statuses`       | Employee Statuses |
| POST   | `/admin/dictionaries/employee-statuses/create`| Employee Statuses |
| PUT    | `/admin/dictionaries/employee-statuses/edit/{id}` | Employee Statuses |
| DELETE | `/admin/dictionaries/employee-statuses/delete/{id}` | Employee Statuses |

### Projects — `/api/projects/`

| Method | Endpoint              | Access              |
|--------|-----------------------|---------------------|
| GET    | `/projects/list`      | Authenticated       |
| GET    | `/projects/{id}`      | Authenticated       |
| POST   | `/projects/create`    | Admin, Manager      |
| PUT    | `/projects/{id}/edit` | Admin, Manager      |
| DELETE | `/projects/{id}/delete` | Admin, Manager    |

### Assignments — `/api/assignments/`

| Method | Endpoint                    | Access         |
|--------|-----------------------------|----------------|
| GET    | `/assignments/list`         | Authenticated  |
| GET    | `/assignments/{id}`         | Authenticated  |
| POST   | `/assignments/create`       | Admin, Manager |
| PUT    | `/assignments/{id}/edit`    | Admin, Manager |
| DELETE | `/assignments/{id}/delete`  | Admin          |

## Database Schema

Core entities and their relationships:

```
Users ──1:1──> Employees ──M:N──> Projects
                  │                    │
                  ├── belongs to ──> Departments
                  ├── belongs to ──> Positions
                  ├── belongs to ──> EmployeeStatuses
                  │                    │
                  └── M:N ──────> Assignments ──> belongs to Project
                                      │
                                      ├── has many ──> Comments
                                      └── has many ──> Attachments
```

## Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL
- Node.js >= 20.19.0 (or >= 22.12.0)
- npm

### Backend Setup

```bash
cd backend

# Install dependencies
composer install

# Copy environment file and configure database
cp .env.example .env

# Update .env with your MySQL credentials:
#   DB_CONNECTION=mysql
#   DB_HOST=127.0.0.1
#   DB_PORT=3306
#   DB_DATABASE=Mstaff-2.0
#   DB_USERNAME=root
#   DB_PASSWORD=

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Start the development server
php artisan serve
```

The API will be available at `http://localhost:8000`.

### Frontend Setup

```bash
cd frontend

# Install dependencies
npm install

# Start the development server
npm run dev
```

### Running Both Together

From the `backend/` directory:

```bash
composer dev
```

This runs `php artisan serve` and `npm run dev` concurrently.

## Roles & Permissions

| Role          | Capabilities                                                                 |
|---------------|-----------------------------------------------------------------------------|
| `admin`       | Full access — manage users, employees, departments, positions, statuses, projects, assignments |
| `manager`     | Create/edit projects and assignments                                        |
| `line_worker` | View projects and assignments                                               |

## License

This project is proprietary.
