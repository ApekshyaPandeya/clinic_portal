<?php
session_start();
require_once '../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        redirect("admin_dashboard.php");
    } else {
        redirect("user_dashboard.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-clinic-medical"></i>
            <span>Clinic Portal</span>
        </div>
        <div class="nav-links" style="display: flex; gap: 1rem;">
            <a href="login.php" class="btn"
                style="background: transparent; color: var(--primary); border: 1px solid var(--primary);">Login</a>
            <a href="register.php" class="btn btn-primary">Sign Up</a>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-content">
            <h1>Healthcare management made simple.</h1>
            <p>A unified portal for doctors and patients to manage appointments, medications, and health records with
                total security.</p>
            <div class="hero-btns" style="display: flex; gap: 1rem;">
                <a href="register.php" class="btn btn-primary" style="width: auto; padding: 1rem 2.5rem;">Launch
                    Portal</a>
            </div>
        </div>

        <div class="hero-img-container">
            <img src="../assets/img/clinic_hero.png" alt="Clinic Management"
                style="max-width: 500px; height: auto; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
        </div>
    </header>

    <section style="padding: 5rem 8%; text-align: center; background: #fff; margin-top: 2rem;">
        <h2 style="margin-bottom: 3rem; font-size: 2.2rem;">Complete Care Solution</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem;">
            <div class="card" style="border: none; background: #f8fafc;">
                <i class="fas fa-prescription-bottle-alt"
                    style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1.5rem;"></i>
                <h3>Medications</h3>
                <p style="color: var(--text-muted);">Stay on track with your prescriptions and dosage schedules.</p>
            </div>
            <div class="card" style="border: none; background: #f8fafc;">
                <i class="fas fa-calendar-check"
                    style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1.5rem;"></i>
                <h3>Appointments</h3>
                <p style="color: var(--text-muted);">Book and manage your visits with specialists effortlessly.</p>
            </div>
            <div class="card" style="border: none; background: #f8fafc;">
                <i class="fas fa-shield-alt"
                    style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1.5rem;"></i>
                <h3>Secure RBAC</h3>
                <p style="color: var(--text-muted);">Role-based access ensures your medical data is only seen by you and
                    your doctor.</p>
            </div>
        </div>
    </section>

    <footer
        style="padding: 3rem 8%; text-align: center; border-top: 1px solid var(--border-color); color: var(--text-muted);">
        <p>&copy; 2026 Clinic Portal. Built for Academic Purposes.</p>
    </footer>
</body>

</html>