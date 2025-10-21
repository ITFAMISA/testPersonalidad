<?php
// API de configuración (mock)
session_start();
header('Content-Type: application/json');

if (!($_SESSION['admin_logged_in'] ?? false)) {
  http_response_code(401);
  echo json_encode(['success' => false, 'error' => 'unauthorized']);
  exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
  echo json_encode(['success' => true, 'data' => [
    'timer' => 15,
    'requireLogin' => false,
    'welcome' => ''
  ]]);
  exit;
}

if ($method === 'POST') {
  // En una implementación real, aquí se validaría y persistiría la configuración
  echo json_encode(['success' => true]);
  exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'method_not_allowed']);
