-- Tahap 1: tabel akun (PostgreSQL)
-- Jalankan setelah membuat database:
--   createdb sipenkos
--   psql -d sipenkos -f sql/01_pengelola_penghuni.sql

CREATE TABLE IF NOT EXISTS pengelola (
    id_pengelola       SERIAL PRIMARY KEY,
    username_pengelola VARCHAR(50)  NOT NULL UNIQUE,
    password_pengelola VARCHAR(255) NOT NULL,
    nama_pengelola     VARCHAR(100) NOT NULL,
    peran              VARCHAR(30)  NOT NULL DEFAULT 'pemilik'
);

-- nik, kontak_darurat & foto_ktp boleh NULL: baru diisi saat formulir reservasi,
-- sedangkan registrasi akun tidak memintanya. UNIQUE tetap berlaku untuk NIK yang terisi
-- (PostgreSQL mengizinkan banyak NULL pada kolom UNIQUE).
CREATE TABLE IF NOT EXISTS penghuni (
    id_penghuni       SERIAL PRIMARY KEY,
    nik               VARCHAR(16)  UNIQUE,
    nama_lengkap      VARCHAR(100) NOT NULL,
    no_hp             VARCHAR(15)  NOT NULL,
    kontak_darurat    VARCHAR(15),
    username_penghuni VARCHAR(50)  NOT NULL UNIQUE,
    password_penghuni VARCHAR(255) NOT NULL,
    foto_ktp          VARCHAR(255)
);
