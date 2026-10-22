# Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Develop secure login and logout functionality using PHP sessions. Verify password using `password_verify()`, regenerate session ID after login, restrict dashboard access, implement role-based redirection, session timeout, and optional secure remember-me token.

---

## 2. Authentication & Session Architecture

1. **Authentication:**
   - Query user by username or email.
   - Verify hashed password using `password_verify($password, $user['password'])`.
2. **Session ID Regeneration:**
   - Calls `session_regenerate_id(true)` immediately after successful password verification to prevent session fixation attacks.
3. **Role-Based Access Control (RBAC):**
   - After login, checks `$user['role']`.
   - `admin` &rarr; redirected to `admin_dashboard.php`.
   - `student` &rarr; redirected to `student_dashboard.php`.
   - Guard function `require_role($role)` prevents students from loading `admin_dashboard.php` and vice versa.
4. **Session Inactivity Timeout:**
   - Tracks `$_SESSION['last_activity']`.
   - If user is inactive for > 600 seconds (10 minutes), session is destroyed and redirected to `login.php?msg=timeout`.
5. **Remember-Me Cookie:**
   - If checked, sets `setcookie('studenthub_remember', $username, time() + 7 days, '/')` to prefill username on subsequent visits.
6. **Logout:**
   - Clears `$_SESSION` array, invalidates browser cookie, calls `session_destroy()`.

---

## 3. Test Cases & Verification Report

| Test ID | Scenario | Steps / Input | Expected Result | Result |
|---|---|---|---|---|
| **AUTH-01** | Successful Admin Login | User: `admin`, Pass: `password123` | Session initialized, redirected to `admin_dashboard.php`. | **Pass** |
| **AUTH-02** | Successful Student Login | User: `rahul24`, Pass: `password123` | Session initialized, redirected to `student_dashboard.php`. | **Pass** |
| **AUTH-03** | Failed Login (Bad Password) | User: `admin`, Pass: `wrongpass` | Error: "Invalid credentials. Incorrect password." | **Pass** |
| **AUTH-04** | Unauthorized Access Attempt | Visit `admin_dashboard.php` directly without login | Redirected to `login.php?msg=unauthorized`. | **Pass** |
| **AUTH-05** | Privilege Escalation Block | Log in as `student`, manually open `admin_dashboard.php` | Access blocked, redirected to `login.php?msg=forbidden`. | **Pass** |
| **AUTH-06** | Session Inactivity Timeout | Leave dashboard idle for > 10 minutes and refresh | Session destroyed, redirected to `login.php?msg=timeout`. | **Pass** |
| **AUTH-07** | Secure Logout | Click "Logout" button | Session destroyed, cookie cleared, redirected to `login.php?msg=logout`. | **Pass** |

---

## 4. Answers to Key Lab Questions
1. **Are sessions started and destroyed securely?**  
   Yes. `session_start()` is called safely, session ID is regenerated after login, and `logout.php` completely clears `$_SESSION` and removes the session cookie.
2. **Is `password_verify()` used correctly?**  
   Yes. Plain passwords are never compared using `==`. They are evaluated against the bcrypt hash using `password_verify()`.
3. **Is unauthorized access blocked?**  
   Yes. The helper `require_role()` terminates unauthorized requests with immediate redirection.
4. **Is session timeout implemented?**  
   Yes. Configured at 600 seconds based on elapsed time comparison with `time()`.
