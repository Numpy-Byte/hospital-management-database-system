<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->prepare('INSERT INTO admissions (patient_id,room_id,admission_date,reason,admission_status) VALUES (?,?,?,?,"Admitted")')->execute([(int)$_POST['patient_id'], (int)$_POST['room_id'], $_POST['admission_date'], trim($_POST['reason'])]);
        flash('success', 'Patient admitted; room is now unavailable.');
    } catch (PDOException $e) {
        flash('error', 'Could not admit patient: ' . $e->getMessage());
    }
    header('Location: rooms.php');
    exit;
}
$rooms = $pdo->query('SELECT * FROM rooms ORDER BY room_number')->fetchAll();
$patients = $pdo->query('SELECT patient_id,full_name FROM patients ORDER BY full_name')->fetchAll();
$admissions = $pdo->query('SELECT a.*,p.full_name patient,r.room_number FROM admissions a JOIN patients p ON a.patient_id=p.patient_id JOIN rooms r ON a.room_id=r.room_id WHERE a.admission_status="Admitted" ORDER BY a.admission_date DESC')->fetchAll();
$page_title = 'Rooms & Admissions';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">INPATIENT CARE</p>
        <h1>Rooms & admissions</h1>
    </div><a class="button secondary" href="rooms.php?admit=1">+ Admit patient</a>
</div><?php if (isset($_GET['admit'])): ?><section class="panel">
        <h2>New admission</h2><?php if (!$patients): ?><p class="notice error">Register a patient first.</p><?php else: ?><form method="post" class="form-grid two"><label>Patient<select name="patient_id"><?php foreach ($patients as $p): ?><option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option><?php endforeach; ?></select></label><label>Available room<select name="room_id"><?php foreach ($rooms as $r): ?><?php if ($r['availability_status'] === 'Available'): ?><option value="<?= $r['room_id'] ?>"><?= htmlspecialchars($r['room_number'] . ' — ' . $r['room_type']) ?></option><?php endif; ?><?php endforeach; ?></select></label><label>Admission date<input type="date" name="admission_date" required></label><label>Reason<input name="reason" required></label><button>Admit patient</button></form><?php endif; ?>
    </section><?php endif; ?><section class="panel">
    <h2>Room occupancy</h2>
    <table>
        <tr>
            <th>Room</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Daily charge</th>
            <th>Availability</th>
        </tr><?php foreach ($rooms as $r): ?><tr>
                <td><?= htmlspecialchars($r['room_number']) ?></td>
                <td><?= htmlspecialchars($r['room_type']) ?></td>
                <td><?= $r['capacity'] ?></td>
                <td>৳<?= number_format($r['daily_charge'], 2) ?></td>
                <td><span class="badge"><?= htmlspecialchars($r['availability_status']) ?></span></td>
            </tr><?php endforeach; ?>
    </table>
</section>
<section class="panel">
    <h2>Currently admitted patients</h2>
    <table>
        <tr>
            <th>Patient</th>
            <th>Room</th>
            <th>Admission date</th>
            <th>Reason</th>
        </tr><?php foreach ($admissions as $a): ?><tr>
                <td><?= htmlspecialchars($a['patient']) ?></td>
                <td><?= htmlspecialchars($a['room_number']) ?></td>
                <td><?= $a['admission_date'] ?></td>
                <td><?= htmlspecialchars($a['reason']) ?></td>
            </tr><?php endforeach; ?>
    </table>
</section><?php require __DIR__ . '/includes/footer.php'; ?>