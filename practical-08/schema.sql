-- ==============================================================
-- Practical 8: MySQL Schema Design & Seed Data
-- Database Name: studenthub_db
-- ==============================================================

-- 1. Create Database if not exists
CREATE DATABASE IF NOT EXISTS studenthub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub_db;

-- Disable Foreign Key checks temporarily for clean drop
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. Table: users (Authentication & RBAC)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Table: students (Student Profile Details)
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    enrollment_no VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    course VARCHAR(50) NOT NULL,
    semester INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_students_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. Table: events (Campus Events & Workshops)
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(100) NOT NULL,
    max_seats INT NOT NULL DEFAULT 50,
    poster VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Table: registrations (Junction Table: Students registering for Events - M:N Relationship)
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'pending', 'cancelled') NOT NULL DEFAULT 'confirmed',
    CONSTRAINT fk_reg_student FOREIGN KEY (student_id) 
        REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reg_event FOREIGN KEY (event_id) 
        REFERENCES events(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT uq_student_event UNIQUE (student_id, event_id)
) ENGINE=InnoDB;

-- ==============================================================
-- 6. Seed Data for Initial Testing
-- Note: Passwords are hashed using password_hash('password123', PASSWORD_DEFAULT)
-- Hash below corresponds to: password123
-- ==============================================================

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@charusat.edu.in', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
('rahul24', '24it001@charusat.edu.in', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'student'),
('diya24', '24cs010@charusat.edu.in', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'student');

INSERT INTO students (user_id, enrollment_no, full_name, email, mobile, course, semester) VALUES
(2, '24IT001', 'Rahul Patel', '24it001@charusat.edu.in', '9876543210', 'B.Tech IT', 3),
(3, '24CS010', 'Diya Joshi', '24cs010@charusat.edu.in', '9876543211', 'B.Tech CSE', 3),
(NULL, '24CE025', 'Bhavya Shah', '24ce025@charusat.edu.in', '9876543212', 'B.Tech CE', 3),
(NULL, '24BCA014', 'Chirag Desai', '24bca014@charusat.edu.in', '9876543213', 'BCA', 2);

INSERT INTO events (title, description, category, event_date, venue, max_seats) VALUES
('Hack-CHARUSAT 2026', '24-hour university coding hackathon for budding software developers.', 'Technical', '2026-11-15', 'FTE Seminar Hall', 100),
('Spandan Cultural Gala', 'Annual music, drama and folk dance competition.', 'Cultural', '2026-11-25', 'University Amphitheatre', 400),
('Cloud Computing Workshop', 'Practical container deployment and AWS cloud hosting.', 'Workshop', '2026-12-05', 'IT Lab 2', 45),
('Inter-Branch Cricket Tournament', 'T20 leather ball knock-out tournament for engineering branches.', 'Sports', '2026-12-12', 'CHARUSAT Sports Oval', 16);

INSERT INTO registrations (student_id, event_id, status) VALUES
(1, 1, 'confirmed'),
(1, 3, 'confirmed'),
(2, 1, 'confirmed'),
(2, 2, 'pending'),
(3, 4, 'confirmed');
