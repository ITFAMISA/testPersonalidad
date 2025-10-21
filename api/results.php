<?php
// API de resultados (mock). Sustituir por consultas a DB en el futuro.
session_start();
header('Content-Type: application/json');

if (!($_SESSION['admin_logged_in'] ?? false)) {
  http_response_code(401);
  echo json_encode(['success' => false, 'error' => 'unauthorized']);
  exit;
}

// Datos simulados
$data = [
  [
    'id' => 101,
    'timestamp' => date(DATE_ATOM, strtotime('-2 days 14:15')),
    'username' => 'jlopez',
    'dominantStyle' => 'TRANSFORMACIONAL',
    'score' => 45,
    'styleScores' => [
      'AUTORITARIO' => 22,
      'DEMOCRÁTICO' => 39,
      'TRANSFORMACIONAL' => 45,
      'TRANSACCIONAL' => 28,
      'LAISSEZ-FAIRE' => 18,
      'CARISMÁTICO' => 41,
      'SITUACIONAL' => 34,
    ],
    'normalized' => [
      'AUTORITARIO' => 49,
      'DEMOCRÁTICO' => 87,
      'TRANSFORMACIONAL' => 100,
      'TRANSACCIONAL' => 62,
      'LAISSEZ-FAIRE' => 40,
      'CARISMÁTICO' => 91,
      'SITUACIONAL' => 76,
    ]
  ],
  [
    'id' => 102,
    'timestamp' => date(DATE_ATOM, strtotime('-1 day 09:05')),
    'username' => 'mgarcia',
    'dominantStyle' => 'DEMOCRÁTICO',
    'score' => 42,
    'styleScores' => [
      'AUTORITARIO' => 20,
      'DEMOCRÁTICO' => 42,
      'TRANSFORMACIONAL' => 36,
      'TRANSACCIONAL' => 30,
      'LAISSEZ-FAIRE' => 24,
      'CARISMÁTICO' => 33,
      'SITUACIONAL' => 37,
    ],
    'normalized' => [
      'AUTORITARIO' => 48,
      'DEMOCRÁTICO' => 100,
      'TRANSFORMACIONAL' => 86,
      'TRANSACCIONAL' => 71,
      'LAISSEZ-FAIRE' => 57,
      'CARISMÁTICO' => 79,
      'SITUACIONAL' => 88,
    ]
  ],
];

echo json_encode(['success' => true, 'data' => $data]);
