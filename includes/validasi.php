<?php
// Validasi & sanitasi input terpusat. Setiap fungsi mengembalikan [data_bersih, daftar_error].
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/konstanta.php';

function tanggal_valid(string $t): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $t);
    return $d && $d->format('Y-m-d') === $t;
}

function validasi_registrasi(array $in): array
{
    $d = [
        'nama_lengkap' => input_str($in, 'nama_lengkap', 100),
        'no_hp'        => input_str($in, 'no_hp', 15),
        'username'     => input_str($in, 'username', 50),
        'password'     => is_string($in['password'] ?? null) ? $in['password'] : '',
        'konfirmasi'   => is_string($in['konfirmasi'] ?? null) ? $in['konfirmasi'] : '',
    ];
    $err = [];
    if ($d['nama_lengkap'] === '') { $err[] = 'Nama lengkap wajib diisi.'; }
    if (!preg_match('/^[0-9+]{8,15}$/', $d['no_hp'])) { $err[] = 'Nomor HP hanya berisi angka (8-15 digit).'; }
    if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $d['username'])) { $err[] = 'Username 3-50 karakter: huruf, angka, titik, garis bawah, atau strip.'; }
    if (strlen($d['password']) < 8) { $err[] = 'Kata sandi minimal 8 karakter.'; }
    if ($d['password'] !== $d['konfirmasi']) { $err[] = 'Konfirmasi kata sandi tidak sama.'; }
    return [$d, $err];
}


function nik_masuk_akal(string $nik): bool
{
    if (!preg_match('/^\d{16}$/', $nik) || preg_match('/^(\d)\1{15}$/', $nik)) {
        return false;
    }
    $provinsi = [11, 12, 13, 14, 15, 16, 17, 18, 19, 21, 31, 32, 33, 34, 35, 36, 51, 52, 53, 61, 62, 63, 64, 65,
                 71, 72, 73, 74, 75, 76, 81, 82, 91, 92, 93, 94, 95, 96];
    $hari = (int) substr($nik, 6, 2);
    $bulan = (int) substr($nik, 8, 2);
    $hariOk = ($hari >= 1 && $hari <= 31) || ($hari >= 41 && $hari <= 71);
    return in_array((int) substr($nik, 0, 2), $provinsi, true) && $hariOk && $bulan >= 1 && $bulan <= 12;
}

function validasi_reservasi(array $in, bool $nikWajib = true): array
{
    $d = [
        'nik'            => input_str($in, 'nik', 16),
        'kelas'          => input_str($in, 'kelas', 50),
        'durasi_bulan'   => input_int($in, 'durasi_bulan'),
        'tgl_mulai'      => input_str($in, 'tgl_mulai', 10),
        'kontak_darurat' => input_str($in, 'kontak_darurat', 15),
        'nama_kontak'    => input_str($in, 'nama_kontak', 100),
        'hubungan_kontak' => input_str($in, 'hubungan_kontak', 30),
    ];
    $err = [];
    if ($d['kelas'] === '') { $err[] = 'Tipe kamar wajib dipilih.'; }
    if ($nikWajib && !nik_masuk_akal($d['nik'])) { $err[] = 'NIK harus 16 digit angka dan sesuai format KTP.'; }
    if (!isset(DURASI_SEWA[$d['durasi_bulan']])) { $err[] = 'Durasi sewa harus 1, 6, atau 12 bulan.'; }   // whitelist
    if (!tanggal_valid($d['tgl_mulai']) || $d['tgl_mulai'] < date('Y-m-d')) { $err[] = 'Tanggal mulai tidak valid atau sudah lewat.'; }
    if (!preg_match('/^[0-9+]{8,15}$/', $d['kontak_darurat'])) { $err[] = 'Nomor kontak darurat tidak valid.'; }
    if (mb_strlen($d['nama_kontak']) < 2) { $err[] = 'Nama kontak darurat wajib diisi.'; }
    if (!in_array($d['hubungan_kontak'], HUBUNGAN_KONTAK, true)) { $err[] = 'Pilih hubungan dengan kontak darurat.'; }   // whitelist
    return [$d, $err];
}

function validasi_durasi(array $in): array
{
    $d = ['durasi_bulan' => input_int($in, 'durasi_bulan')];
    $err = isset(DURASI_SEWA[$d['durasi_bulan']]) ? [] : ['Durasi harus 1, 6, atau 12 bulan.'];
    return [$d, $err];
}

