(function () {
  const KUNCI_NOTIF = "sipenkosNotifications";
  const HALAMAN_LOGIN = "../calon-penghuni/login.php";
  const HALAMAN_SETELAH_KELUAR = "../calon-penghuni/beranda.php";

  const ambilJSON = (key) => {
    try {
      return JSON.parse(localStorage.getItem(key) || "null");
    } catch (error) {
      return null;
    }
  };

  const NOTIF_AWAL = [
    { id: "NTF-001", tipe: "jatuh-tempo", judul: "Tagihan jatuh tempo 2 hari lagi", pesan: "Tagihan Oktober 2026 Rp 950.000", waktu: "2026-10-25", dibaca: false, tautan: "tagihan.php" },
    { id: "NTF-002", tipe: "kerusakan", judul: "Laporan kerusakan sedang diproses", pesan: "Keran kamar mandi bocor", waktu: "2026-10-18", dibaca: false, tautan: "laporan-kerusakan.php" },
    { id: "NTF-003", tipe: "tagihan", judul: "Tagihan Oktober 2026 sudah terbit", pesan: "", waktu: "2026-10-01", dibaca: true, tautan: "tagihan.php" },
    { id: "NTF-004", tipe: "pembayaran", judul: "Pembayaran September 2026 dikonfirmasi", pesan: "", waktu: "2026-09-25", dibaca: true, tautan: "riwayat-pembayaran.php" }
  ];

  const bacaNotif = () => {
    const data = ambilJSON(KUNCI_NOTIF);
    if (Array.isArray(data)) return data;
    simpanNotif(NOTIF_AWAL);
    return NOTIF_AWAL.map((item) => ({ ...item }));
  };

  function simpanNotif(daftar) {
    try {
      localStorage.setItem(KUNCI_NOTIF, JSON.stringify(daftar));
    } catch (error) {
      console.error("Notifikasi tidak bisa disimpan:", error);
    }
    window.dispatchEvent(new CustomEvent("penghuni-notif-berubah"));
  }

  window.SIPENKOS_PENGHUNI = { kunciNotif: KUNCI_NOTIF, bacaNotif, simpanNotif };

  document.addEventListener("DOMContentLoaded", () => {
    // Guard login
    if (localStorage.getItem("isLoggedIn") !== "true") {
      window.location.href = HALAMAN_LOGIN;
      return;
    }

    const profil = ambilJSON("sipenkosDemoUser") || {};
    const nama = profil.nama || localStorage.getItem("userName") || "Penghuni";
    const namaDepan = nama.trim().split(/\s+/)[0] || "Penghuni";

    const headerName = document.getElementById("headerName");
    const headerAvatar = document.getElementById("headerAvatar");
    if (headerName) headerName.textContent = namaDepan;
    if (headerAvatar) headerAvatar.textContent = namaDepan.charAt(0).toUpperCase();

    const userProfile = document.getElementById("userProfile");
    if (userProfile) userProfile.style.display = "flex";

    const titik = document.getElementById("notificationDot");
    const perbaruiTitik = () => {
      if (titik) titik.hidden = !bacaNotif().some((item) => !item.dibaca);
    };
    perbaruiTitik();
    window.addEventListener("penghuni-notif-berubah", perbaruiTitik);
    window.addEventListener("storage", (e) => {
      if (e.key === KUNCI_NOTIF) perbaruiTitik();
    });

    const dialog = document.createElement("dialog");
    dialog.className = "dialog-keluar";
    dialog.setAttribute("aria-labelledby", "judulKeluar");
    dialog.innerHTML = `
      <div class="dialog-keluar-ikon" aria-hidden="true">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
      </div>
      <h2 id="judulKeluar">Keluar dari akun?</h2>
      <p>Kamu perlu masuk kembali untuk mengakses halaman penghuni.</p>
      <div class="dialog-keluar-aksi">
        <button type="button" class="btn-batal" data-batal>Batal</button>
        <button type="button" class="btn-keluar" data-keluar>Keluar</button>
      </div>`;
    document.body.appendChild(dialog);

    const bukaDialog = () => {
      if (typeof dialog.showModal === "function") dialog.showModal();
      else if (confirm("Yakin ingin keluar dari akun SIPENKOS?")) keluar();
    };

    const keluar = () => {
      localStorage.removeItem("isLoggedIn");
      window.location.href = HALAMAN_SETELAH_KELUAR;
    };

    dialog.querySelector("[data-batal]").addEventListener("click", () => dialog.close());
    dialog.querySelector("[data-keluar]").addEventListener("click", keluar);
    dialog.addEventListener("click", (e) => {
      if (e.target === dialog) dialog.close();
    });

    document.querySelectorAll("#logoutButton, [data-logout]").forEach((el) =>
      el.addEventListener("click", (e) => {
        e.preventDefault();
        bukaDialog();
      })
    );

    const tombolAkun = document.getElementById("accountToggle");
    const menuAkun = document.getElementById("accountMenu");
    if (tombolAkun && menuAkun) {
      const atur = (buka) => {
        menuAkun.hidden = !buka;
        tombolAkun.setAttribute("aria-expanded", String(buka));
      };
      tombolAkun.addEventListener("click", (e) => {
        e.stopPropagation();
        atur(menuAkun.hidden);
      });
      document.addEventListener("click", (e) => {
        if (!menuAkun.hidden && !menuAkun.contains(e.target)) atur(false);
      });
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") atur(false);
      });
    }
  });
})();
