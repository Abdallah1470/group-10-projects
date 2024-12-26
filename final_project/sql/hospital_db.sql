CREATE DATABASE IF NOT EXISTS hospital_db;
USE hospital_db;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'doctor', 'patient') NOT NULL,
    photo VARCHAR(255), -- Path to the user's photo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: doctors
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    description TEXT, -- A brief description of the doctor
    contact_info VARCHAR(100),
    location VARCHAR(255), -- Doctor's location (e.g., clinic or hospital address)
    photo VARCHAR(255), -- Path to the doctor's photo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: services
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: appointments
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    status ENUM('scheduled', 'completed', 'canceled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

-- Table: contact_requests
CREATE TABLE IF NOT EXISTS contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for frequently queried columns
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_appointments_user_id ON appointments(user_id);
CREATE INDEX idx_appointments_doctor_id ON appointments(doctor_id);

-- Insert initial data for testing
INSERT INTO users (name, email, password, role, photo) VALUES
('Admin User', 'admin@example.com', SHA2('admin_password', 256), 'admin', 'images/admin.jpg'),
('Doctor One', 'doctor1@example.com', SHA2('doctor_password', 256), 'doctor', 'images/doctor1.jpg'),
('Patient One', 'patient1@example.com', SHA2('patient_password', 256), 'patient', 'images/patient1.jpg');

INSERT INTO doctors (name, specialty, description, contact_info, location, photo) VALUES
('Dr. Abdallah', 'Cardiology', 'Experienced cardiologist specializing in heart health.', '+201024096379', 'Cairo', 'images/doctor_1.jpg'),
('Dr. Abdelrahman', 'Pediatrics', 'Expert in child health and development.', '+201123456789', 'Cairo', 'images/doctor_2.jpg');

INSERT INTO services (name, description) VALUES
('General Checkup', 'A comprehensive health checkup.'),
('Vaccination', 'Immunization against various diseases.'),
('Consultation', 'Consultation with a specialist doctor.');
