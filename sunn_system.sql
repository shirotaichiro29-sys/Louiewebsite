
USE sunn_system;

-- Users (for login)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin (password: admin123) - stored as MD5 like the original demo
INSERT INTO users (name,email,password) VALUES
('Administrator','admin@example.com', MD5('admin123'))
ON DUPLICATE KEY UPDATE email=email;

-- Students table (added gender)
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_number VARCHAR(50) UNIQUE,
  name VARCHAR(200) NOT NULL,
  gender VARCHAR(20),
  age INT,
  course VARCHAR(100),
  contact VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample students for testing
INSERT INTO students (student_number, name, gender, age, course, contact) VALUES
('2023001','Juan Dela Cruz','Male',20,'BSIT','09171234567'),
('2023002','Maria Santos','Female',19,'BSBA','09181234567')
ON DUPLICATE KEY UPDATE student_number=student_number;

-- Medical records
CREATE TABLE IF NOT EXISTS medical_records (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  checkup_date DATE,
  symptoms TEXT,
  diagnosis TEXT,
  treatment TEXT,
  medication TEXT,
  file_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
