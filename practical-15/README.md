# Practical 15: Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  
**Project:** StudentHub - Comprehensive Full-Stack Campus Web Application  

---

## 1. Project Overview & Architectural Summary
Practical 15 delivers the consolidated, end-to-end integration of the semester-long **StudentHub Portal**. It unifies frontend semantic design, responsive styling, client validation, dynamic DOM manipulation, procedural PHP backend processing, role-based session control, MySQL relational storage, RESTful APIs, and security audit trails.

### Tech Stack Utilized:
- **Frontend:** Semantic HTML5, CSS3 (Flexbox & Grid), Vanilla JavaScript (ES6+).
- **Backend:** Procedural PHP 8.x with native `mysqli` extension.
- **Database:** MySQL 8.x / MariaDB in Third Normal Form (3NF).
- **Communication:** HTTP POST, RESTful JSON Endpoints, AJAX / Fetch API.
- **Server Environment:** XAMPP (Apache HTTP Server & MySQL).

---

## 2. Directory Structure of Practical 15
```
practical-15/
├── css/
│   └── style.css              # Consolidated responsive stylesheet
├── includes/
│   ├── auth.php               # Session security, timeout & RBAC guards
│   ├── db.php                 # MySQLi database connection
│   ├── footer.php             # Semantic accessible footer include
│   ├── header.php             # Accessible header with dynamic role menu
│   └── logger.php             # Security audit logging helper
├── uploads/                   # Target directory for event poster uploads
├── about.php                  # Institutional about page
├── admin_dashboard.php        # Executive administrator dashboard
├── ajax_events.php            # Single-Page AJAX event explorer
├── api_events.php             # RESTful JSON endpoint for events
├── events.php                 # Public / student event catalog with 1-click register
├── index.php                  # Main landing page with highlights & announcements
├── login.php                  # Secure login with remember-me & timeout handling
├── logout.php                 # Session invalidation & cookie removal
├── manage_events.php          # Admin event CRUD with validated file upload
├── manage_students.php        # Admin student CRUD with search and filter
├── register.php               # Student registration with password hashing
├── student_dashboard.php      # Student dashboard with profile & enrolled events
├── studenthub_final.sql       # Full database dump with tables & seed data
└── README.md                  # Comprehensive documentation and test report
```

---

## 3. Local Deployment & Setup Guide (XAMPP)

### Step 1: Copy Project Files
Place the entire `wdf` repository or `practical-15` folder into your XAMPP web root directory:
```
C:\xampp\htdocs\wdf\practical-15\
```

### Step 2: Start Apache and MySQL
1. Launch the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

### Step 3: Import the Database
1. Open your browser and go to `http://localhost/phpmyadmin/`.
2. Click **Import** in the top navigation tab.
3. Click **Choose File** and select `studenthub_final.sql` from `practical-15/`.
4. Click **Go** to create the `studenthub_db` schema, tables (`users`, `students`, `events`, `registrations`, `audit_logs`), and seed data.

### Step 4: Run the Application
Open your web browser and navigate to:
```
http://localhost/wdf/practical-15/index.php
```

### Default Demo Credentials:
- **Administrator Account:**  
  Username: `admin` | Password: `password123`
- **Student Account:**  
  Username: `rahul24` | Password: `password123`

---

## 4. End-to-End System Test Report

| Module | Test Case Description | Test Input | Expected Result | Status |
|---|---|---|---|---|
| **Auth** | Student Registration | New user credentials + valid mobile & email | User inserted in `users` & `students`, password hashed with Bcrypt, audit log recorded. | **Passed** |
| **Auth** | Duplicate Detection | Register using existing email: `24it001@charusat.edu.in` | Halted with error: "An account with that email already exists." | **Passed** |
| **Auth** | Role-Based Access | Student tries to open `admin_dashboard.php` | Request blocked with `403 Forbidden` / redirected to login. | **Passed** |
| **Auth** | Inactivity Timeout | Leave session inactive for > 10 minutes | Session destroyed, redirected to `login.php?msg=timeout`. | **Passed** |
| **CRUD** | Student Management | Add student in `manage_students.php` | Real-time SQL INSERT, student appears in table immediately. | **Passed** |
| **CRUD** | Event Poster Upload | Upload 1.2MB `.png` poster in `manage_events.php` | Validated, renamed uniquely into `uploads/`, thumbnail displayed in list. | **Passed** |
| **CRUD** | Poster Size Rejection | Upload file > 2MB | Upload aborted with error: "File exceeds 2MB limit." | **Passed** |
| **AJAX** | Live Fetch Search | Type "Hackathon" in `ajax_events.php` | Zero page reload; table filtered instantaneously via Fetch API. | **Passed** |
| **Audit** | Traceability | Admin publishes new event | Row created in `audit_logs` table with user ID, action, and IP address. | **Passed** |

---

## 5. Security & Accessibility Audit Checklists

### Security Verification:
- [x] **Password Protection:** Standard Bcrypt hashing via `password_hash()` and verification via `password_verify()`.
- [x] **Session Fixation Defense:** Calls `session_regenerate_id(true)` upon successful authentication.
- [x] **SQL Injection Defense:** Inputs sanitized using `mysqli_real_escape_string()` and type casting (`intval()`).
- [x] **XSS Prevention:** Output encoded with `htmlspecialchars()`.
- [x] **File Upload Shielding:** MIME type verification, extension whitelist, and server-side file renaming.
- [x] **Role Authorization:** RBAC guards `require_admin()` and `require_student()` protect private views.

### Accessibility (WCAG 2.1 Basic Level):
- [x] Semantic landmarks: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`.
- [x] Form accessibility: Explicit `<label for="...">` matching input IDs.
- [x] Table accessibility: `<thead>`, `<tbody>`, and column headers.
- [x] High-contrast typography and keyboard tab navigation.

---

## 6. GitHub Submission Instructions
```bash
# 1. Open Terminal in the root wdf directory
cd D:\wdf

# 2. Check git status
git status

# 3. Stage all 15 practicals
git add practical-01/ practical-02/ practical-03/ practical-04/ practical-05/
git add practical-06/ practical-07/ practical-08/ practical-09/ practical-10/
git add practical-11/ practical-12/ practical-13/ practical-14/ practical-15/

# 4. Commit practical milestones
git commit -m "feat: complete practicals 1 to 15 for ITUE203 StudentHub portal"

# 5. Push to GitHub submission repository
git push origin main
```

---

## 7. Answers to Practical 15 Key Questions

### Q1: Are all pages properly linked and navigable?
- Yes. The unified header navigation (`includes/header.php`) dynamically links public pages, student-specific dashboard routes, and admin management panels based on the session state.

### Q2: Are sessions, roles, validations, and database operations working end-to-end?
- Yes. Students can register &rarr; log in &rarr; view their dashboard &rarr; register for events. Administrators can log in &rarr; view aggregated stats &rarr; manage students &rarr; post events with posters &rarr; review security audit trails.

### Q3: Are security and accessibility checklists completed?
- Yes. Bcrypt password encryption, session regeneration, input sanitization, file upload constraints, and WCAG-aligned semantic markup are fully verified.

### Q4: Is project documentation sufficient for installation and testing?
- Yes. Step-by-step XAMPP deployment instructions, default demo credentials, SQL schema import steps, and test execution tables are documented above.

### Q5: Is the project repository clean and properly organized?
- Yes. Each lab is segregated into its own dedicated folder (`practical-01` through `practical-15`) with clean, commented code written specifically at a clear, beginner-friendly standard.
