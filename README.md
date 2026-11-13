# Lab Activity 10: Barangay Bulletin &ndash; Purok Serra

Ateneo de Davao University &bull; 4-334 Full-Stack Web Development  
1st Semester, July&ndash;November 2026  
**Student:** Kaiyou Serra  
**Repository:** `LabActivity10-Serra`

---

## Project Overview

The Barangay Matina Crossing Hall in Davao City has migrated its cork board bulletin system into an online **Barangay Bulletin**. Residents can browse community announcements, search titles, filter by category, page through results, and read detailed bulletins while view counts are automatically tracked.

This project implements:
- **Migrations** with foreign key constraints, `up()` and `down()` methods, and an alter migration for view counting.
- **Eloquent Models** with explicit `$fillable` properties and attribute `$casts`.
- **Faker Factories & Seeders** with state management (`draft()`) and relational sequences.
- **Query Builder & Pagination** with conditional search (`?q=`), category filtering (`?category=`), pinned posts priority, and query string preservation (`withQueryString()`).
- **Show View** with draft protection (`abort(404)`) and view counter increment via Eloquent `save()`.
- **Blade & Tailwind CSS** responsive layout and component cards.

---

## Personalization Details

| Requirement | Implementation Detail |
|---|---|
| **Site Title** | `Barangay Bulletin – Purok Serra` |
| **Kagawad Author** | `Kagawad Kaiyou Serra` (`kagawad.serra@bulletin.test`) created with `Author::create()` |
| **Pinned Post** | `Purok Serra Clean-up Drive` (Pinned: `true`, Published: `2026-11-16 08:00:00`) |
| **Birthday Post Date** | `November 16, 2026` |
| **Posts Per Page** | **6** (Last name "Serra" has 5 letters; minimum requirement is 6 per page for names under 6 letters) |
| **Custom Category** | `Entertainment` (in addition to `Announcement`, `Health`, `Peace & Order`, and `Ayuda`) |

---

## Installation & How to Run

Follow these steps to set up and run the application locally:

### 1. Clone the repository and install PHP dependencies
```bash
git clone https://github.com/your-username/LabActivity10-Serra.git
cd LabActivity10-Serra
composer install
```

### 2. Environment setup & application key
```bash
cp .env.example .env
php artisan key:generate
```

### 3. SQLite Database configuration
Laravel 12 uses SQLite by default. Ensure the database file exists:
```bash
# Windows PowerShell
New-Item -ItemType File -Path database/database.sqlite -Force

# Linux / macOS
touch database/database.sqlite
```

### 4. Run migrations and seed data
```bash
php artisan migrate:fresh --seed
```
This seeds:
- **5 Authors** (1 hand-crafted Kagawad + 4 factory-generated authors)
- **51 Posts** (40 published factory posts, 10 draft factory posts, and 1 hand-crafted pinned birthday post)

### 5. Install frontend dependencies & compile assets
```bash
npm install
npm run dev
# or for a production build:
npm run build
```

### 6. Start the local development server
```bash
php artisan serve
```
Open [http://localhost:8000](http://localhost:8000) in your web browser. It will automatically redirect to `/posts`.

---

## Deliverables & Verification

- **Tinker Verification Screenshot:** Saved at `docs/tinker.png` showing `App\Models\Post::find(1)` title modification and saving via ActiveRecord.
- **Rollback Verification:** Tested with `php artisan migrate:rollback` and `php artisan migrate`.
- **Automated Tests:** Run `php artisan test` to verify routes, filtering, pagination, 404 draft responses, and view counting.
