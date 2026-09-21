<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admission = ($_POST['admission_id'] ?? '') === '' ? null : (int)$_POST['admission_id'];
    $pdo->prepare('INSERT INTO treatments (patient_id,doctor_id,admission_id,diagnosis_details,prescribed_medicines,treatment_date,follow_up_instructions) VALUES (?,?,?,?,?,?,?)')->execute([(int)$_POST['patient_id'], (int)$_POST['doctor_id'], $admission, trim($_POST['diagnosis_details']), trim($_POST['prescribed_medicines']), $_POST['treatment_date'], trim($_POST['follow_up_instructions'])]);
    flash('success', 'Treatment record saved.');
    header('Location: treatments.php');
    exit;
}
$patients = $pdo->query('SELECT patient_id,full_name FROM patients ORDER BY full_name')->fetchAll();
$doctors = $pdo->query('SELECT doctor_id,full_name FROM doctors WHERE employment_status="Active" ORDER BY full_name')->fetchAll();
$admissions = $pdo->query('SELECT a.admission_id,p.full_name,r.room_number FROM admissions a JOIN patients p ON a.patient_id=p.patient_id JOIN rooms r ON a.room_id=r.room_id WHERE a.admission_status="Admitted" ORDER BY a.admission_date DESC')->fetchAll();
$rows = $pdo->query('SELECT t.*,p.full_name patient,d.full_name doctor FROM treatments t JOIN patients p ON t.patient_id=p.patient_id JOIN doctors d ON t.doctor_id=d.doctor_id ORDER BY t.treatment_date DESC,t.treatment_id DESC')->fetchAll();
$page_title = 'Treatments';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">CLINICAL RECORDS</p>
        <h1>Treatments & prescriptions</h1>
        <p>Record diagnoses, prescribed medicines, and follow-up instructions.</p>
    </div><a class="button secondary" href="treatments.php?new=1">+ Add treatment</a>
</div>
<?php if (isset($_GET['new'])): ?><section class="panel reveal">
        <div class="panel-title">
            <div>
                <p class="eyebrow">NEW CLINICAL RECORD</p>
                <h2>Add treatment</h2>
            </div><span class="panel-icon">✚</span>
        </div><?php if (!$patients || !$doctors): ?><p class="notice error">Add at least one patient and active doctor first.</p><?php else: ?><form method="post" class="form-grid two"><label>Patient<select name="patient_id" required><?php foreach ($patients as $p): ?><option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option><?php endforeach; ?></select></label><label>Doctor<select name="doctor_id" required><?php foreach ($doctors as $d): ?><option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['full_name']) ?></option><?php endforeach; ?></select></label><label>Related admission <select name="admission_id">
                        <option value="">Outpatient / none</option><?php foreach ($admissions as $a): ?><option value="<?= $a['admission_id'] ?>"><?= htmlspecialchars($a['full_name'] . ' — Room ' . $a['room_number']) ?></option><?php endforeach; ?>
                    </select></label><label>Treatment date<input type="date" name="treatment_date" required></label><label class="full">Diagnosis details<textarea name="diagnosis_details" required></textarea></label><label>Prescribed medicines<textarea name="prescribed_medicines"></textarea></label><label>Follow-up instructions<textarea name="follow_up_instructions"></textarea></label><button>Save treatment</button></form><?php endif; ?>
    </section><?php endif; ?>
<section class="panel reveal">
    <h2>Saved treatment records</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Diagnosis</th>
            <th>Medicines</th>
            <th>Follow-up</th>
        </tr><?php foreach ($rows as $row): ?><tr>
                <td><?= htmlspecialchars($row['treatment_date']) ?></td>
                <td><?= htmlspecialchars($row['patient']) ?></td>
                <td><?= htmlspecialchars($row['doctor']) ?></td>
                <td><?= htmlspecialchars($row['diagnosis_details']) ?></td>
                <td><?= htmlspecialchars($row['prescribed_medicines'] ?: '—') ?></td>
                <td><?= htmlspecialchars($row['follow_up_instructions'] ?: '—') ?></td>
            </tr><?php endforeach; ?>
    </table><?php if (!$rows): ?><p class="muted">No treatment records have been saved yet.</p><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>