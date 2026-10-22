# Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Create simple PHP JSON API endpoints for events or students and consume them using Fetch API. Perform list, add, update, and delete operations asynchronously without full page reload.

---

## 2. API Specification (`api/events.php`)

| Method / Action | Endpoint | Payload Format | Description | Response JSON |
|---|---|---|---|---|
| **GET** | `api/events.php` | None | Returns all events array | `{"success": true, "message": "...", "data": [...]}` |
| **GET** | `api/events.php?id=X` | None | Returns single event details | `{"success": true, "data": {...}}` |
| **POST** (Create) | `api/events.php` | JSON / Form Body | Adds new event to MySQL | `{"success": true, "message": "New event created", "data": {...}}` |
| **POST** (Update) | `api/events.php` (`action="update"`) | JSON / Form Body | Updates event record by ID | `{"success": true, "message": "Event #X updated successfully"}` |
| **POST** (Delete) | `api/events.php` (`action="delete"`) | JSON / Form Body | Deletes event record by ID | `{"success": true, "message": "Event #X was deleted"}` |

---

## 3. Asynchronous Workflow in JavaScript
1. **Zero Full Page Refresh:**
   `e.preventDefault()` halts form submission. Data is communicated in the background using JavaScript's native `fetch()`.
2. **Instant DOM Feedback:**
   Upon successful creation or modification, the table re-renders without refreshing the window. When an item is deleted, the corresponding `<tr>` is immediately removed from the DOM via `targetRow.remove()`.
3. **Toast Notifications:**
   A floating toast appears with real-time feedback messages returned by the PHP API.

---

## 4. Answers to Key Lab Questions
1. **Do PHP endpoints return valid JSON responses?**  
   Yes. Every response is emitted with `header('Content-Type: application/json; charset=UTF-8')` and serialized via `json_encode()`.
2. **Are GET and POST requests handled properly?**  
   Yes. `$_SERVER['REQUEST_METHOD']` distinguishes retrieval (`GET`) from write/mutation operations (`POST`).
3. **Are loading, success, and error states shown on the frontend?**  
   Yes. A loading indicator appears during data retrieval, and green/red toasts alert the user about outcome status.
4. **Is input validation performed before database operations?**  
   Yes. Mandatory fields are verified in JavaScript and re-validated server-side before running MySQL queries.
