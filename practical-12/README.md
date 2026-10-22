# Practical 12: Event Management CRUD with Poster Upload and File Validation

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Develop event management functionality for admin users. Admin can add, view, update, delete, and upload event posters with file type and file size validation.

---

## 2. Server-Side File Upload Security & Validation Workflow

1. **`enctype="multipart/form-data"`:**
   HTML forms must declare this encoding attribute so the browser can send binary multipart file data via POST.
2. **File Size Check:**
   `$_FILES['poster']['size']` must not exceed `2 * 1024 * 1024` (2MB).
3. **Extension Whitelist:**
   Extracted using `pathinfo($name, PATHINFO_EXTENSION)`. Restricted strictly to `['jpg', 'jpeg', 'png', 'webp']`.
4. **MIME Type Validation:**
   Verified via `mime_content_type($_FILES['poster']['tmp_name'])` to ensure malicious scripts (.php, .exe) renamed as images are rejected.
5. **Secure File Renaming:**
   Uploaded files are given random hashed names (`"poster_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext`) to avoid directory traversal and filename collision attacks.
6. **Garbage Collection on Delete/Update:**
   When an event is deleted or a poster is updated, the previous file is cleanly deleted from the disk using `unlink()`.

---

## 3. Answers to Key Lab Questions
1. **Are add, update, and delete functionalities implemented correctly?**  
   Yes. Events can be created (`add_event.php`), modified with optional poster replacement (`edit_event.php`), and deleted along with file cleanup (`delete_event.php`).
2. **Is the UI linked with the database correctly?**  
   Yes. Every database field (`title`, `description`, `category`, `event_date`, `venue`, `max_seats`, `poster`) is accurately bound to form inputs and table columns.
3. **Are poster uploads validated for type and size?**  
   Yes. Checks size <= 2MB, allowed extensions (.jpg, .png, .webp), and verifies internal MIME content types.
4. **Are success and failure messages displayed?**  
   Yes. Flash notifications (`?msg=added`, `?msg=updated`, `?msg=deleted`) inform the user with green/red banners.
