<?php
require_once __DIR__ . '/config/database.php';

try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Shop | Boutique en ligne</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <h1>STUDENT <span>SHOP</span></h1>
        <p>Apprendre • S'équiper • Réussir</p>
    </header>
    <nav>
        <a href="#">Accueil</a>
        <a href="#produits">Nos produits</a>
        <a href="#contact">Contact</a>
    </nav>
    <main>
        <section class="hero">
            <h2>Bienvenue chez Student Shop !</h2>
            <p>Tout ce dont un étudiant a besoin, en un seul endroit.</p>
            <a href="#produits" class="bouton">Découvrir nos produits</a>
        </section>
        <section id="produits">
            <h2>Nos produits</h2>
            <div class="produits">
                <?php foreach ($products as $product): ?>
    <article class="produit">
        <div class="image-produit">
            <?php
            $images = [
                'Informatique' => '💻',
                'Accessoires' => '🎧',
                'Sacs' => '🎒',
                'Fournitures' => '📚'
            ];
            echo $images[$product['category']] ?? '🛍️';
            ?>
        </div>

        <h3><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h3>

        <p><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>

        <strong>
            <?= number_format((float) $product['price'], 0, ',', ' ') ?> FC
        </strong>

        <button onclick="ajouterAuPanier(
            <?= htmlspecialchars(json_encode($product['name']), ENT_QUOTES, 'UTF-8') ?>,
            <?= (float) $product['price'] ?>
        )">
            Ajouter au panier
        </button>
    </article>
<?php endforeach; ?>
            </div>
        </section>
        <section class="panier">
            <h2>🛒 Mon panier</h2>
            <ul id="liste-panier"></ul>
            <p>Total : <strong id="total">0 FC</strong></p>
        </section>
    </main>
    <footer id="contact">
        <p>© 2026 Student Shop — Tous droits réservés.</p>
        <p>Votre boutique pour les études et l'informatique.</p>
        <p>Contacter nous au +243 990 869 072</p>
        <p>Fier de vous cher client !! </p>
    </footer>
</body>
</html>