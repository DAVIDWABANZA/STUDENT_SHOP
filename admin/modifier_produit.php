<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    exit('Identifiant du produit invalide.');
}
$categories = [
    'Informatique',
    'Accessoires',
    'Sacs',
    'Fournitures'
];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    http_response_code(404);
    exit('Produit introuvable.');
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
    $categorie = $_POST['categorie'] ?? '';
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    if (
        $nom === '' ||
        $prix === false || $prix === null || $prix <= 0 ||
        $stock === false || $stock === null || $stock < 0 ||
        !in_array($categorie, $categories, true)
    ) {
        $message = 'Vérifie les informations saisies.';
    } else {
        try {
            $update = $pdo->prepare(
                "UPDATE products
                 SET name = ?, description = ?, price = ?, category = ?, stock = ?
                 WHERE id = ?"
            );
            $update->execute([
                $nom,
                $description,
                $prix,
                $categorie,
                $stock,
                $id
            ]);
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $message = 'Produit modifié avec succès !';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = 'Une erreur est survenue pendant la modification.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit | STUDENT SHOP</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 25px 15px;
            font-family: Arial, sans-serif;
            background: #f3f6fc;
            color: #24334d;
        }
        .conteneur {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,.08);
        }
        h1 { color: #1246a0; text-align: center; }
        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd5e3;
            border-radius: 8px;
            font-size: 16px;
        }
        textarea { min-height: 100px; }
        button {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #1455c0;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .message {
            padding: 12px;
            border-radius: 8px;
            background: #e5f8eb;
            color: #176b32;
        }
        .retour {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #1455c0;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="conteneur">
    <h1>✏️ Modifier un produit</h1>
    <?php if ($message !== ''): ?>
        <p class="message">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>
    <form method="POST">
        <label for="nom">Nom du produit</label>
        <input
            type="text"
            id="nom"
            name="nom"
            maxlength="150"
            value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>"
            required
        >
        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <label for="prix">Prix en FC</label>
        <input
            type="number"
            id="prix"
            name="prix"
            min="1"
            step="any"
            value="<?= htmlspecialchars((string) $product['price'], ENT_QUOTES, 'UTF-8') ?>"
            required
        >
        <label for="categorie">Catégorie</label>
        <select id="categorie" name="categorie" required>
            <?php foreach ($categories as $categorie): ?>
                <option
                    value="<?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $product['category'] === $categorie ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="stock">Quantité en stock</label>
        <input
            type="number"
            id="stock"
            name="stock"
            min="0"
            step="1"
            value="<?= (int) $product['stock'] ?>"
            required
        >
        <button type="submit">Enregistrer les modifications</button>
    </form>
    <a class="retour" href="produits.php">← Retour à la liste des produits</a>
</div>
</body>
</html>