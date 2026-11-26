<?php
return [
    // Lama kamar ditahan (jam) sejak tombol reservasi ditekan / sejak bukti ditolak.
    'hold_jam'    => (int) (getenv('HOLD_JAM') ?: 24),
    // Pembatas percobaan (anti brute-force). Bisa dilonggarkan lewat environment variable saat pengujian.
    'login_maks'      => (int) (getenv('THROTTLE_LOGIN') ?: 5),     // gagal login per (username + IP) per 15 menit
    'login_ip_maks'   => (int) (getenv('THROTTLE_LOGIN_IP') ?: 30), // gagal login per IP per 15 menit
    'daftar_maks'     => (int) (getenv('THROTTLE_DAFTAR') ?: 10),   // registrasi per IP per jam
    // Hold berulang yang tidak dibayar: setelah N kali kedaluwarsa dalam 7 hari, reservasi baru ditunda.
    'maks_kedaluwarsa' => (int) (getenv('MAKS_KEDALUWARSA') ?: 3),
    // Sesi otomatis berakhir bila tidak aktif (menit).
    'sesi_idle_menit' => (int) (getenv('SESI_IDLE') ?: 120),
    // Rekening tujuan transfer yang ditampilkan di halaman pembayaran.
    'bank'        => getenv('BANK_NAMA') ?: 'BCA',
    'no_rekening' => getenv('BANK_NOREK') ?: '123 456 7890',
    'atas_nama'   => getenv('BANK_ATASNAMA') ?: 'Bu Nining',
    // Info kos untuk footer & halaman Tentang.
    'alamat'      => 'Jl. Pisang Agung III No. 29, Malang, Jawa Timur',
    'whatsapp'    => '0854-4421-1076',
    'jam_bertamu' => '07.00-22.00 WIB',
];
