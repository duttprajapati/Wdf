# Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Develop an admin dashboard that displays user statistics, student count, event count and latest registrations. Restrict access to admin users only and show dynamic menu options based on role. Store important admin actions in `audit_logs`.

---

## 2. Dynamic Features Implemented
1. **Real-Time Aggregation Queries:**
   - Total Students: `SELECT COUNT(*) FROM students`
   - Total Events: `SELECT COUNT(*) FROM events`
   - Total Registrations: `SELECT COUNT(*) FROM registrations`
   - Total Accounts: `SELECT COUNT(*) FROM users`
2. **Cross-Table Joins for Recent Activity:**
   Uses an `INNER JOIN` across `registrations`, `students`, and `events` to present human-readable event sign-ups.
3. **Role-Based Dynamic Menu (`navbar.php`):**
   Renders distinct menu tabs depending on whether `$_SESSION['user_role']` is `admin`, `student`, or `guest`.
4. **Security Audit Log Trail (`audit_logs` table):**
   Logs user actions with action name, description, client IP address (`$_SERVER['REMOTE_ADDR']`), and automated timestamp.
5. **Strict RBAC Enforcement:**
   Direct attempts by non-administrators to access `admin_dashboard.php` result in a `403 Forbidden` error screen.

---

## 3. Answers to Key Lab Questions
1. **Are dashboard values loaded dynamically from the database?**  
   Yes. Metric cards query live database totals via SQL `COUNT(*)` rather than hardcoded figures.
2. **Is access restricted to admin users?**  
   Yes. Checks `$_SESSION['user_role'] === 'admin'` at the top of the file before rendering privileged information.
3. **Are role-based menus working?**  
   Yes. Administrators see management links, while students see personal profile and event registration links.
4. **Are important admin actions recorded in `audit_logs`?**  
   Yes. The helper `record_audit_log()` stores actions (e.g. `ADMIN_LOGIN`, `MAINTENANCE_TRIGGER`) in MySQL.
