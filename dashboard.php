<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$counts = [];
foreach (['patients', 'doctors', 'appointments', 'admissions', 'bills'] as $table) {
    $counts[$table] = db()->query("SELECT COUNT(*) FROM $table")->fetchColumn();
}
$revenue = db()->query("SELECT COALESCE(SUM(final_amount), 0) FROM bills WHERE payment_status = 'Paid'")->fetchColumn();
$upcoming = db()->query("SELECT a.appointment_date, a.appointment_time, p.full_name patient, d.full_name doctor FROM appointments a JOIN patients p ON a.patient_id=p.patient_id JOIN doctors d ON a.doctor_id=d.doctor_id WHERE a.appointment_date >= CURDATE() AND a.status='Scheduled' ORDER BY a.appointment_date, a.appointment_time LIMIT 5")->fetchAll();
$page_title = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">OVERVIEW</p>
        <h1>Good day, <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <p>Manage hospital information from one place.</p>
    </div><a class="button" href="patients.php?action=new">+ Register patient</a>
</div>
<section class="stats">
    <article><b><?= $counts['patients'] ?></b><span>Patients</span></article>
    <article><b><?= $counts['doctors'] ?></b><span>Doctors</span></article>
    <article><b><?= $counts['appointments'] ?></b><span>Appointments</span></article>
    <article><b>৳<?= number_format($revenue, 2) ?></b><span>Paid revenue</span></article>
</section>
<section class="panel">
    <h2>Upcoming appointments</h2><?php if (!$upcoming): ?><p class="muted">No upcoming scheduled appointments.</p><?php else: ?><table>
            <tr>
                <th>Date & time</th>
                <th>Patient</th>
                <th>Doctor</th>
            </tr><?php foreach ($upcoming as $row): ?><tr>
                    <td><?= htmlspecialchars($row['appointment_date'] . ' ' . $row['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($row['patient']) ?></td>
                    <td><?= htmlspecialchars($row['doctor']) ?></td>
                </tr><?php endforeach; ?>
        </table><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>