# Task 1 — Entity, Attribute, and Key Specification

This data dictionary is the written companion to the ER diagram in `PROJECT_EXPLANATION.md`. `INT UNSIGNED` values are positive whole-number identifiers; `VARCHAR(n)` stores text up to *n* characters; `DECIMAL(p,2)` stores money exactly.

| Entity | Attribute list and domain | Candidate key(s) | Primary key |
| --- | --- | --- | --- |
| Department | `department_id INT`; `department_name VARCHAR(100)`; `location VARCHAR(100)`; `contact_number VARCHAR(20)`; `head_of_department VARCHAR(120)` | department_id, department_name | department_id |
| Doctor | `doctor_id INT`; `department_id INT` (FK); `full_name VARCHAR(120)`; `gender ENUM`; `specialization VARCHAR(120)`; `phone VARCHAR(20)`; `email VARCHAR(120)`; `qualification VARCHAR(120)`; `joining_date DATE`; `consultation_fee DECIMAL(10,2)`; `employment_status ENUM` | doctor_id, phone, email | doctor_id |
| Patient | `patient_id INT`; `full_name VARCHAR(120)`; `gender ENUM`; `date_of_birth DATE`; `blood_group VARCHAR(5)`; `address VARCHAR(255)`; `phone VARCHAR(20)`; `emergency_contact VARCHAR(20)`; `registration_date DATE` | patient_id | patient_id |
| Appointment | `appointment_id INT`; `patient_id INT` (FK); `doctor_id INT` (FK); `appointment_date DATE`; `appointment_time TIME`; `purpose VARCHAR(255)`; `status ENUM`; `consultation_notes TEXT` | appointment_id | appointment_id |
| Room | `room_id INT`; `room_number VARCHAR(20)`; `room_type ENUM`; `capacity TINYINT`; `daily_charge DECIMAL(10,2)`; `availability_status ENUM` | room_id, room_number | room_id |
| Admission | `admission_id INT`; `patient_id INT` (FK); `room_id INT` (FK); `admission_date DATE`; `discharge_date DATE`; `reason VARCHAR(255)`; `admission_status ENUM` | admission_id | admission_id |
| Treatment | `treatment_id INT`; `patient_id INT` (FK); `doctor_id INT` (FK); `admission_id INT` (optional FK); `diagnosis_details TEXT`; `prescribed_medicines TEXT`; `treatment_date DATE`; `follow_up_instructions TEXT` | treatment_id | treatment_id |
| Bill | `bill_id INT`; `patient_id INT` (FK); `bill_date DATE`; `total_charge DECIMAL(12,2)`; `discount DECIMAL(12,2)`; `tax DECIMAL(12,2)`; `final_amount DECIMAL(12,2)`; `payment_method ENUM`; `payment_status ENUM` | bill_id | bill_id |
| User | `user_id INT`; `username VARCHAR(50)`; `email VARCHAR(120)`; `password_hash VARCHAR(255)`; `created_at TIMESTAMP` | user_id, username, email | user_id |
| Appointment Log (weak/audit entity) | `log_id INT`; `appointment_id INT`; patient/doctor IDs; date/time; purpose; status; `deleted_at TIMESTAMP` | log_id | log_id |

## Relationships and cardinality

- A **Department** has 0..N **Doctors**; each Doctor belongs to exactly 1 Department.
- A **Patient** books 0..N **Appointments**; each Appointment is for exactly 1 Patient and 1 Doctor.
- A **Patient** has 0..N **Admissions**; each Admission reserves exactly 1 Room.
- A **Patient** receives 0..N **Treatments**; each Treatment is provided by 1 Doctor and may relate to 0..1 Admission.
- A **Patient** receives 0..N **Bills**.
- `Appointment Log` is a weak audit entity created from an Appointment deletion; it retains the deleted appointment's identifying data.

## Integrity rules shown in the implementation

Foreign keys enforce parent-child relationships. `UNIQUE` protects doctor email/phone and room number. `CHECK` prevents negative charges. Enums restrict status values. The room trigger blocks a second admission to an unavailable room, and the bill triggers calculate `final_amount`.
