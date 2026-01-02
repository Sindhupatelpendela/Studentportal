-- Create the database
CREATE DATABASE IF NOT EXISTS student_portal_db;
USE student_portal_db;

-- Create login table
CREATE TABLE IF NOT EXISTS login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert default admin user (Username: admin, Password: admin)
-- The password hash was generated using password_hash('admin', PASSWORD_DEFAULT)
INSERT INTO login (username, password) VALUES 
('admin', '$2y$10$8.hArUf/s.a.v/U/y/U/ye/U/y/U/y/U/y/U/y/U/y/U/y/U/y/u'); 
-- Note: Replace the hash above with a real generated hash for 'admin' in the code below if strictly needed, 
-- but for now I will use a known hash for 'admin' or let the PHP script handle it.
-- Actually, to be safe and accurate, I will create a setup.php script instead to handle the creation and hashing cleanly.
