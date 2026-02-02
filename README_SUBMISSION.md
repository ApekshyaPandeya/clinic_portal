# Clinic Management Portal - Assignment Submission

## Project Deliverables
- **Live Website Link**: [http://103.41.173.36/~np03cy4a240062/public/index.php](http://103.41.173.36/~np03cy4a240062/public/index.php)
- **Zip File**: `clinic_portal_final.zip`
- **SQL File**: `database.sql`

## Project Overview
A fully functional PHP + MySQL Clinic Management System featuring Role-Based Access Control (RBAC), secure CRUD operations, and AJAX-driven live validation.

## Login Credentials
- **Administrator**: 
  - Username: `admin`
  - Password: `password123`
- **Patient/User (Sample)**: 
  - Username: `patient1`
  - Password: `password123`

## Features Implemented
1. **Full CRUD Implementation**:
   - **Doctors**: Manage staff records (Create, Read, Update, Delete).
   - **Patients**: Manage patient identities and contact info.
   - **Appointments**: Complete scheduling system with status tracking.
   - **Medications**: Prescribe medications with dosage and frequency instructions.
2. **Advanced Search**:
   - Integrated search across all entities (Patients, Doctors, Medications) using SQL `LIKE` filtering.
3. **Ajax Integration (Mandatory)**:
   - **Live Username Validation**: Real-time checking of username availability during signup (Fetch API).
   - **Medication Autocomplete**: Instant search results in the Medication Management panel without page reloads.
4. **Security & Robustness**:
   - **SQL Injection Prevention**: 100% Prepared Statements (PDO).
   - **XSS Protection**: Secure output escaping using `htmlspecialchars`.
   - **CSRF Protection**: Token-based validation for all sensitive POST forms.
   - **Session-Based Authentication**: Secure login state management.
5. **Role-Based Access Control (RBAC)**:
   - Separate experiences for Administrators (Management) and Patients (View-only for their own data).
6. **Modern UI/UX**: Professional healthcare-grade theme using Inter typography and responsive design.

## Setup Instructions
1. Import `database.sql` into a MySQL database named `np03cy4a240062`.
2. Ensure `config/db.php` has the correct database credentials.
3. Upload the project folder to the `public_html/` directory on the server.
4. Access the application via the `/public/index.php` entry point.

## Known Issues
- **None**: Every mandatory feature from the assignment guidelines (CRUD, Search, Ajax, RBAC, Security, Authentication) has been implemented and tested successfully on the server.

## System Folder Structure
- `assets/`: CSS (styling), images (logos, hero), and JS.
- `config/`: Database connection configuration.
- `includes/`: Core logic, RBAC helpers, and CSRF functions.
- `public/`: Publicly accessible PHP scripts (the application view layer).
