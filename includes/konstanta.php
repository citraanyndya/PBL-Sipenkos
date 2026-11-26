<?php
// Daftar nilai yang diizinkan (whitelist) dipakai form & validasi server.
const DURASI_SEWA = [1 => '1 Bulan', 6 => '6 Bulan', 12 => '12 Bulan'];
const KATEGORI_FASILITAS = ['Kamar', 'Umum'];
const KATEGORI_PENGELUARAN = ['Operasional Rutin', 'Perbaikan Fasilitas', 'Lainnya'];
const STATUS_KAMAR = ['tersedia', 'ditahan', 'terisi'];
const STATUS_PEMESANAN = ['menunggu_verifikasi', 'diterima', 'ditolak', 'kedaluwarsa'];
const STATUS_BAYAR = ['menunggu', 'diterima', 'ditolak'];
const STATUS_LAPORAN = ['baru', 'diproses', 'selesai'];
const BULAN_INDO = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Pembayaran: metode, jenis, dan hubungan kontak darurat (whitelist).
const METODE_BAYAR = ['transfer' => 'Transfer Bank', 'tunai' => 'Tunai (bayar langsung ke pemilik)'];
const JENIS_BAYAR = ['penuh' => 'Bayar Penuh', 'dp' => 'DP (uang muka)', 'pelunasan' => 'Pelunasan'];
const PERSEN_DP = 50;   // DP = 50% dari total tagihan
const HUBUNGAN_KONTAK = ['Orang Tua', 'Saudara', 'Wali', 'Pasangan', 'Teman', 'Lainnya'];
