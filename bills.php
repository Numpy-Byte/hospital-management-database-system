<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare('INSERT INTO bills (patient_id,bill_date,total_charge,discount,tax,payment_method,payment_status) VALUES (?,?,?,?,?,?,?)')->execute([(int)$_POST['patient_id'], $_POST['bill_date'], $_POST['total_charge'], $_POST['discount'], $_POST['tax'], $_POST['payment_method'], $_POST['payment_status']]);
    flash('success', 'Bill created. Final amount calculated by the database trigger.');
    header('Location: bills.php');
    exit;
}
$patients = $pdo->query('SELECT patient_id,full_name FROM patients ORDER BY full_name')->fetchAll();
$bills = $pdo->query('SELECT b.*,p.full_name patient FROM bills b JOIN patients p ON b.patient_id=p.patient_id ORDER BY b.bill_date DESC')->fetchAll();
$page_title = 'Billing';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">FINANCE</p>
        <h1>Billing</h1>
    </div><a class="button secondary" href="bills.php?new=1">+ Create bill</a>
</div><?php if (isset($_GET['new'])): ?><section class="panel">
        <h2>New bill</h2><?php if (!$patients): ?><p class="notice error">Register a patient first.</p><?php else: ?><form method="post" class="form-grid two"><label>Patient<select name="patient_id"><?php foreach ($patients as $p): ?><option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option><?php endforeach; ?></select></label><label>Bill date<input type="date" name="bill_date" required></label><label>Total charge<input type="number" name="total_charge" min="0" step="0.01" required></label><label>Discount<input type="number" name="discount" value="0" min="0" step="0.01" required></label><label>Tax<input type="number" name="tax" value="0" min="0" step="0.01" required></label><label>Payment method<select name="payment_method">
                        <option>Cash</option>
                        <option>Card</option>
                        <option>Mobile Banking</option>
                        <option>Insurance</option>
                    </select></label><label>Payment status<select name="payment_status">
                        <option>Unpaid</option>
                        <option>Paid</option>
                        <option>Partial</option>
                    </select></label><button>Create bill</button></form><?php endif; ?>
    </section><?php endif; ?><section class="panel">
    <table>
        <tr>
            <th>Bill</th>
            <th>Patient</th>
            <th>Date</th>
            <th>Charge</th>
            <th>Discount</th>
            <th>Tax</th>
            <th>Final</th>
            <th>Status</th>
        </tr><?php foreach ($bills as $b): ?><tr>
                <td>B-<?= $b['bill_id'] ?></td>
                <td><?= htmlspecialchars($b['patient']) ?></td>
                <td><?= $b['bill_date'] ?></td>
                <td>৳<?= number_format($b['total_charge'], 2) ?></td>
                <td>৳<?= number_format($b['discount'], 2) ?></td>
                <td>৳<?= number_format($b['tax'], 2) ?></td>
                <td>৳<?= number_format($b['final_amount'], 2) ?></td>
                <td><span class="badge"><?= htmlspecialchars($b['payment_status']) ?></span></td>
            </tr><?php endforeach; ?>
    </table>
</section><?php require __DIR__ . '/includes/footer.php'; ?>