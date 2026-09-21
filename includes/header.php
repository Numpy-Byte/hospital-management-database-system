<?php require_once __DIR__ . '/auth.php';
$page_title = $page_title ?? 'HospitalCare'; ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($page_title) ?> | HospitalCare</title>
  <link rel="stylesheet" href="assets/style.css">
  <?php if (!empty($extra_styles)): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($extra_styles) ?>"><?php endif; ?>
</head>

<body>
  <div class="medical-orb orb-one"></div>
  <div class="medical-orb orb-two"></div>
  <header class="topbar"><a class="brand" href="home.php">✚ HospitalCare</a>
    <?php if (logged_in()): ?>
      <nav><a href="dashboard.php">Dashboard</a><a href="patients.php">Patients</a><a href="doctors.php">Doctors</a><a href="appointments.php">Appointments</a><a href="treatments.php">Treatments</a><a href="rooms.php">Rooms & Admissions</a><a href="bills.php">Billing</a></nav>
      <div class="user-menu"><span><?= htmlspecialchars($_SESSION['username']) ?></span><a class="logout" href="logout.php">Log out</a></div>
    <?php else: ?>
      <nav class="guest-nav"><a href="home.php#features">Features</a><a href="home.php#about">About</a></nav>
      <div class="user-menu"><a href="login.php">Sign in</a><a class="button nav-cta" href="register.php">Create account</a></div>
    <?php endif; ?>
  </header>
  <main class="container"><?php show_flash(); ?>