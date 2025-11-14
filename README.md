# Travel Blog — Laravel 12 + Filament 4

A small but production-style travel blog built with **Laravel 12**, **PHP 8.2**, and **Filament 4**.  
The project is used as a portfolio piece and as a playground to refresh backend skills after a break from development.

> Public frontend + CMS-like admin panel + AJAX comments.

---

## Tech Stack

- **Backend:** PHP 8.2, Laravel 12
- **Admin panel:** Filament 4
- **Database:** MySQL (or any relational DB supported by Laravel)
- **Frontend:** Blade templates, Bootstrap 5.3, vanilla JS (no frontend framework)
- **Auth / API:** Laravel Sanctum (installed, ready for future use)

---

## Features

### 📰 Public blog

- Landing page with **hero slider**:
    - Random selection of 3 published articles with a `hero_image` for the background.
- Article listing:
    - Only `published` articles are shown.
    - Ordering: **pinned first**, then by `created_at` (newest first).
    - Manual pagination (`skip` / `take`) with 6 posts per page.
- Article cards:
    - Hero image (local storage or external URL resolved via helper).
    - Title, short description.
    - Category badges.
    - “Read more” link (`/articles/{id}-{slug}`).

### ✍️ Two article types

The project supports two content styles:

1. **Default blog article**  
   Rendered via `resources/views/frontend/articles/default.blade.php`.

2. **Itinerary article**  
   Rendered via `resources/views/frontend/articles/article_itinerary.blade.php` and backed by the `ArticleItinerary` model:
    - Intro text, map URL.
    - `itinerary_days` JSON (list of days, titles, activities, images).
    - `trip_budget` JSON + `trip_budget_advice`.
    - Result/summary fields.

The article type is effectively determined by the presence of an `ArticleItinerary` record (`$article->itinerary`).

### 💬 AJAX comments

Each article has a simple comments section:

- **Database:** `article_comments` table with FK constrained to `articles` and cascade delete.
- **API endpoint:**  
  `POST /api/article/comments`
- **Validation:** `StoreArticleCommentRequest`:
    - `article_id` (must exist in `articles`),
    - `user_name` (string, max 20),
    - `comment` (string, max 500).
- **Controller:** `CommentController@store`:
    - Creates `ArticleComment` instance.
    - Returns JSON with HTTP `201 Created`.
- **Frontend JS:** `public/js/article_comments.js`:
    - Submits comment via `fetch` (JSON).
    - On success, injects the new comment into the DOM without page reload.
    - Basic error handling and form reset.

### 🗂 Categories & tags

- `Category` model (`categories` table; `title`, `slug`).
- Many-to-many relationship via `article_category` pivot table:
    - Composite primary key (`article_id`, `category_id`).
    - Timestamps.
- On the frontend:
    - Categories appear as Bootstrap badges on the index and article pages.
    - Badge colors are randomized via `UiHelper`, avoiding repetitive colors.

---

## Admin Panel (Filament 4)

The admin panel is built using Filament v4 and lives under:

- **URL:** `/admin`
- **Dev credentials (for local/demo use only):**

`Email:    dev-admin@gmail.com
Login:    dev-admin
Password: dev-admin`

### Article management

**Resource:** `App\Filament\Resources\Articles\ArticleResource`

- **List page** (`ListArticles`):
    - **Columns:**
        - `title`
        - `type_label` — virtual column based on whether an itinerary exists:
            - 📝 Blog Article
            - 🧭 Itinerary
        - `categories.title` — comma-separated badges.
        - `comments_count` — uses `->counts('comments')` on the relation.
        - `pinned` (boolean icon)
        - `published` (boolean icon)
        - `created_at`, `updated_at` (sortable, toggleable).
    - **Custom type sorting:**
        - Uses `EXISTS (SELECT 1 FROM article_itineraries WHERE article_itineraries.article_id = articles.id)` inside `ORDER BY` to group article types.

- **Actions on each row:**
    - `Edit` — standard Filament `EditAction`.
    - `ActionGroup` with:
        - **Go to**
            - Opens the public article page in a new tab:
              ```php
              route('articles.show', ['id' => $record->id, 'slug' => $record->slug])
              ```
        - **Preview** (currently disabled)
            - Configured to open a large modal with an `<iframe>` using the `admin.article-preview-modal` Blade view.

- **Forms:**
    - Reusable form schema in `ArticleForm::configure(Schema $schema)`:
        - `published`, `title`, `description`, `hero_image` (`FileUpload::make()->image()`), `hero_title`, `body` (`Textarea` / `RichEditor` depending on usage).
    - Additional inline form setup in `ListArticles` for editing content directly from the table.

