<?php
$folder = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$tujuan = $folder . '/calon-penghuni/beranda.php';

header('Location: ' . $tujuan, true, 302);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPENKOS</title>
</head>
<body>
  <p>Mengalihkan ke <a href="<?= htmlspecialchars($tujuan, ENT_QUOTES, 'UTF-8') ?>">beranda SIPENKOS</a>...</p>
</body>
</html>
