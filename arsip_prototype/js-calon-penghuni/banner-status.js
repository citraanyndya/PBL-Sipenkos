document.addEventListener("DOMContentLoaded", () => {
  const banner = document.getElementById("bannerStatus");
  if (!banner) return;

  const bannerIcon = document.getElementById("bannerStatusIcon");
  const bannerTitle = document.getElementById("bannerStatusTitle");
  const bannerDescription = document.getElementById("bannerStatusDescription");
  const bannerButton = document.getElementById("bannerStatusButton");

  const petaStatus = {
    menunggu_verifikasi: "menunggu",
    diterima: "diterima",
    ditolak: "ditolak"
  };

  const kontenPerStatus = {
    menunggu: {
      ikon: "⏳",
      judul: "Reservasimu sedang diverifikasi",
      deskripsi: "Bukti pembayaran sedang diperiksa oleh pemilik kos."
    },
    diterima: {
      ikon: "✅",
      judul: "Reservasimu diterima!",
      deskripsi: "Reservasi dan pembayaran kamu sudah dikonfirmasi oleh pemilik kos."
    },
    ditolak: {
      ikon: "✕",
      judul: "Pembayaran ditolak",
      deskripsi: "Bukti pembayaran belum dapat diterima. Silakan unggah ulang."
    }
  };

  function tampilkanBanner() {
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    if (!isLoggedIn) {
      banner.hidden = true;
      return;
    }

    let pembayaran = null;
    try {
      pembayaran = JSON.parse(localStorage.getItem("paymentData") || "null");
    } catch (error) {
      pembayaran = null;
    }

    // Belum pernah reservasi/bayar sama sekali → tidak ada yang perlu ditunjukkan.
    if (!pembayaran) {
      banner.hidden = true;
      return;
    }

    const kunci = petaStatus[pembayaran.statusPembayaran] || "menunggu";
    const konten = kontenPerStatus[kunci];

    bannerIcon.textContent = konten.ikon;
    bannerTitle.textContent = konten.judul;
    bannerDescription.textContent = konten.deskripsi;
    bannerButton.textContent = "Lihat Status Reservasi";
    bannerButton.href = "status-reservasi.php";

    banner.hidden = false;
  }

  tampilkanBanner();

  window.addEventListener("storage", (event) => {
    if (event.key === "paymentData") tampilkanBanner();
  });
});