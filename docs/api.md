# API Documentation

## Base URL
`/api`

---

## Articles

### 1. Get All Articles
Retrieve a list of all articles.

*   **Endpoint:** `GET /articles`
*   **Name:** `api.articles.index`
*   **Controller:** `App\Http\Controllers\Api\ArticleController@index`
*   **Response:**
    *   `200 OK`: JSON array of article objects.

### 2. Create Article
Create a new article.

*   **Endpoint:** `POST /articles`
*   **Name:** `api.articles.store`
*   **Controller:** `App\Http\Controllers\Api\ArticleController@store`
*   **Request Body (Form-Data/Multipart):**
    *   `slug` (string, required): Unique URL slug (max 100 chars).
    *   `title` (string, required): Article title (max 255 chars).
    *   `description` (string, required): Short description.
    *   `body` (string, optional): Main content of the article.
    *   `hero_title` (string, optional): Title for the hero section.
    *   `hero_image` (file, optional): Image file (jpeg, png, jpg, gif, svg, max 2MB).
    *   `published` (boolean, required): `1` or `0`.
    *   `pinned` (boolean, required): `1` or `0`.
    *   `categories` (array, optional): Array of category IDs.
    *   `categories[*]` (integer): Existing category ID.
*   **Response:**
    *   `201 Created`: JSON object containing the created article and a success message.
    *   `500 Internal Server Error`: If creation fails.

### 3. Get Article Details
Retrieve a specific article by ID.

*   **Endpoint:** `GET /articles/{id}`
*   **Name:** `api.articles.show`
*   **Controller:** `App\Http\Controllers\Api\ArticleController@show`
*   **Query Parameters:**
    *   `showCategories` (optional): If present, includes associated categories in the response.
    *   `showComments` (optional): If present, includes associated comments in the response.
*   **Response:**
    *   `200 OK`: JSON object of the article.
    *   `404 Not Found`: If the article ID does not exist.

### 4. Update Article
Update an existing article.

*   **Endpoint:** `POST /articles/{id}`
    *   *Note: Uses POST for update to handle file uploads easier in Laravel/PHP, though method spoofing `_method=PUT` is often used, the route is defined as POST directly here.*
*   **Name:** `api.articles.update`
*   **Controller:** `App\Http\Controllers\Api\ArticleController@update`
*   **Request Body (Form-Data/Multipart):**
    *   `slug` (string, optional): Unique URL slug.
    *   `title` (string, optional): Article title.
    *   `description` (string, optional): Short description.
    *   `body` (string, optional): Main content.
    *   `hero_title` (string, optional): Title for the hero section.
    *   `hero_image` (file, optional): Image file.
    *   `published` (boolean, optional): `1` or `0`.
    *   `pinned` (boolean, optional): `1` or `0`.
    *   `categories` (array, optional): Array of category IDs to sync.
*   **Response:**
    *   `200 OK`: JSON object containing the updated article and success message.
    *   `404 Not Found`: If the article does not exist.

### 5. Delete Article
Remove an article.

*   **Endpoint:** `DELETE /articles/{id}`
*   **Name:** `api.articles.destroy`
*   **Controller:** `App\Http\Controllers\Api\ArticleController@destroy`
*   **Response:**
    *   `204 No Content`: Successfully deleted.

---

## Comments

### 1. Add Comment to Article
Create a comment for a specific article.

*   **Endpoint:** `POST /article/comments`
*   **Name:** `api.article.comments.store`
*   **Controller:** `App\Http\Controllers\CommentController@store`
*   **Request Body (JSON):**
    *   `article_id` (integer, required): ID of the existing article.
    *   `user_name` (string, required): Name of the commenter (max 20 chars).
    *   `comment` (string, required): The comment text (max 500 chars).
*   **Response:**
    *   `201 Created`: JSON object of the created comment.

---

## Search

### 1. Ajax Search
Perform a quick search for articles by title (autocomplete style).

*   **Endpoint:** `GET /search`
*   **Name:** `api.search.ajax`
*   **Controller:** `App\Http\Controllers\SearchController@searchAjax`
*   **Query Parameters:**
    *   `q` (string): The search query string.
*   **Behavior:**
    *   Returns an empty array if the query length is less than 2 characters.
    *   Returns up to 8 articles matching the title.
*   **Response:**
    *   `200 OK`: JSON array of objects containing `id`, `slug`, and `title`.

---

## User

### 1. Get Current User
Retrieve the currently authenticated user.

*   **Endpoint:** `GET /user`
*   **Middleware:** `auth:sanctum`
*   **Response:**
    *   `200 OK`: JSON object of the authenticated user.
    *   `401 Unauthorized`: If the user is not logged in or token is invalid.
