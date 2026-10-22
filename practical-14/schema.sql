-- Practical 14: Audit Logs Table Schema
USE studenthub_db;

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NOT NULL DEFAULT '127.0.0.1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Sample Seed Logs
INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES
(1, 'USER_LOGIN', 'Admin logged into administration control panel', '127.0.0.1'),
(1, 'EVENT_CREATE', 'Added new event: Hack-CHARUSAT 2026', '127.0.0.1'),
(1, 'STUDENT_DELETE', 'Removed inactive student record #14', '127.0.0.1'),
(1, 'SETTINGS_UPDATE', 'Updated portal registration deadline to 15-Nov-2026', '127.0.0.1');
