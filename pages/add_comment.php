<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);

    if ($menu_id && $comment && $rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO menu_comments (menu_id, comment, rating) VALUES (?, ?, ?)");
        $success = $stmt->execute([$menu_id, $comment, $rating]);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
exit;