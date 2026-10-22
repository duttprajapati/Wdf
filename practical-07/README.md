# Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Process submitted registration/contact form data using PHP. Validate and sanitize inputs on the server side and store records in CSV or JSON file format. Display success/error messages.

---

## 2. Server-Side Security & Sanitization
1. **`htmlspecialchars($str)`**: Prevents Cross-Site Scripting (XSS) by encoding characters like `<` and `>` into `&lt;` and `&gt;`.
2. **`trim($str)`**: Strips unnecessary leading/trailing whitespace.
3. **`filter_var($email, FILTER_VALIDATE_EMAIL)`**: Standard PHP email validation mechanism.
4. **`preg_match("/^[6-9]\d{9}$/", $mobile)`**: Validates 10-digit mobile number format.

---

## 3. File Storage Implementation

### CSV File Storage:
- Handled with `fopen($path, 'a')` in append mode.
- Writes formatted data line by line using `fputcsv($handle, $fields)`.
- Automatically generates the header row if the file does not yet exist.

### JSON File Storage:
- Existing array loaded using `file_get_contents()`.
- Decoded into PHP associative array using `json_decode(..., true)`.
- New record is appended: `$records[] = $newEntry`.
- Serialized and saved back using `file_put_contents($path, json_encode($records, JSON_PRETTY_PRINT))`.

---

## 4. Answers to Key Lab Questions

### Q1: Is the form submitted using POST?
- Yes. The form uses `method="POST"`, and PHP verifies the request with `$_SERVER["REQUEST_METHOD"] === "POST"`. Sensitive details and long messages are not exposed in the URL query string.

### Q2: Are inputs validated and sanitized server side?
- Yes. All inputs are sanitized using `trim()` and `htmlspecialchars()`, followed by required field checks, regex checks, and filter validations.

### Q3: Is file writing handled safely?
- Yes. Directory existence is verified (`mkdir()` if missing), file open handles are checked, and handles are closed using `fclose()`.

### Q4: Are success and error responses displayed clearly?
- Yes. Errors are rendered in a distinct red callout with bulleted messages, and successes are rendered in a green alert with a one-click link to `records.php` to review stored entries.
