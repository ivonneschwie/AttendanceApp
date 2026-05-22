# Attendance Scanner

This is a web-based attendance scanner application built with Laravel and Firebase. It allows instructors to create rooms, manage students, and track attendance using QR codes.

---

## 📋 Table of Contents

- [System Prerequisites](#system-prerequisites)
- [Required PHP Extensions](#required-php-extensions)
  - [Enabling Extensions on Windows](#enabling-extensions-on-windows)
  - [Enabling Extensions on Linux/macOS](#enabling-extensions-on-linuxmacos)
- [Step-by-Step Installation Guide](#step-by-step-installation-guide)
  - [1. Clone the Repository](#1-clone-the-repository)
  - [2. Install Composer Dependencies](#2-install-composer-dependencies)
  - [3. Install JavaScript Dependencies](#3-install-javascript-dependencies)
  - [4. Environment Setup](#4-environment-setup)
  - [5. Firebase Credentials Setup](#5-firebase-credentials-setup)
  - [6. Compile Frontend Assets](#6-compile-frontend-assets)
  - [7. Run the Development Server](#7-run-the-development-server)
  - [8. Windows SSL Certificate Setup](#8-windows-ssl-certificate-setup-required-for-firebase-connection)
- [🛠️ Available Commands](#️-available-commands)
- [📖 Additional Documentation](#-additional-documentation)

---

## 📖 Additional Documentation

For more specialized details, refer to the following documents:

*   [Firebase Setup & Credentials Configuration Guide](docs/firebase_setup.md)
*   [API Endpoints Specification](docs/api_endpoints.md)
*   [Application Architecture & System Overview](docs/architecture.md)

---

## 🛠️ System Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP**: `^8.2` (PHP 8.2 or higher is required)
- **Composer**: [Dependency Manager for PHP](https://getcomposer.org/)
- **Node.js**: [JavaScript runtime environment](https://nodejs.org/) (includes `npm`)

---

## 🔌 Required PHP Extensions

To ensure all backend dependencies work correctly (including Firebase, PHPSpreadsheet, and QR code generation), the following PHP extensions **must** be enabled:

| Extension | Purpose | Required By |
| :--- | :--- | :--- |
| `curl` | HTTP client operations | `kreait/firebase-php` |
| `gd` or `imagick` | Image manipulation and QR code generation | `simplesoftwareio/simple-qrcode`, `phpoffice/phpspreadsheet` |
| `zip` | Reading and writing compressed spreadsheet files | `phpoffice/phpspreadsheet` |
| `mbstring` | Multi-byte string manipulation | Core Laravel and various packages |
| `openssl` | Encrypted communication | `kreait/firebase-php`, security libraries |
| `xml` / `libxml` / `dom` | XML parser | `phpoffice/phpspreadsheet` |
| `sodium` | Cryptographic features | Firebase PHP SDK |

### Enabling Extensions on Windows

1. Locate your active `php.ini` file (typically found in your PHP installation directory, e.g., `C:\php\php.ini` or inside your WampServer/XAMPP directory).
2. Open the file in a text editor.
3. Search for the extension lines and remove the leading semicolon `;` to uncomment them:
   ```ini
   extension=curl
   extension=gd
   extension=zip
   extension=mbstring
   extension=openssl
   extension=sodium
   extension=fileinfo
   ```
4. Save the file and restart your terminal or web server (e.g., Apache/Nginx) for the changes to take effect.

### Enabling Extensions on Linux/macOS

Run the following commands matching your OS and package manager (example for Debian/Ubuntu with PHP 8.2):
```bash
sudo apt-get update
sudo apt-get install php8.2-curl php8.2-gd php8.2-zip php8.2-mbstring php8.2-xml php8.2-sodium
```

---

## 🚀 Step-by-Step Installation Guide

> [!NOTE]
> **No Database Setup or Migrations Required:** This application runs entirely on Firebase. You do **not** need to set up SQLite, MySQL, or run `php artisan migrate`. Local sessions and caching are configured to use file storage automatically.

Follow these steps sequentially to set up the project locally:

### 1. Clone the Repository
Clone the codebase and navigate to the project directory:
```bash
git clone https://github.com/ivonneschwie/AttendanceApp.git
cd AttendanceApp
```

### 2. Install Composer Dependencies
Download and install backend packages:
```bash
composer install
```

### 3. Install JavaScript Dependencies
Download and install frontend NPM packages:
```bash
npm install
```

### 4. Environment Setup
Create your local environment configuration file:
1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
2. Generate an application encryption key:
   ```bash
   php artisan key:generate
   ```
3. Open the `.env` file. 
   Make sure to configure:
   * `FIREBASE_PROJECT_ID=your-firebase-project-id` (Set this to your Firebase Project ID)
   * `FIREBASE_CREDENTIALS=storage/app/firebase/firebase-credentials.json` 

### 5. Firebase Credentials Setup
This application connects to Firebase for authentication, database syncing, and other backend operations. The service account credentials must be placed in a dedicated JSON file separate from the `.env` file.

1. Create a folder named `firebase` inside the `storage/app/` directory if it does not already exist:
   - **Command Line (Bash):** `mkdir -p storage/app/firebase`
   - **Command Line (PowerShell):** `New-Item -ItemType Directory -Force -Path storage\app\firebase`
2. Download your Firebase Private Key JSON from the **Firebase Console** (Project Settings > Service Accounts > Generate New Private Key).
3. Place the downloaded JSON file in the created directory and rename it exactly to:
   ```
   storage/app/firebase/firebase-credentials.json
   ```
4. Open your `.env` file and verify or configure the Firebase configuration settings:
   ```ini
   FIREBASE_CREDENTIALS=storage/app/firebase/firebase-credentials.json
   FIREBASE_PROJECT_ID=your-firebase-project-id
   ```

### 6. Compile Frontend Assets
Build your styles and scripts using Vite:
- **For Development (hot reloading):**
  ```bash
  npm run dev
  ```
- **For Production (static build):**
  ```bash
  npm run build
  ```

### 7. Run the Development Server
Start the local Laravel development server:
```bash
php artisan serve
```
The application will now be running and accessible at: **`http://127.0.0.1:8000`**

### 8. Windows SSL Certificate Setup (Required for Firebase Connection)
When running the application on Windows, you might encounter a connection error when trying to log in or register:
> `cURL error 60: SSL certificate problem: unable to get local issuer certificate`

This happens because PHP on Windows does not include a root certificate authority (CA) bundle by default.

**To resolve this:**
1. A valid CA bundle (`cacert.pem`) has already been downloaded to the repository at:
   `D:\GithubRepos\AttendanceApp\storage\app\cacert.pem`
2. Open your active `php.ini` file. (To locate the active file path, run `php --ini` in your terminal).
3. Search for the `curl.cainfo` and `openssl.cafile` directives.
4. **Remove the leading semicolon (`;`)** to uncomment these lines, and set their values to the absolute path of `cacert.pem`:
   ```ini
   [curl]
   curl.cainfo = "D:\GithubRepos\AttendanceApp\storage\app\cacert.pem"

   [openssl]
   openssl.cafile = "D:\GithubRepos\AttendanceApp\storage\app\cacert.pem"
   ```
   > [!IMPORTANT]
   > Make sure to remove the leading semicolon (`;`) otherwise PHP will ignore the configuration!
5. Save `php.ini` and **restart** your `php artisan serve` command/terminal.

---

## 🛠️ Available Commands

Here is a summary of commonly used commands in this repository:

* **`php artisan serve`**: Starts the Laravel development server.
* **`npm run dev`**: Starts the Vite hot-reloading development server.
* **`npm run build`**: Compiles assets for production.
* **`php artisan test`**: Runs the PHPUnit test suite.
* **composer install**: Installs PHP package dependencies.
* **npm install**: Installs NPM package dependencies.

