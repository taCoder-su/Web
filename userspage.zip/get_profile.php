<?php
// get_profile.php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    echo json_encode(['success' => true, 'user' => $user]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch profile']);
}