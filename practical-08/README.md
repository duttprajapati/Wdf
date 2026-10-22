# Practical 8: MySQL Schema Design, ER Model and Database Connectivity

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Design the database for StudentHub with students, events and registrations. Create PHP database connection using MySQL statements.

---

## 2. Setup & Execution Instructions in XAMPP
1. Start **Apache** and **MySQL** modules from the XAMPP Control Panel.
2. Open your browser and navigate to `http://localhost/phpmyadmin/`.
3. Create a new database named `studenthub_db` or import the provided `schema.sql` file.
4. Test the connectivity script in your browser:
   `http://localhost/wdf/practical-08/test_connection.php`

---

## 3. Schema Structure Summary

- **`users` Table:**
  Contains credentials (`username`, `email`, `password` hash, `role`).
- **`students` Table:**
  Contains academic details (`enrollment_no`, `full_name`, `email`, `mobile`, `course`, `semester`, `user_id` FK).
- **`events` Table:**
  Contains campus activity listings (`title`, `description`, `category`, `event_date`, `venue`, `max_seats`, `poster`).
- **`registrations` Table:**
  Associative junction table linking students and events (`student_id` FK, `event_id` FK, `registration_date`, `status`).

---

## 4. Answers to Key Lab Questions

### Q1: Is the schema normalized properly?
- Yes. The database adheres to **Third Normal Form (3NF)**:
  - 1NF: All columns hold atomic single values.
  - 2NF: No partial key dependencies.
  - 3NF: No transitive functional dependencies. Student details depend directly on the student ID, not on registration attributes.

### Q2: Are primary keys and foreign keys correctly defined?
- Yes. Every table has an auto-incrementing integer Primary Key (`id`).
- Foreign keys enforce referential integrity with cascading deletes/updates (`ON DELETE CASCADE` on registrations, `ON DELETE SET NULL` on students).

### Q3: Is the database connection handled securely?
- Yes. Handled via `mysqli_connect()`, with connection error checks (`mysqli_connect_error()`), UTF-8 character encoding (`utf8mb4`), and centralized in `db.php` for code reuse.
