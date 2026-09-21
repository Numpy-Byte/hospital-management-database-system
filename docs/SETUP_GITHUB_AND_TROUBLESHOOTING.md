# Complete Setup, GitHub, and Troubleshooting Guide

## Setting Up with XAMPP

1. Clone or copy the project files into your XAMPP web root directory:
   ```bash
   git clone https://github.com/Numpy-Byte/hospital-management-database-system.git C:\xampp\htdocs\HospitalManagementSystem
   ```
   Or copy the project folder into `C:\xampp\htdocs\HospitalManagementSystem`.
2. Confirm the entry file exists at `C:\xampp\htdocs\HospitalManagementSystem\home.php`.
3. Start **Apache** and **MySQL** from the XAMPP Control Panel.

## Database Setup

1. Open `http://localhost/phpmyadmin` in your web browser.
2. Click the **Import** tab at the top.
3. Select `database/hospital_management.sql` from the project folder.
4. Click **Import**.
5. This automatically creates the `hospital_management` database, all required tables, triggers, sample data, and indexes.

*(Optional migration note)*: If upgrading an older database instance, the avatar gender migration is located at `database/migrations/2026-07-16-add-doctor-gender.sql`. New setups already include this in `hospital_management.sql`.

## Troubleshooting Database Connections

If the browser displays **Connection refused** or `SQLSTATE[HY000] [2002]`:

1. Confirm MySQL is running (green status) in the XAMPP Control Panel.
2. In phpMyAdmin, check the port displayed on the server line (default is `3306`, but some setups use `3307`).
3. To customize your local port or password without modifying tracked files:
   - Copy `config/database.local.example.php` to `config/database.local.php`.
   - Update `DB_PORT` or credentials in `config/database.local.php`.
   - `config/database.local.php` is ignored by `.gitignore` so your private credentials will never be committed.
4. Refresh your browser (Ctrl+F5).

If phpMyAdmin works but the website reports **Unknown database**, re-import `database/hospital_management.sql`. If it reports **Access denied**, ensure your user is `root` with no password, or set your password in `config/database.local.php`.

## Publishing and Managing on GitHub

### Git Workflow

1. Initialize and link to GitHub (if not already cloned):
   ```bash
   git init
   git add .
   git commit -m "Initial commit: HospitalCare management database system"
   git branch -M main
   git remote add origin https://github.com/Numpy-Byte/hospital-management-database-system.git
   git push -u origin main
   ```
2. For everyday changes:
   ```bash
   git status
   git add .
   git commit -m "Describe your changes"
   git push
   ```

### Using GitHub Desktop

1. Open GitHub Desktop and choose **File → Add local repository**.
2. Select `C:\xampp\htdocs\HospitalManagementSystem`.
3. Commit and push your changes to `origin/main`.

### Team Collaboration Best Practices

1. Each teammate clones the repository to their own `C:\xampp\htdocs\HospitalManagementSystem` directory.
2. Import `database/hospital_management.sql` locally in phpMyAdmin.
3. Before beginning work, run `git pull origin main`.
4. Create feature branches for new work:
   ```bash
   git checkout -b feature/appointment-enhancements
   ```
5. Commit, push branch, and create a Pull Request on GitHub for peer review.

> **Security Reminder**: Never commit real patient data, local credentials (`config/database.local.php`), or database backups containing personal information.
