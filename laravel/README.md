# InstaApp Backend

This is the Laravel backend for InstaApp.

## Setup

1. Copy `.env.example` to `.env` and configure your database settings.
2. Run migrations and seeders:
    ```
    php artisan migrate --seed
    ```
3. Serve the application:
    ```
    php artisan serve
    ```

## Features

-   User registration and login with Laravel Sanctum authentication.
-   Post creation, editing, deletion by owners.
-   Like and comment functionality.
-   Authorization policies for access control.

## API Endpoints

-   `POST /api/register` - Register a new user.
-   `POST /api/login` - Login and get access token.
-   `POST /api/logout` - Logout and revoke token (requires auth).
-   `GET /api/posts` - List all posts.
-   `POST /api/posts` - Create a new post (requires auth).
-   `GET /api/posts/{post}` - Get a single post.
-   `PUT /api/posts/{post}` - Update a post (owner only).
-   `DELETE /api/posts/{post}` - Delete a post (owner only).
-   `POST /api/posts/{post}/like` - Like a post (requires auth).
-   `DELETE /api/posts/{post}/like` - Unlike a post (requires auth).
-   `GET /api/posts/{post}/comments` - List comments on a post.
-   `POST /api/posts/{post}/comments` - Add comment to a post (requires auth).
-   `GET /api/comments/{comment}` - Get a single comment.
-   `PUT /api/comments/{comment}` - Update a comment (owner only).
-   `DELETE /api/comments/{comment}` - Delete a comment (owner only).
