<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'database.php';

// Fetch registered students
$result = $conn->query("SELECT * FROM students WHERE TRIM(firstname) != '' AND TRIM(lastname) != '' ORDER BY id DESC");
$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
$total_students = count($students);

$admin_first = $_SESSION['firstname'] ?? 'Rechelle Ann';
$admin_last  = $_SESSION['lastname']  ?? 'Ababa';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Directory &bull; Rechelle Ann Console</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --canvas-bg: #e2e8f0;
            --dark-ink: #0f172a;
            --surface-white: #ffffff;
            --accent-cyan: #06b6d4;
            --accent-yellow: #f59e0b;
            --border-thick: 2px solid #0f172a;
        }

        body {
            background-color: var(--canvas-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark-ink);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        .neo-box {
            background: var(--surface-white);
            border: var(--border-thick);
            border-radius: 20px;
            box-shadow: 6px 6px 0px var(--dark-ink);
        }

        /* Profile Left Widget */
        .profile-card {
            background: var(--dark-ink);
            color: #ffffff;
            border: var(--border-thick);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 6px 6px 0px var(--accent-cyan);
        }

        /* Table Styling */
        .neo-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .neo-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.25rem;
            border-bottom: 2px solid var(--dark-ink);
        }

        .neo-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #cbd5e1;
            vertical-align: middle;
            font-size: 0.925rem;
        }

        .neo-table tr:hover td {
            background-color: #f1f5f9;
        }

        .btn-neo-btn {
            background: var(--dark-ink);
            color: #ffffff;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            border: var(--border-thick);
            box-shadow: 3px 3px 0px var(--accent-cyan);
            transition: all 0.15s ease;
        }

        .btn-neo-btn:hover {
            background: #1e293b;
            color: #ffffff;
            transform: translate(-1px, -1px);
            box-shadow: 5px 5px 0px var(--dark-ink);
        }

        .pill-id {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="container-fluid" style="max-width: 1250px;">

    <div class="row g-4">
        <!-- Left Side Profile & Status Panel -->
        <div class="col-lg-3">
            <div class="profile-card mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-info text-dark fw-bold d-flex align-items-center justify-content-center mono fs-4" style="width: 50px; height: 50px;">
                        <?= strtoupper(substr($admin_first, 0, 1)); ?>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-white"><?= htmlspecialchars($admin_first); ?></h6>
                        <small class="text-info mono" style="font-size: 0.75rem;">ADMINISTRATOR</small>
                    </div>
                </div>

                <div class="border-top border-secondary pt-3 mt-3">
                    <small class="text-white-50 d-block mb-1 mono">DATABASE</small>
                    <span class="badge bg-light text-dark font-monospace w-100 py-2 text-start px-2 text-truncate">phpcrudrechelleann</span>
                </div>

                <a href="logout.php" class="btn btn-outline-danger w-100 rounded-3 mt-4 text-white border-white border-opacity-25 py-2 fw-semibold">
                    <i class="bi bi-power me-1"></i> Sign Out
                </a>
            </div>

            <!-- Stats Tile -->
            <div class="neo-box p-3 text-center mb-3">
                <span class="text-muted small fw-bold text-uppercase d-block">TOTAL RECORDS</span>
                <h1 class="fw-extrabold mono mb-0 mt-1 text-dark" id="statCounter"><?= $total_students; ?></h1>
            </div>

            <div class="neo-box p-3 text-center bg-white">
                <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold px-3 py-1 rounded-pill">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> System Live
                </span>
            </div>
        </div>

        <!-- Right Side Data Console -->
        <div class="col-lg-9">
            
            <!-- Top Controls -->
            <div class="neo-box p-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-extrabold mb-0">Student Registry Console</h4>
                    <small class="text-muted">Manage active student entries in real time</small>
                </div>

                <button class="btn-neo-btn d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                    <i class="bi bi-plus-lg fs-6"></i> Add Student Entry
                </button>
            </div>

            <!-- Alerts -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success border-2 border-dark rounded-3 shadow-sm py-2 px-3 small fw-bold mb-4 d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_GET['success']); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger border-2 border-dark rounded-3 shadow-sm py-2 px-3 small fw-bold mb-4 d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-exclamation-octagon-fill me-2"></i> <?= htmlspecialchars($_GET['error']); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Table Container -->
            <div class="neo-box overflow-hidden">
                <div class="p-3 bg-light border-bottom border-2 border-dark d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="fw-extrabold text-uppercase small mono"><i class="bi bi-table me-1"></i> Student Roster</span>
                    <div style="max-width: 260px; width: 100%;">
                        <input type="text" id="neoFilter" class="form-control form-control-sm border-2 border-dark rounded-3" placeholder="Search entries...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="neo-table">
                        <thead>
                            <tr>
                                <th># ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Full Identity</th>
                                <th class="text-end">Controls</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $row): ?>
                                    <tr class="entry-row">
                                        <td><span class="pill-id mono">ID-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></span></td>
                                        <td><?= htmlspecialchars($row['firstname']); ?></td>
                                        <td><?= htmlspecialchars($row['lastname']); ?></td>
                                        <td><strong class="entry-name text-dark"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></strong></td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <button class="btn btn-sm btn-outline-dark rounded-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Edit Modal -->
                                            <div class="modal fade text-start" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-2 border-dark rounded-4 shadow-lg">
                                                        <form action="update.php" method="POST">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h6 class="modal-title fw-bold">Edit Student ID-<?= $row['id']; ?></h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body py-3">
                                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-bold">First Name</label>
                                                                    <input type="text" name="firstname" class="form-control border-2 border-dark rounded-3" value="<?= htmlspecialchars($row['firstname']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label small fw-bold">Last Name</label>
                                                                    <input type="text" name="lastname" class="form-control border-2 border-dark rounded-3" value="<?= htmlspecialchars($row['lastname']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 pt-0">
                                                                <button type="button" class="btn btn-light border-dark btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted mono">
                                        No student records stored.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Modal: Add Student Entry -->
<div class="modal fade" id="createStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2 border-dark rounded-4 shadow-lg">
            <form action="insert.php" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">New Student Entry</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">First Name</label>
                        <input type="text" name="firstname" class="form-control border-2 border-dark rounded-3" placeholder="First Name" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Last Name</label>
                        <input type="text" name="lastname" class="form-control border-2 border-dark rounded-3" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border-dark btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const neoFilter = document.getElementById('neoFilter');
    const rows = document.querySelectorAll('.entry-row');
    const counter = document.getElementById('statCounter');

    if (neoFilter) {
        neoFilter.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let matches = 0;

            rows.forEach(row => {
                const name = row.querySelector('.entry-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    row.style.display = '';
                    matches++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (counter) counter.textContent = matches;
        });
    }
</script>
</body>
</html>