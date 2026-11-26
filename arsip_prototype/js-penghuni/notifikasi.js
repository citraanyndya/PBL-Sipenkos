document.addEventListener("DOMContentLoaded", () => {
  const { bacaNotif, simpanNotif, kunciNotif } = window.SIPENKOS_PENGHUNI;

  const daftarEl = document.getElementById("daftarNotifikasi");
  const tombolSemua = document.getElementById("tandaiSemua");

  const IKON = {
    "jatuh-tempo": '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    kerusakan: '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94z"/>',
    tagihan: '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/><path d="M8 8h8"/><path d="M8 12h8"/><path d="M8 16h5"/>',
    pembayaran: '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>'
  };

  const formatTanggal = (nilai) => {
    const tanggal = new Date(nilai);
    if (isNaN(tanggal)) return "-";
    return tanggal.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" });
  };

  const urutkan = (daftar) =>
    [...daftar].sort((a, b) => new Date(b.waktu) - new Date(a.waktu));

  function buatSvg(tipe) {
    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("viewBox", "0 0 24 24");
    svg.setAttribute("aria-hidden", "true");
    svg.innerHTML = IKON[tipe] || IKON.tagihan;
    return svg;
  }

  function tandaiDibaca(id) {
    simpanNotif(bacaNotif().map((item) => (item.id === id ? { ...item, dibaca: true } : item)));
  }

  function buatItem(item) {
    const baris = document.createElement("a");
    baris.className = `notif-item tipe-${item.tipe}${item.dibaca ? "" : " belum-dibaca"}`;
    baris.href = item.tautan || "#";

    const ikon = document.createElement("span");
    ikon.className = "notif-ikon";
    ikon.appendChild(buatSvg(item.tipe));

    const isi = document.createElement("div");
    isi.className = "notif-isi";

    const judul = document.createElement("p");
    judul.className = "notif-judul";
    judul.textContent = item.judul;

    if (!item.dibaca) {
      const titik = document.createElement("span");
      titik.className = "notif-titik";
      titik.setAttribute("aria-label", "Belum dibaca");
      judul.appendChild(titik);
    }

    isi.appendChild(judul);

    if (item.pesan) {
      const pesan = document.createElement("p");
      pesan.className = "notif-pesan";
      pesan.textContent = item.pesan;
      isi.appendChild(pesan);
    }

    const tanggal = document.createElement("time");
    tanggal.className = "notif-tanggal";
    tanggal.dateTime = item.waktu;
    tanggal.textContent = formatTanggal(item.waktu);

    baris.append(ikon, isi, tanggal);
    baris.addEventListener("click", () => tandaiDibaca(item.id));
    return baris;
  }

  function render() {
    const daftar = urutkan(bacaNotif());

    tombolSemua.hidden = !daftar.some((item) => !item.dibaca);

    if (daftar.length === 0) {
      const kosong = document.createElement("p");
      kosong.className = "notif-kosong";
      kosong.textContent = "Belum ada notifikasi.";
      daftarEl.replaceChildren(kosong);
      return;
    }

    daftarEl.replaceChildren(...daftar.map(buatItem));
  }

  tombolSemua.addEventListener("click", () => {
    simpanNotif(bacaNotif().map((item) => ({ ...item, dibaca: true })));
  });

  render();
  window.addEventListener("penghuni-notif-berubah", render);
  window.addEventListener("storage", (e) => {
    if (e.key === kunciNotif) render();
  });
});
