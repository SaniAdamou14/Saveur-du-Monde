<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'saveurs_du_monde');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si un admin existe, sinon en créer un par défaut
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin");
    if ($stmt->fetchColumn() == 0) {
        $defaultUsername = 'admin';
        $defaultPassword = 'admin123';
        $hash = password_hash($defaultPassword, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->execute([$defaultUsername, $hash]);
        
        // Optionnel : Afficher un message dans les logs ou en console
        error_log("Admin par défaut créé : $defaultUsername / $defaultPassword");
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>