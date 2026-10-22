# Practical 11: Student Management CRUD with Search and Filter

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Create a student management module to add, view, edit, delete, search and filter student records. All database operations must use MySQLi statements and clear success/failure messages.

---

## 2. Seed Data Execution
To populate the minimum 20 student records required for testing:
1. Open phpMyAdmin (`http://localhost/phpmyadmin`).
2. Select `studenthub_db`.
3. Import or execute the SQL queries from `seed_20_students.sql`.

---

## 3. CRUD Operations Summary

| Operation | Page / Endpoint | MySQLi Statement | Feedback UI |
|---|---|---|---|
| **CREATE** | `add_student.php` | `INSERT INTO students (enrollment_no, full_name, email, mobile, course, semester) VALUES (...)` | Redirect with `?msg=added` green banner. |
| **READ** | `index.php` | `SELECT * FROM students WHERE 1=1 [filters] ORDER BY id DESC` | Rendered in HTML table with record count. |
| **UPDATE** | `edit_student.php?id=X` | `UPDATE students SET enrollment_no=..., full_name=..., ... WHERE id=X` | Form pre-filled; redirects with `?msg=updated`. |
| **DELETE** | `delete_student.php?id=X` | `DELETE FROM students WHERE id=X` | JavaScript confirmation dialog (`confirm()`); redirects with `?msg=deleted`. |
| **SEARCH** | `index.php?search=...` | `AND (full_name LIKE '%keyword%' OR email LIKE '%keyword%' OR enrollment_no LIKE '%keyword%')` | Dynamic table refresh with filtered results. |
| **FILTER** | `index.php?course=...` | `AND course = 'B.Tech IT'` | Dropdown filtering by department/course. |

---

## 4. Answers to Key Lab Questions
1. **Are SELECT, INSERT, UPDATE, and DELETE operations working?**  
   Yes. All four CRUD primitives are fully implemented using procedural MySQLi with appropriate input sanitization.
2. **Is search by name/email/course implemented?**  
   Yes. The search input uses SQL `LIKE` with wildcards across `full_name`, `email`, and `enrollment_no`, while the course selector filters exact course strings.
3. **Are delete operations confirmed before execution?**  
   Yes. The delete button contains an inline confirmation modal:
   ```html
   onclick="return confirm('Are you sure you want to permanently delete this student record?');"
   ```
   This prevents accidental data loss.