function validasi_kamar(array $in): array
{
    $d = [
        'nomor_kamar'  => input_str($in, 'nomor_kamar', 10),
        'kelas_kamar'  => input_str($in, 'kelas_kamar', 50),
        'harga_sewa'   => input_int($in, 'harga_sewa'),
        'status_kamar' => input_str($in, 'status_kamar', 20),
        'fasilitas'    => array_values(array_unique(array_filter(array_map('intval', (array) ($in['fasilitas'] ?? [])), fn($x) => $x > 0))),
    ];
    $err = [];
    if ($d['nomor_kamar'] === '') { $err[] = 'Nomor kamar wajib diisi.'; }
    if ($d['kelas_kamar'] === '') { $err[] = 'Tipe kamar wajib diisi.'; }
    if ($d['harga_sewa'] === null || $d['harga_sewa'] < 1 || $d['harga_sewa'] > 100000000) { $err[] = 'Harga sewa harus bilangan bulat lebih dari 0.'; }
    if (!in_array($d['status_kamar'], STATUS_KAMAR, true)) { $err[] = 'Status kamar tidak valid.'; }
    return [$d, $err];
}

function validasi_fasilitas(array $in): array
{
    $d = ['nama_fasilitas' => input_str($in, 'nama_fasilitas', 100), 'kategori_fasilitas' => input_str($in, 'kategori_fasilitas', 50)];
    $err = [];
    if ($d['nama_fasilitas'] === '') { $err[] = 'Nama fasilitas wajib diisi.'; }
    if (!in_array($d['kategori_fasilitas'], KATEGORI_FASILITAS, true)) { $err[] = 'Kategori fasilitas tidak valid.'; }
    return [$d, $err];
}

function validasi_aturan(array $in): array
{
    $d = ['nama_aturan' => input_str($in, 'nama_aturan', 100), 'deskripsi_aturan' => input_str($in, 'deskripsi_aturan', 1000)];
    $err = [];
    if ($d['nama_aturan'] === '') { $err[] = 'Nama aturan wajib diisi.'; }
    if ($d['deskripsi_aturan'] === '') { $err[] = 'Deskripsi aturan wajib diisi.'; }
    return [$d, $err];
}

function validasi_pengeluaran(array $in): array
{
    $d = [
        'tgl_pengeluaran'       => input_str($in, 'tgl_pengeluaran', 10),
        'kategori'              => input_str($in, 'kategori', 50),
        'deskripsi_pengeluaran' => input_str($in, 'deskripsi_pengeluaran', 500),
        'jml_biaya'             => input_int($in, 'jml_biaya'),
    ];
    $err = [];
    if (!tanggal_valid($d['tgl_pengeluaran'])) { $err[] = 'Tanggal tidak valid.'; }
    if (!in_array($d['kategori'], KATEGORI_PENGELUARAN, true)) { $err[] = 'Kategori tidak valid.'; }
    if ($d['deskripsi_pengeluaran'] === '') { $err[] = 'Keterangan wajib diisi.'; }
    if ($d['jml_biaya'] === null || $d['jml_biaya'] < 1 || $d['jml_biaya'] > 1000000000) { $err[] = 'Nominal harus bilangan bulat lebih dari 0.'; }
    return [$d, $err];
}

function validasi_laporan(array $in): array
{
    $d = ['deskripsi_laporan' => input_str($in, 'deskripsi_laporan', 1000)];
    $err = $d['deskripsi_laporan'] === '' ? ['Deskripsi kerusakan wajib diisi.'] : [];
    return [$d, $err];
}


function pastikan_harga_sama($hargaDb, ?int $hargaTampil): void
{
    if ($hargaTampil === null || $hargaTampil < 1) {
        throw new Exception('Harga sewa pada formulir tidak terbaca. Muat ulang halaman lalu coba lagi.');
    }
    if (abs((float) $hargaDb - (float) $hargaTampil) > 0.004) {
        throw new Exception('Harga kamar berubah menjadi ' . rupiah($hargaDb) . ' per bulan (sebelumnya ' . rupiah($hargaTampil)
            . '). Periksa kembali rincian di bawah, lalu kirim ulang jika setuju.');
    }
}

