<?php
require_once '../includes/config.php';
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT name, description, price, category, image FROM menu");
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="menu_export.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Nom', 'Description', 'Prix', 'Catégorie', 'Image']);

foreach ($menu_items as $item) {
    fputcsv($output, [$item['name'], $item['description'], $item['price'], $item['category'], $item['image']]);
}

fclose($output);
exit;