- **Separate create pages:**
    - `CreateArticle` — create a regular blog article.
    - `CreateItineraryArticle` — create an itinerary article with extra fields for structured trip data.

---

### Category management

**Resource:** `App\Filament\Resources\Categories\CategoryResource`

- Basic CRUD for categories (`title`, `slug`).
- Simple form and table schemas via `CategoryForm`, `CategoryInfolist`, `CategoriesTable`.

---

### Helpers & utilities

- **PageHelper:**
    - `resolveImageUrl(string|null $image): string`
        - If the image is a local file path — converts it using `Storage::url()`.
        - If the image is an external URL like `https://picsum.photos/...` — returns it as-is.
        - Fallback placeholder: `https://placehold.co/1600x800`.
    - `makeSeoTitle(string $str = '', int $length = 0): ?string`
        - Prefixes page titles with `config('app.head_title_prefix')`
        - Optionally truncates using `Str::limit`.

- **UiHelper:**
    - Provides a mapping of semantic badge color names to Bootstrap classes.
    - `randomBadgeColor()` picks a random color key, excluding previously used ones, to visually diversify category badges.

---

### Data Model (simplified)

- **articles**
    - `id`
    - `slug`
    - `title`
    - `description`
    - `body`
    - `hero_title`
    - `hero_image`
    - `published` (bool)
    - `pinned` (bool)
    - `timestamps`

- **categories**
    - `id`
    - `title` (unique)
    - `slug` (unique)

- **article_category (pivot)**
    - `article_id` (FK → `articles.id`, cascade)
    - `category_id` (FK → `categories.id`, cascade)
    - Primary key: (`article_id`, `category_id`)
    - `timestamps`

- **article_comments**
    - `id`
    - `article_id` (FK → `articles.id`, cascade)
    - `user_name`
    - `comment`
    - `timestamps`

- **article_itineraries**
    - `id`
    - `article_id` (unique FK to `articles.id`)
    - `intro`
    - `map_url`
    - `itinerary_days` (JSON, cast to array)
    - `trip_budget` (JSON, cast to array)
    - `trip_budget_advice`
    - `results_title`
    - `results_description`
    - `timestamps`

---

### Getting Started

- **Requirements**
    - PHP 8.2+
    - Composer
    - Node.js & npm (for default Laravel tooling; the frontend here is mostly plain Blade + Bootstrap)
    - MySQL (or another DB supported by Laravel)
    - Git

- **Installation**

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/Blog-project-porfolio.git
cd Blog-project-porfolio

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies (optional, for Vite/build)
npm install

# 4. Create environment file
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Configure DB settings in .env
# DB_DATABASE=...
# DB_USERNAME=...
# DB_PASSWORD=...

# 7. Run migrations and seeders
php artisan migrate
php artisan db:seed --class=ArticlesSeeder

# 8. Run the dev server
php artisan serve
```

- The application will be available at `http://localhost:8000`.

    - **Public blog:** `http://localhost:8000/`
    - **Admin panel (Filament):** `http://localhost:8000/admin`

---

### Project Structure (high level)

```
app/
  Helpers/
    PageHelper.php
    UiHelper.php
  Http/
    Controllers/
      IndexController.php
      ArticleController.php
      CommentController.php
    Requests/
      StoreArticleCommentRequest.php
  Models/
    Article.php
    Category.php
    ArticleComment.php
    ArticleItinerary.php
  Filament/
    Resources/
      Articles/
        ArticleResource.php
        Pages/
        Tables/
        Schemas/
      Categories/
        CategoryResource.php
        Pages/
        Tables/
        Schemas/

resources/
  views/
    frontend/
      layout/
        layout.blade.php
        __header.blade.php
        __hero.blade.php
        __article_comments.blade.php
      index.blade.php
      articles/
        default.blade.php
        article_itinerary.blade.php
    admin/
      article-preview-modal.blade.php
    filament/
      resources/
        articles/pages/article-preview.blade.php

public/
  js/
    article_comments.js
```

---

### Roadmap / Possible Improvements

Some ideas that can be implemented later:

- Add full-text search by articles and categories.
- Add filters on the public index page (by category, type, pinned).
- Extend the public API (read-only endpoints for articles and itineraries).
- Add user authentication for managing comments.
- Add simple tests for controllers, API, and helpers.
- Deploy the project to a public hosting and add the demo link here.

---

### Why this project is in my portfolio

This project demonstrates:

- Up-to-date Laravel 12 and Filament 4 usage.
- Real-world patterns: migrations, seeders, Eloquent relationships, form requests, helpers.
- Integration between:
    - Filament admin panel,
    - Blade frontend,
    - JSON API for AJAX comments.
- Ability to ship a small but complete product: from DB schema to UX details.

