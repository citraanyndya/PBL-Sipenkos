-- Tahap 2: kamar, fasilitas, dan relasi M:N kamar_fasilitas
-- Jalankan: psql -d sipenkos -f sql/02_kamar_fasilitas.sql

CREATE TABLE IF NOT EXISTS kamar (
    id_kamar     SERIAL PRIMARY KEY,
    nomor_kamar  VARCHAR(10)   NOT NULL UNIQUE,
    kelas_kamar  VARCHAR(50)   NOT NULL,
    harga_sewa   NUMERIC(12,2) NOT NULL CHECK (harga_sewa > 0),
    status_kamar VARCHAR(20)   NOT NULL DEFAULT 'tersedia'
                 CHECK (status_kamar IN ('tersedia', 'ditahan', 'terisi')),
    foto_kamar   VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS fasilitas (
    id_fasilitas       SERIAL PRIMARY KEY,
    nama_fasilitas     VARCHAR(100) NOT NULL,
    kategori_fasilitas VARCHAR(50)  NOT NULL
);

CREATE TABLE IF NOT EXISTS kamar_fasilitas (
    id_kamar     INTEGER NOT NULL REFERENCES kamar(id_kamar)         ON DELETE CASCADE,
    id_fasilitas INTEGER NOT NULL REFERENCES fasilitas(id_fasilitas) ON DELETE CASCADE,
    PRIMARY KEY (id_kamar, id_fasilitas)
);
