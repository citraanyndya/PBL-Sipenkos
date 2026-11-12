document.addEventListener("DOMContentLoaded", () => {
  if (localStorage.getItem("isLoggedIn") !== "true") {
    window.location.href = "login.php";
    return;
  }

  let pembayaran = null;
  try {
    pembayaran = JSON.parse(localStorage.getItem("paymentData") || "null");
  } catch (error) {
    pembayaran = null;
  }

  if (!pembayaran) {
    window.location.href = "pembayaran.php";
    return;
  }

  const reservasi = pembayaran.reservasi || {};

  const statusBanner = document.getElementById("statusBanner");
  const statusIkon = document.getElementById("statusIkon");
  const statusPill = document.getElementById("statusPill");
  const statusPesan = document.getElementById("statusPesan");
  const statusAksi = document.getElementById("statusAksi");
  const langkahVerifikasi = document.getElementById("langkahVerifikasi");
  const langkahNomor = document.getElementById("langkahNomor");
  const langkahLabel = document.getElementById("langkahLabel");
  const fotoKamar = document.getElementById("fotoKamar");
  const ringkasanList = document.getElementById("ringkasanList");
  const nominalLabel = document.getElementById("nominalLabel");
  const nominalDibayar = document.getElementById("nominalDibayar");
  const timelineList = document.getElementById("timelineList");
  const dialogBukti = document.getElementById("dialogBukti");
  const buktiGambar = document.getElementById("buktiGambar");
  const buktiPesan = document.getElementById("buktiPesan");
  const tutupBukti = document.getElementById("tutupBukti");

  function formatRupiah(angka) {
    return "Rp " + Number(angka || 0).toLocaleString("id-ID");
  }

  function formatTanggal(nilai) {
    const tanggal = new Date(nilai);
    if (isNaN(tanggal)) return "-";
    return tanggal.toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" });
  }

  function formatWaktu(nilai) {
    const waktu = new Date(nilai);
    if (isNaN(waktu)) return "-";
    const tanggal = waktu.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" });
    const jam = waktu.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });
    return `${tanggal}, ${jam}`;
  }

  const jenisPembayaran = {
    dp: "DP (Down Payment) 50%",
    full: "Bayar Penuh"
  };

  const waktuUpload = new Date(pembayaran.tanggalUpload);
  const waktuPengajuan = reservasi.tanggalPengajuan ? new Date(reservasi.tanggalPengajuan) : waktuUpload;

  const tambahMenit = (menit) => new Date(waktuUpload.getTime() + menit * 60000);
  const waktuVerifikasi = tambahMenit(25);
  const waktuKeputusan = tambahMenit(40);
  const alasanPenolakan = pembayaran.alasanPenolakan
    || "Bukti transfer tidak terbaca oleh pemilik kos. Silakan unggah ulang atau hubungi pemilik kos.";

  const judulKamar = reservasi.judulKamar || "kamar yang dipesan";

  const kondisi = {
    menunggu: {
      kelasBanner: "status-banner--menunggu",
      ikon: "◷",
      pill: "Menunggu Verifikasi",
      pesan: "Pemilik kos sedang memeriksa data dan bukti pembayaran kamu. Hasilnya dikirim lewat notifikasi.",
      aksi: { teks: "Lihat Bukti Pembayaran", kelas: "btn btn-outline", jenis: "bukti" },
      langkah: { kelas: "active", nomor: "3", label: "Menunggu Verifikasi" },
      tampilTotalHarus: true,
      labelNominal: "Dibayar",
      timeline: [
        { status: "selesai", ikon: "✓", judul: "Reservasi dikirim", keterangan: formatWaktu(waktuPengajuan) },
        { status: "selesai", ikon: "✓", judul: "Bukti pembayaran diunggah", keterangan: formatWaktu(waktuUpload) },
        { status: "aktif", ikon: "◷", judul: "Verifikasi pemilik kos", keterangan: "Sedang diproses" },
        { status: "menunggu", ikon: "●", judul: "Reservasi diterima", keterangan: "Menunggu langkah sebelumnya" }
      ]
    },
    diterima: {
      kelasBanner: "status-banner--diterima",
      ikon: "✓",
      pill: "Diterima",
      pesan: `Reservasi dan pembayaran kamu sudah dikonfirmasi. Kamu menempati ${judulKamar} mulai ${formatTanggal(reservasi.tglMulai)}.`,
      aksi: { teks: "Masuk ke Dashboard Penghuni →", kelas: "btn btn-primary", jenis: "link", href: "dashboard-penghuni.php" },
      langkah: { kelas: "completed", nomor: "✓", label: "Verifikasi Selesai" },
      tampilTotalHarus: false,
      labelNominal: "Dibayar",
      timeline: [
        { status: "selesai", ikon: "✓", judul: "Reservasi dikirim", keterangan: formatWaktu(waktuPengajuan) },
        { status: "selesai", ikon: "✓", judul: "Bukti pembayaran diunggah", keterangan: formatWaktu(waktuUpload) },
        { status: "selesai", ikon: "✓", judul: "Verifikasi pemilik kos", keterangan: formatWaktu(waktuVerifikasi) },
        {
          status: "selesai", ikon: "✓", judul: "Reservasi diterima", keterangan: formatWaktu(waktuKeputusan),
          catatan: "Kamar siap ditempati sesuai tanggal mulai sewa."
        }
      ]
    },
    ditolak: {
      kelasBanner: "status-banner--ditolak",
      ikon: "✕",
      pill: "Ditolak",
      pesan: `Alasan: ${alasanPenolakan}`,
      aksi: { teks: "⇧ Unggah Ulang Bukti", kelas: "btn btn-primary", jenis: "link", href: "pembayaran.php" },
      langkah: { kelas: "ditolak", nomor: "✕", label: "Verifikasi Ditolak" },
      tampilTotalHarus: false,
      labelNominal: "Jumlah transfer",
      timeline: [
        { status: "selesai", ikon: "✓", judul: "Reservasi dikirim", keterangan: formatWaktu(waktuPengajuan) },
        { status: "selesai", ikon: "✓", judul: "Bukti pembayaran diunggah", keterangan: formatWaktu(waktuUpload) },
        { status: "selesai", ikon: "✓", judul: "Verifikasi pemilik kos", keterangan: formatWaktu(waktuVerifikasi) },
        { status: "ditolak", ikon: "✕", judul: "Reservasi ditolak", keterangan: formatWaktu(waktuKeputusan) }
      ]
    }
  };

  const petaStatus = {
    menunggu_verifikasi: "menunggu",
    diterima: "diterima",
    ditolak: "ditolak"
  };
  const statusUrl = new URLSearchParams(window.location.search).get("status");
  const kunci = kondisi[statusUrl]
    ? statusUrl
    : (petaStatus[pembayaran.statusPembayaran] || "menunggu");
  const isi = kondisi[kunci];

  statusBanner.className = `status-banner ${isi.kelasBanner}`;
  statusIkon.textContent = isi.ikon;
  statusPill.textContent = isi.pill;
  statusPesan.textContent = isi.pesan;

  statusAksi.className = isi.aksi.kelas;
  statusAksi.textContent = isi.aksi.teks;
  if (isi.aksi.jenis === "link") {
    statusAksi.href = isi.aksi.href;
    statusAksi.removeAttribute("role");
  } else {
    statusAksi.href = "#";
    statusAksi.setAttribute("role", "button");
    statusAksi.addEventListener("click", (event) => {
      event.preventDefault();
      bukaBukti();
    });
  }

  langkahVerifikasi.className = `alur-sewa ${isi.langkah.kelas}`;
  langkahNomor.textContent = isi.langkah.nomor;
  langkahLabel.textContent = isi.langkah.label;

  if (reservasi.gambar) fotoKamar.src = reservasi.gambar;
  fotoKamar.alt = `Foto ${judulKamar}`;

  function buatBaris(label, nilai) {
    const baris = document.createElement("div");
    baris.className = "rincian-item";
    const teks = document.createElement("span");
    teks.textContent = label;
    const hasil = document.createElement("span");
    hasil.textContent = nilai;
    baris.append(teks, hasil);
    return baris;
  }

  const daftarRincian = [
    ["Kamar", reservasi.judulKamar || "-"],
    ["Durasi", `${Number(reservasi.durasi) || 0} Bulan`],
    ["Metode", jenisPembayaran[pembayaran.tipePembayaran] || "-"]
  ];
  if (isi.tampilTotalHarus) {
    daftarRincian.push(["Total Harus Dibayar", formatRupiah(pembayaran.pembayaranSekarang)]);
  }
  daftarRincian.push(["Mulai", formatTanggal(reservasi.tglMulai)]);

  ringkasanList.replaceChildren(...daftarRincian.map(([label, nilai]) => buatBaris(label, nilai)));
  nominalLabel.textContent = isi.labelNominal;
  nominalDibayar.textContent = formatRupiah(pembayaran.pembayaranSekarang);

  // ----- Render: timeline -----
  timelineList.replaceChildren(...isi.timeline.map((langkah) => {
    const item = document.createElement("li");
    item.className = `timeline-item ${langkah.status}`;

    const penanda = document.createElement("span");
    penanda.className = "timeline-marker";
    penanda.textContent = langkah.ikon;

    const konten = document.createElement("div");
    konten.className = "timeline-konten";

    const judul = document.createElement("strong");
    judul.textContent = langkah.judul;

    const keterangan = document.createElement("p");
    keterangan.textContent = langkah.keterangan;

    konten.append(judul, keterangan);

    if (langkah.catatan) {
      const catatan = document.createElement("p");
      catatan.className = "timeline-catatan";
      catatan.textContent = langkah.catatan;
      konten.appendChild(catatan);
    }

    item.append(penanda, konten);
    return item;
  }));

  function bukaBukti() {
    let pratinjau = null;
    try {
      pratinjau = localStorage.getItem("buktiPratinjau");
    } catch (error) {
      pratinjau = null;
    }

    if (pratinjau) {
      buktiGambar.src = pratinjau;
      buktiGambar.hidden = false;
      buktiPesan.textContent = `${pembayaran.namaFile || "Bukti pembayaran"}`;
    } else {
      buktiGambar.hidden = true;
      buktiGambar.removeAttribute("src");
      buktiPesan.textContent = `${pembayaran.namaFile || "File"} — pratinjau hanya tersedia untuk gambar JPG/PNG. File asli baru tersimpan setelah backend aktif.`;
    }

    dialogBukti.showModal();
  }

  tutupBukti.addEventListener("click", () => dialogBukti.close());
  dialogBukti.addEventListener("click", (event) => {
    if (event.target === dialogBukti) dialogBukti.close();
  });
});