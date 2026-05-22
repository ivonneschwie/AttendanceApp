# API Endpoints Documentation

The Attendance Scanner application exposes API endpoints that can be integrated with mobile scanning apps or external attendance scanners. These APIs handle checking in and checking out students using their unique student IDs.

---

## 🔒 Base Configuration

- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`

---

## 📌 1. Mark Room Attendance

Marks a student's check-in (time-in) or check-out (time-out) inside a specific class/room attendance sheet.

### Request Definition

- **URL**: `/api/attendance`
- **Method**: `POST`
- **Payload Type**: `JSON`

### Request Body

| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `studentUid` | `string` | **Yes** | The unique Firebase UID of the student scanning in/out. |
| `roomCode` | `string` | **Yes** | The unique code of the room (e.g., `RM-XYZ123`). |
| `listId` | `string` | **Yes** | The unique ID of the specific attendance sheet session. |

#### Example Request Body
```json
{
  "studentUid": "user_firebase_uid_12345",
  "roomCode": "RM-CS101",
  "listId": "attendance_session_may23"
}
```

---

### Response Specifications

#### Case 1: First Scan (Time-In Success)
Returned when a student scans in for the first time in this session.
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": true,
    "message": "Successfully timed in"
  }
  ```

#### Case 2: Second Scan (Time-Out Success)
Returned when a student scans a second time to leave.
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": true,
    "message": "Successfully timed out"
  }
  ```

#### Case 3: Subsequent Scans (Time-Out Updated)
Returned if a student scans again after they have already timed out. Updates their `time_out` value.
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": true,
    "message": "Time out updated"
  }
  ```

#### Case 4: Invalid Student for Room
Returned if the student is not registered inside this room's student list in Firebase.
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": false,
    "message": "Invalid student for this room"
  }
  ```

---

## 📌 2. Mark Event Attendance

Marks a student's check-in or check-out inside a specific event.

### Request Definition

- **URL**: `/api/event-attendance`
- **Method**: `POST`
- **Payload Type**: `JSON`

### Request Body

| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `studentUid` | `string` | **Yes** | The unique Firebase UID of the student. |
| `eventId` | `string` | **Yes** | The unique ID of the event. |

#### Example Request Body
```json
{
  "studentUid": "user_firebase_uid_12345",
  "eventId": "event_annual_gala_2026"
}
```

---

### Response Specifications

#### Case 1: First Scan (Time-In Success)
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": true,
    "message": "Successfully timed in"
  }
  ```

#### Case 2: Second Scan (Time-Out Success)
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": true,
    "message": "Successfully timed out"
  }
  ```

#### Case 3: Subsequent Scans (Time-Out Updated)
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": true,
    "message": "Time out updated"
  }
  ```

#### Case 4: Invalid Student
Returned if the student's account does not exist inside Firebase.
- **Status Code**: `200 OK`
- **Body**:
  ```json
  {
    "success": false,
    "message": "Invalid student"
  }
  ```
