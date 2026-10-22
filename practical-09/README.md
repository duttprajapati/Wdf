# Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Implement secure user registration by inserting user details into MySQL using MySQLi. Validate input on frontend and backend, check duplicate email/username, hash passwords using `password_hash()`, and display appropriate messages.

---

## 2. Security Mechanisms Implemented
1. **Password Hashing:**
   Passwords are never stored in plaintext. They are encrypted using `password_hash($password, PASSWORD_DEFAULT)` which applies the secure standard Bcrypt algorithm with automated salt generation.
2. **Duplicate Email & Username Prevention:**
   A SQL query checks for prior existence:
   ```sql
   SELECT id, email, username FROM users WHERE email = '$email' OR username = '$username' LIMIT 1;
   ```
   If a match is found, registration halts with an appropriate feedback message.
3. **Multi-Table Relational Insert:**
   The user credentials are inserted into `users`. The generated auto-increment ID (`mysqli_insert_id($conn)`) is then linked as a foreign key (`user_id`) in `students`.

---

## 3. Registration Test Report (Submission Requirement)

| Case No. | Test Scenario | Inputs Tested | Expected Outcome | Result |
|---|---|---|---|---|
| **TR-01** | Valid Student Registration | User: `sneha24`, Email: `24it050@charusat.edu.in`, Pass: `Student@123` | Password hashed in DB, row created in `users` and `students`, success message displayed. | **Pass** |
| **TR-02** | Duplicate Email Check | Email: `24it001@charusat.edu.in` (already in DB) | Error: "An account with this email address already exists. Please login instead." | **Pass** |
| **TR-03** | Duplicate Username Check | User: `admin` (already in DB) | Error: "This username is already taken. Please choose another username." | **Pass** |
| **TR-04** | Invalid Email Format | Email: `sneha_charusat` | Error: "A valid college email address is required." | **Pass** |
| **TR-05** | Short Password | Password: `123` | Error: "Password must be at least 6 characters long." | **Pass** |
| **TR-06** | Passwords Mismatch | Pass: `MyPass1`, Confirm: `MyPass2` | Client alert & backend error: "Password and Confirm Password do not match." | **Pass** |

---

## 4. Answers to Key Lab Questions
1. **Is `password_hash()` used before storing passwords?**  
   Yes. Plaintext passwords are not saved. `password_hash($password, PASSWORD_DEFAULT)` generates a 60-character hash stored in `users.password`.
2. **Is duplicate email/username checked?**  
   Yes. Both `email` and `username` have unique constraints in MySQL and are validated with a `SELECT` query prior to insertion.
3. **Are frontend and backend validations consistent?**  
   Yes. Both layers check password matching, required fields, and email validity.
