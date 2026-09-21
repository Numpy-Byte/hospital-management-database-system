<?php
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Modern hospital care';
$extra_styles = 'assets/home.css';
require __DIR__ . '/includes/header.php';
?>
<section class="home-hero reveal">
    <div class="hero-copy">
        <p class="eyebrow">HOSPITAL MANAGEMENT, SIMPLIFIED</p>
        <h1>Care coordination, thoughtfully connected.</h1>
        <p class="hero-text">HospitalCare brings patient records, clinical teams, appointments, admissions, treatments, and billing into one clear and dependable workspace.</p>
        <div class="hero-actions"><?php if (logged_in()): ?><a class="button" href="dashboard.php">Open dashboard</a><a class="text-link" href="patients.php?action=new">Register a patient →</a><?php else: ?><a class="button" href="register.php">Create an account</a><a class="button secondary" href="login.php">Sign in</a><?php endif; ?></div>
        <div class="trust-line"><span>✚</span> Built for the CSE303 Database Management project</div>
    </div>
    <div class="hero-visual" aria-label="Hospital information dashboard illustration">
        <div class="visual-glow"></div>
        <div class="visual-card main-card">
            <div class="card-top"><span class="mini-logo">✚</span><span class="live-pill">SYSTEM READY</span></div>
            <h3>Today at HospitalCare</h3>
            <div class="mini-row"><span class="mini-avatar teal">DR</span>
                <div><b>Clinical team</b><small>Departments & doctors</small></div><i>→</i>
            </div>
            <div class="mini-row"><span class="mini-avatar blue">PT</span>
                <div><b>Patient records</b><small>Secure and organized</small></div><i>→</i>
            </div>
            <div class="mini-chart"><span style="height:48%"></span><span style="height:77%"></span><span style="height:60%"></span><span style="height:89%"></span><span style="height:70%"></span><span style="height:94%"></span></div>
        </div>
        <div class="visual-card floating-card"><span class="float-icon">✓</span>
            <div><b>Appointments</b><small>Managed in one place</small></div>
        </div>
    </div>
</section>
<section id="features" class="home-section reveal">
    <div class="section-intro">
        <p class="eyebrow">DESIGNED FOR CLARITY</p>
        <h2>Every essential record, in its place.</h2>
        <p>Each module reflects a real relationship in the underlying MySQL database.</p>
    </div>
    <div class="feature-grid">
        <article><span class="feature-icon">⌁</span>
            <h3>Patient care</h3>
            <p>Register, search, update, and remove patient records with full CRUD support.</p>
        </article>
        <article><span class="feature-icon">✚</span>
            <h3>Clinical team</h3>
            <p>Organize doctors by department and present them with polished professional profiles.</p>
        </article>
        <article><span class="feature-icon">◷</span>
            <h3>Appointments</h3>
            <p>Connect patients and doctors through a clear scheduling workflow.</p>
        </article>
        <article><span class="feature-icon">▦</span>
            <h3>Admissions & billing</h3>
            <p>Track room occupancy and let MySQL calculate final bills automatically.</p>
        </article>
    </div>
</section>
<section id="about" class="home-band reveal">
    <div>
        <p class="eyebrow">DATABASE-FIRST DESIGN</p>
        <h2>More than a good-looking interface.</h2>
    </div>
    <p>HospitalCare demonstrates relational design with foreign keys, integrity constraints, indexed searches, triggers, user authentication, and persistent MySQL records—all through a usable PHP application.</p><a class="text-link" href="<?= logged_in() ? 'dashboard.php' : 'register.php' ?>"><?= logged_in() ? 'Go to dashboard' : 'Get started' ?> →</a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>