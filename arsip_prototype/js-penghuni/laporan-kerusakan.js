// =========================================================
// Fitur Laporan Kerusakan (penghuni) — frontend
// Dipakai oleh: penghuni/laporan-kerusakan.php        (daftar)
//               penghuni/buat-laporan-kerusakan.php   (form)
//               penghuni/detail-laporan-kerusakan.php (detail, ?id=...)
//
// Data demo di localStorage (sampai backend tersedia):
//   - "sipenkosDamageReports" : daftar laporan, terbaru di indeks 0
//                               (dashboard.js membaca laporan[0].status)
//   - "paymentData" / "draftSewa" : untuk nomor kamar penghuni
//
// Status laporan: "Baru" -> "Diproses" -> "Selesai" (diubah oleh pemilik kos).
// =========================================================
document.addEventListener("DOMContentLoaded", () => {
  const KUNCI = "sipenkosDamageReports";
  const MAKS_UKURAN_FOTO = 5 * 1024 * 1024; // 5 MB
  const MAKS_SISI_FOTO = 1024; // foto diperkecil agar muat di localStorage
  const $ = (id) => document.getElementById(id);
  const halaman = document.body.dataset.halaman;

  // ---------- util ----------
  const ambilJSON = (kunci) => {
    try {
      return JSON.parse(localStorage.getItem(kunci) || "null");
    } catch (error) {
      return null;
    }
  };

  const simpanJSON = (kunci, nilai) => {
    try {
      localStorage.setItem(kunci, JSON.stringify(nilai));
      return true;
    } catch (error) {
      return false; // biasanya karena penyimpanan browser penuh
    }
  };

  const ambilLaporan = () => {
    const data = ambilJSON(KUNCI);
    return Array.isArray(data) ? data : [];
  };

  const formatTanggal = (iso) => {
    const t = new Date(iso);
    if (Number.isNaN(t.getTime())) return "-";
    return t.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" });
  };

  const formatWaktu = (iso) => {
    const t = new Date(iso);
    if (Number.isNaN(t.getTime())) return "";
    const jam = t.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" }).replace(":", ".");
    return `${t.toLocaleDateString("id-ID", { day: "numeric", month: "short", year: "numeric" })}, ${jam} WIB`;
  };

  const svg = (isi) =>
    `<svg class="ikon" viewBox="0 0 24 24" aria-hidden="true">${isi}</svg>`;

  const IKON = {
    centang: svg('<path d="M5 12.5l4.5 4.5L19 7.5"/>'),
    jam: svg('<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'),
    kunciInggris: svg('<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>'),
    jamPasir: svg('<path d="M6 3h12M6 21h12M7 3c0 5 10 5 10 9s-10 4-10 9M17 3c0 5-10 5-10 9s10 4 10 9"/>'),
    panah: svg('<path d="M5 12h14M13 6l6 6-6 6"/>'),
    air: svg('<path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11z"/>'),
    listrik: svg('<path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/>'),
    lainnya: svg('<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>')
  };

  const ikonKategori = (kategori = "") => {
    if (/air|mandi/i.test(kategori)) return IKON.air;
    if (/listrik/i.test(kategori)) return IKON.listrik;
    return IKON.lainnya;
  };

  const kelasStatus = (status = "Baru") => `badge-status badge-status--${String(status).toLowerCase()}`;

  // header, cek login, & tombol keluar diatur common.js
  const profil = ambilJSON("sipenkosDemoUser") || {};
  const namaDepan = (profil.nama || "Penghuni").trim().split(/\s+/)[0];

  // lokasi = kamar penghuni (dari data sewa)
  const ambilLokasi = () => {
    const pembayaran = ambilJSON("paymentData") || {};
    const sewa = pembayaran.reservasi || ambilJSON("draftSewa") || {};
    const nomor = sewa.nomorKamar || sewa.noKamar;
    if (nomor) return `Kamar ${nomor}`;
    if (sewa.judulKamar) return String(sewa.judulKamar).split(",")[0];
    return "Kamar kamu";
  };

  // =========================================================
  // HALAMAN DAFTAR (frame 20)
  // =========================================================
  if (halaman === "daftar") {
    const semua = ambilLaporan();
    let filterAktif = "Semua";

    const buatItem = (laporan) => {
      const li = document.createElement("li");
      li.className = "card-box item-laporan";

      const thumb = document.createElement("div");
      thumb.className = "thumb";
      if (laporan.foto) {
        const img = document.createElement("img");
        img.src = laporan.foto;
        img.alt = "";
        thumb.append(img);
      } else {
        thumb.innerHTML = ikonKategori(laporan.kategori);
      }

      const tengah = document.createElement("div");
      const judul = document.createElement("h2");
      judul.className = "item-judul";
      judul.textContent = laporan.judul;
      const meta = document.createElement("p");
      meta.className = "item-meta";
      meta.textContent = `${laporan.kategori}  ·  ${formatTanggal(laporan.dibuatPada)}`;
      tengah.append(judul, meta);

      const kanan = document.createElement("div");
      kanan.className = "item-kanan";
      const badge = document.createElement("span");
      badge.className = kelasStatus(laporan.status);
      badge.textContent = laporan.status;
      const link = document.createElement("a");
      link.className = "link-detail";
      link.href = `detail-laporan-kerusakan.php?id=${encodeURIComponent(laporan.id)}`;
      link.innerHTML = `Lihat Detail ${IKON.panah}`;
      link.setAttribute("aria-label", `Lihat detail laporan ${laporan.judul}`);
      kanan.append(badge, link);

      li.append(thumb, tengah, kanan);
      return li;
    };

    const tampilkan = () => {
      const hasil = filterAktif === "Semua" ? semua : semua.filter((l) => l.status === filterAktif);
      const daftar = $("daftarLaporan");
      daftar.replaceChildren(...hasil.map(buatItem));

      const kosong = hasil.length === 0;
      $("kosong").hidden = !kosong;
      if (kosong) {
        const belumAda = semua.length === 0;
        $("kosongJudul").textContent = belumAda ? "Belum ada laporan" : `Tidak ada laporan berstatus ${filterAktif}`;
        $("kosongPesan").textContent = belumAda
          ? "Ada fasilitas kamar yang rusak? Buat laporan agar pemilik kos bisa segera menindaklanjuti."
          : "Coba pilih filter status yang lain.";
      }
    };

    $("filterStatus").addEventListener("click", (event) => {
      const tombol = event.target.closest(".filter-pill");
      if (!tombol) return;
      filterAktif = tombol.dataset.filter;
      document.querySelectorAll(".filter-pill").forEach((el) => {
        const aktif = el === tombol;
        el.classList.toggle("aktif", aktif);
        el.setAttribute("aria-pressed", String(aktif));
      });
      tampilkan();
    });

    tampilkan();
  }

  // =========================================================
  // HALAMAN BUAT LAPORAN (frame 21)
  // =========================================================
  if (halaman === "buat") {
    const form = $("formLaporan");
    const lokasi = ambilLokasi();
    $("lokasi").value = lokasi;

    let fotoData = null; // data URL foto yang sudah diperkecil

    const tandaiError = (idField, idPesan, salah, teks) => {
      const field = $(idField);
      const pesan = $(idPesan);
      if (field) {
        field.classList.toggle("salah", salah);
        field.setAttribute("aria-invalid", String(salah));
      }
      if (teks) pesan.textContent = teks;
      pesan.hidden = !salah;
    };

    // --- foto: validasi, perkecil, pratinjau ---
    const perkecilFoto = (file) =>
      new Promise((resolve, reject) => {
        const pembaca = new FileReader();
        pembaca.onerror = reject;
        pembaca.onload = () => {
          const gambar = new Image();
          gambar.onerror = reject;
          gambar.onload = () => {
            const skala = Math.min(1, MAKS_SISI_FOTO / Math.max(gambar.width, gambar.height));
            const kanvas = document.createElement("canvas");
            kanvas.width = Math.round(gambar.width * skala);
            kanvas.height = Math.round(gambar.height * skala);
            kanvas.getContext("2d").drawImage(gambar, 0, 0, kanvas.width, kanvas.height);
            resolve(kanvas.toDataURL("image/jpeg", 0.8));
          };
          gambar.src = pembaca.result;
        };
        pembaca.readAsDataURL(file);
      });

    const hapusFoto = () => {
      fotoData = null;
      $("foto").value = "";
      $("pratinjau").hidden = true;
      $("areaUnggah").hidden = false;
    };

    const prosesFoto = async (file) => {
      tandaiError(null, "fotoError", false);
      if (!file) return;
      if (!["image/jpeg", "image/png"].includes(file.type)) {
        hapusFoto();
        tandaiError(null, "fotoError", true, "Format foto harus JPG atau PNG.");
        return;
      }
      if (file.size > MAKS_UKURAN_FOTO) {
        hapusFoto();
        tandaiError(null, "fotoError", true, "Ukuran foto maksimal 5 MB.");
        return;
      }
      try {
        fotoData = await perkecilFoto(file);
        $("pratinjauGambar").src = fotoData;
        $("pratinjauNama").textContent = file.name;
        $("pratinjau").hidden = false;
        $("areaUnggah").hidden = true;
      } catch (error) {
        hapusFoto();
        tandaiError(null, "fotoError", true, "Foto tidak bisa dibaca. Coba foto lain.");
      }
    };

    $("foto").addEventListener("change", (event) => prosesFoto(event.target.files[0]));
    $("hapusFoto").addEventListener("click", hapusFoto);

    const area = $("areaUnggah");
    ["dragenter", "dragover"].forEach((jenis) =>
      area.addEventListener(jenis, (event) => {
        event.preventDefault();
        area.classList.add("seret");
      })
    );
    ["dragleave", "drop"].forEach((jenis) =>
      area.addEventListener(jenis, (event) => {
        event.preventDefault();
        area.classList.remove("seret");
      })
    );
    area.addEventListener("drop", (event) => prosesFoto(event.dataTransfer.files[0]));

    // hilangkan pesan error saat field diisi
    [["kategori", "kategoriError"], ["judul", "judulError"], ["deskripsi", "deskripsiError"]].forEach(([field, pesan]) => {
      $(field).addEventListener("input", () => {
        if ($(field).value.trim()) tandaiError(field, pesan, false);
      });
    });

    // --- kirim ---
    form.addEventListener("submit", (event) => {
      event.preventDefault();

      const kategori = $("kategori").value;
      const judul = $("judul").value.trim();
      const deskripsi = $("deskripsi").value.trim();

      tandaiError("kategori", "kategoriError", !kategori);
      tandaiError("judul", "judulError", !judul);
      tandaiError("deskripsi", "deskripsiError", !deskripsi);

      const pertamaSalah = !kategori ? "kategori" : !judul ? "judul" : !deskripsi ? "deskripsi" : null;
      if (pertamaSalah) {
        $(pertamaSalah).focus();
        return;
      }

      const sekarang = new Date();
      const tanggalKode = `${sekarang.getFullYear()}${String(sekarang.getMonth() + 1).padStart(2, "0")}${String(sekarang.getDate()).padStart(2, "0")}`;
      const daftar = ambilLaporan();

      const laporan = {
        id: `LK-${tanggalKode}-${String(daftar.length + 1).padStart(3, "0")}`,
        judul,
        kategori,
        lokasi,
        deskripsi,
        foto: fotoData,
        status: "Baru",
        pelapor: namaDepan,
        dibuatPada: sekarang.toISOString(),
        diterimaPada: null,
        selesaiPada: null,
        catatan: null
      };

      // terbaru di depan, agar dashboard menampilkan status laporan terakhir
      daftar.unshift(laporan);

      if (!simpanJSON(KUNCI, daftar)) {
        tandaiError(null, "fotoError", true,
          "Penyimpanan browser penuh. Coba kirim tanpa foto atau gunakan foto yang lebih kecil.");
        return;
      }

      window.location.href = `detail-laporan-kerusakan.php?id=${encodeURIComponent(laporan.id)}`;
    });
  }

  // =========================================================
  // HALAMAN DETAIL (frame 22)
  // =========================================================
  if (halaman === "detail") {
    const id = new URLSearchParams(window.location.search).get("id");
    const laporan = ambilLaporan().find((l) => l.id === id);

    if (!laporan) {
      $("tidakDitemukan").hidden = false;
      return;
    }

    $("kontenDetail").hidden = false;
    document.title = `${laporan.judul} - SIPENKOS`;

    $("detailJudul").textContent = laporan.judul;
    $("detailStatus").className = kelasStatus(laporan.status);
    $("detailStatus").textContent = laporan.status;
    $("detailId").textContent = `#${laporan.id}`;
    $("detailKategori").textContent = laporan.kategori;
    $("detailLokasi").textContent = laporan.lokasi;
    $("detailTanggal").textContent = formatTanggal(laporan.dibuatPada);
    $("detailDeskripsi").textContent = laporan.deskripsi;
    $("detailPelapor").textContent =
      `Pengajuan dibuat oleh ${laporan.pelapor || namaDepan} (Penghuni ${laporan.lokasi})`;

    if (laporan.foto) {
      $("detailFoto").src = laporan.foto;
      $("blokFoto").hidden = false;
    }

    if (laporan.catatan) {
      $("catatanIsi").textContent = laporan.catatan;
      $("catatanPengelola").hidden = false;
    }

    // --- riwayat penanganan ---
    const status = laporan.status;
    const sudahDiterima = status === "Diproses" || status === "Selesai";
    const sudahSelesai = status === "Selesai";

    const langkah = [
      {
        judul: "Laporan dikirim",
        ket: formatWaktu(laporan.dibuatPada),
        keadaan: "selesai"
      },
      {
        judul: "Diterima pemilik kos",
        ket: sudahDiterima ? formatWaktu(laporan.diterimaPada) || "Laporan sudah ditinjau" : "Menunggu ditinjau pemilik kos",
        keadaan: sudahDiterima ? "selesai" : "berjalan",
        ikon: IKON.jam
      },
      {
        judul: sudahSelesai ? "Sudah diperbaiki" : "Sedang diperbaiki",
        ket: sudahSelesai ? "Perbaikan sudah dikerjakan" : sudahDiterima ? "Teknisi telah ditugaskan" : "Menunggu jadwal perbaikan",
        keadaan: sudahSelesai ? "selesai" : sudahDiterima ? "berjalan" : "menunggu",
        ikon: IKON.kunciInggris
      },
      {
        judul: "Selesai",
        ket: sudahSelesai ? formatWaktu(laporan.selesaiPada) || "Perbaikan telah selesai" : "Menunggu konfirmasi perbaikan",
        keadaan: sudahSelesai ? "selesai" : "menunggu"
      }
    ];

    $("timeline").replaceChildren(
      ...langkah.map((l) => {
        const li = document.createElement("li");
        li.className = `timeline-item ${l.keadaan}`;
        if (l.keadaan === "berjalan") li.setAttribute("aria-current", "step");

        const ikon = l.keadaan === "selesai" ? IKON.centang : l.keadaan === "berjalan" ? l.ikon : IKON.jamPasir;
        const titik = document.createElement("span");
        titik.className = "timeline-dot";
        titik.innerHTML = ikon;

        const teks = document.createElement("div");
        const judul = document.createElement("p");
        judul.className = "timeline-judul";
        judul.textContent = l.judul;
        const ket = document.createElement("p");
        ket.className = "timeline-ket";
        ket.textContent = l.ket;
        teks.append(judul, ket);

        li.append(titik, teks);
        return li;
      })
    );

    // --- SIMULASI (khusus development): pemilik kos memproses laporan ---
    const simulasi = $("simulasiPenanganan");
    if (simulasi && !sudahSelesai) {
      const ubahStatus = (statusBaru) => {
        const daftar = ambilLaporan();
        const i = daftar.findIndex((l) => l.id === laporan.id);
        if (i === -1) return;
        const sekarang = new Date().toISOString();
        const catatan = $("simulasiCatatan").value.trim();
        daftar[i] = {
          ...daftar[i],
          status: statusBaru,
          diterimaPada: daftar[i].diterimaPada || sekarang,
          selesaiPada: statusBaru === "Selesai" ? sekarang : daftar[i].selesaiPada || null,
          catatan: catatan || daftar[i].catatan || null
        };
        if (!simpanJSON(KUNCI, daftar)) {
          alert("Status tidak bisa disimpan di browser ini.");
          return;
        }
        window.location.reload();
      };
      $("btnProsesLaporan").hidden = status !== "Baru";
      $("btnSelesaiLaporan").hidden = status !== "Diproses";
      $("btnProsesLaporan").addEventListener("click", () => ubahStatus("Diproses"));
      $("btnSelesaiLaporan").addEventListener("click", () => ubahStatus("Selesai"));
      simulasi.hidden = false;
    }
  }
});
