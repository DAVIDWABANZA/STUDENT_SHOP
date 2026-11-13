<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProduits = $stmt->fetchColumn();
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalCommandes = $stmt->fetchColumn();
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
            background: #f3f6fc;
            color: #24334d;
        }
        header {
            background: #1246a0;
            color: white;
            padding: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .deconnexion {
            background: white;
            color: #1246a0;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        main {
            max-width: 1000px;
            margin: 35px auto;
            padding: 0 20px;
        }
        .bienvenue {
            margin-bottom: 25px;
        }
        .cartes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .carte {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }
        .carte h2 {
            color: #1246a0;
            font-size: 18px;
        }
        .nombre {
            font-size: 35px;
            font-weight: bold;
            margin: 15px 0;
        }
        .actions {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
        }
        .action {
            display: block;
            background: #1455c0;
            color: white;
            padding: 17px;
            text-align: center;
            text-decoration: none;
            border-radius: 9px;
            font-weight: bold;
        }
        .action:hover {
            background: #0d3f94;
        }
        .retour {
            background: #24334d;
        }
    </style>
</head>
<body>
<header>
    <h1>STUDENT SHOP — Administration</h1>
    <a class="deconnexion" href="logout.php">Déconnexion</a>
</header>
<main>
    <section class="bienvenue">
        <h2>Bienvenue, <?= htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES, 'UTF-8') ?> ! 👋</h2>
        <p>Gère facilement les produits et les commandes de ta boutique.</p>
    </section>
    <section class="cartes">
        <div class="carte">
            <h2>Produits</h2>
            <p class="nombre"><?= (int) $totalProduits ?></p>
            <p>Produits enregistrés dans la boutique.</p>
        </div>
        <div class="carte">
            <h2>Commandes</h2>
            <p class="nombre"><?= (int) $totalCommandes ?></p>
            <p>Commandes enregistrées dans la base de données.</p>
        </div>
    </section>
    <section class="actions">
        <a class="action" href="ajouter_produit.php">➕ Ajouter un produit</a>
        <a class="action" href="produits.php">📦 Gérer les produits</a>
        <a class="action retour" href="../index.php">🛍️ Retour à la boutique</a>
    </section>
</main>
</body>
</html>