<?php

function throttle_kunci(string $jenis, string $identitas = ''): string
{
    return mb_substr($jenis . ':' . mb_strtolower($identitas) . '|' . ($_SERVER['REMOTE_ADDR'] ?? '-'), 0, 150);
}

function throttle_terblokir(PDO $pdo, string $kunci, int $maks, int $menit): bool
{
    $s = $pdo->prepare("SELECT COUNT(*) FROM percobaan_login WHERE kunci = :k AND waktu > NOW() - (:m * INTERVAL '1 minute')");
    $s->execute(['k' => $kunci, 'm' => $menit]);
    return (int) $s->fetchColumn() >= $maks;
}

function throttle_catat(PDO $pdo, string $kunci): void
{
    $pdo->prepare("INSERT INTO percobaan_login (kunci) VALUES (:k)")->execute(['k' => $kunci]);
    if (random_int(1, 50) === 1) {   // bersih-bersih berkala
        $pdo->exec("DELETE FROM percobaan_login WHERE waktu < NOW() - INTERVAL '1 day'");
    }
}

function throttle_reset(PDO $pdo, string $kunci): void
{
    $pdo->prepare("DELETE FROM percobaan_login WHERE kunci = :k")->execute(['k' => $kunci]);
}
