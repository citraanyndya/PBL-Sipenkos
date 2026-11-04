const dataKamar = {
  "1": {
    judul: "Tipe 1 Non-AC, Kamar Mandi Luar",
    harga: "Rp 500.000",
    status: "Tersedia - sisa 1",
    badge: "tersedia",
    gambarUtama: "assets/images/kamar-tipe1.jpeg",
    deskripsi: "Kamar Tipe 1 merupakan pilihan ekonomis yang nyaman di Kos Bu Nining, dirancang untuk memaksimalkan sirkulasi udara alami dan pencahayaan ruangan. Dilengkapi fasilitas kamar mandi luar yang selalu terawat bersih, tipe ini sangat cocok bagi mahasiswi yang menginginkan hunian tenang dengan budget ramah kantong di kawasan Malang.",
    galeri: [
      { src: "assets/images/Lemari.jpeg", label: "Lemari" },
      { src: "assets/images/meja-belajar.jpeg", label: "Meja Belajar" }
    ],
    fasilitas: [
      {icon: "🛏️", teks: "Kasur & Bantal" }, 
      {icon: "🚪", teks: "Lemari Pakaian" }, 
      {icon: "🪑", teks: "Meja & Kursi" }   
    ]
  },
  "2": {
    judul: "Tipe 2 Non-AC, Kamar Mandi Dalam",
    harga: "Rp 650.000",
    status: "Tersedia - sisa 2",
    badge: "tersedia",
    gambarUtama: "assets/images/kamar-tipe2.jpeg",
    deskripsi: "Kamar Tipe 2 memberikan privasi lebih dengan fasilitas kamar mandi pribadi di dalam kamar tanpa menggunakan AC. Ruangan didesain sejuk dan tertata rapi, menawarkan kenyamanan optimal untuk mendukung aktivitas istirahat maupun fokus belajar mahasiswi sehari-hari.",
    galeri: [
      { src: "assets/images/kamar-mandi.jpeg", label: "Kamar Mandi" },
      { src: "assets/images/Lemari.jpeg", label: "Lemari" },
      { src: "assets/images/meja-belajar.jpeg", label: "Meja Belajar" }
    ],
    fasilitas: [
      { icon: "🚿", teks: "Kamar Mandi Dalam" }, 
      { icon: "🛏️", teks: "Kasur & Bantal" },
      { icon: "🚪", teks: "Lemari Pakaian" }, 
      { icon: "🪑", teks: "Meja & Kursi" }
    ]
  },
  "3": {
    judul: "Tipe 3 AC, Kamar Mandi Luar",
    harga: "Rp 800.000",
    status: "Penuh",
    badge: "penuh",
    gambarUtama: "assets/images/kamar-tipe3.jpeg",
    deskripsi: "Kamar Tipe 3 menyajikan kenyamanan udara sejuk dengan pendingin ruangan (AC) untuk melepas penat setelah seharian beraktivitas di kampus. Akses fasilitas kamar mandi luar yang bersih dan dekat dari area kamar menjadikannya pilihan ideal yang praktis dan efisien.",
    galeri: [
      { src: "assets/images/Lemari.jpeg", label: "Lemari" },
      { src: "assets/images/meja-belajar.jpeg", label: "Meja Belajar" }
    ],
    fasilitas: [
      { icon: "❄️", teks: "AC" },
      { icon: "🛏️", teks: "Kasur & Bantal" },
      { icon: "🚪", teks: "Lemari Pakaian" },
      { icon: "🪑", teks: "Meja & Kursi" }
    ]
  },
  "4": {
    judul: "Tipe 4 AC, Kamar Mandi Dalam",
    harga: "Rp 950.000",
    status: "Tersedia - sisa 3",
    badge: "tersedia",
    gambarUtama: "assets/images/kamar-tipe4.jpeg",
    deskripsi: "Kamar Tipe 4 merupakan pilihan paling lengkap dan nyaman di Kos Bu Nining, dilengkapi pendingin ruangan (AC) dan fasilitas kamar mandi privat di dalam kamar. Suasana kamar bersih, tenang, dan tertata rapi sehingga sangat ideal untuk mendukung istirahat maupun fokus belajar dan bekerja mahasiswa serta pekerja dikawasan Malang.",
    galeri: [
      { src: "assets/images/kamar-mandi.jpeg", label: "Kamar Mandi" },
      { src: "assets/images/Lemari.jpeg", label: "Lemari" },
      { src: "assets/images/meja-belajar.jpeg", label: "Meja Belajar" }
    ],
    fasilitas: [
      { icon: "❄️", teks: "AC" },
      { icon: "🚿", teks: "Kamar Mandi Dalam" },
      { icon: "🛏️", teks: "Kasur & Bantal" },
      { icon: "🚪", teks: "Lemari Pakaian" },
      { icon: "🪑", teks: "Meja & Kursi" }
    ]
  }
};

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const tipeId = urlParams.get("tipe");

  const kamar = dataKamar[tipeId] || dataKamar["4"];

  document.title = `Detail ${kamar.judul} - SIPENKOS`;

  const elJudul = document.querySelector(".detail-header .tipe-kamar");
  if (elJudul) elJudul.textContent = kamar.judul;

  const elHarga = document.querySelector(".nominal");
  if (elHarga) elHarga.textContent = kamar.harga;

  const elDeskripsi = document.querySelector(".deskripsi-box .deskripsi-kamar");
  if (elDeskripsi) elDeskripsi.textContent = kamar.deskripsi;

  const elFotoUtama = document.querySelector(".foto-utama");
  if (elFotoUtama) elFotoUtama.src = kamar.gambarUtama;

  const elStatus = document.querySelector(".status-group span:last-child");
  if (elStatus) {
    elStatus.textContent = kamar.status;
    elStatus.className = kamar.badge;
  }

  const elThumbList = document.querySelector(".thumb-list");
  if (elThumbList && kamar.galeri) {
    elThumbList.innerHTML = kamar.galeri.map((item, index) => `
      <div class="thumb-item ${index === 0 ? 'active' : ''}">
        <img src="${item.src}" alt="${item.label}">
        <span>${item.label}</span>
      </div>
    `).join("");
  }

  const elFasilitasKamar = document.querySelector(".fasilitas");
  if (elFasilitasKamar && kamar.fasilitas) {
    elFasilitasKamar.innerHTML = kamar.fasilitas.map(item => `
      <li class="list-fasilitas">
        <span class="icon">${item.icon}</span>
        <span>${item.teks}</span>
      </li>
    `).join("");
  }

  const btnReservasi = document.querySelector(".btn-reservasi");
  if (btnReservasi && kamar.badge === "penuh") {
    btnReservasi.textContent = "Kamar Penuh (Tidak Dapat Direservasi)";
    btnReservasi.classList.add("btn-disabled");
    btnReservasi.classList.remove("btn-primary");
    btnReservasi.removeAttribute("href");
  }
});