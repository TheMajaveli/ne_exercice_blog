# Mon Blog

A blog application built with Laravel.

## About This Project

This is a blog application developed as part of a Next-U PHP exercise. The project utilizes Laravel framework to provide a robust and scalable blogging platform.

## Features

- Article creation and management
- User-friendly interface
- Database integration for content storage
- Built with Laravel's modern PHP architecture

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL or compatible database
- Node.js & NPM (for frontend assets)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/TheMajaveli/ne_exercice_blog.git
   cd ne_exercice_blog
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file and configure your database:
   ```bash
   cp .env.example .env
   ```
   Edit `.env` with your database credentials.

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Run migrations:
   ```bash
   php artisan migrate
   ```

7. Build frontend assets:
   ```bash
   npm run build
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

Visit `http://localhost:8000` in your browser to access the application.

## Technology Stack

- **Framework**: Laravel
- **Language**: PHP
- **Database**: MySQL
- **Frontend**: Blade templates with Vite

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
