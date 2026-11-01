# SIPENKOS Backend

REST API untuk **SIPENKOS** (Sistem Pengelolaan dan Reservasi Kos Putri Berbasis Web), Kos Bu Nining, Malang.
Backend ini mengikuti Dokumen 1 (PRD dan Workflow) dan Dokumen 2 v2 (Arsitektur dan Logika Sistem, ERD 11 tabel). Tidak ada frontend di sini; frontend dari rekan tim cukup memanggil API ini.

**Stack:** Node.js 18+, Express 4, MySQL/MariaDB (`mysql2`), JWT, bcryptjs, multer, zod.

## 1. Cara menjalankan

```bash
npm install
cp .env.example .env        # lalu isi DB_*, JWT_SECRET, ADMIN_*
npm run db:init:contoh      # buat database + 11 tabel + data contoh (fasilitas dan kamar)
                            # (pakai "npm run db:init" bila tidak ingin data contoh)
npm run admin:seed          # buat akun pengelola dari ADMIN_USERNAME / ADMIN_PASSWORD
npm run dev                 # server di http://localhost:3000
```

Cek: `GET http://localhost:3000/api/health`. Jika memakai XAMPP, nyalakan MySQL dulu; user bawaan `root` tanpa password.
Alternatif tanpa skrip: import `database/schema.sql` lewat phpMyAdmin ke database `sipenkos`.

## 2. Struktur

```
database/schema.sql          11 tabel sesuai ERD
database/seed-contoh.sql     data contoh (ganti dengan data asli)
scripts/                     init-db.js, seed-admin.js
src/routes/                  auth, public, penghuni, admin, files
src/services/                logika bisnis + SQL
src/middleware/              auth (JWT, role), upload, validate, error
src/validators/schemas.js    validasi input (zod)
tests/                       unit + alur bisnis
```

## 3. Konvensi API

- Base URL: `/api`. Respons sukses: `{ "success": true, "message": "...", "data": ... }`.
- Respons gagal: `{ "success": false, "message": "...", "errors": [{ "field", "message" }] }` (`errors` hanya pada validasi 422).
- Kode status: 400 input salah, 401 belum login/token salah, 403 role tidak berhak, 404 tidak ada, 409 konflik status, 413/415 berkas terlalu besar/tipe salah, 422 validasi.
- Header untuk endpoint terproteksi: `Authorization: Bearer <token>`.
- Tanggal `YYYY-MM-DD`; waktu `YYYY-MM-DD HH:MM:SS` (zona Asia/Jakarta). Nominal berupa angka.
- Unggahan memakai `multipart/form-data`. Nama field berkas: `foto_ktp`, `bukti_transfer`, `foto_kerusakan`, `foto_kamar`. Maks `MAX_UPLOAD_MB` (bawaan 5 MB); gambar jpg/png/webp (bukti transfer boleh pdf).

## 4. Referensi endpoint

### Publik (Guest)
| Method | Path | Keterangan |
|---|---|---|
| GET | `/kamar?status=&kelas=` | Katalog kamar + fasilitas + status ketersediaan |
| GET | `/kamar/:id` | Detail kamar |
| GET | `/fasilitas` | Daftar fasilitas |
| GET | `/aturan-kos` | Aturan kos |
| GET | `/uploads/kamar/<file>` | Foto kamar (statis, publik) |

### Autentikasi
| Method | Path | Body | Keterangan |
|---|---|---|---|
| POST | `/auth/register` | nama_lengkap, no_hp, kontak_darurat, username, password (min 8) | Daftar; langsung mengembalikan `token` |
| POST | `/auth/login` | username, password | Login penghuni |
| POST | `/auth/admin/login` | username, password | Login pengelola |
| GET | `/auth/me` | | Profil. Untuk penghuni ada `fase`: `calon_penghuni` / `penghuni_aktif` |

**State-Based UI:** frontend cukup membaca `user.fase` dari `/auth/me` (atau dari respons login) untuk memilih tampilan. Fase diturunkan dari pemesanan berstatus `Disetujui`, sehingga berubah otomatis setelah Admin menyetujui.

