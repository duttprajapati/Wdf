# Practical 8: Entity-Relationship (ER) Model Documentation

## 1. Conceptual ER Overview
The **StudentHub Portal** schema is modeled in Third Normal Form (3NF) to eliminate data redundancy and preserve referential integrity.

### Entities:
1. **USERS**: Represents login credentials, username, email, encrypted password, and system access role (`admin` or `student`).
2. **STUDENTS**: Represents academic student information (enrollment number, full name, phone, course, semester).
3. **EVENTS**: Represents college activities, workshops, seminars, and sports events.
4. **REGISTRATIONS**: Junction table tracking event participation by students.

---

## 2. Cardinalities & Relationships

1. **USERS (1) &mdash;&mdash;&mdash; (0..1) STUDENTS**
   - **Type:** One-to-One / Optional One-to-One.
   - A user with role `student` corresponds to exactly one student profile. An `admin` user does not need a student profile record.
   - **FK:** `students.user_id` &rarr; `users.id` (`ON DELETE SET NULL`).

2. **STUDENTS (1) &mdash;&mdash;&mdash; (M) REGISTRATIONS (M) &mdash;&mdash;&mdash; (1) EVENTS**
   - **Type:** Many-to-Many (M:N) decomposed into two One-to-Many relationships via `registrations`.
   - One student can register for multiple events.
   - One event can have registrations from multiple students.
   - **FK 1:** `registrations.student_id` &rarr; `students.id` (`ON DELETE CASCADE`).
   - **FK 2:** `registrations.event_id` &rarr; `events.id` (`ON DELETE CASCADE`).
   - **Unique Constraint:** `(student_id, event_id)` prevents a student from registering twice for the same event.

---

## 3. Visual ASCII ER Diagram

```
+--------------------+               +-------------------------+
|       USERS        |               |        STUDENTS         |
+--------------------+               +-------------------------+
| PK id              |<---(0..1)-----| PK id                   |
|    username (UQ)   |               | FK user_id              |
|    email (UQ)      |               |    enrollment_no (UQ)   |
|    password (hash) |               |    full_name            |
|    role            |               |    email (UQ)           |
|    created_at      |               |    mobile               |
+--------------------+               |    course               |
                                     |    semester             |
                                     +------------+------------+
                                                  |
                                                  | (1)
                                                  |
                                                  v (M)
+--------------------+               +------------+------------+
|       EVENTS       |               |      REGISTRATIONS      |
+--------------------+               +-------------------------+
| PK id              |<---(1)--------| PK id                   |
|    title           |       (M)     | FK student_id           |
|    description     |               | FK event_id             |
|    category        |               |    registration_date    |
|    event_date      |               |    status               |
|    venue           |               +-------------------------+
|    max_seats       |
|    poster          |
|    created_at      |
+--------------------+
```

---

## 4. Normalization Verification
- **1NF:** All attributes contain atomic values. Each column has a distinct name.
- **2NF:** All non-key attributes are fully functionally dependent on the entire primary key.
- **3NF:** No transitive dependencies exist. Student attributes depend solely on `students.id`.
