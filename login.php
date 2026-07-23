<?php

session_start();

if (!empty($_SESSION['auth'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === 'admin' && $password === 'MiniShop@03') {
        session_regenerate_id(true);

        $_SESSION['auth'] = true;
        $_SESSION['username'] = 'admin';

        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Sai ten dang nhap hoac mat khau.';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniShop — Dang nhap</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background: #f3f4f6;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 28px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            box-sizing: border-box;
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 14px;
            font-weight: bold;
        }

        input {
            width: 100%;
            margin-top: 6px;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            margin-top: 18px;
            padding: 11px;
            cursor: pointer;
        }

        .error {
            padding: 10px;
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .hint {
            margin-top: 18px;
            padding: 10px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }
    </style>
</head>

<body>
    <main class="login-box">
        <h1>Dang nhap MiniShop</h1>

        <?php if ($error !== ''): ?>
            <p class="error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <label for="username">Ten dang nhap</label>
            <input
                id="username"
                name="username"
                type="text"
                value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                required
                autocomplete="username"
            >

            <label for="password">Mat khau</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
            >

            <button type="submit">Dang nhap</button>
        </form>

        <div class="hint">
            <strong>Tai khoan bai tap:</strong><br>
            admin / MiniShop@03
        </div>
    </main>
</body>
</html>
