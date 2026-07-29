# WORKSPACE AGENT RULES & CONVENTIONS - PERSONAL HUB & WEDDING INVITATION

This workspace enforces strict coding conventions, architecture patterns, UI design rules, Docker container workflows, and Git synchronization standards for all AI Agents and Developers working on this repository.

---

## 1. Tech Stack Standards
- **Backend:** Laravel 13.x (PHP 8.4+)
- **Frontend Architecture:** Inertia.js v2 + Vue 3 (Composition API `<script setup>` with TypeScript)
- **Styling:** Tailwind CSS v4 + Shadcn Vue / Radix Vue / Lucide Icons
- **Realtime WebSockets:** Laravel Reverb (Event Broadcasting)
- **Admin Panel:** FilamentPHP v5
- **Content Engine:** Spatie Laravel Markdown + Shiki Syntax Highlighting
- **Database & Cache:** MySQL 8.0 & Redis 7
- **Containerization:** Docker Compose (PHP-FPM, Nginx, MySQL, Redis, Reverb, Queue Worker, Vite Dev Server)

---

## 2. UI Design & Aesthetics Rules (CRITICAL)

### Three-Tier Scope & Theme Architecture

1. **PUBLIC Wedding Invitation Pages (`resources/js/Pages/Wedding/Index.vue`, `Show.vue`)**:
   - **Bright, Elegant & Romantic Pastel Theme ONLY**.
   - **Backgrounds:** Soft Warm Cream (`#FAF8F5`, `bg-rose-50/50`, `bg-amber-50/30`), Soft Rose Blush (`#FDF2F8`, `#FCE7F3`), Warm Ivory (`#FFFDF9`).
   - **Accents:** Soft Rose Gold (`#EC4899`, `#F43F5E`), Champagne Gold (`#D97706`, `#F59E0B`), Soft Sage Green (`#10B981`).
   - **Text Colors:** Deep Warm Rosewood (`#881337`, `text-rose-950`), Rich Slate/Charcoal (`text-slate-800`).
   - **STRICTLY PROHIBITED:** DO NOT use dark slate/black backgrounds (`bg-slate-950`, `bg-black`) on PUBLIC Wedding invitation pages!

2. **PRIVATE Planning & Timeline (`resources/js/Pages/Wedding/Timeline.vue`, `/admin` - FilamentPHP)**:
   - **MINIMALIST Design Standard (Phong cách Tối giản)**: Clean, elegant, light neutral or subtle dark layout (`bg-slate-50` / `bg-[#0F172A]`), crisp typography, subtle borders, high contrast readability.
   - **STRICTLY PROHIBITED:** DO NOT use noisy neon badges, multicolored glowing bars, or complex ornate visual elements on private planning pages! Keep the layout clean, uncluttered, functional, and minimal.

3. **PUBLIC Portfolio & Technical Blog (`resources/js/Pages/Portfolio/*`, `resources/js/Pages/Blog/*`)**:
   - **Modern Tech Dark Theme** (`bg-slate-950`, `text-slate-100`, Indigo/Slate accents).

---

## 3. Code Quality & Architecture Rules

### PHP & Laravel Rules
- **Strict Types:** Every PHP file must declare strict types at the very top: `declare(strict_types=1);`.
- **Thin Controllers & Action Pattern:** Controllers must only handle HTTP/Inertia requests and delegate business logic to Single-Action classes in `App\Actions\*` or `App\Services\*`.
- **Form Requests:** Never perform validation directly inside Controllers. Always create dedicated `FormRequest` classes.
- **Enums:** Use PHP Backed Enums for all entity statuses (`RsvpStatus`, `PostStatus`, `GuestRole`, `MilestoneStatus`).
- **DTOs:** Use readonly DTO classes for transferring data between controllers, actions, and services.
- **Database:** Use ULID/UUID primary keys for core resources (`guests`, `wishes`, `posts`, `wedding_milestones`). Always add foreign keys, indexes, factory classes, and seeders.

### Vue 3 & TypeScript Rules
- **Composition API:** Always use `<script setup lang="ts">`.
- **Type Safety:** Define explicit interfaces/types for all Component Props, Emits, and Inertia Page Props.
- **Component Separation:** Keep components modular and reusable in `resources/js/Components/`. Inertia pages reside in `resources/js/Pages/`.

### Quality Verification
- Run `vendor/bin/pint` (or `npm run docker:pint`) to format PHP code.
- Run `vendor/bin/phpstan analyse` to ensure zero static analysis errors.
- Write Pest PHP Feature Tests for all new API/Inertia routes and business logic. Run via `vendor/bin/pest` (or `npm run docker:test`).

---

## 4. Docker Container Development Workflow

### Container Lifecycle Management
- **Start All Services:** `npm run docker:up` (or `docker compose up -d`)
- **Stop All Services:** `npm run docker:down` (or `docker compose down`)
- **Rebuild Containers:** `npm run docker:build` (or `docker compose build`)

### Service Topology
| Service Name | Description | Internal Port | Host Port |
| :--- | :--- | :--- | :--- |
| `app` | PHP 8.4-FPM Core Application | 9000 | N/A |
| `web` | Nginx Reverse Proxy | 80 | `8085` |
| `db` | MySQL 8.0 Database | 3306 | `3307` |
| `redis` | Redis 7 Cache & Queue | 6379 | `6380` |
| `reverb` | Laravel Reverb WebSockets | 8080 | `8082` |
| `queue` | Background Queue Worker | N/A | N/A |
| `vite` | Vite Dev Server (HMR) | 5173 | `5173` |

### Executing Commands in Docker
Agents and developers MUST execute Artisan, Composer, and Pest commands through the `app` container:
- **Migrations:** `npm run docker:migrate` (or `docker compose exec app php artisan migrate`)
- **Database Refresh & Seed:** `npm run docker:fresh` (or `docker compose exec app php artisan migrate:fresh --seed`)
- **Testing:** `npm run docker:test` (or `docker compose exec app vendor/bin/pest`)
- **Code Formatting:** `npm run docker:pint` (or `docker compose exec app vendor/bin/pint`)
- **Interactive Shell:** `npm run docker:bash` (or `docker compose exec app bash`)

---

## 5. Git Workflow & Synchronization Rules

### Conventional Commits
All commit messages MUST follow Conventional Commits format:
- `feat(module): description`
- `fix(module): description`
- `docs(scope): description`
- `style(scope): description`
- `refactor(module): description`
- `test(scope): description`
- `chore(scope): description`

### Atomic Commits & Sync Policy
1. **Atomic Commits:** Make small, logical, self-contained commits. Do not lump unrelated changes into one single commit.
2. **Pull & Rebase:** Before making changes or pushing, execute `git status` and `git pull origin <branch> --rebase` to ensure local branch is up to date.
3. **Conflict Handling:** Analyze both versions carefully during git conflicts. Never blindly drop changes.
4. **Security & Secrets:** NEVER commit `.env`, secret credentials, API keys, or private certificates. Maintain `.env.example` and `.env.docker`.
