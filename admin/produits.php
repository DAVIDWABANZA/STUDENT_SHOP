<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des produits | STUDENT SHOP</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f3f6fc;
            color: #24334d;
        }
        .conteneur {
            max-width: 1100px;
            margin: auto;
        }
        h1 { color: #1246a0; }
        .retour, .ajouter {
            display: inline-block;
            padding: 12px 16px;
            margin: 8px 5px 20px 0;
            border-radius: 8px;
            background: #1455c0;
            color: white;
            text-decoration: none;
        }
        .tableau {
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,.06);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5eaf2;
        }
        th {
            background: #1246a0;
            color: white;
        }
        .modifier {
            color: #1455c0;
            font-weight: bold;
        }
        .vide {
            padding: 20px;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="conteneur">
    <h1>📦 Gestion des produits</h1>
    <p>Voici les produits enregistrés dans ta base de données.</p>
    <a class="retour" href="dashboard.php">← Tableau de bord</a>
    <a class="ajouter" href="ajouter_produit.php">➕ Ajouter un produit</a>
    <?php if (count($products) > 0): ?>
        <div class="tableau">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix (FC)</th>
                        <th>Catégorie</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= (int) $product['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?= number_format((float) $product['price'], 0, ',', ' ') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td><?= (int) $product['stock'] ?></td>
                            <td>
    <a class="modifier"
       href="modifier_produit.php?id=<?= (int) $product['id'] ?>">
        ✏️ Modifier
    </a>

    <form action="supprimer_produit.php" method="POST"
          onsubmit="return confirm('Veux-tu vraiment supprimer ce produit ?');"
          style="display:inline;">
        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit"
                style="background:#dc3545;color:white;border:0;padding:8px;border-radius:5px;cursor:pointer;">
            🗑️ Supprimer
        </button>
    </form>
</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="vide">Aucun produit enregistré pour le moment.</p>
    <?php endif; ?>
</div>
</body>
</html>