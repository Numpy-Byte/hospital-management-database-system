-- Hospital Management Database System | Import this file in phpMyAdmin.
CREATE DATABASE IF NOT EXISTS hospital_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital_management;

CREATE TABLE users (
  user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE departments (
  department_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_name VARCHAR(100) NOT NULL UNIQUE,
  location VARCHAR(100) NOT NULL,
  contact_number VARCHAR(20) NOT NULL,
  head_of_department VARCHAR(120) NULL
);
CREATE TABLE doctors (
  doctor_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NOT NULL,
  full_name VARCHAR(120) NOT NULL,
  gender ENUM('Male','Female','Unspecified') NOT NULL DEFAULT 'Unspecified',
  specialization VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  qualification VARCHAR(120) NOT NULL,
  joining_date DATE NOT NULL,
  consultation_fee DECIMAL(10,2) NOT NULL CHECK (consultation_fee >= 0),
  employment_status ENUM('Active','On Leave','Inactive') NOT NULL DEFAULT 'Active',
  CONSTRAINT fk_doctor_department FOREIGN KEY (department_id) REFERENCES departments(department_id) ON UPDATE CASCADE
);
CREATE TABLE patients (
  patient_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  date_of_birth DATE NOT NULL,
  blood_group VARCHAR(5) NULL,
  address VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  emergency_contact VARCHAR(20) NULL,
  registration_date DATE NOT NULL DEFAULT (CURRENT_DATE),
  INDEX idx_patient_name (full_name), INDEX idx_patient_phone (phone)
);
CREATE TABLE rooms (
  room_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(20) NOT NULL UNIQUE,
  room_type ENUM('General','Cabin','ICU','Emergency') NOT NULL,
  capacity TINYINT UNSIGNED NOT NULL DEFAULT 1,
  daily_charge DECIMAL(10,2) NOT NULL CHECK (daily_charge >= 0),
  availability_status ENUM('Available','Unavailable','Maintenance') NOT NULL DEFAULT 'Available'
);
CREATE TABLE appointments (
  appointment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  purpose VARCHAR(255) NOT NULL,
  status ENUM('Scheduled','Completed','Cancelled') NOT NULL DEFAULT 'Scheduled',
  consultation_notes TEXT NULL,
  CONSTRAINT fk_appointment_patient FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
  CONSTRAINT fk_appointment_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id),
  INDEX idx_appointment_date (appointment_date), INDEX idx_appointment_patient (patient_id)
);
CREATE TABLE admissions (
  admission_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  room_id INT UNSIGNED NOT NULL,
  admission_date DATE NOT NULL,
  discharge_date DATE NULL,
  reason VARCHAR(255) NOT NULL,
  admission_status ENUM('Admitted','Discharged') NOT NULL DEFAULT 'Admitted',
  CONSTRAINT fk_admission_patient FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
  CONSTRAINT fk_admission_room FOREIGN KEY (room_id) REFERENCES rooms(room_id),
  INDEX idx_admission_status (admission_status)
);
CREATE TABLE treatments (
  treatment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NOT NULL,
  admission_id INT UNSIGNED NULL,
  diagnosis_details TEXT NOT NULL,
  prescribed_medicines TEXT NULL,
  treatment_date DATE NOT NULL,
  follow_up_instructions TEXT NULL,
  CONSTRAINT fk_treatment_patient FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
  CONSTRAINT fk_treatment_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id),
  CONSTRAINT fk_treatment_admission FOREIGN KEY (admission_id) REFERENCES admissions(admission_id)
);
CREATE TABLE bills (
  bill_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  bill_date DATE NOT NULL,
  total_charge DECIMAL(12,2) NOT NULL DEFAULT 0 CHECK (total_charge >= 0),
  discount DECIMAL(12,2) NOT NULL DEFAULT 0 CHECK (discount >= 0),
  tax DECIMAL(12,2) NOT NULL DEFAULT 0 CHECK (tax >= 0),
  final_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  payment_method ENUM('Cash','Card','Mobile Banking','Insurance') NOT NULL DEFAULT 'Cash',
  payment_status ENUM('Paid','Unpaid','Partial') NOT NULL DEFAULT 'Unpaid',
  CONSTRAINT fk_bill_patient FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
  INDEX idx_bill_patient (patient_id), INDEX idx_bill_status (payment_status)
);
CREATE TABLE appointment_log (
  log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT UNSIGNED NOT NULL, patient_id INT UNSIGNED NOT NULL, doctor_id INT UNSIGNED NOT NULL,
  appointment_date DATE NOT NULL, appointment_time TIME NOT NULL, purpose VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL,
  deleted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DELIMITER $$
CREATE TRIGGER before_admission_insert BEFORE INSERT ON admissions FOR EACH ROW
BEGIN
  IF (SELECT availability_status FROM rooms WHERE room_id = NEW.room_id) <> 'Available' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This room is unavailable; admission prevented.';
  END IF;
  UPDATE rooms SET availability_status = 'Unavailable' WHERE room_id = NEW.room_id;
END$$
CREATE TRIGGER before_bill_insert BEFORE INSERT ON bills FOR EACH ROW
BEGIN SET NEW.final_amount = NEW.total_charge + NEW.tax - NEW.discount; END$$
CREATE TRIGGER before_bill_update BEFORE UPDATE ON bills FOR EACH ROW
BEGIN SET NEW.final_amount = NEW.total_charge + NEW.tax - NEW.discount; END$$
CREATE TRIGGER after_appointment_delete AFTER DELETE ON appointments FOR EACH ROW
BEGIN INSERT INTO appointment_log (appointment_id,patient_id,doctor_id,appointment_date,appointment_time,purpose,status) VALUES (OLD.appointment_id,OLD.patient_id,OLD.doctor_id,OLD.appointment_date,OLD.appointment_time,OLD.purpose,OLD.status); END$$
DELIMITER ;

INSERT INTO departments (department_name,location,contact_number,head_of_department) VALUES
('Cardiology','Building A, Floor 2','01700000001','Dr. Farzana Rahman'),('Neurology','Building A, Floor 3','01700000002','Dr. Karim Hasan'),('Orthopedics','Building B, Floor 1','01700000003','Dr. Saiful Islam'),('Pediatrics','Building B, Floor 2','01700000004','Dr. Nusrat Jahan'),('Emergency','Ground Floor','01700000005','Dr. Mahmud Alam');
INSERT INTO rooms (room_number,room_type,capacity,daily_charge,availability_status) VALUES ('G-101','General',2,1500,'Available'),('C-201','Cabin',1,4000,'Available'),('ICU-01','ICU',1,10000,'Available'),('ER-01','Emergency',1,2500,'Available');

-- Required assignment queries (run individually in phpMyAdmin SQL tab):
-- 1 SELECT full_name, gender, phone FROM patients;
-- 2 SELECT d.full_name, d.specialization, dp.department_name FROM doctors d JOIN departments dp ON d.department_id=dp.department_id;
-- 3 SELECT full_name, phone FROM patients WHERE full_name LIKE '%Rahman%';
-- 4 SELECT a.appointment_id,p.full_name patient,d.full_name doctor,a.appointment_date FROM appointments a JOIN patients p ON a.patient_id=p.patient_id JOIN doctors d ON a.doctor_id=d.doctor_id;
-- 5 SELECT full_name, consultation_fee FROM doctors WHERE consultation_fee > (SELECT AVG(consultation_fee) FROM doctors);
-- 6 SELECT dp.department_name,COUNT(d.doctor_id) doctor_count FROM departments dp LEFT JOIN doctors d ON dp.department_id=d.department_id GROUP BY dp.department_id,dp.department_name;
-- 7 SELECT dp.department_name,AVG(d.consultation_fee) average_fee FROM departments dp JOIN doctors d ON dp.department_id=d.department_id GROUP BY dp.department_id,dp.department_name HAVING AVG(d.consultation_fee)>1000;
-- 8 SELECT room_number,room_type,daily_charge FROM rooms;
-- 9 SELECT p.full_name,a.admission_date FROM admissions a JOIN patients p ON a.patient_id=p.patient_id WHERE a.admission_status='Admitted';
-- 10 SELECT p.full_name,SUM(b.final_amount) total_billed FROM patients p JOIN bills b ON p.patient_id=b.patient_id GROUP BY p.patient_id,p.full_name;

-- Optional least-privilege app account; adjust password before use, then set it in config/database.php.
-- CREATE USER 'hospital_app'@'localhost' IDENTIFIED BY 'ChangeThisStrongPassword!';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON hospital_management.* TO 'hospital_app'@'localhost'; FLUSH PRIVILEGES;
