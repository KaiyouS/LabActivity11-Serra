# Lab Activity 11: Barangay Bulletin &ndash; Relationships, Eager Loading & Form Requests

Ateneo de Davao University &bull; 4-334 Full-Stack Web Development  
1st Semester, July&ndash;November 2026  
**Student:** Kaiyou Serra  
**Repository:** `LabActivity11-Serra`

---

## Project Overview

Building on **Lab Activity 10**, this project enhances the **Barangay Bulletin &ndash; Purok Serra** application for Barangay Matina Crossing. It answers the residents' questions: *"Kinsa nag-post ani?"* and *"Asa ko mo-comment?"*.

Key features implemented:
- **Database Relationships:** `hasOne`, `belongsTo`, `hasMany`, `belongsToMany` (pivot `post_tag`), and `hasManyThrough`.
- **Eager Loading & N+1 Prevention:** `Model::preventLazyLoading(! app()->isProduction())` enabled, paired with `with(['author', 'tags'])`, `withCount('comments')`, and nested `load(['comments.user'])`.
- **Custom Form Requests:** `StorePostRequest` (author-only authorization, tag validation) and `StoreCommentRequest` (authenticated resident authorization, customized minimum length message).
- **Blade Error UX:** Error summaries (`$errors->any()`), inline `@error` messages, preserved old input with `@checked` on tag checkboxes, and `@csrf` protection.
- **Authentication:** Session-based authentication reused from Lab 9 with role-aware UI navigation.

---

## Seeded User Credentials

| Role | Name | Email Address | Password |
|---|---|---|---|
| **Kagawad (Author)** | Kagawad Kaiyou Serra | `kagawad.serra@bulletin.test` | `bulletin2026` |
| **Resident (User)** | Lola Serra | `lola.serra@bulletin.test` | `bulletin2026` |
| **Residents (Factory)** | 8 Resident Users | Generated via UserFactory | `password` |

> *Note: All passwords are securely hashed using `Hash::make()` in seeders.*

---

## Defined Bulletin Tags

The five tags available in Purok Serra are:
1. `Purok Serra` &ndash; Official bulletins and updates localized to Purok Serra.
2. `Urgent` &ndash; Critical notices requiring immediate attention (pinned posts sync with this).
3. `Libre` &ndash; Free public services, medicine distributions, and assistance drives.
4. `Schedule` &ndash; Dates and timetables for garbage collection, water supply, and assemblies.
5. `Advisory` &ndash; Community guidelines, safety tips, and weather/health precautions *(Choice Tag)*.

---

## Eloquent Relationship Design & Explanations

| Relationship | Models Linked | Relation Type | Why This Relation Type Was Chosen |
|---|---|---|---|
| **User &harr; Author** | `User` &rarr; `Author` | `hasOne` / `belongsTo` | An official user account (e.g., Kagawad) corresponds to at most one author profile. Storing `user_id` as a nullable unique foreign key on `authors` links login credentials to author credentials. |
| **User &harr; Comment** | `User` &rarr; `Comment` | `hasMany` / `belongsTo` | Registered residents and officials can write multiple comments across the bulletin. Each comment records `user_id` referencing the commenter. |
| **Author &harr; Post** | `Author` &rarr; `Post` | `hasMany` / `belongsTo` | An author publishes multiple community bulletins over time, while each post belongs strictly to one author via `author_id`. |
| **Author &harr; Comment (via Post)** | `Author` &rarr; `Comment` | `hasManyThrough` | Authors do not directly manage comments; residents leave comments on posts created by the author. `hasManyThrough(Comment::class, Post::class)` allows counting all comments received across all posts by that author for display on `/authors/{author}`. |
| **Post &harr; Comment** | `Post` &rarr; `Comment` | `hasMany` / `belongsTo` | Each bulletin post can have a dedicated thread of discussion containing zero to many comments, cascading on post deletion. |
| **Post &harr; Tag** | `Post` &harr; `Tag` | `belongsToMany` | A bulletin post can have multiple descriptive tags, and a single tag can categorize many posts. Joined via the alphabetical `post_tag` pivot table with a composite primary key `(post_id, tag_id)`. |

---

## Personalization Summary

| Requirement | Implementation Detail |
|---|---|
| **Site Title** | `Barangay Bulletin – Purok Serra` |
| **Kagawad Profile** | `Kagawad Kaiyou Serra` (`kagawad.serra@bulletin.test`), linked to Kagawad User account |
| **Pinned Post** | `Purok Serra Clean-up Drive` (Pinned: `true`, Published: `2026-11-16 08:00:00`) |
| **Pinned Post Tags** | Exactly `Purok Serra` and `Urgent` synced via relationship |
| **Pinned Post Comments** | 3 seeded comments, including an authentic response from `Lola Serra` |
| **Posts Per Page** | **6** (Last name "Serra" has 5 letters; minimum requirement is 6 per page) |
| **Comment Validation Error** | Exact message: `“Purok Serra comments need at least 3 characters.”` |
| **Five Categories** | `Announcement`, `Health`, `Peace & Order`, `Ayuda`, `Entertainment` |

---

## Installation & How to Run

Follow these standard steps to set up and run the project locally:

### 1. Clone the repository and install PHP dependencies
```bash
git clone https://github.com/your-username/LabActivity11-Serra.git
cd LabActivity11-Serra
composer install
```

### 2. Environment configuration & application key
```bash
cp .env.example .env
php artisan key:generate
```

### 3. SQLite Database setup
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

### 5. Install frontend dependencies & compile assets
```bash
npm install
npm run dev
# or for production:
npm run build
```

### 6. Start the local server
```bash
php artisan serve
```
Visit [http://localhost:8000](http://localhost:8000) (automatically redirects to `/posts`).

---

## Verification & Automated Testing

All features, relationships, authorization rules, and N+1 prevention have been thoroughly verified with PHPUnit / Pest tests:

```bash
php artisan test
```

### Test Suite Summary:
- **AuthTest:** Login, logout, credential validation, redirect to `/posts`.
- **CommentTest:** Guest protection ("Sign in to comment"), comment creation via relationship, custom error message validation (`"Purok Serra comments need at least 3 characters."`), newest-first ordering.
- **PostCreateTest:** Guest protection, resident 403 authorization guard (`StorePostRequest`), author post creation with tag syncing and `published_at` timestamping.
- **AuthorTest:** Author profile view (`/authors/{author}`), post list, and "N comments received" via `hasManyThrough`.
- **TagFilterTest:** Filtering posts by `?tag=`, displaying tag badges.
- **SeedingAndRelationshipTest:** Verifies all 6 relationship directions, seeded Lola & Kagawad accounts, 5 tags, and pinned post comments.
- **PostTest (Baseline):** Verifies search, category filtering, pagination (6 per page), draft 404 response, and view counting.
