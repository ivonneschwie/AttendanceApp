# Attendance Scanner

This is a web-based attendance scanner application built with Laravel and Firebase. It allows instructors to create rooms, manage students, and track attendance using QR codes.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

*   **PHP:** Version 8.2 or higher
*   **Composer:** [Dependency Manager for PHP](https://getcomposer.org/)
*   **Node.js:** [JavaScript runtime environment](https://nodejs.org/) (which includes npm)
*   **A web server:** (e.g., Nginx, Apache) or you can use the built-in Laravel development server.

## Dependencies

### Backend (PHP - Composer)

*   [php: ^8.2](https://www.php.net/)
*   [kreait/firebase-php: ^7.0](https://firebase-php.readthedocs.io/)
*   [kreait/laravel-firebase: ^6.0](https://github.com/kreait/laravel-firebase)
*   [laravel/framework: ^12.0](https://laravel.com/)
*   [laravel/tinker: ^2.10.1](https://laravel.com/docs/12.x/artisan#tinker)
*   [phpoffice/phpspreadsheet: ^5.5](https://phpspreadsheet.readthedocs.io/)
*   [simplesoftwareio/simple-qrcode: ^4.2](https://www.simplesoftware.io/simple-qrcode)

### Frontend (JavaScript - npm)

*   [@tailwindcss/vite: ^4.0.0](https://tailwindcss.com/docs/vite)
*   [axios: ^1.11.0](https://axios-http.com/)
*   [concurrently: ^9.0.1](https://github.com/open-cli-tools/concurrently)
*   [laravel-vite-plugin: ^2.0.0](https://laravel-vite.netlify.app/)
*   [tailwindcss: ^4.0.0](https://tailwindcss.com/)
*   [vite: ^7.0.7](https://vitejs.dev/)

## Installation Instructions

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/Teejay-corp/attendance-scanner.git
    cd attendance-scanner
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**

    ```bash
    npm install
    ```

4.  **Set up your environment file:**

    *   Copy the `.env.example` file to a new file named `.env`:

        ```bash
        cp .env.example .env
        ```

    *   Generate an application key:

        ```bash
        php artisan key:generate
        ```

    *   Configure your Firebase credentials in the `.env` file. You will need to add the following variables:

        ```
        FIREBASE_PROJECT_ID=your-firebase-project-id
        FIREBASE_PRIVATE_KEY=your-firebase-private-key
        FIREBASE_CLIENT_EMAIL=your-firebase-client-email
        FIREBASE_DATABASE_URL=your-firebase-database-url
        ```

5.  **Build the frontend assets:**

    ```bash
    npm run build
    ```

6.  **Run the application:**

    ```bash
    php artisan serve
    ```

    The application will be available at `http://127.0.0.1:8000`.

## Available Commands

*   **`npm run dev`**: Start the Vite development server.
*   **`npm run build`**: Build the frontend assets for production.
*   **`php artisan serve`**: Start the Laravel development server.
*   **`php artisan test`**: Run the application's test suite.
*   **`composer install`**: Install PHP dependencies.
*   **`npm install`**: Install JavaScript dependencies.

