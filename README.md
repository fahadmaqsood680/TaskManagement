# Task Management System

A Task Management System built with Laravel 12, featuring task creation, assignment, prioritization, progress tracking, and user management. The system is designed to handle categorization, and provide a calendar view to manage tasks.

## Features

- **Role-based Authentication**: Admin, Manager, and User roles.
  - **Admin**: Can assign tasks to both Managers and Users.
  - **Manager**: Can assign tasks only to Users.
  - **User**: Can only view and manage tasks assigned to them.
- **Task Management**: Create, update, and manage tasks with titles, descriptions, priorities, due dates, and categories.
- **Task Progress**: Track task progress with a progress bar and mark tasks as 'pending', 'in progress', or 'completed'.
- **Comments**: Comment section appears only for tasks marked as completed.
- **Calendar View**: Visualize tasks in a calendar format.
- **Sorting and Filtering**: Easily sort and filter tasks by priority, category, and status.

## Installation

### Prerequisites

- PHP >= 8.0
- Composer
- Laravel 12
- MySQL or another database

### Installation Steps

1. Clone the repository:

   git clone https://github.com/fahadmaqsood680/TaskManagement.git

2. Navigate to the project folder:
  cd TaskManagement

3. Install the dependencies:
   composer install

4. Set up the environment file:
    cp .env.example .env

5. Generate the application key:
    php artisan key:generate

6. Set up your database in the .env file:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=task_management
    DB_USERNAME=root
    DB_PASSWORD=

7. Run the migrations:
   php artisan migrate

8. Seed the database with sample data (optional):
   php artisan db:seed

9. Run the development server:
   php artisan serve

10. Open your browser and visit http://localhost:8000





