<?php
// Pintu masuk: arahkan ke beranda calon penghuni (alamat dihitung otomatis agar benar di subfolder/virtual host).
$folder = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
header('Location: ' . $folder . '/calon-penghuni/beranda.php', true, 302);
exit;
