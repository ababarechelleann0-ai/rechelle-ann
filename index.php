<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Node &bull; Rechelle Ann Registry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-base: #f1f5f9;
            --card-surface: #ffffff;
            --text-primary: #0f172a;
            --accent-cyan: #06b6d4;
            --accent-dark: #0891b2;
            --border-line: #cbd5e1;
        }

        body {
            background-color: var(--bg-base);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
            color: var(--text-primary);
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        .auth-container {
            width: 100%;
            max-width: 420px;
            background: var(--card-surface);
            border: 2px solid var(--text-primary);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            box-shadow: 8px 8px 0px #0f172a;
            position: relative;
        }

        .node-tag {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .custom-input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .custom-input {
            width: 100%;
            background: #f8fafc;
            border: 2px solid #94a3b8;
            border-radius: 12px;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            font-size: 0.95rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            outline: none;
            border-color: var(--text-primary);
            background: #ffffff;
            box-shadow: 4px 4px 0px #0f172a;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
        }

        .btn-neo {
            width: 100%;
            background: var(--text-primary);
            color: #ffffff;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 12px;
            border: 2px solid var(--text-primary);
            transition: all 0.15s ease;
            box-shadow: 4px 4px 0px var(--accent-cyan);
        }

        .btn-neo:hover {
            background: var(--accent-dark);
            color: #ffffff;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px var(--text-primary);
        }

        .btn-neo:active {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px var(--text-primary);
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="node-tag mono"><i class="bi bi-hdd-stack-fill text-info me-1"></i> NODE_01</span>
        <span class="badge bg-dark text-white rounded-pill px-2 py-1 mono" style="font-size: 0.65rem;">phpcrudrechelleann</span>
    </div>

    <h4 class="fw-extrabold mb-1">Database Access</h4>
    <p class="text-muted small mb-4">Rechelle Ann Registry System</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger border-2 border-dark rounded-3 py-2 px-3 small fw-semibold mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
            <div><?= htmlspecialchars($_GET['error']); ?></div>
        </div>
    <?php endif; ?>

    <form action="pakicheck.php" method="POST">
        <div class="custom-input-group">
            <i class="bi bi-person-badge input-icon"></i>
            <input type="text" name="username" class="custom-input" placeholder="User ID / Username" required autofocus>
        </div>

        <div class="custom-input-group mb-4">
            <i class="bi bi-shield-lock input-icon"></i>
            <input type="password" name="password" class="custom-input" placeholder="Passcode" required>
        </div>

        <button type="submit" class="btn btn-neo">ENTER SYSTEM &rarr;</button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <small class="text-muted mono" style="font-size: 0.7rem;">SECURITY LEVEL 1 &bull; RECHELLE ANN ABABA</small>
    </div>
</div>

</body>
</html>