### Penghuni (`/penghuni/*`, role penghuni)
| Method | Path | Keterangan |
|---|---|---|
| POST | `/penghuni/pemesanan` | Ajukan reservasi. Form: `id_kamar`, `tgl_mulai`, `durasi_bulan` (1-36), `foto_ktp` (wajib bila belum pernah diunggah) |
| GET | `/penghuni/pemesanan` | Riwayat pemesanan |
| GET | `/penghuni/tagihan` | Rincian tagihan: `terbayar`, `sisa`, `terlambat`, dan `riwayat_pembayaran` (lengkap dengan `bukti_transfer_url`) |
| POST | `/penghuni/tagihan/:id/bayar` | Unggah bukti transfer. Form: `bukti_transfer` + `jml_bayar` (opsional). Kosong = lunasi seluruh sisa; lebih kecil = DP/cicilan |
| GET | `/penghuni/pembayaran` | Riwayat pembayaran (ada `bukti_transfer_url` dan `status_bayar`: Menunggu Verifikasi / Diterima / Ditolak) |
| POST | `/penghuni/laporan-kerusakan` | Form: `deskripsi_laporan`, `foto_kerusakan` (opsional). Hanya Penghuni Aktif |
| GET | `/penghuni/laporan-kerusakan` | Laporan milik sendiri |

### Pengelola (`/admin/*`, role pengelola)
| Method | Path | Keterangan |
|---|---|---|
| GET | `/admin/dashboard` | Kapasitas kamar (kosong/terisi/antrean), hal perlu ditindak, ringkasan keuangan bulan ini |
| GET | `/admin/hunian` | Kondisi hunian per kamar (penghuni aktif, jumlah antrean) |
| GET | `/admin/pemesanan?status=` , `/admin/pemesanan/:id` | Daftar/detail (termasuk `foto_ktp_url`) |
| PATCH | `/admin/pemesanan/:id/setujui` | Terima: Disetujui, kamar Terisi, tagihan terbit |
| PATCH | `/admin/pemesanan/:id/tolak` | Tolak |
| PATCH | `/admin/pemesanan/:id/selesai` | Akhiri masa sewa |
| GET | `/admin/tagihan?status=&id_pemesanan=` | Semua tagihan |
| GET | `/admin/pembayaran?status=` | Antrean verifikasi (`status=Menunggu Verifikasi`) |
| PATCH | `/admin/pembayaran/:id/verifikasi` | Body `{ "keputusan": "terima" \| "tolak" }` |
| GET | `/admin/laporan-kerusakan?status=` | Daftar laporan |
| PATCH | `/admin/laporan-kerusakan/:id/status` | Body `{ "status_laporan": "Diproses" \| "Selesai" }` |
| GET/POST | `/admin/pengeluaran` | Daftar (`dari`, `sampai`, `kategori`) / catat: `kategori` (`perbaikan`\|`rutin`), `deskripsi_pengeluaran`, `jml_biaya`, `tgl_pengeluaran` (opsional), `id_laporan` (opsional) |
| PUT/DELETE | `/admin/pengeluaran/:id` | Ubah / hapus |
| GET | `/admin/laporan/net-income?dari=&sampai=` | Pendapatan Bersih + `piutang` + rincian per kamar/kategori (bawaan: bulan berjalan) |
| GET | `/admin/laporan/transaksi?dari=&sampai=` | Buku kas gabungan pemasukan dan pengeluaran, tertaut ke tagihan/penghuni/kamar/laporan kerusakan, dengan `saldo_berjalan` |
| POST/PUT/DELETE | `/admin/kamar`, `/admin/kamar/:id` | CRUD kamar (multipart bila ada `foto_kamar`) |
| PUT | `/admin/kamar/:id/fasilitas` | Body `{ "id_fasilitas": [1,2] }` |
| POST/PUT/DELETE | `/admin/fasilitas[/:id]` | CRUD fasilitas |
| POST/PUT/DELETE | `/admin/aturan-kos[/:id]` | CRUD aturan kos |

### Berkas privat
`GET /api/files/:nama` (KTP, bukti transfer, foto kerusakan) wajib header `Authorization`. Pengelola boleh semua; penghuni hanya miliknya. Karena `<img src>` tidak bisa mengirim header, frontend perlu `fetch` dengan token lalu `URL.createObjectURL(blob)`. URL berkas selalu datang dalam kolom `*_url` pada respons.

## 5. Aturan bisnis yang diimplementasikan

