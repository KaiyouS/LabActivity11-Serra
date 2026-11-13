# Lab Activity 11 - Barangay Bulletin (Purok Serra)

Kaiyou Serra · 4-334 Full-Stack Web Development · Ateneo de Davao University

This continues my Lab 10 Barangay Bulletin. In this lab I added comments, tags, a
login for residents and Kagawads, an author page, and Form Requests for validating
comments and new posts. The main focus was Eloquent relationships and eager loading
so there are no N+1 queries.

## Seeded logins

| Account | Email | Password |
|---|---|---|
| Kagawad Kaiyou Serra (author, can post) | kagawad.serra@bulletin.test | bulletin2026 |
| Lola Serra (resident, can only comment) | lola.serra@bulletin.test | bulletin2026 |

There are also 8 random residents from the UserFactory (password is `password`).

## Tags

- Purok Serra
- Urgent
- Libre
- Schedule
- Advisory (my own tag, for safety and weather reminders)

The pinned post (Purok Serra Clean-up Drive) is always synced to **Purok Serra** and **Urgent**.

## Relationships and why I used them

- **User hasOne Author / Author belongsTo User**: a login account can have at most one
  author profile. I put a nullable unique `user_id` on `authors` because most users are
  residents and don't have an author profile. This is what `StorePostRequest` checks,
  so residents get a 403 when they try to post.
- **Author hasMany Post / Post belongsTo Author**: one Kagawad writes many posts, but each
  post has one author (`author_id`).
- **Post hasMany Comment / Comment belongsTo Post**: a post can have many comments.
  Comments are deleted when their post is deleted (cascade).
- **User hasMany Comment / Comment belongsTo User**: so I can show who wrote each comment.
- **Post belongsToMany Tag (and back)**: a post can have several tags and a tag can be on
  many posts, so I needed the `post_tag` pivot table. Tags are only added with
  `attach()`/`sync()`, never with manual inserts.
- **Author hasManyThrough Comment (through Post)**: comments are not directly linked to
  authors, but I needed the "N comments received" count on the author page. hasManyThrough
  goes authors → posts → comments without an extra column.

`Model::preventLazyLoading()` is turned on outside production, so I used `with()`,
`withCount('comments')` and `load('comments.user')` in the controllers.

## How to run

Requires PHP 8.4+, Composer and Node.js.

```bash
git clone https://github.com/KaiyouS/LabActivity11-Serra.git
cd LabActivity11-Serra
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite     # Windows: New-Item database/database.sqlite
php artisan migrate:fresh --seed
npm install
npm run dev
php artisan serve                   # in another terminal
```

Then open http://localhost:8000.

To run the tests: `php artisan test`

Icons are Material Symbols from Google Fonts, so they need an internet connection to show.
