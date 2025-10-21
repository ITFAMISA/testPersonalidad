<?php
// API de usuarios (mock)
session_start();
header('Content-Type: application/json');

if (!($_SESSION['admin_logged_in'] ?? false)) {
  http_response_code(401);
  echo json_encode(['success' => false, 'error' => 'unauthorized']);
  exit;
}

$users = [
  [ 'id' => 1, 'username' => 'admin', 'name' => 'Administrador', 'role' => 'admin', 'created_at' => '2024-01-01', 'last_login' => date('Y-m-d H:i') ],
  [ 'id' => 2, 'username' => 'jlopez', 'name' => 'Juan Lopez', 'role' => 'analyst', 'created_at' => '2024-03-18', 'last_login' => '2025-09-30 08:10' ],
  [ 'id' => 3, 'username' => 'mgarcia', 'name' => 'María Garcia', 'role' => 'manager', 'created_at' => '2024-05-22', 'last_login' => '2025-10-01 15:42' ],
];

echo json_encode(['success' => true, 'data' => $users]);
