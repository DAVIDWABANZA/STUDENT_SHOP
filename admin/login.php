<?php
session_start();
require_once __DIR__ . '/../config/database.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $stmt = $pdo->prepare(
        "SELECT id, username, password FROM admins WHERE username = ? LIMIT 1"
    );
    $stmt->execute([$identifiant]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($mot_de_passe, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php');
        exit;
    }
    $message = 'Identifiant ou mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | STUDENT SHOP</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #eaf2ff, #ffffff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .connexion {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(0, 40, 100, 0.12);
        }
        h1 {
            color: #1246a0;
            text-align: center;
            margin-bottom: 8px;
        }
        .description {
            color: #666;
            text-align: center;
            margin-bottom: 28px;
        }
        label {
            display: block;
            margin: 15px 0 8px;
            font-weight: bold;
            color: #24334d;
        }
        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #d5dce8;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus {
            outline: 2px solid #2c68d8;
            border-color: transparent;
        }
        button {
            width: 100%;
            padding: 14px;
            margin-top: 24px;
            border: none;
            border-radius: 8px;
            background: #1455c0;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #0d3f94;
        }
        .erreur {
            padding: 12px;
            background: #ffe9e9;
            color: #a30000;
            border-radius: 8px;
            text-align: center;
        }
        .retour {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #1455c0;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="connexion">
        <h1>STUDENT SHOP</h1>
        <p class="description">Espace d'administration</p>
        <?php if ($message !== ''): ?>
            <p class="erreur">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="identifiant">Identifiant</label>
            <input
                type="text"
                id="identifiant"
                name="identifiant"
                required
                autocomplete="username"
            >
            <label for="mot_de_passe">Mot de passe</label>
            <input
                type="password"
                id="mot_de_passe"
                name="mot_de_passe"
                required
                autocomplete="current-password"
            >
            <button type="submit">Se connecter</button>
        </form>
        <a class="retour" href="../index.php">
            ← Retour à la boutique
        </a>
    </main>
</body>
</html>