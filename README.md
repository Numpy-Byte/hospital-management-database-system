# HospitalCare — Hospital Management Database System

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql&logoColor=white)
![XAMPP](https://img.shields.io/badge/Run%20with-XAMPP-FB7A24)
![License](https://img.shields.io/badge/License-MIT-green.svg)

HospitalCare is a PHP and MySQL academic web application for managing core hospital information through a clean, multi-page interface. It was built for a Database Management System course to demonstrate relational database design, authentication, CRUD operations, foreign keys, indexing, triggers, and database connectivity with XAMPP.

> **Academic project notice:** this is a learning prototype. It must not be used with real patient data or treated as a production healthcare application.

## Features

- User registration and login with securely hashed passwords and sessions
- Patient Management with full Create, Read, Update, and Delete (CRUD) operations
- Department-linked doctor records
- Patient-to-doctor appointment scheduling
- Room availability and inpatient admission management
- Billing with automatic final-amount calculation
- Illustrated male, female, and neutral doctor avatars with professional profile cards
- Subtle, reduced-motion-aware page reveal animations
- A public, logo-linked landing page plus Treatment Management records
- Responsive top navigation and dashboard statistics
- MySQL foreign keys, constraints, indexes, and audit logging triggers
- Ten course-required SQL queries, supplied as ready-to-run statements

## Screens and pages

| Page | What it demonstrates |
| --- | --- |
| Dashboard | Summary counts, paid revenue, and upcoming appointments |
| Patients | Full CRUD and simple name/phone search |
| Doctors | Department relationship and staff records |
| Appointments | Patient-doctor scheduling relationship |
| Rooms & Admissions | Room occupancy and availability business rule |
| Billing | Charge, discount, tax, and triggered final amount |
| Login & Registration | Authentication and persistent user accounts |

## Quick start with XAMPP

### 1. Install and start XAMPP

Install [XAMPP](https://www.apachefriends.org/), open the XAMPP Control Panel, then click **Start** beside **Apache** and **MySQL**. Both service labels should turn green.

### 2. Put the website in XAMPP

Copy this entire project folder into:

```text
C:\xampp\htdocs\HospitalManagementSystem
```

You do **not** import the website itself. XAMPP automatically serves folders placed inside `htdocs`.

### 3. Import the database

1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Select **Import** from the top menu.
3. Select `database/hospital_management.sql` from this project.
4. Click **Import**.

This one SQL file creates the `hospital_management` database, its tables, foreign keys, indexes, sample departments/rooms, triggers, and query examples.

### 4. Launch the site

Visit [http://localhost/HospitalManagementSystem/](http://localhost/HospitalManagementSystem/), choose **Create an account**, then sign in. Add doctors and patients before adding appointments or admissions.

## Configuration

XAMPP normally uses MySQL account `root` with an empty password, which works without any configuration. If your MySQL account differs:

1. Copy `config/database.local.example.php` as `config/database.local.php`.
2. Enter your local MySQL credentials in `database.local.php`. If phpMyAdmin shows a server port such as `127.0.0.1:3307`, set `DB_PORT` to that number.
3. Keep that local file private—Git already excludes it.

## Applying the visual-profile upgrade to an existing database

If you imported the database before the doctor-profile update, import this one small migration through phpMyAdmin's **Import** tab:

```text
database/migrations/2026-07-16-add-doctor-gender.sql
```

It adds the doctor avatar-style field without removing any existing records. New database installations do not need this extra step because the main SQL file already includes it.

## Database design

The system contains these entities:

```text
users, departments, doctors, patients, rooms, appointments,
admissions, treatments, bills, appointment_log
```

The primary relationships are:

```text
Department → Doctors
Patient + Doctor → Appointments
Patient + Room → Admissions
Patient + Doctor → Treatments
Patient → Bills
```

For the full explanation, ER diagram, integrity decisions, trigger details, and portfolio talking points, read [docs/PROJECT_EXPLANATION.md](docs/PROJECT_EXPLANATION.md).

## Course-report pack

The implementation and written work are organized so the team can complete every required section of the supplied CSE303 brief:

- [Entity and key data dictionary](docs/ER_DATA_DICTIONARY.md)
- [RAID 4 parity and recovery calculation](docs/RAID4_RECOVERY.md)
- [UNF to 3NF normalization work](docs/NORMALIZATION.md)
- [B-Tree and B+ Tree construction/search](docs/B_TREE_AND_BPLUS_TREE.md)
- [XAMPP setup, error recovery, and GitHub teamwork guide](docs/SETUP_GITHUB_AND_TROUBLESHOOTING.md)
- [MySQL access-control demonstration](database/access_control.sql)
- [Requirement-by-requirement audit and trigger test SQL](docs/COURSE_REQUIREMENTS_AUDIT.md)

### Course requirements implemented

| Requirement | Implementation |
| --- | --- |
| Database connectivity | PHP PDO connection in `config/database.php` |
| User authentication | Registration, hashed passwords, login sessions |
| Patient CRUD | `patients.php` |
| Constraints and relationships | Primary keys, foreign keys, unique fields, checks, enums |
| Indexing | Patient, appointment, admission, and billing indexes |
| Required triggers | Room availability, final bill calculation, appointment deletion audit |
| Required queries | Ten documented queries at the bottom of the SQL file |

## Trigger demonstrations

1. Admit a patient to an available room. The database changes its status to `Unavailable`.
2. Try admitting another patient to that room. MySQL blocks it automatically.
3. Create or update a bill. Its final amount is automatically calculated as `total charge + tax − discount`.
4. Delete an appointment in phpMyAdmin and view `appointment_log` to see its audit record.

## Working as a team with GitHub

### First-time setup

1. Clone the GitHub repository named `hospital-management-database-system`:
   ```bash
   git clone https://github.com/Numpy-Byte/hospital-management-database-system.git
   ```
2. Place or clone it into your XAMPP web root (`C:\xampp\htdocs\HospitalManagementSystem`).
3. Import `database/hospital_management.sql` into phpMyAdmin.

### Daily workflow

Never have everyone edit `main` directly. Each person should make a branch, for example:

```text
feature/patient-page
feature/appointments
feature/database-queries
docs/report-and-screenshots
```

They should commit their work, open a pull request, let one teammate review it, and merge only when it works. This keeps a clean history for your portfolio and prevents accidental overwriting.

### Sharing database changes

Do not repeatedly re-import the main SQL file after the team has entered test data—it can overwrite or conflict with records. For any schema change, create a small migration file such as:

```text
database/migrations/2026-07-20-add-treatment-cost.sql
```

Every teammate imports that new migration through phpMyAdmin. To share sample records, use phpMyAdmin's **Export → Custom** option and export only non-sensitive test data.

## Suggested team roles

- **Database designer:** ER diagram, normalization, schema, indexes, and SQL queries
- **Back-end developer:** authentication, PHP/PDO, patient CRUD
- **Feature developer:** appointments, admissions, billing, and triggers
- **Documentation lead:** report, RAID 4, B-tree/B+ tree task, screenshots, and presentation

## Future roadmap

- Role-based permissions for administrator, receptionist, doctor, and accountant
- Treatment and prescription management forms
- Automated discharge that releases rooms
- Analytics/reporting dashboard
- CSRF protection, tests, and deployment containerization

## License

Released under the [MIT License](LICENSE).
