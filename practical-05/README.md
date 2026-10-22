# Practical 5: Registration Form with Frontend Validation and User-Friendly Error Handling

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Create a student registration form with HTML5 input types and JavaScript validation for name, email, mobile number, password, confirm password, course, year, gender, and terms acceptance. Use Regular Expression for validations.

---

## 2. Validation Rules & Regular Expressions Used

| Field | Validation Criteria | Regular Expression / Logic |
|---|---|---|
| **Full Name** | Alphabets and spaces only, min 3 and max 50 characters. | `/^[a-zA-Z\s]{3,50}$/` |
| **Email** | Standard RFC compliant email format. | `/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/` |
| **Mobile Number** | Exactly 10 digits starting with 6, 7, 8, or 9 (Indian telecom standard). | `/^[6-9]\d{9}$/` |
| **Password** | At least 8 characters long, checks score for uppercase, lowercase, numbers, and symbols. | `password.length >= 8` + live strength meter |
| **Confirm Password** | Must be identical to password input. | `confirmPassword === password` |
| **Course** | Non-empty dropdown option selection. | `course !== ""` |
| **Year of Study** | At least one radio button selected. | `querySelector('input[name="year"]:checked') !== null` |
| **Gender** | At least one radio button selected. | `querySelector('input[name="gender"]:checked') !== null` |
| **Terms Acceptance** | Mandatory checkbox checked. | `terms.checked === true` |

---

## 3. Test Cases Table (Submission Requirement)

| Test Case ID | Test Scenario | Input Data | Expected Output | Actual Result |
|---|---|---|---|---|
| **TC01** | Name containing numeric characters | `Rahul123` | Error: "Name must be at least 3 letters and contain only alphabets and spaces." | Pass |
| **TC02** | Invalid email format | `student@` or `student@charusat` | Error: "Enter a valid email address." | Pass |
| **TC03** | Mobile number under 10 digits | `98765` | Error: "Mobile number must be exactly 10 digits starting with 6, 7, 8, or 9." | Pass |
| **TC04** | Mobile number starting with invalid digit (e.g. 2) | `2345678901` | Error: "Mobile number must be exactly 10 digits starting with 6, 7, 8, or 9." | Pass |
| **TC05** | Short password (< 8 chars) | `pass1` | Error: "Password must be at least 8 characters long." (Strength: Weak) | Pass |
| **TC06** | Passwords mismatch | Pass: `Secure#2026`, Confirm: `Secure#2025` | Error: "Passwords do not match." | Pass |
| **TC07** | Course unselected | `-- Select Your Course --` | Error: "Please select your course." | Pass |
| **TC08** | Terms unchecked | Checkbox unchecked | Error: "You must accept the terms and conditions." | Pass |
| **TC09** | Valid registration input | Name: `Rahul Patel`, Email: `24it001@charusat.edu.in`, Mobile: `9876543210`, Pass: `Charusat@2026` | Green borders, success banner displayed, form resets. | Pass |

---

## 4. Answers to Key Lab Questions
1. **Are correct input types and attributes used?**  
   Yes. `type="email"`, `type="tel"`, `type="password"`, `type="radio"`, and `type="checkbox"` were used with `autocomplete` and `required` attributes.
2. **Are validation errors displayed near the relevant fields?**  
   Yes. Specific `<div class="error-message">` elements are placed immediately below each field, with real-time clearing upon correction.
3. **Is password strength checked?**  
   Yes. A dynamic colored strength bar dynamically calculates score based on length and character sets (Weak = Red, Medium = Orange, Strong = Green).
4. **Is the form accessible?**  
   Yes. Every control is tied to a `<label for="...">`, errors are read by screen readers, and full keyboard navigation (Tab/Shift+Tab) is preserved.
