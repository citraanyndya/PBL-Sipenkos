window.SIPENKOS = window.SIPENKOS || {};

(function () {
  const KUNCI_NOTIFIKASI = "sipenkosNotifikasi";

  function bacaNotifikasi() {
    try {
      const data = JSON.parse(localStorage.getItem(KUNCI_NOTIFIKASI) || "[]");
      return Array.isArray(data) ? data : [];
    } catch (error) {
      return [];
    }
  }

  function simpanNotifikasi(daftar) {
    try {
      localStorage.setItem(KUNCI_NOTIFIKASI, JSON.stringify(daftar));
    } catch (error) {
      console.error("Notifikasi tidak bisa disimpan di browser:", error);
    }
    window.dispatchEvent(new CustomEvent("notifikasi-berubah"));
  }

  function tandaiDibaca(id) {
    simpanNotifikasi(bacaNotifikasi().map((item) => (item.id === id ? { ...item, dibaca: true } : item)));
  }

  function formatWaktu(nilai) {
    const waktu = new Date(nilai);
    if (isNaN(waktu)) return "-";
    const tanggal = waktu.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" });
    const jam = waktu.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });
    return `${tanggal}, ${jam}`;
  }

  window.SIPENKOS.formatWaktu = formatWaktu;
  window.SIPENKOS.notifikasi = {
    kunci: KUNCI_NOTIFIKASI,
    baca: bacaNotifikasi,
    simpan: simpanNotifikasi,
    tandaiDibaca: tandaiDibaca
  };

  /*DEMO*/
  window.SIPENKOS.buatNotifikasiDemo = function () {
    const sekarang = new Date();
    const lama = bacaNotifikasi().filter((item) => !item.demo);

    simpanNotifikasi([
      {
        id: "NTF-" + sekarang.getTime() + "-diterima",
        tipe: "diterima",
        judul: "Reservasi diterima",
        pesan: "Reservasi dan pembayaran kamu sudah dikonfirmasi oleh pemilik kos.",
        waktu: sekarang.toISOString(),
        dibaca: false,
        tautan: "status-pembayaran.php?status=diterima",
        demo: true
      },
      {
        id: "NTF-" + sekarang.getTime() + "-ditolak",
        tipe: "ditolak",
        judul: "Pembayaran ditolak",
        pesan: "Bukti pembayaran belum dapat diterima. Silakan unggah ulang.",
        waktu: sekarang.toISOString(),
        dibaca: false,
        tautan: "status-pembayaran.php?status=ditolak",
        demo: true
      },
      ...lama
    ]);
  };
})();

document.addEventListener("DOMContentLoaded", () => {
  let isLoggedIn = false;
  let profile = null;

  try {
    isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    profile = JSON.parse(localStorage.getItem("sipenkosDemoUser") || "null");
  } catch (error) {
    isLoggedIn = false;
    profile = null;
  }

  const userName = (localStorage.getItem("userName") || profile?.nama || "Pengguna").trim();
  const guestActions = document.getElementById("guestActions");
  const userProfile = document.getElementById("userProfile");
  const headerName = document.getElementById("headerName");
  const headerAvatar = document.getElementById("headerAvatar");

  if (guestActions) guestActions.style.display = isLoggedIn ? "none" : "flex";
  if (userProfile) userProfile.style.display = isLoggedIn ? "flex" : "none";
  if (headerName && isLoggedIn) headerName.textContent = userName || "Pengguna";
  if (headerAvatar && isLoggedIn) headerAvatar.textContent = (userName || "P").charAt(0).toUpperCase();
  if (isLoggedIn && userProfile && !document.getElementById("btnKeluar")) {
    const btnKeluar = document.createElement("button");
    btnKeluar.type = "button";
    btnKeluar.id = "btnKeluar";
    btnKeluar.className = "btn btn-outline btn-sm";
    btnKeluar.textContent = "Keluar";
    btnKeluar.addEventListener("click", () => {
      ["isLoggedIn", "userName", "kamarTerpilih", "draftSewa"].forEach((kunci) => localStorage.removeItem(kunci));
      window.location.href = "beranda.php";
    });
    userProfile.appendChild(btnKeluar);
  }

  const tombolNotif = userProfile?.querySelector(".btn-icon-notif");
  if (!isLoggedIn || !tombolNotif) return;

  const titikNotif = tombolNotif.querySelector(".badge-dot");
  const notifikasi = window.SIPENKOS.notifikasi;
  const panel = document.createElement("div");
  panel.className = "notif-panel";
  panel.id = "notifPanel";
  panel.hidden = true;
  userProfile.appendChild(panel);
  tombolNotif.setAttribute("aria-haspopup", "true");
  tombolNotif.setAttribute("aria-expanded", "false");
  tombolNotif.setAttribute("aria-controls", "notifPanel");

  function buatItem(item) {
    const baris = document.createElement("div");
    baris.className = `notif-item ${item.tipe}${item.dibaca ? "" : " belum-dibaca"}`;
    const ikon = document.createElement("span");
    ikon.className = "notif-ikon";
    ikon.textContent = item.tipe === "ditolak" ? "✕" : "✓";
    const isi = document.createElement("div");
    isi.className = "notif-isi";
    const judul = document.createElement("strong");
    judul.textContent = item.judul;
    const pesan = document.createElement("p");
    pesan.textContent = item.pesan;
    const waktu = document.createElement("small");
    waktu.className = "catatan";
    waktu.textContent = window.SIPENKOS.formatWaktu(item.waktu);
    const tautan = document.createElement("a");
    tautan.className = "btn btn-outline btn-sm";
    tautan.href = item.tautan;
    tautan.textContent = "Lihat Status";
    tautan.addEventListener("click", () => notifikasi.tandaiDibaca(item.id));

    isi.append(judul, pesan, waktu, tautan);
    baris.append(ikon, isi);
    return baris;
  }

  function tampilkanPanel() {
    const daftar = notifikasi.baca();
    const belumDibaca = daftar.filter((item) => !item.dibaca).length;

    if (titikNotif) titikNotif.hidden = belumDibaca === 0;
    const header = document.createElement("div");
    header.className = "notif-header";

    const judulHeader = document.createElement("strong");
    judulHeader.textContent = "Notifikasi";
    header.appendChild(judulHeader);

    if (daftar.some((item) => item.demo)) {
      const demo = document.createElement("span");
      demo.className = "notif-demo";
      demo.textContent = "Mode demo";
      header.appendChild(demo);
    }

    const isiPanel = [header];

    if (daftar.length === 0) {
      const kosong = document.createElement("p");
      kosong.className = "pesan-memuat";
      kosong.textContent = "Belum ada notifikasi.";
      isiPanel.push(kosong);
    } else {
      daftar.forEach((item) => isiPanel.push(buatItem(item)));
    }

    panel.replaceChildren(...isiPanel);
  }

  function aturPanel(buka) {
    panel.hidden = !buka;
    tombolNotif.setAttribute("aria-expanded", buka ? "true" : "false");
  }

  tombolNotif.addEventListener("click", () => aturPanel(panel.hidden));

  document.addEventListener("click", (event) => {
    if (!panel.hidden && !userProfile.contains(event.target)) aturPanel(false);
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !panel.hidden) aturPanel(false);
  });

  window.addEventListener("notifikasi-berubah", tampilkanPanel);
  window.addEventListener("storage", (event) => {
    if (event.key === notifikasi.kunci) tampilkanPanel();
  });

  tampilkanPanel();
});