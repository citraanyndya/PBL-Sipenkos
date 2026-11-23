<?php
$page_module = $page_module ?? '';
$body_attrs = $body_attrs ?? '';

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function inisial($nama)
{
    $hasil = '';
    foreach (array_slice(explode(' ', $nama), 0, 2) as $kata) {
        $hasil .= strtoupper(substr($kata, 0, 1));
    }
    return $hasil;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body data-page="<?php echo $page_id; ?>"<?php if ($page_module != '') { echo ' data-module="' . $page_module . '"'; } ?><?php echo $body_attrs; ?>>
<div class="app">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/navbar.php'; ?>
        <main class="main" id="main">
