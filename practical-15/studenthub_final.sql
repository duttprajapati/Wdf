-- ==============================================================
-- Practical 15: Final Integrated Database Script
-- Database: studenthub_db
-- ==============================================================

CREATE DATABASE IF NOT EXISTS studenthub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Students Table
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
    CONSTRAINT fk_final_stud_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. Events Table
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

-- 4. Registrations Table
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'pending', 'cancelled') NOT NULL DEFAULT 'confirmed',
    CONSTRAINT fk_final_reg_stud FOREIGN KEY (student_id) 
        REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_final_reg_evt FOREIGN KEY (event_id) 
        REFERENCES events(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT uq_final_reg UNIQUE (student_id, event_id)
) ENGINE=InnoDB;

-- 5. Audit Logs Table
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NOT NULL DEFAULT '127.0.0.1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_final_audit_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==============================================================
-- Seed Data (Passwords default to: password123)
-- ==============================================================

INSERT INTO users (id, username, email, password, role) VALUES
(1, 'admin', 'admin@charusat.edu.in', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
(2, 'rahul24', '24it001@charusat.edu.in', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'student'),
(3, 'diya24', '24cs010@charusat.edu.in', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'student');

INSERT INTO students (id, user_id, enrollment_no, full_name, email, mobile, course, semester) VALUES
(1, 2, '24IT001', 'Rahul Patel', '24it001@charusat.edu.in', '9876543201', 'B.Tech IT', 3),
(2, 3, '24CS010', 'Diya Joshi', '24cs010@charusat.edu.in', '9876543202', 'B.Tech CSE', 3),
(3, NULL, '24CE015', 'Bhavya Shah', '24ce015@charusat.edu.in', '9876543203', 'B.Tech CE', 3),
(4, NULL, '24BCA008', 'Falguni Shah', '24bca008@charusat.edu.in', '9876543204', 'BCA', 2);

INSERT INTO events (id, title, description, category, event_date, venue, max_seats) VALUES
(1, 'Hack-CHARUSAT 2026', '24-hour coding sprint building real-world solutions.', 'Technical', '2026-11-15', 'FTE Seminar Hall', 120),
(2, 'Web Frameworks Workshop', 'Hands-on training covering HTML5, CSS3, JavaScript, PHP, and MySQL.', 'Workshop', '2026-11-20', 'IT Lab 3', 50),
(3, 'Spandan Cultural Gala', 'Annual student cultural fest featuring musical performances.', 'Cultural', '2026-11-28', 'Amphitheatre', 450),
(4, 'Inter-Branch Cricket Cup', 'Leather ball cricket championship among college departments.', 'Sports', '2026-12-05', 'University Sports Oval', 16);

INSERT INTO registrations (student_id, event_id, status) VALUES
(1, 1, 'confirmed'),
(1, 2, 'confirmed'),
(2, 1, 'confirmed'),
(2, 3, 'pending'),
(3, 4, 'confirmed');

INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES
(1, 'SYSTEM_INIT', 'Initial deployment of StudentHub completed', '127.0.0.1');
