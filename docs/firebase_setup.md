# Firebase Setup & Configuration Guide

This guide explains how to set up and configure the Firebase project required for the Attendance Scanner application. The application utilizes Firebase for **Authentication**, **Realtime Database**, and **Cloud Messaging**.

---

## 🛠️ Step 1: Create a Firebase Project

1. Go to the [Firebase Console](https://console.firebase.google.com/).
2. Click **Add Project** (or **Create a Project**).
3. Enter a project name (e.g., `attendance-scanner-app`), accept the terms, and click **Continue**.
4. Choose whether to enable Google Analytics (optional, can be disabled for local development) and click **Create Project**.

---

## 🔑 Step 2: Set Up Firebase Authentication

The application uses Firebase Auth to register and authenticate Instructors, Students, and Administrators.

1. In the Firebase console left sidebar, navigate to **Build > Authentication**.
2. Click **Get Started**.
3. Under the **Sign-in method** tab, select **Email/Password**.
4. Enable the **Email/Password** provider and click **Save**.

### 🌐 Authorizing Local Domains
For web login authentication and redirection to function correctly, your local application domains must be whitelisted:
1. Still within **Authentication**, click the **Settings** tab at the top of the panel.
2. Select **Authorized domains** from the left column.
3. Check the list. `localhost` is usually added by default.
4. If you are accessing the server using the IP address (e.g. `http://127.0.0.1:8000`), click **Add domain** and enter:
   ```
   127.0.0.1
   ```
5. Click **Add** to save.

---

## 💾 Step 3: Set Up Realtime Database

The application stores rooms, students, events, and attendance records inside the Firebase Realtime Database.

1. In the left sidebar, navigate to **Build > Realtime Database**.
2. Click **Create Database**.
3. Choose your database location (e.g., Singapore/Asia-Southeast1 or United States) and click **Next**.
4. Start in **Locked Mode** and click **Enable**.
5. Copy your **Realtime Database URL** (e.g., `https://your-project-id-default-rtdb.firebaseio.com/`). You will need this for your Laravel configuration.

### Configure Database Rules

To allow the backend and student scanning apps to write records correctly while securing data, you should configure rules. 
Navigate to the **Rules** tab in the Realtime Database and replace the rules with the following schema:

```json
{
  "rules": {
    ".read": "auth != null",
    ".write": "auth != null",
    "users": {
      "$uid": {
        ".read": "true",
        ".write": "true"
      }
    },
    "rooms": {
      "$roomCode": {
        ".read": "true",
        ".write": "true"
      }
    },
    "attendance": {
      "$roomCode": {
        ".read": "true",
        ".write": "true"
      }
    },
    "event-attendance": {
      "$eventId": {
        ".read": "true",
        ".write": "true"
      }
    }
  }
}
```
*Note: For production environments, further restrict these rules so that users can only read/write their own records or records of rooms they belong to.*

---

## 📄 Step 4: Generate Service Account Credentials

To authenticate the Laravel backend with Firebase services, you must download a service account private key.

1. In the Firebase Console, click the **Gear Icon (⚙️)** next to "Project Overview" in the top-left sidebar and select **Project settings**.
2. Go to the **Service accounts** tab.
3. Verify that the **Firebase Admin SDK** option is selected.
4. Click **Generate new private key** at the bottom of the page.
5. Confirm by clicking **Generate key**. A `.json` file containing your credentials will download to your machine.

---

## 📁 Step 5: Place Credentials in Laravel App

1. Rename the downloaded `.json` file to `firebase-credentials.json`.
2. Move this file to the following path in your cloned repository:
   `storage/app/firebase/firebase-credentials.json`
   *(Create the `firebase` directory inside `storage/app/` if it does not exist)*.
3. Update your [.env](../.env) file with your **Firebase Project ID** and the credentials file path:
   ```ini
   FIREBASE_CREDENTIALS=storage/app/firebase/firebase-credentials.json
   FIREBASE_PROJECT_ID=your-firebase-project-id
   ```
4. Open [FirebaseService.php](../app/Services/FirebaseService.php) and verify or update the database URI:
   ```php
   ->withDatabaseUri('https://your-project-id-default-rtdb.firebaseio.com');
   ```

---

## ⚠️ Troubleshooting: Windows cURL Error 60 (SSL Certificate Problem)

When running the application on Windows, you might encounter a connection error when trying to log in or register:
> `cURL error 60: SSL certificate problem: unable to get local issuer certificate`

This happens because PHP on Windows does not have a built-in certificate authority (CA) bundle to verify SSL certificates.

### How to resolve:
1. We have downloaded the official curl CA bundle `cacert.pem` and saved it to:
   ```
   .\AttendanceApp\storage\app\cacert.pem
   ```
2. Open your active `php.ini` file. (To find which file is active, run `php --ini` in your command prompt).
3. Locate the `[curl]` and `[openssl]` configurations.
4. **Remove the leading semicolon (`;`)** from the `curl.cainfo` and `openssl.cafile` lines (uncomment them) and set their values to the absolute path of `cacert.pem`:
   ```ini
   [curl]
   curl.cainfo = ".\AttendanceApp\storage\app\cacert.pem"

   [openssl]
   openssl.cafile = ".\AttendanceApp\storage\app\cacert.pem"
   ```
   > [!IMPORTANT]
   > Ensure the leading semicolon (`;`) is completely removed from the start of the configuration lines, otherwise PHP will ignore these settings.
5. Save the `php.ini` file.
6. **Restart** your terminal running `php artisan serve` for the changes to take effect.

