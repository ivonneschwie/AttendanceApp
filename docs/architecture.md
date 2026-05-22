# Application Architecture & System Overview

This document provides a technical overview of the Attendance Scanner application, detailing its hybrid database structure, directory layouts, and user permissions.

---

## 🏗️ Core Architecture Overview

The application is built on a serverless database architecture, combining the **Laravel Framework (v12)** with **Firebase** services for all persistent application data.

### Storage Roles

```
                     ┌────────────────────────┐
                     │   Attendance App Web   │
                     └───────────┬────────────┘
                                 │
                 ┌───────────────┴───────────────┐
                 ▼                               ▼
     ┌──────────────────────┐        ┌──────────────────────┐
     │  Local File System   │        │   Firebase Service   │
     ├──────────────────────┤        ├──────────────────────┤
     │ - Laravel Sessions   │        │ - Firebase Auth      │
     │ - Cache Data         │        │ - Rooms & Events     │
     │ - Temp File Exports  │        │ - Student Lists      │
     │                      │        │ - Attendance Logs    │
     └──────────────────────┘        └──────────────────────┘
```

1. **Local File System (`storage/framework/`)**:
   - Stores session records (`SESSION_DRIVER=file`) and cached variables (`CACHE_STORE=file`) as temporary files on disk.
   - Eliminates the need to maintain or initialize a local database instance (such as SQLite or MySQL).

2. **Firebase Realtime Database & Authentication**:
   - **Authentication**: Stores and validates login/signup credentials for all users.
   - **Realtime Database**: Acts as the primary database for all application entities. Any update made by instructors or scans from students updates instantly.

---

## 👥 User Roles & Access Control

The application implements three key user roles, each guarded by specific middleware:

| Role | Middleware | Privileges |
| :--- | :--- | :--- |
| **Admin** | `admin` | Register new Instructors, view administrator dashboard stats. |
| **Instructor** | `instructor` | Create rooms and events, generate QR codes, manage student rosters, view attendance lists, correct attendance records, and export attendance sheets. |
| **Student** | `student` | Register accounts, join rooms by code, and view their registered room information. |

### Routing Structure

Routes are grouped and isolated using Laravel middleware in `routes/web.php`:
- **Public Routes**: `/`, `/login`, `/signup`, `/logout`
- **Admin Group**: `/admin/*` (uses the `admin` middleware)
- **Instructor Group**: `/instructor/*` (uses the `instructor` middleware)
- **Student Group**: `/student/*` (uses the `student` middleware)
- **API Group**: `/api/*` (handles anonymous/scanned updates from checking machines or mobile applications)

---

## 📁 Key File Map

- [FirebaseService.php](file:///d:/GithubRepos/AttendanceApp/app/Services/FirebaseService.php): Creates the connections to Firebase Authentication, Realtime Database, and Cloud Messaging using the service account credentials.
- [FirebaseController.php](file:///d:/GithubRepos/AttendanceApp/app/Http/Controllers/FirebaseController.php): Manages registration, logins, and landing dashboards by communicating with Firebase Auth and DB.
- [RoomController.php](file:///d:/GithubRepos/AttendanceApp/app/Http/Controllers/RoomController.php): Handles creating/viewing rooms, adding classes, modifying student rosters, and modifying attendance sheets.
- [EventController.php](file:///d:/GithubRepos/AttendanceApp/app/Http/Controllers/EventController.php): Handles creating/managing events (which differ from classroom rooms because they are one-off assemblies with different check-in lists).
- [ExportController.php](file:///d:/GithubRepos/AttendanceApp/app/Http/Controllers/ExportController.php): Uses the `PhpSpreadsheet` library to build Excel formats of attendance lists and initiates browser downloads.
- [ApiController.php](file:///d:/GithubRepos/AttendanceApp/app/Http/Controllers/ApiController.php): Provides endpoints for hardware scanners or scanning devices to register student arrivals/departures.
- [firebase-credentials.json](file:///d:/GithubRepos/AttendanceApp/storage/app/firebase/firebase-credentials.json): The key file containing Firebase credentials (git-ignored for security).
