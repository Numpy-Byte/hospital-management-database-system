<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare('INSERT INTO doctors (department_id,full_name,gender,specialization,phone,email,qualification,joining_date,consultation_fee,employment_status) VALUES (?,?,?,?,?,?,?,?,?,?)')->execute([
        (int)$_POST['department_id'],
        trim($_POST['full_name']),
        $_POST['gender'],
        trim($_POST['specialization']),
        trim($_POST['phone']),
        trim($_POST['email']),
        trim($_POST['qualification']),
        $_POST['joining_date'],
        $_POST['consultation_fee'],
        $_POST['employment_status']
    ]);
    flash('success', 'Doctor profile added.');
    header('Location: doctors.php');
    exit;
}
$departments = $pdo->query('SELECT * FROM departments ORDER BY department_name')->fetchAll();
$doctors = $pdo->query('SELECT d.*, dp.department_name FROM doctors d JOIN departments dp ON d.department_id=dp.department_id ORDER BY d.full_name')->fetchAll();
$page_title = 'Doctors';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">CLINICAL STAFF</p>
        <h1>Our medical team</h1>
        <p>Professional profiles for the doctors caring for your patients.</p>
    </div><a class="button secondary" href="doctors.php?new=1">+ Add doctor</a>
</div>
<?php if (isset($_GET['new'])): ?>
    <section class="panel reveal">
        <div class="panel-title">
            <div>
                <p class="eyebrow">NEW PROFILE</p>
                <h2>Add a doctor</h2>
            </div><span class="panel-icon">✚</span>
        </div>
        <form method="post" class="form-grid two"><label>Department<select name="department_id" required><?php foreach ($departments as $d): ?><option value="<?= $d['department_id'] ?>"><?= htmlspecialchars($d['department_name']) ?></option><?php endforeach; ?></select></label><label>Full name<input name="full_name" placeholder="Dr. Amina Rahman" required></label><label>Avatar style<select name="gender" required>
                    <option value="Female">Female doctor</option>
                    <option value="Male">Male doctor</option>
                    <option value="Unspecified">Neutral doctor</option>
                </select></label><label>Specialization<input name="specialization" placeholder="e.g. Cardiologist" required></label><label>Phone<input name="phone" required></label><label>Email<input type="email" name="email" required></label><label>Qualification<input name="qualification" placeholder="e.g. MBBS, MD" required></label><label>Joining date<input type="date" name="joining_date" required></label><label>Consultation fee<input type="number" min="0" step="0.01" name="consultation_fee" required></label><label>Employment status<select name="employment_status">
                    <option>Active</option>
                    <option>On Leave</option>
                    <option>Inactive</option>
                </select></label><button>Add doctor profile</button></form>
    </section>
<?php endif; ?>
<section class="team-grid reveal">
    <?php foreach ($doctors as $d): $avatar = $d['gender'] === 'Female' ? 'female-doctor.svg' : ($d['gender'] === 'Male' ? 'male-doctor.svg' : 'doctor.svg'); ?>
        <article class="doctor-card">
            <div class="doctor-cover"></div><img class="doctor-avatar" src="assets/avatars/<?= $avatar ?>" alt="<?= htmlspecialchars($d['gender']) ?> doctor avatar">
            <div class="doctor-content"><span class="badge"><?= htmlspecialchars($d['employment_status']) ?></span>
                <h2><?= htmlspecialchars($d['full_name']) ?></h2>
                <p class="specialty"><?= htmlspecialchars($d['specialization']) ?></p>
                <div class="doctor-meta"><span>⌂ <?= htmlspecialchars($d['department_name']) ?></span><span>◷ Joined <?= htmlspecialchars(date('M Y', strtotime($d['joining_date']))) ?></span></div>
                <div class="doctor-footer"><span>Consultation</span><strong>৳<?= number_format($d['consultation_fee'], 2) ?></strong></div>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<?php if (!$doctors): ?><section class="empty-state reveal">
        <div class="empty-icon">✚</div>
        <h2>Your medical team starts here</h2>
        <p>Add the first doctor profile to begin scheduling appointments.</p><a class="button" href="doctors.php?new=1">Add the first doctor</a>
    </section><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>