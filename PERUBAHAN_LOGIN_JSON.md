# Catatan revisi SIPENKOS

## Yang diubah
1. Login penghuni sekarang memakai username + password.
2. Login pengelola tetap memakai username + password, tetapi kini menggunakan modul autentikasi JSON yang sama.
3. Form registrasi penghuni menambahkan username unik.
4. Data akun disimpan dalam `localStorage` sebagai JSON pada key `sipenkosDatabase`.
5. Sesi aktif disimpan pada `sipenkosDemoSession`.
6. Halaman admin dan penghuni diberi pemeriksaan role pada sisi frontend.
7. Disediakan `data/pengguna.json` sebagai contoh bentuk data awal.

## Akun demo
- Penghuni: username `penghuni`, password `penghuni123`
- Pengelola: username `admin`, password `admin12345`

## Cara melihat data JSON
Buka browser pada halaman SIPENKOS, tekan F12 > Console, lalu jalankan:
```js
window.SIPENKOS_SESI.eksporJSON()
```
Untuk menyimpan hasilnya menjadi file, salin hasil JSON tersebut ke file `.json`.

## Catatan penting
Versi ini masih menggunakan penyimpanan browser (localStorage), bukan penyimpanan file JSON di server. Data hanya ada pada browser/perangkat yang sama. Autentikasi frontend bukan pengamanan produksi. Saat backend PHP Native + PostgreSQL diterapkan, validasi role harus dilakukan di server dan password wajib di-hash.
