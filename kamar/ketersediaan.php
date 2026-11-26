<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';

$rows = $pdo->query(
    "SELECT kelas_kamar AS kelas, COUNT(*) FILTER (WHERE status_kamar = 'tersedia') AS sisa, COUNT(*) AS total
       FROM kamar GROUP BY kelas_kamar"
)->fetchAll();
foreach ($rows as &$r) {
    $r['sisa'] = (int) $r['sisa'];
    $r['total'] = (int) $r['total'];
}
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