| Kode | Implementasi |
|---|---|
| BL-01..03 | Akun baru = Calon Penghuni; persetujuan pemesanan otomatis menjadikan Penghuni Aktif dan mengisi `id_pengelola_verifikator` |
| BL-04..07 | Status kamar dihitung ulang dari pemesanan: ada Disetujui = Terisi, ada Menunggu = Antrean, selain itu Kosong |
| TG-01..07 | Saat disetujui terbit `durasi_bulan` tagihan (satu per bulan dari `tgl_mulai`), nominal `harga_sewa`, jatuh tempo + `TENGGAT_BAYAR_HARI`; `terlambat` diturunkan dari tanggal |
| PB-01..05 | Unggah bukti (boleh DP): pembayaran dan tagihan Menunggu Verifikasi. Terima: pembayaran Diterima, tagihan Lunas bila total diterima mencukupi, selain itu Dibayar Sebagian. Tolak: pembayaran **dan** tagihan berstatus Ditolak, penghuni mengunggah ulang |
| DP | Nominal < sisa harus ≥ `DP_MINIMAL_PERSEN` (bawaan 30%) dari total tagihan; nominal tidak boleh melebihi sisa; satu bukti tertunda per tagihan |
| NI-01..07 | Net Income = jumlah `jml_bayar` berstatus **Diterima** (DP maupun pelunasan) dikurangi jumlah `jml_biaya`; langsung terbaca setelah Admin memverifikasi; rincian per kamar; pengeluaran tanpa `id_laporan` masuk `pengeluaran_tidak_terkait_kamar` |

## 6. Catatan untuk merge dengan frontend

- **CORS:** isi `CORS_ORIGINS` di `.env` dengan alamat dev frontend (mis. Vite `http://localhost:5173`).
- **Satu server:** build frontend, isi `FRONTEND_PATH=../frontend/dist`; server akan menyajikan frontend dan API pada port yang sama (rute non-`/api` jatuh ke `index.html`).
- Penyimpanan token (localStorage dsb.) dan penentuan tampilan berdasarkan `role` serta `fase` dilakukan di frontend.

## 7. Deviasi dan asumsi (perlu dikonfirmasi tim)

- `penghuni.foto_ktp` dibuat **boleh NULL** (di diagram wajib) karena KTP diunggah saat reservasi, setelah akun dibuat.
- Penghuni tidak lagi memiliki NIK (revisi dosen); identitas dibuktikan lewat foto KTP yang diverifikasi pengelola.
- Status pembayaran memakai **Diterima** (bukan Lunas) agar DP dan pelunasan sama-sama sah; "Lunas" kini hanya status tagihan.
- `DP_MINIMAL_PERSEN=30` adalah angka usulan; ubah di `.env` (0 = tanpa minimum).
- Tidak ada kolom `created_at`; skema dijaga persis seperti diagram.
- Satu penghuni hanya boleh punya satu pemesanan berstatus Menunggu/Disetujui pada satu waktu.
- Perpanjangan sewa tidak ada di Dokumen 2 v2, jadi tidak diimplementasikan.
- Nilai status kamar (`Kosong`/`Antrean`/`Terisi`), status laporan, dan status `Selesai` pemesanan adalah usulan Dokumen 2.
- Validasi tipe berkas memakai MIME dari klien (belum memeriksa isi berkas).

## 8. Revisi dosen: dampak pada kode

| Revisi | Perubahan |
|---|---|
| NIK dihapus | Kolom `penghuni.nik`, validasi registrasi, dan respons profil/pemesanan tidak lagi memuat NIK |
| Keterangan fungsi upload KTP | UI. Pesan galat API sudah menjelaskan fungsi KTP (verifikasi identitas oleh pengelola) |
| Bisa DP | Lihat aturan DP di bagian 5; tagihan punya `terbayar`, `sisa`, dan status `Dibayar Sebagian` |
| Keuangan terhubung | Pemasukan dihitung dari pembayaran Diterima secara langsung; tambahan `piutang` dan buku kas `/admin/laporan/transaksi` |
| Verifikasi ditolak berstatus Ditolak | Pembayaran dan tagihan sama-sama berstatus `Ditolak` (bukan Menunggu / Belum Bayar) |
| Riwayat pembayaran kurang bukti transfer | `bukti_transfer_url` ada di `/penghuni/pembayaran` dan di `riwayat_pembayaran` pada `/penghuni/tagihan` |
| Ganti foto, halaman verifikasi admin (contoh ditolak) | UI/asset. Foto kamar diganti lewat `PUT /admin/kamar/:id` |

Database lama dapat dimigrasi dengan `database/migrations/002_revisi_dosen.sql`, atau cukup buat ulang dengan `npm run db:init:contoh`.

## 9. Pengujian

`npm test` menjalankan unit test tanggal/tagihan dan satu skenario alur bisnis penuh (reservasi, persetujuan, tagihan, pembayaran, kerusakan, Net Income, dasbor). Skenario itu berjalan di SQLite bawaan Node 22.13+ (dilewati otomatis di versi lebih lama) dan **belum menggantikan uji terhadap MySQL asli**.
