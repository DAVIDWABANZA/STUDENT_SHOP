<?php
require_once __DIR__ . '/config/database.php';
$username = 'admin';
$newPassword = 'ShopAdmin_2026!';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $pdo->prepare(
    "UPDATE admins SET password = ? WHERE username = ?"
);
$stmt->execute([$hash, $username]);
if ($stmt->rowCount() > 0) {
    echo "Mot de passe administrateur mis à jour avec succès !";
} else {
    echo "Aucune modification effectuée. Vérifie le compte admin.";
}
?>