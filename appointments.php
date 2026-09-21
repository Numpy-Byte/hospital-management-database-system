<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare('INSERT INTO appointments (patient_id,doctor_id,appointment_date,appointment_time,purpose,status,consultation_notes) VALUES (?,?,?,?,?,?,?)')->execute([(int)$_POST['patient_id'], (int)$_POST['doctor_id'], $_POST['appointment_date'], $_POST['appointment_time'], trim($_POST['purpose']), $_POST['status'], trim($_POST['consultation_notes'])]);
    flash('success', 'Appointment scheduled.');
    header('Location: appointments.php');
    exit;
}
$patients = $pdo->query('SELECT patient_id,full_name FROM patients ORDER BY full_name')->fetchAll();
$doctors = $pdo->query('SELECT doctor_id,full_name FROM doctors WHERE employment_status="Active" ORDER BY full_name')->fetchAll();
$rows = $pdo->query('SELECT a.*,p.full_name patient,d.full_name doctor FROM appointments a JOIN patients p ON a.patient_id=p.patient_id JOIN doctors d ON a.doctor_id=d.doctor_id ORDER BY a.appointment_date DESC,a.appointment_time DESC')->fetchAll();
$page_title = 'Appointments';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">SCHEDULING</p>
        <h1>Appointments</h1>
    </div><a class="button secondary" href="appointments.php?new=1">+ Schedule</a>
</div><?php if (isset($_GET['new'])): ?><section class="panel">
        <h2>Schedule an appointment</h2><?php if (!$patients || !$doctors): ?><p class="notice error">Add at least one patient and active doctor first.</p><?php else: ?><form method="post" class="form-grid two"><label>Patient<select name="patient_id" required><?php foreach ($patients as $p): ?><option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option><?php endforeach; ?></select></label><label>Doctor<select name="doctor_id" required><?php foreach ($doctors as $d): ?><option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['full_name']) ?></option><?php endforeach; ?></select></label><label>Date<input type="date" name="appointment_date" required></label><label>Time<input type="time" name="appointment_time" required></label><label>Purpose<input name="purpose" required></label><label>Status<select name="status">
                        <option>Scheduled</option>
                        <option>Completed</option>
                        <option>Cancelled</option>
                    </select></label><label class="full">Consultation notes<textarea name="consultation_notes"></textarea></label><button>Schedule appointment</button></form><?php endif; ?>
    </section><?php endif; ?><section class="panel">
    <table>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Purpose</th>
            <th>Status</th>
        </tr><?php foreach ($rows as $r): ?><tr>
                <td><?= $r['appointment_date'] ?></td>
                <td><?= $r['appointment_time'] ?></td>
                <td><?= htmlspecialchars($r['patient']) ?></td>
                <td><?= htmlspecialchars($r['doctor']) ?></td>
                <td><?= htmlspecialchars($r['purpose']) ?></td>
                <td><span class="badge"><?= htmlspecialchars($r['status']) ?></span></td>
            </tr><?php endforeach; ?>
    </table>
</section><?php require __DIR__ . '/includes/footer.php'; ?>