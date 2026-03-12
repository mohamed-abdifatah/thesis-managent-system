# GitHub Copilot Instructions for Thesis Management System

This document provides context and guidelines for AI agents working on the Thesis Management System codebase.

## 🏗 Project Architecture & Stack

- **Framework:** Laravel 12.0 (Bleeding Edge) + PHP 8.2+
- **Frontend:** Blade Templates, Tailwind CSS v4 (via Vite), Alpine.js.
- **Database:** MySQL with Eloquent ORM.
- **Environment:** Linux-based development.

## 📐 Core Architectural Patterns

### 1. User & Role Management
- **Separation of Concerns:** The system separates Authentication identity (`User` model) from Domain Profile (`Student`, `Supervisor` models).
  - `User` table: Handles login, `role_id`, `department_id`.
  - Roles are defined in `roles.md` and seeded in the database.
  - Profile Relationships: `User` has `hasOne` relations to `student()` and `supervisor()`.
- **Role Routing:**
  - The `DashboardController::index()` method acts as a dispatcher. It checks `auth()->user()->role->name` and returns the specific view (e.g., `dashboard.student`, `dashboard.admin`).
  - **Do not** put role-specific logic in the generic `dashboard.blade.php`; keep them in their dedicated view files.

### 2. Domain Data Flow
- **Central Entity:** The `Thesis` model is the core aggregate root.
  - It connects `Student`, `Supervisor`, `Proposals`, and `ThesisVersions`.
- **Defense Workflow:** Handled via `DefenseSession` and `Evaluation` models.

### 3. Frontend Architecture
- **Theme/UI:** The project adapts a pre-built template located in `references/`.
  - When creating new pages, check `references/` for HTML examples of the desired layout (e.g., `apps-calendar.html`, `projects-create.html`).
- **Layouts:**
  - Main Layout: `resources/views/layouts/app.blade.php`.
  - Partials: `sidebar.blade.php`, `topbar.blade.php` handle navigation.
- **Asset Pipeline:** Uses `vite` for building assets. `tailwind.config.js` and `postcss.config.js` are configured.

## 🛠 Developer Workflow

- **Start Development Server:**
  Run the all-in-one development command defined in `composer.json`:
  ```bash
  composer run dev
  ```
  *(Runs `php artisan serve`, `queue:listen`, `pail`, and `npm run dev` concurrently)*

- **Running Tests:**
  ```bash
  composer test
  ```

- **Database Setup:**
  ```bash
  composer run setup
  ```
  *(Handles `.env`, key generation, migrations, and npm build)*

## 💡 Project-Specific Conventions

- **Models:** Use `protected $guarded = [];` rather than `$fillable` for rapid development, unless specific security constraints are required.
- **Routing:** Define all web routes in `routes/web.php`. Use Resource Controllers where possible.
- **Naming:** Follow Laravel standard naming (Snake case for DB columns, Camel case for variables).
- **Views:**
  - `resources/views/dashboard/` contains the entry points for different user roles.
  - Use Blade components/partials for repeated UI elements.

## 🔍 Key Files to Know

- `app/Http/Controllers/DashboardController.php`: Role-based redirect logic.
- `app/Models/User.php`: entry point for relationships (`student`, `supervisor`).
- `roles.md`: The "Source of Truth" for role permissions and definitions.
- `composer.json`: Contains the `scripts` section defining the build/run aliases.
