# CSE303 Brief — Completion Audit

| Brief requirement | Evidence in this project | Status |
| --- | --- | --- |
| Task 1: entities, attributes, domains, candidate and primary keys | `docs/ER_DATA_DICTIONARY.md` | Complete |
| Task 1: ER relationships, cardinality, weak entity, roles | `docs/PROJECT_EXPLANATION.md` and data dictionary | Complete |
| Task 2: MySQL tables, constraints, and relationships | `database/hospital_management.sql` | Complete |
| Task 2: ten required SQL queries | Commented query section at the bottom of `hospital_management.sql` | Complete |
| Task 2: three required triggers | Room, bill, and appointment-log triggers in `hospital_management.sql` | Complete |
| Task 2: user access control | `database/access_control.sql` | Complete |
| Task 3: RAID 4 parity and single-disk recovery | `docs/RAID4_RECOVERY.md` | Complete |
| Task 4: UNF, 1NF, 2NF, 3NF | `docs/NORMALIZATION.md` | Complete |
| Task 5: B-Tree/B+ Tree and searches | `docs/B_TREE_AND_BPLUS_TREE.md` | Complete |
| Task 6: registration, login, database connectivity, patient CRUD | `register.php`, `login.php`, `patients.php`, and `config/database.php` | Complete |
| Extra: hospital web pages | Dashboard, doctors, appointments, treatments, rooms/admissions, bills, and landing page | Complete |

## Trigger demonstration SQL

Run these only after adding at least one patient and doctor; replace IDs with your actual values.

```sql
-- Demonstrate the room trigger: first insert succeeds and marks the room unavailable.
INSERT INTO admissions (patient_id, room_id, admission_date, reason, admission_status)
VALUES (1, 1, CURDATE(), 'Observation', 'Admitted');

-- Run again with the same room ID to demonstrate the trigger blocking an unavailable room.
INSERT INTO admissions (patient_id, room_id, admission_date, reason, admission_status)
VALUES (2, 1, CURDATE(), 'Second admission test', 'Admitted');

-- Demonstrate bill calculation; final_amount becomes 1075.00 automatically.
INSERT INTO bills (patient_id, bill_date, total_charge, discount, tax, payment_method, payment_status)
VALUES (1, CURDATE(), 1000.00, 50.00, 125.00, 'Cash', 'Unpaid');

-- Demonstrate appointment logging by deleting an existing appointment.
DELETE FROM appointments WHERE appointment_id = 1;
SELECT * FROM appointment_log;
```

## Submission checklist

1. Add your name, ID, and section to the report cover.
2. Take screenshots of phpMyAdmin tables, relationships, indexes, trigger results, the landing page, login, and patient CRUD.
3. Add your ER diagram using the documented entities and relationships.
4. Run each required query and include its screenshot/result in the report.
5. Do not use real patient data.
