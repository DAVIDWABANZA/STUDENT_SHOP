<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';
// Vérifier le jeton de sécurité
if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Requête refusée. Retourne à la liste des produits.');
}
// Vérifier l'identifiant du produit
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    exit('Identifiant du produit invalide.');
}
// Supprimer le produit
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);
header('Location: produits.php?message=suppression');
exit;