-- ==============================================================
-- Practical 11: Seed 20 Student Records
-- Database: studenthub_db | Table: students
-- ==============================================================

USE studenthub_db;

-- Clear previous student records for fresh test
DELETE FROM registrations;
DELETE FROM students;

INSERT INTO students (enrollment_no, full_name, email, mobile, course, semester) VALUES
('24IT001', 'Aarav Sharma', '24it001@charusat.edu.in', '9876543201', 'B.Tech IT', 3),
('24IT002', 'Bhavya Patel', '24it002@charusat.edu.in', '9876543202', 'B.Tech IT', 3),
('24IT003', 'Chirag Desai', '24it003@charusat.edu.in', '9876543203', 'B.Tech IT', 3),
('24IT004', 'Deepak Verma', '24it004@charusat.edu.in', '9876543204', 'B.Tech IT', 3),
('24IT005', 'Eshaan Vora', '24it005@charusat.edu.in', '9876543205', 'B.Tech IT', 3),
('24CS006', 'Diya Joshi', '24cs006@charusat.edu.in', '9876543206', 'B.Tech CSE', 1),
('24CS007', 'Falguni Shah', '24cs007@charusat.edu.in', '9876543207', 'B.Tech CSE', 1),
('24CS008', 'Gaurav Mehta', '24cs008@charusat.edu.in', '9876543208', 'B.Tech CSE', 3),
('24CS009', 'Hiral Trivedi', '24cs009@charusat.edu.in', '9876543209', 'B.Tech CSE', 3),
('24CS010', 'Ishaan Dave', '24cs010@charusat.edu.in', '9876543210', 'B.Tech CSE', 5),
('24CE011', 'Jhanvi Pandya', '24ce011@charusat.edu.in', '9876543211', 'B.Tech CE', 3),
('24CE012', 'Karan Solanki', '24ce012@charusat.edu.in', '9876543212', 'B.Tech CE', 3),
('24CE013', 'Lipi Parmar', '24ce013@charusat.edu.in', '9876543213', 'B.Tech CE', 5),
('24CE014', 'Manan Bhatt', '24ce014@charusat.edu.in', '9876543214', 'B.Tech CE', 1),
('24BCA015', 'Neha Makwana', '24bca015@charusat.edu.in', '9876543215', 'BCA', 2),
('24BCA016', 'Om Raval', '24bca016@charusat.edu.in', '9876543216', 'BCA', 2),
('24BCA017', 'Prisha Soni', '24bca017@charusat.edu.in', '9876543217', 'BCA', 4),
('24MCA018', 'Rajeshwari Iyer', '24mca018@charusat.edu.in', '9876543218', 'MCA', 1),
('24MCA019', 'Siddharth Nair', '24mca019@charusat.edu.in', '9876543219', 'MCA', 3),
('24MCA020', 'Tanvi Choksi', '24mca020@charusat.edu.in', '9876543220', 'MCA', 3);
