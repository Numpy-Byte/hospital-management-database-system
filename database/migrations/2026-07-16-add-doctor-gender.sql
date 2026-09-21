-- Run this once in phpMyAdmin because the original database was already imported.
USE hospital_management;

ALTER TABLE doctors
ADD COLUMN gender ENUM('Male','Female','Unspecified') NOT NULL DEFAULT 'Unspecified'
AFTER full_name;
