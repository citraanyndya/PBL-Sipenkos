
document.addEventListener("DOMContentLoaded", () => {
  const ambilJSON = (key) => {
    try {
      return JSON.parse(localStorage.getItem(key) || "null");
    } catch (error) {
      return null;
    }
  };

  const profil = ambilJSON("sipenkosDemoUser") || {};
  const pembayaran = ambilJSON("paymentData") || {};
  const reservasi = pembayaran.reservasi || ambilJSON("draftSewa") || {};

  const nama = profil.nama || localStorage.getItem("userName") || "Penghuni";
  const namaAwal = nama.trim().split(/\s+/)[0] || "Penghuni";

  const formatRupiah = (angka) =>
    "Rp " + Number(angka || 0).toLocaleString("id-ID");

  const parseTanggal = (nilai) => {
    if (!nilai) return null;

    const tanggal = new Date(`${nilai}T00:00:00`);

    return Number.isNaN(tanggal.getTime()) ? null : tanggal;
  };

  const formatTanggal = (tanggal) =>
    tanggal.toLocaleDateString("id-ID", {
      day: "2-digit",
      month: "short",
      year: "numeric"
    });

  const formatBulanTahun = (tanggal) =>
    tanggal.toLocaleDateString("id-ID", {
      month: "long",
      year: "numeric"
    });

  const tambahBulan = (tanggal, jumlah) => {
    const hasil = new Date(tanggal);
    const tanggalAsli = hasil.getDate();

    hasil.setDate(1);
    hasil.setMonth(hasil.getMonth() + jumlah);

    const hariTerakhir = new Date(
      hasil.getFullYear(),
      hasil.getMonth() + 1,
      0
    ).getDate();

    hasil.setDate(Math.min(tanggalAsli, hariTerakhir));

    return hasil;
  };

  const mulai = parseTanggal(reservasi.tglMulai);
  const durasi = Math.max(1, Number(reservasi.durasi) || 1);

  const akhir = mulai
    ? new Date(tambahBulan(mulai, durasi).getTime() - 86400000)
    : null;

  const periode = mulai && akhir
    ? `${formatTanggal(mulai)} – ${formatTanggal(akhir)}`
    : "Periode sewa belum tersedia";

  const harga =
    Number(reservasi.hargaPerBulan) ||
    (Number(reservasi.totalHarga) / durasi) ||
    0;

  const judulKamar = reservasi.judulKamar || "Kamar penghuni";
  const nomorKamar = reservasi.nomorKamar || reservasi.noKamar || "-";

  document.getElementById("welcomeName").textContent = namaAwal;
  document.getElementById("headerName").textContent = namaAwal;
  document.getElementById("headerAvatar").textContent =
    namaAwal.charAt(0).toUpperCase();

  document.getElementById("roomSubtitle").textContent =
    nomorKamar !== "-"
      ? `Kamar ${nomorKamar} · ${judulKamar}`
      : judulKamar;

  document.getElementById("rentalPeriod").textContent = periode;
  document.getElementById("roomTitle").textContent = judulKamar;
  document.getElementById("roomPrice").textContent =
    `${formatRupiah(harga)} / bulan`;

  document.getElementById("roomPeriod").textContent = periode;

  const gambar = reservasi.gambar ||
    "../../assets/images/kamar-tipe4.jpeg";

  document.getElementById("roomImage").src = gambar;
  document.getElementById("roomImage").alt = `Foto ${judulKamar}`;

  document.getElementById("roomDetailButton").href =
    reservasi.idKamar
      ? `../penghuni/detail-kamar.php?tipe=${encodeURIComponent(reservasi.idKamar)}`
      : "../penghuni/daftar-kamar.php";


  const hariIni = new Date();
  hariIni.setHours(0, 0, 0, 0);

  const billMessage = document.getElementById("billMessage");
  const billAlert = document.getElementById("billAlert");

  let jatuhTempo = null;

  if (mulai && akhir) {
    const bulanTagihan = new Date(
      hariIni.getFullYear(),
      hariIni.getMonth(),
      27
    );

    const bulanPertama = new Date(
      mulai.getFullYear(),
      mulai.getMonth() + (mulai.getDate() > 27 ? 1 : 0), 27
    );

    if (hariIni < bulanPertama) {
      jatuhTempo = bulanPertama;
    } else {
      jatuhTempo = bulanTagihan;
    }

    if (hariIni > jatuhTempo) {
      billMessage.textContent =
        `Batas pembayaran tagihan ${formatBulanTahun(jatuhTempo)} ` +
        `telah lewat pada tanggal 27. Segera lakukan pembayaran.`;

      billAlert.classList.add("bill-alert--late");
    } else {
      billMessage.textContent =
        `Tagihan ${formatBulanTahun(jatuhTempo)} sebesar ` +
        `${formatRupiah(harga)} paling lambat dibayar tanggal 27.`;

      billAlert.classList.remove("bill-alert--late");
    }

    if (jatuhTempo > akhir) {
      jatuhTempo = null;
    }
  }

  if (!jatuhTempo) {
    billMessage.textContent =
      "Tidak ada tagihan aktif untuk periode sewa saat ini.";

    billAlert.classList.add("bill-alert--clear");
    billAlert.classList.remove("bill-alert--late");

    const tombolTagihan = billAlert.querySelector(".primary-button");

    if (tombolTagihan) {
      tombolTagihan.textContent = "Lihat Tagihan";
    }
  }

  document.getElementById("lastPaymentMonth").textContent =
    tanggalBayar && !Number.isNaN(tanggalBayar.getTime())
      ? formatBulanTahun(tanggalBayar)
      : "Belum ada data";

  document.getElementById("paymentStatus").textContent = "Lunas";

  const laporan = ambilJSON("sipenkosDamageReports") || [];

  document.getElementById("damageStatus").textContent =
    laporan.length
      ? (laporan[0].status || "Sedang diproses")
      : "Belum ada laporan";

  const notifikasi = ambilJSON("sipenkosNotifications") || [];

  const adaBelumDibaca = notifikasi.some(
    (item) => !item.dibaca
  );

  document.getElementById("notificationDot").hidden =
    !adaBelumDibaca;

  const toast = document.getElementById("toastMessage");
  let toastTimer;

  document.querySelectorAll("[data-coming-soon]").forEach((link) => {
    link.addEventListener("click", (event) => {
      event.preventDefault();

      toast.textContent =
        "Halaman ini sedang disiapkan dan akan segera tersedia.";

      toast.classList.add("show");

      clearTimeout(toastTimer);

      toastTimer = setTimeout(() => {
        toast.classList.remove("show");
      }, 2600);
    });
  });

  document.getElementById("logoutButton").addEventListener("click", () => {
    if (!confirm("Yakin ingin keluar dari akun SIPENKOS?")) return;

    localStorage.removeItem("isLoggedIn");

    window.location.href = "beranda.php";
  });

  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebarOverlay");

  const tutupMenu = () => {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
  };

  document.getElementById("mobileMenu").addEventListener("click", () => {
    sidebar.classList.toggle("open");
    overlay.classList.toggle("show");
  });

  overlay.addEventListener("click", tutupMenu);
});
