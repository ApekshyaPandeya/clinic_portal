# Clinic Management Portal (ClinicPro)

A fully functional, secure, and modern clinic management system built with PHP and MySQL.

## Features
- **Session-Based Authentication:** Secure login for Admin and Staff.
- **Full CRUD:** Manage Doctors and Patients (Create, Read, Update, Delete).
- **Security:**
  - **SQL Injection Prevention:** 100% PDO Prepared Statements.
  - **XSS Protection:** Output sanitization using `htmlspecialchars`.
  - **CSRF Protection:** Token-based validation for all major POST requests.
- **Advanced UI:** Glassmorphism design with responsive sidebar and dash cards.
- **Ajax Search:** Real-time patient lookup without page reloads.

## Setup Instructions
1. **Database:**
   - Open PHPMyAdmin and create a database named `clinic_db`.
   - Import the `database.sql` file located in the project root.
2. **Configuration:**
   - Edit `config/db.php` if your database credentials differ (default: `root` / no password).
3. **Login:**
   - **Username:** `admin`
   - **Password:** `password` (hashed in DB)
   - *Note: In a production environment, use `password_hash()` for new users.*

## Project Structure
- `config/`: Database connection.
- `public/`: Web-accessible files (index, dashboard, CRUD).
- `includes/`: Reusable functions and auth checks.
- `assets/`: CSS and generated medical imagery.

## Implementation Details
- **CRUD:** Integrated in `doctors.php` and `patients.php`.
- **Prepared Statements:** Found in `login_process.php` and all API/CRUD files.
- **Ajax:** Implemented in `patients.php` calling `api_patients.php`.
