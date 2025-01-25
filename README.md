
# Laravel API - Task Management System

This is a simple RESTful API built with the Laravel framework, Docker, and PostgreSQL. It allows authenticated users to manage a list of tasks, with CRUD operations and filtering by task status (completed or not completed).

---

## **Table of Contents**

- [Prerequisites](#prerequisites)
- [Step-by-Step Deployment](#step-by-step-deployment)
- [Testing the API](#testing-the-api)
- [API Routes](#api-routes)
- [Project Structure](#project-structure)

---

## **Prerequisites**

Before you begin, ensure that you have the following installed on your system:

- **Docker**: For running the application inside a containerized environment.
- **Docker Compose**: For managing multi-container applications.
- **PHP 8.x** and **Composer** (if you want to work locally without Docker).
- **Postman** or **cURL**: For testing API endpoints.
  
If you're running the project with Docker (recommended), these tools should already be handled through Laravel Sail.

---

## **Step-by-Step Deployment**

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/your-username/task-management-api.git
cd task-management-api
```

### 2. Set Up Environment Variables

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the `.env` file with your local configurations. Make sure the database settings match the PostgreSQL container if you're using Docker.

Example:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=task_management
DB_USERNAME=postgres
DB_PASSWORD=secret
```

### 3. Start Docker Containers

Start the Laravel application with Docker using Laravel Sail:

```bash
./vendor/bin/sail up -d
```

This will launch the application, PostgreSQL, and other necessary services in Docker containers.
Dependencies will automatically be installed when Sail runs.

### 4. Run Migrations

To create the necessary tables in your database, run:

```bash
./vendor/bin/sail artisan migrate
```

This will run the migrations for tasks and users in the PostgreSQL database.

### 5. Test the API

Once everything is set up, your application should be accessible at:

- **API URL**: `http://localhost` (or `http://127.0.0.1`)

---

## **Testing the API**

### Authentication Routes

- **Register a new user**:  
  `POST /api/register`  
  Request body:  
  ```json
  {
    "email": "test@example.com",
    "password": "password"
  }
  ```

- **Login**:  
  `POST /api/login`  
  Request body:  
  ```json
  {
    "email": "test@example.com",
    "password": "password"
  }
  ```

  Response:  
  ```json
  {
    "access_token": "your-access-token"
  }
  ```

- **Verify Email**:  
  `GET /api/verify-email/{id}/{hash}`  
  Example:  
  `GET /api/verify-email/1/abc123`

- **Logout**:  
  `POST /api/logout`  
  Authenticated request with a Bearer token.

### Task Routes (Protected by Authentication)

- **Get all tasks**:  
  `GET /api/tasks`  
  (Authenticated)

- **Create a new task**:  
  `POST /api/tasks`  
  Request body:  
  ```json
  {
    "name": "New Task",
    "description": "Description of the new task",
    "is_completed": false
  }
  ```

- **Show a specific task**:  
  `GET /api/tasks/{id}`  
  Example:  
  `GET /api/tasks/1`

- **Update a task**:  
  `PATCH /api/tasks/{id}`  
  Request body:  
  ```json
  {
    "name": "Updated Task",
    "description": "Updated description",
    "is_completed": true
  }
  ```

- **Delete a task**:  
  `DELETE /api/tasks/{id}`  
  Example:  
  `DELETE /api/tasks/1`

- **Filter tasks by status**:  
  `GET /api/tasks/filter/{status}`  
  Example:  
  `GET /api/tasks/filter/completed`

  Status options:  
  - `completed`
  - `not_completed`

---

## **API Routes Summary**

| Method | Endpoint                                | Description                                   |
|--------|-----------------------------------------|-----------------------------------------------|
| POST   | /api/register                           | Register a new user                           |
| POST   | /api/login                              | Login and get an access token                |
| GET    | /api/verify-email/{id}/{hash}           | Verify user email                            |
| POST   | /api/logout                             | Logout the authenticated user                |
| GET    | /api/tasks                              | List all tasks (requires auth)               |
| POST   | /api/tasks                              | Create a new task (requires auth)            |
| GET    | /api/tasks/{id}                         | Show details of a specific task (requires auth)|
| PATCH  | /api/tasks/{id}                         | Update a task (requires auth)                |
| DELETE | /api/tasks/{id}                         | Delete a task (requires auth)                |
| GET    | /api/tasks/filter/{status}              | Filter tasks by status (requires auth)       |

---

## **Project Structure**

Here’s an overview of the key project files and directories:

- **app/Http/Controllers**: Contains controllers like `TaskController`, `AuthenticationController`, etc.
- **database/migrations**: Contains migration files for setting up tables (tasks, users, etc.).
- **routes/api.php**: API route definitions.
- **.env**: Environment configuration file.
- **docker-compose.yml**: Docker Compose configuration for multi-container setup.

---

