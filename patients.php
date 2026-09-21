<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$pdo = db();
$fields = ['full_name', 'gender', 'date_of_birth', 'blood_group', 'address', 'phone', 'emergency_contact'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [];
    foreach ($fields as $field) {
        $values[] = trim($_POST[$field] ?? '');
    }
    if (($_POST['mode'] ?? '') === 'edit') {
        $values[] = (int)$_POST['patient_id'];
        $pdo->prepare('UPDATE patients SET full_name=?, gender=?, date_of_birth=?, blood_group=?, address=?, phone=?, emergency_contact=? WHERE patient_id=?')->execute($values);
        flash('success', 'Patient updated.');
    } else {
        $pdo->prepare('INSERT INTO patients (full_name,gender,date_of_birth,blood_group,address,phone,emergency_contact) VALUES (?,?,?,?,?,?,?)')->execute($values);
        flash('success', 'Patient registered.');
    }
    header('Location: patients.php');
    exit;
}
if (isset($_GET['delete'])) {
    $pdo->prepare('DELETE FROM patients WHERE patient_id=?')->execute([(int)$_GET['delete']]);
    flash('success', 'Patient deleted.');
    header('Location: patients.php');
    exit;
}
$editing = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare('SELECT * FROM patients WHERE patient_id=?');
    $s->execute([(int)$_GET['edit']]);
    $editing = $s->fetch();
}
$search = trim($_GET['search'] ?? '');
$q = $pdo->prepare('SELECT * FROM patients WHERE full_name LIKE ? OR phone LIKE ? ORDER BY patient_id DESC');
$q->execute(["%$search%", "%$search%"]);
$patients = $q->fetchAll();
$page_title = 'Patients';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">PATIENT MANAGEMENT</p>
        <h1><?= $editing ? 'Edit patient' : 'Patient records' ?></h1>
    </div><a class="button secondary" href="patients.php?action=new">+ Add patient</a>
</div>
<?php if (isset($_GET['action']) || $editing): ?><section class="panel">
        <h2><?= $editing ? 'Update patient information' : 'Register a patient' ?></h2>
        <form method="post" class="form-grid two"><input type="hidden" name="mode" value="<?= $editing ? 'edit' : 'create' ?>"><?php if ($editing): ?><input type="hidden" name="patient_id" value="<?= $editing['patient_id'] ?>"><?php endif; ?><label>Full name<input name="full_name" required value="<?= htmlspecialchars($editing['full_name'] ?? '') ?>"></label><label>Gender<select name="gender" required>
                    <option value="">Select</option><?php foreach (['Male', 'Female', 'Other'] as $v): ?><option <?= (($editing['gender'] ?? '') === $v) ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?>
                </select></label><label>Date of birth<input type="date" name="date_of_birth" required value="<?= htmlspecialchars($editing['date_of_birth'] ?? '') ?>"></label><label>Blood group<input name="blood_group" placeholder="e.g. O+" value="<?= htmlspecialchars($editing['blood_group'] ?? '') ?>"></label><label>Phone<input name="phone" required value="<?= htmlspecialchars($editing['phone'] ?? '') ?>"></label><label>Emergency contact<input name="emergency_contact" value="<?= htmlspecialchars($editing['emergency_contact'] ?? '') ?>"></label><label class="full">Address<textarea name="address" required><?= htmlspecialchars($editing['address'] ?? '') ?></textarea></label><button><?= $editing ? 'Save changes' : 'Register patient' ?></button><?php if ($editing): ?><a class="button secondary" href="patients.php">Cancel</a><?php endif; ?></form>
    </section><?php endif; ?>
<section class="panel">
    <form class="search"><input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name or phone"><button>Search</button></form>
    <table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Blood group</th>
            <th>Actions</th>
        </tr><?php foreach ($patients as $p): ?><tr>
                <td>P-<?= $p['patient_id'] ?></td>
                <td><?= htmlspecialchars($p['full_name']) ?></td>
                <td><?= htmlspecialchars($p['gender']) ?></td>
                <td><?= htmlspecialchars($p['phone']) ?></td>
                <td><?= htmlspecialchars($p['blood_group'] ?: '—') ?></td>
                <td><a href="patients.php?edit=<?= $p['patient_id'] ?>">Edit</a> · <a class="danger" href="patients.php?delete=<?= $p['patient_id'] ?>" onclick="return confirm('Delete this patient?')">Delete</a></td>
            </tr><?php endforeach; ?>
    </table><?php if (!$patients): ?><p class="muted">No matching patients found.</p><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>