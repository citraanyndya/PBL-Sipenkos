// =========================================================
// Fitur Perpanjangan Sewa (penghuni) — frontend
// Dipakai oleh: penghuni/perpanjangan-sewa.php (form)
//               penghuni/status-perpanjangan-sewa.php (status)
//
// Data demo di localStorage (sampai backend tersedia):
//   - "paymentData" / "draftSewa"  : sewa yang sedang berjalan (dibuat alur reservasi)
//   - "sipenkosPerpanjangan"       : pengajuan perpanjangan penghuni
//
// Untuk presentasi, status bisa dipaksa lewat URL (tidak disimpan):
//   status-perpanjangan-sewa.php?status=disetujui | ?status=ditolak
// =========================================================
document.addEventListener("DOMContentLoaded", () => {
  const KUNCI = "sipenkosPerpanjangan";
  const PERSEN_DP = 50;
  const $ = (id) => document.getElementById(id);

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
      return false;
    }
  };

  const rupiah = (angka) => "Rp " + Math.round(Number(angka) || 0).toLocaleString("id-ID");

  const keTanggal = (iso) => {
    if (!iso) return null;
    const tanggal = new Date(`${iso}T00:00:00`);
    return Number.isNaN(tanggal.getTime()) ? null : tanggal;
  };

  const keISO = (t) =>
    `${t.getFullYear()}-${String(t.getMonth() + 1).padStart(2, "0")}-${String(t.getDate()).padStart(2, "0")}`;

  const formatTanggal = (t) =>
    t ? t.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" }) : "-";

  const formatWaktu = (iso) => {
    const w = new Date(iso);
    if (Number.isNaN(w.getTime())) return "";
    const jam = w.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" }).replace(":", ".");
    return `${w.toLocaleDateString("id-ID", { day: "numeric", month: "short", year: "numeric" })}, ${jam}`;
  };

  // sama dengan dashboard.js, agar periode yang tampil konsisten
  const tambahBulan = (tanggal, jumlah) => {
    const hasil = new Date(tanggal);
    const hariAsli = hasil.getDate();
    hasil.setDate(1);
    hasil.setMonth(hasil.getMonth() + jumlah);
    const hariTerakhir = new Date(hasil.getFullYear(), hasil.getMonth() + 1, 0).getDate();
    hasil.setDate(Math.min(hariAsli, hariTerakhir));
    return hasil;
  };

  const geserHari = (tanggal, hari) => new Date(tanggal.getFullYear(), tanggal.getMonth(), tanggal.getDate() + hari);

  const nomorTipe = (judul = "") => (String(judul).match(/Tipe\s*(\d+)/i) || [])[1] || "";

  const LABEL_SKEMA = { dp: `DP (Down Payment) ${PERSEN_DP}%`, lunas: "Lunas" };
  const LABEL_METODE = { transfer: "Transfer", cash: "Cash" };

  // ---------- header & keluar (sama di kedua halaman) ----------
  const profil = ambilJSON("sipenkosDemoUser") || {};
  const namaDepan = (profil.nama || "Penghuni").trim().split(/\s+/)[0];
  if ($("headerName")) $("headerName").textContent = namaDepan;
  if ($("headerAvatar")) $("headerAvatar").textContent = namaDepan.charAt(0).toUpperCase();

  $("logoutButton")?.addEventListener("click", () => {
    if (!confirm("Yakin ingin keluar dari akun SIPENKOS?")) return;
    localStorage.removeItem("isLoggedIn");
    window.location.href = "../calon-penghuni/beranda.php";
  });

  // ---------- data sewa saat ini ----------
  const ambilSewaSaatIni = () => {
    const pembayaran = ambilJSON("paymentData") || {};
    const sewa = pembayaran.reservasi || ambilJSON("draftSewa");
    const mulai = keTanggal(sewa && sewa.tglMulai);
    if (!sewa || !mulai) return null;

    const durasi = Math.max(1, Number(sewa.durasi) || 1);
    const akhir = geserHari(tambahBulan(mulai, durasi), -1);
    const idTipe = Number(sewa.idKamar) || Number(nomorTipe(sewa.judulKamar)) || null;
    const nomorKamar = sewa.nomorKamar || sewa.noKamar || "";

    return {
      idTipe,
      judul: sewa.judulKamar || "Kamar",
      nomorKamar,
      mulai,
      akhir,
      hargaPerBulan: Number(sewa.hargaPerBulan) || 0
    };
  };

  // =======================================================
  // HALAMAN FORM
  // =======================================================
  const form = $("formPerpanjangan");
  if (form) {
    const params = new URLSearchParams(window.location.search);
    const pengajuanLama = ambilJSON(KUNCI);

    // Satu penghuni hanya punya satu pengajuan aktif: arahkan ke halaman status
    if (pengajuanLama && pengajuanLama.status !== "ditolak" && !params.has("baru")) {
      window.location.replace("status-perpanjangan-sewa.php");
      return;
    }

    const sewa = ambilSewaSaatIni();
    if (!sewa) {
      $("emptyState").hidden = false;
      return;
    }

    const dataKamar = (window.SIPENKOS && window.SIPENKOS.dataKamar) || [];

    // info sewa saat ini
    const labelKamar = sewa.nomorKamar
      ? `Kamar ${sewa.nomorKamar} · ${sewa.judul}`
      : sewa.judul;
    $("infoKamar").textContent = labelKamar.replace(/Tipe (\d+) /, "Tipe $1 Kamar ");
    $("infoPeriode").textContent =
      `Masa sewa saat ini ${formatTanggal(sewa.mulai)} – ${formatTanggal(sewa.akhir)}`;

    // pilihan tipe kamar dari kamar-data.js
    const wadahTipe = $("pilihanTipe");
    dataKamar.forEach((kamar) => {
      const kamarSaatIni = kamar.id === sewa.idTipe;
      const bisaDipilih = kamar.tersedia || kamarSaatIni; // kamar sendiri selalu boleh
      const label = document.createElement("label");
      label.className = "opsi" + (bisaDipilih ? "" : " nonaktif");
      label.title = bisaDipilih ? kamar.judul : `${kamar.judul} (penuh)`;
      label.innerHTML = `<input type="radio" name="tipe" value="${kamar.id}"${bisaDipilih ? "" : " disabled"}>
        <span>Tipe ${kamar.id}</span>${bisaDipilih ? "" : "<small>Penuh</small>"}`;
      if (kamarSaatIni) label.querySelector("input").checked = true;
      wadahTipe.append(label);
    });
    if (!form.querySelector('input[name="tipe"]:checked')) {
      form.querySelector('input[name="tipe"]:not(:disabled)')?.click();
    }

    const nilai = (nama) => form.querySelector(`input[name="${nama}"]:checked`)?.value;

    const hitung = () => {
      const tipe = dataKamar.find((k) => String(k.id) === nilai("tipe"));
      const harga = tipe ? tipe.hargaPerBulan : sewa.hargaPerBulan;
      const durasi = Number(nilai("durasi")) || 1;
      const skema = nilai("skema") || "dp";
      const awal = geserHari(sewa.akhir, 1);
      const akhir = geserHari(tambahBulan(awal, durasi), -1);
      const total = harga * durasi;
      const dibayar = skema === "dp" ? Math.round((total * PERSEN_DP) / 100) : total;
      return { tipe, harga, durasi, skema, metode: nilai("metode") || "transfer", awal, akhir, total, dibayar };
    };

    const perbaruiRingkasan = () => {
      const h = hitung();
      $("ringTipe").textContent = h.tipe ? `Tipe ${h.tipe.id}` : "-";
      $("ringMasa").textContent = `${formatTanggal(h.awal)} – ${formatTanggal(h.akhir)}`;
      $("ringSkema").textContent = h.skema === "dp" ? "DP (Down Payment)" : "Lunas";
      $("ringMetode").textContent = LABEL_METODE[h.metode];
      $("ringTotal").textContent = rupiah(h.total);
      $("ringDpBaris").hidden = h.skema !== "dp";
      $("ringDp").textContent = rupiah(h.dibayar);
    };

    form.addEventListener("change", perbaruiRingkasan);

    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const h = hitung();
      if (!h.tipe) {
        alert("Pilih tipe kamar terlebih dahulu.");
        return;
      }

      const sekarang = new Date();
      const pengajuan = {
        id: `EXT-${keISO(sekarang).slice(0, 7).replace("-", "")}-${String(sekarang.getTime()).slice(-3)}`,
        nomorKamar: sewa.nomorKamar,
        tipeId: h.tipe.id,
        tipeJudul: h.tipe.judul,
        hargaPerBulan: h.harga,
        durasi: h.durasi,
        skema: h.skema,
        metode: h.metode,
        tglMulaiBaru: keISO(h.awal),
        tglAkhirBaru: keISO(h.akhir),
        total: h.total,
        dibayar: h.dibayar,
        sisa: h.total - h.dibayar,
        status: "menunggu_verifikasi",
        diajukanPada: sekarang.toISOString()
      };

      if (!simpanJSON(KUNCI, pengajuan)) {
        alert("Pengajuan tidak bisa disimpan di browser ini. Silakan coba lagi.");
        return;
      }
      window.location.href = "status-perpanjangan-sewa.php";
    });

    form.hidden = false;
    perbaruiRingkasan();
    return;
  }

  // =======================================================
  // HALAMAN STATUS
  // =======================================================
  const konten = $("kontenStatus");
  if (!konten) return;

  const pengajuan = ambilJSON(KUNCI);
  if (!pengajuan) {
    $("emptyState").hidden = false;
    return;
  }

  const statusUrl = new URLSearchParams(window.location.search).get("status");
  const status = ["menunggu_verifikasi", "disetujui", "ditolak"].includes(statusUrl)
    ? statusUrl
    : pengajuan.status || "menunggu_verifikasi";

  const TAMPILAN = {
    menunggu_verifikasi: {
      banner: "menunggu",
      pill: "Menunggu Verifikasi",
      pesan: "Pengajuan perpanjangan sedang diperiksa pemilik kos."
    },
    disetujui: {
      banner: "disetujui",
      pill: "Pengajuan Disetujui!",
      pesan: "Pengajuan perpanjangan sewa kamu telah disetujui pemilik kos."
    },
    ditolak: {
      banner: "ditolak",
      pill: "Pengajuan Ditolak",
      pesan: "Pengajuan perpanjangan sewa kamu ditolak pemilik kos."
    }
  };
  const t = TAMPILAN[status];

  $("statusBanner").className = `status-banner status-banner--${t.banner}`;
  $("statusPill").textContent = t.pill;
  $("statusPesan").textContent = t.pesan;

  // detail pengajuan
  const kamar = pengajuan.nomorKamar
    ? `Kamar ${pengajuan.nomorKamar} · Tipe ${pengajuan.tipeId}`
    : `Tipe ${pengajuan.tipeId}`;
  $("detailKamar").textContent = kamar;
  $("detailDurasi").textContent = `${pengajuan.durasi} Bulan`;
  $("detailSkema").textContent = LABEL_SKEMA[pengajuan.skema] || "-";
  $("detailMetode").textContent = LABEL_METODE[pengajuan.metode] || "-";
  $("detailMasa").textContent =
    `${formatTanggal(keTanggal(pengajuan.tglMulaiBaru))} – ${formatTanggal(keTanggal(pengajuan.tglAkhirBaru))}`;
  $("detailTotal").textContent = rupiah(pengajuan.total);
  $("detailDibayar").textContent = rupiah(pengajuan.dibayar);
  $("detailSisa").textContent = rupiah(pengajuan.sisa);
  $("detailNomor").textContent = pengajuan.id;

  // tombol aksi
  const tombol = $("tombolAksi");
  if (status === "disetujui") {
    $("aksiDetail").hidden = false;
    tombol.textContent = pengajuan.skema === "dp" ? "Bayar DP Sekarang" : "Bayar Sekarang";
    // TODO backend: ganti "#" dengan halaman pembayaran tagihan perpanjangan
    tombol.setAttribute("href", "#");
  } else if (status === "ditolak") {
    $("aksiDetail").hidden = false;
    tombol.textContent = "Ajukan Ulang";
    tombol.setAttribute("href", "perpanjangan-sewa.php?baru=1");
  }

  // timeline proses
  const IKON = {
    selesai: '<svg class="ikon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.6 2.6L16 9.5"/></svg>',
    berjalan: '<svg class="ikon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    menunggu: '<svg class="ikon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/></svg>',
    gagal: '<svg class="ikon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></svg>'
  };

  const langkah = [
    {
      keadaan: "selesai",
      judul: `Pengajuan dikirim · ${formatWaktu(pengajuan.diajukanPada)}`,
      ket: "Formulir dan berkas berhasil diterima sistem SIPENKOS"
    }
  ];

  if (status === "menunggu_verifikasi") {
    langkah.push(
      { keadaan: "berjalan", judul: "Verifikasi pemilik kos", ket: "Bu Nining sedang meninjau ketersediaan dan ketentuan masa tinggal" },
      { keadaan: "menunggu", judul: "Masa sewa diperbarui", ket: "Masa sewa dan tagihan baru diperbarui setelah disetujui pemilik kos." }
    );
  } else if (status === "disetujui") {
    langkah.push(
      { keadaan: "selesai", judul: "Verifikasi pemilik kos", ket: "Pengajuan disetujui pemilik kos" },
      { keadaan: "selesai aktif", judul: "Masa sewa diperbarui", ket: "Masa sewa dan tagihan baru sudah diperbarui." }
    );
  } else {
    langkah.push(
      { keadaan: "gagal", judul: "Pengajuan ditolak pemilik kos", ket: "Hubungi pemilik kos untuk informasi lebih lanjut, atau ajukan ulang." },
      { keadaan: "menunggu", judul: "Masa sewa diperbarui", ket: "Masa sewa tidak berubah." }
    );
  }

  const timeline = $("timeline");
  langkah.forEach((l) => {
    const li = document.createElement("li");
    li.className = `timeline-item ${l.keadaan}`;
    const kunciIkon = l.keadaan.split(" ")[0];
    li.innerHTML = `<span class="timeline-dot" aria-hidden="true">${IKON[kunciIkon]}</span>
      <div><p class="timeline-judul"></p><p class="timeline-ket"></p></div>`;
    li.querySelector(".timeline-judul").textContent = l.judul;
    li.querySelector(".timeline-ket").textContent = l.ket;
    timeline.append(li);
  });

  konten.hidden = false;
});
