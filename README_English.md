# Task Manager — README (English)

## Project Overview

This is a simple PHP + MySQL task manager web app. It provides a lightweight backend in the `api/` directory and a minimal front-end that uses `script.js` and `style.css` to interact with the API. The app supports user registration, login, task creation, update, deletion, and list retrieval.

## Features

- User registration and authentication
- Create, read, update, delete tasks (per-user)
- Simple web UI using `index.php`, `script.js`, and `style.css`
- REST-like endpoints in `api/`

## Requirements

- PHP 7.4+ (or compatible)
- MySQL / MariaDB
- A web server (Apache via XAMPP is supported)

## Installation

1. Place the project folder in your web server root (example: XAMPP `htdocs/taskmanager`).
2. Create a new MySQL database (for example: `taskmanager`).
3. Update database credentials in `api/config.php` to match your environment.
4. Import the database schema or create the tables (example SQL shown below).
5. Start the web server and open the site in your browser (for example: `http://localhost/taskmanager`).

## Database Schema (example)

Use this sample SQL to create the minimal tables the app expects. Adjust types and names if your `api/config.php` uses different database or table names.

```
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	title VARCHAR(255) NOT NULL,
	description TEXT,
	completed TINYINT(1) DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Reset / Clean Database (quick SQL)

To remove all tasks and users and reset auto-increment counters, run the following SQL statements (for example in phpMyAdmin or via a MySQL client):

```
DELETE FROM tasks;
DELETE FROM users;
ALTER TABLE tasks AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
```

Important: Running these commands will permanently delete all user and task data. Only run them on development or when you explicitly want to wipe the database.

## API Endpoints

The `api/` folder implements the backend endpoints. Brief summary:

- `api/register.php` — POST: `username`, `password` — create a new user
- `api/login.php` — POST: `username`, `password` — authenticate and start session
- `api/logout.php` — GET/POST — destroy session
- `api/getTasks.php` — GET — returns the logged-in user's tasks
- `api/addTask.php` — POST: `title`, `description` — add a new task for the logged-in user
- `api/updateTask.php` — POST: `id`, `title`, `description`, `completed` — update a task (must belong to user)
- `api/deleteTask.php` — POST: `id` — delete a task (must belong to user)

Check the PHP files in `api/` for exact parameter names and expected responses.

## Example Requests (curl)

Register:

```
curl -X POST -d "username=alice&password=secret" http://localhost/taskmanager/api/register.php
```

Login (store cookies between requests):

```
curl -c cookies.txt -X POST -d "username=alice&password=secret" http://localhost/taskmanager/api/login.php
```

Get tasks (using saved cookies):

```
curl -b cookies.txt http://localhost/taskmanager/api/getTasks.php
```

Add task:

```
curl -b cookies.txt -X POST -d "title=New task&description=Details" http://localhost/taskmanager/api/addTask.php
```

Delete task:

```
curl -b cookies.txt -X POST -d "id=42" http://localhost/taskmanager/api/deleteTask.php
```

## Configuration

- Update `api/config.php` with your database host, username, password, and database name.
- If you run the app in a subfolder, ensure the base URL references in `index.php` and `script.js` match your hosting path.

## Security Notes

- This project stores passwords in the database — ensure `api/register.php` hashes passwords securely (recommended: `password_hash()` / `password_verify()` in PHP).
- Protect `api/` endpoints using sessions and validate that actions operate only on the authenticated user's data.
- For production, use HTTPS and follow general best practices for PHP applications.

## Troubleshooting

- If pages are blank or PHP errors appear, enable `display_errors` during development or check your server error logs.
- If database connections fail, verify credentials in `api/config.php` and that the MySQL server is running.

## File Map

- `index.php` — front-end page
- `db.php` — (if present) DB helper; verify contents before use
- `api/` — backend endpoints (`register.php`, `login.php`, `getTasks.php`, `addTask.php`, `updateTask.php`, `deleteTask.php`, `logout.php`, `config.php`)
- `script.js`, `style.css` — front-end assets

## License

MIT — feel free to adapt and re-use. Include attribution if you redistribute.

## Contact / Next Steps

If you would like to contact me for more information or simply give me feedback, please don’t hesitate:

Email – olivierr.joshua@gmail.com

---
Last updated: February 14, 2026
