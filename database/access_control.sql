-- Task 2: MySQL user access control demonstration.
-- Run as a MySQL administrator. Change the password before using this outside class work.
CREATE USER IF NOT EXISTS 'hospital_app'@'localhost' IDENTIFIED BY 'ChangeThisStrongPassword!';
GRANT SELECT, INSERT, UPDATE, DELETE ON hospital_management.* TO 'hospital_app'@'localhost';
FLUSH PRIVILEGES;

-- Evidence query for the viva:
SHOW GRANTS FOR 'hospital_app'@'localhost';
