document.addEventListener("DOMContentLoaded", () => {
  if (localStorage.getItem("isLoggedIn") !== "true") {
    window.location.href = "login.php";
    return;
  }

  const daftarNotifikasi = document.getElementById("daftarNotifikasi");
  if (!daftarNotifikasi) return;

  const notifikasi = window.SIPENKOS?.notifikasi;
  if (!notifikasi) return;

  function buatItemHalaman(item) {
    const baris = document.createElement("div");
    baris.className = `notif-item notif-item--halaman ${item.tipe}${item.dibaca ? "" : " belum-dibaca"}`;

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

    const aksi = notifikasi.aksiUntukTipe(item.tipe);
    const tautan = document.createElement("a");
    tautan.className = "btn btn-outline btn-sm";
    tautan.href = aksi.href;
    tautan.textContent = aksi.teks;
    tautan.addEventListener("click", () => notifikasi.tandaiDibaca(item.id));

    isi.append(judul, pesan, waktu, tautan);
    baris.append(ikon, isi);
    return baris;
  }

  function render() {
    const daftar = notifikasi.baca();

    if (daftar.length === 0) {
      const kosong = document.createElement("p");
      kosong.className = "pesan-memuat";
      kosong.textContent = "Belum ada notifikasi.";
      daftarNotifikasi.replaceChildren(kosong);
      return;
    }

    daftarNotifikasi.replaceChildren(...daftar.map(buatItemHalaman));
  }

  render();

  window.addEventListener("notifikasi-berubah", render);
  window.addEventListener("storage", (event) => {
    if (event.key === notifikasi.kunci) render();
  });
});