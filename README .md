# SIPENKOS - Sistem Pengelolaan dan Reservasi Kos

SIPENKOS (Sistem Pengelolaan dan Reservasi Kos) adalah platform berbasis web yang dirancang untuk memodernisasi seluruh alur operasional bisnis kos-kosan Mitra (Kos Bu Nining). 

Platform ini menjembatani interaksi antara Tamu/Calon Penyewa, Penghuni Resmi, dan Pemilik Kos dalam satu sistem terintegrasi—mulai dari pencarian kamar, pengajuan reservasi, pembayaran tagihan, hingga pelaporan keuangan usaha kos secara real-time.

---

## Tujuan Proyek

1. Digitalisasi Alur Reservasi: Menggantikan proses pemesanan manual menjadi sistem reservasi mandiri bagi calon penyewa dengan transparansi informasi ketersediaan kamar.
2. Kemudahan Manajemen Bagi Penghuni: Menyediakan pusat kendali (dashboard) mandiri bagi penghuni untuk memantau tagihan, membayar sewa, mengajukan perpanjangan masa tinggal, dan melaporkan kerusakan fasilitas.
3. Transparansi Keuangan: Laporan analitik keuangan lengkap seperti pendapatan kotor, rekap pengeluaran operasional, dan laba bersih guna mendukung pengambilan keputusan bisnis yang akurat.
4. Validasi Berkas & Pembayaran: Meminimalisir kesalahan administrasi melalui fitur verifikasi berkas KTP dan bukti pembayaran dengan alur konfirmasi yang jelas.

---

## Informasi Tim PBL & Mitra

Nama Proyek PBL: SIPENKOS (Sistem Pengelolaan dan Reservasi Kos)
Nama Mitra: Kos Bu Nining
Nama Tim Pengembang:
    Alya Fazilatun Nisa (254107060129)
    Citra Anyndya Pramesti (254107060042)
    Muhammad Furqon Adzikri Ramadhan (254107060068)
    Muhammad Reza Fahlevi (254107060158)
    Rafa Achyana Taqwa (254107060097)

---

## Fitur Utama Berdasarkan Peran Pengguna

### 1. Guest / Calon Penyewa (Tanpa Akun)
 Landing Page & Katalog Interaktif: Menampilkan daftar tipe kamar beserta spesifikasi, fasilitas umum (WiFi, Dapur, Listrik, Parkir, Laundry, CCTV), dan Badge Status (Tersedia / Penuh).
 Halaman Detail Kamar Dinamis: Informasi detail per tipe kamar dengan fitur pemilih durasi sewa (1 bulan, 6 bulan, atau 12 bulan).
 Alur Pembuatan Akun: Otomatis mengarahkan calon penyewa untuk registrasi akun saat mengklik tombol penyewaan.

### 2. Calon Penyewa & Alur Reservasi (Proses Transaksi)
 Form Pengajuan Sewa: Pengisian data diri, tanggal mulai kos, durasi sewa, informasi kontak darurat, serta unggah foto KTP.
 Kalkulasi Harga Otomatis: Perhitungan total biaya sewa yang disesuaikan secara otomatis berdasarkan durasi pilihan (1, 6, atau 12 bulan).
 Unggah Bukti Pembayaran: Modul pembayaran awal dan unggah bukti transfer.
 Tracking Status Reservasi: Status verifikasi real-time (Disetujui / Membutuhkan Perbaikan Berkas). Jika berkas blur atau kurang lengkap, sistem memberikan saran perbaikan tanpa menolak calon penyewa secara permanen.

### 3. Dashboard Penghuni Resmi
 Ringkasan Akun Penghuni: Tampilan khusus yang terbuka setelah reservasi disetujui.
 Manajemen Tagihan & Riwayat Pembayaran: Informasi jatuh tempo tagihan sewa bulanan dan arsip transaksi masa lalu.
 Pengajuan Perpanjangan Sewa: Formulir mandiri untuk memperpanjang durasi tinggal sebelum masa sewa berakhir.
 Laporan Kerusakan Fasilitas: Fitur pengaduan masalah/kerusakan barang di kamar atau area publik kos.
 Notifikasi & Aturan Kos: Pusat informasi tata tertib kos dan pengumuman penting dari pemilik.

### 4. Dashboard Pemilik Kos (Admin)
 Dukungan Banyak Pemilik : Sistem dapat diakses oleh pengelola kos sekaligus dengan hak akses teratur.
 Analitik & Laporan Keuangan Lengkap:
   Rekap Pendapatan Kotor (Total uang sewa yang masuk).
   Rekap Pengeluaran Operasional (Listrik, air, perawatan fasilitas, gaji kebersihan).
   Estimasi Laba Bersih (Net Profit) kosan secara periodik (bulanan/tahunan).
 Verifikasi Reservasi & Berkas: Pemeriksaan kelengkapan KTP dan keabsahan bukti bayar calon penyewa.
 Kelola Data Kamar & Penghuni: Pengaturan status ketersediaan kamar, penetapan harga, dan manajemen data aktif penghuni kos.

---

## 🛠️ Teknologi & Stack Digunakan

 Front-End: HTML5, Modular CSS, JavaScript.
 Back-End: PHP Native.
 Database: MySQL.
 Version Control: Git & GitHub.

---

## 📁 Struktur Folder Repositori (On Progress)

```text
sipenkos/
├── assets/                     # Seluruh aset visual & script pendukung
│   ├── css/
│   │   ├── global.css          # Reset CSS, variabel warna universal, navbar, footer
│   │   └── dashboard.css       # Layout khusus sidebar & komponen dashboard
│   ├── js/
│   │   ├── main.js             # Logika interaksi UI universal
│   │   └── detail-kamar.js     # Pembaca URL Parameter ?id= & renderer data kamar
│   └── images/                 # Foto header, tipe kamar, & aset ilustrasi UI
├── beranda.html                # Halaman utama / Beranda publik
├── daftar-kamar.html           # Katalog kamar & status ketersediaan
├── detail-kamar.html           # Detail spesifikasi & durasi sewa
├── registrasi.html             # Form registrasi calon penyewa
├── login.html                  # Form masuk akun
├── form-sewa.html              # Form pengisian data diri & KTP
├── pembayaran.html             # Unggah bukti pembayaran
├── status-reservasi.html       # Halaman pantau verifikasi berkas
├── dashboard.html              # Panel utama penghuni & laporan admin
└── README.md                   # Dokumentasi resmi proyek