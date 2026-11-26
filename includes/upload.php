<?php
// Upload gambar aman: hanya JPG/PNG (dicek dari isi file, bukan ekstensi), maks. 5 MB,
// nama file diacak. Melempar Exception bila gagal.
const UPLOAD_MAKS_BYTE = 5 * 1024 * 1024;

function upload_dir($folder)
{
    return dirname(__DIR__) . '/uploads/' . $folder;
}

function simpan_upload($field, $folder, $wajib = true)
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        if ($wajib) {
            throw new Exception('File gambar wajib diunggah.');
        }
        return null;
    }
    $f = $_FILES[$field];
    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE || $f['size'] > UPLOAD_MAKS_BYTE) {
        throw new Exception('Ukuran file maksimal 5 MB.');
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload gagal, coba lagi.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ekstensi = ['image/jpeg' => 'jpg', 'image/png' => 'png'][$mime] ?? null;
    if ($ekstensi === null) {
        throw new Exception('File harus berformat JPG atau PNG.');
    }
    if (!is_uploaded_file($f['tmp_name'])) {
        throw new Exception('Upload tidak valid.');
    }
    $dir = upload_dir($folder);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $nama = bin2hex(random_bytes(12)) . '.' . $ekstensi;
    $tujuan = $dir . '/' . $nama;

    if (extension_loaded('gd')) {
        $ukuran = @getimagesize($f['tmp_name']);
        if (!$ukuran || $ukuran[0] < 1 || $ukuran[1] < 1) {
            throw new Exception('Berkas bukan gambar yang valid.');
        }
        if ($ukuran[0] > 8000 || $ukuran[1] > 8000) {
            throw new Exception('Dimensi gambar terlalu besar (maksimal 8000 piksel).');
        }
        $img = $mime === 'image/png' ? @imagecreatefrompng($f['tmp_name']) : @imagecreatefromjpeg($f['tmp_name']);
        if (!$img) {
            throw new Exception('Berkas gambar rusak atau tidak dapat dibaca.');
        }
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            // Pertahankan orientasi foto dari HP sebelum EXIF dibuang.
            $exif = @exif_read_data($f['tmp_name']);
            $putar = [3 => 180, 6 => -90, 8 => 90][$exif['Orientation'] ?? 1] ?? 0;
            if ($putar !== 0 && ($rot = imagerotate($img, $putar, 0))) {
                $img = $rot;
            }
        }
        $ok = $mime === 'image/png' ? imagepng($img, $tujuan, 6) : imagejpeg($img, $tujuan, 88);
        imagedestroy($img);
        if (!$ok) {
            throw new Exception('Gagal menyimpan file.');
        }
    } elseif (!move_uploaded_file($f['tmp_name'], $tujuan)) {
        throw new Exception('Gagal menyimpan file.');
    }
    return $nama;
}

function hapus_upload($folder, $nama)
{
    if ($nama && is_file(upload_dir($folder) . '/' . basename($nama))) {
        @unlink(upload_dir($folder) . '/' . basename($nama));
    }
}
