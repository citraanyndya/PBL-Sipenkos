# Penyimpanan data SIPENKOS

- `pengguna.json` adalah contoh/seed struktur akun penghuni dan pengelola.
- Pada versi frontend demo, data aktif disimpan sebagai JSON di `localStorage` dengan key `sipenkosDatabase`.
- Database JSON memisahkan akun dalam `pengguna` dan `pengelola`, serta menyediakan ruang data operasional `dataPenghuni` dan `dataPengelola`.
- API JavaScript: `SIPENKOS_SESI.bacaData("penghuni", "kategori")`, `SIPENKOS_SESI.simpanData("penghuni", "kategori", array)`; gunakan role `pengelola` untuk data pengelola.
- Gunakan `window.SIPENKOS_SESI.eksporJSON()` dari console browser untuk melihat/menyalin seluruh database JSON.
- Data localStorage hanya tersedia pada browser/perangkat yang sama. Data tidak otomatis tersimpan ke server atau file `pengguna.json`.
- Sebelum dipakai secara nyata, implementasikan endpoint PHP Native dan simpan data ke PostgreSQL. Password wajib di-hash menggunakan `password_hash()` dan diverifikasi dengan `password_verify()`. Jangan menyimpan password teks biasa di server maupun file JSON produksi.
- Akun demo: penghuni `penghuni` / `penghuni123`; pengelola `admin` / `admin12345`.
