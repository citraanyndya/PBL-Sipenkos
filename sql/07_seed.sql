-- Tahap 7: data awal (seed). Jalankan: psql -d sipenkos -f sql/07_seed.sql
-- Akun pengelola awal -> username: admin | password: admin12345 (GANTI setelah login pertama)

INSERT INTO pengelola (username_pengelola, password_pengelola, nama_pengelola, peran)
VALUES ('admin', '$2y$10$WvQIpzpkJPfqumLnGFFW5.12Xgj9.oESvkOKrwu/gGFcKIaz0Zw.a', 'Admin Kos Bu Nining', 'pemilik')
ON CONFLICT (username_pengelola) DO NOTHING;

INSERT INTO fasilitas (nama_fasilitas, kategori_fasilitas) VALUES
    ('AC', 'Kamar'),
    ('Kamar Mandi Dalam', 'Kamar'),
    ('Kamar Mandi Luar', 'Kamar'),
    ('Kasur & Bantal', 'Kamar'),
    ('Lemari Pakaian', 'Kamar'),
    ('Meja & Kursi', 'Kamar'),
    ('Wi-Fi', 'Umum'),
    ('Dapur Bersama', 'Umum'),
    ('Listrik Token', 'Umum'),
    ('Parkir Motor', 'Umum'),
    ('CCTV 24 Jam', 'Umum');

-- 20 kamar: nomor 01-07 Tipe 1, 08-12 Tipe 2, 13-15 Tipe 3, 16-20 Tipe 4
INSERT INTO kamar (nomor_kamar, kelas_kamar, harga_sewa) VALUES
    ('01', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('02', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('03', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('04', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('05', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('06', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('07', 'Tipe 1 Non-AC, Kamar Mandi Luar', 500000),
    ('08', 'Tipe 2 Non-AC, Kamar Mandi Dalam', 650000),
    ('09', 'Tipe 2 Non-AC, Kamar Mandi Dalam', 650000),
    ('10', 'Tipe 2 Non-AC, Kamar Mandi Dalam', 650000),
    ('11', 'Tipe 2 Non-AC, Kamar Mandi Dalam', 650000),
    ('12', 'Tipe 2 Non-AC, Kamar Mandi Dalam', 650000),
    ('13', 'Tipe 3 AC, Kamar Mandi Luar', 800000),
    ('14', 'Tipe 3 AC, Kamar Mandi Luar', 800000),
    ('15', 'Tipe 3 AC, Kamar Mandi Luar', 800000),
    ('16', 'Tipe 4 AC, Kamar Mandi Dalam', 950000),
    ('17', 'Tipe 4 AC, Kamar Mandi Dalam', 950000),
    ('18', 'Tipe 4 AC, Kamar Mandi Dalam', 950000),
    ('19', 'Tipe 4 AC, Kamar Mandi Dalam', 950000),
    ('20', 'Tipe 4 AC, Kamar Mandi Dalam', 950000);

-- Fasilitas per tipe kamar (fasilitas dasar + umum ditambahkan ke semua kamar)
INSERT INTO kamar_fasilitas (id_kamar, id_fasilitas)
SELECT k.id_kamar, f.id_fasilitas FROM kamar k, fasilitas f
WHERE k.kelas_kamar = 'Tipe 1 Non-AC, Kamar Mandi Luar' AND f.nama_fasilitas IN ('Kamar Mandi Luar', 'Kasur & Bantal', 'Lemari Pakaian', 'Meja & Kursi', 'Wi-Fi', 'Dapur Bersama', 'Listrik Token', 'Parkir Motor', 'CCTV 24 Jam');

INSERT INTO kamar_fasilitas (id_kamar, id_fasilitas)
SELECT k.id_kamar, f.id_fasilitas FROM kamar k, fasilitas f
WHERE k.kelas_kamar = 'Tipe 2 Non-AC, Kamar Mandi Dalam' AND f.nama_fasilitas IN ('Kamar Mandi Dalam', 'Kasur & Bantal', 'Lemari Pakaian', 'Meja & Kursi', 'Wi-Fi', 'Dapur Bersama', 'Listrik Token', 'Parkir Motor', 'CCTV 24 Jam');

INSERT INTO kamar_fasilitas (id_kamar, id_fasilitas)
SELECT k.id_kamar, f.id_fasilitas FROM kamar k, fasilitas f
WHERE k.kelas_kamar = 'Tipe 3 AC, Kamar Mandi Luar' AND f.nama_fasilitas IN ('AC', 'Kamar Mandi Luar', 'Kasur & Bantal', 'Lemari Pakaian', 'Meja & Kursi', 'Wi-Fi', 'Dapur Bersama', 'Listrik Token', 'Parkir Motor', 'CCTV 24 Jam');

INSERT INTO kamar_fasilitas (id_kamar, id_fasilitas)
SELECT k.id_kamar, f.id_fasilitas FROM kamar k, fasilitas f
WHERE k.kelas_kamar = 'Tipe 4 AC, Kamar Mandi Dalam' AND f.nama_fasilitas IN ('AC', 'Kamar Mandi Dalam', 'Kasur & Bantal', 'Lemari Pakaian', 'Meja & Kursi', 'Wi-Fi', 'Dapur Bersama', 'Listrik Token', 'Parkir Motor', 'CCTV 24 Jam');

INSERT INTO aturan_kos (nama_aturan, deskripsi_aturan, id_pengelola) VALUES
    ('Jam Bertamu', 'Bertamu maksimal hingga pukul 22.00 WIB di area ruang tamu bersama.', 1),
    ('Keamanan & Kunci', 'Pintu utama wajib dikunci kembali setelah pukul 22.00 WIB.', 1),
    ('Pembayaran Sewa', 'Pembayaran sewa dilakukan paling lambat tanggal 27 setiap bulannya.', 1),
    ('Kebersihan & Ketertiban', 'Dilarang membuat kegaduhan di atas pukul 22.00 WIB dan wajib menjaga kebersihan area bersama.', 1),
    ('Penggunaan Fasilitas', 'Fasilitas umum (dapur, mesin cuci, dll) harap dikembalikan dalam keadaan bersih setelah digunakan.', 1),
    ('Hewan Peliharaan', 'Dilarang membawa atau memelihara hewan peliharaan di area kos.', 1);
