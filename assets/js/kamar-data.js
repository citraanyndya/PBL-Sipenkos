window.SIPENKOS = window.SIPENKOS || {};
window.SIPENKOS.dataKamar = [
  {
    id: 1,
    judul: "Tipe 1 Non-AC, Kamar Mandi Luar",
    hargaPerBulan: 500000,
    status: "Tersedia - sisa 1",
    tersedia: true,
    gambarUtama: "assets/images/kamar-tipe1.jpeg",
    deskripsi: "Kamar Tipe 1 merupakan pilihan ekonomis yang nyaman di Kos Bu Nining, dirancang untuk memaksimalkan sirkulasi udara alami dan pencahayaan ruangan. Dilengkapi fasilitas kamar mandi luar yang selalu terawat bersih, tipe ini cocok bagi penghuni yang menginginkan hunian tenang dengan harga terjangkau di kawasan Malang.",
    galeri: [
      { src: "assets/images/Lemari.jpeg", label: "Lemari" },
      { src: "assets/images/meja-belajar.jpeg", label: "Meja Belajar" }
    ],
    fasilitas: [
      { icon: "🛏️", teks: "Kasur & Bantal" },
      { icon: "🚪", teks: "Lemari Pakaian" },
      { icon: "🪑", teks: "Meja & Kursi" }
    ]
  },
  {
    id: 2,
    judul: "Tipe 2 Non-AC, Kamar Mandi Dalam",
    hargaPerBulan: 650000,
    status: "Tersedia - sisa 2",
    tersedia: true,
    gambarUtama: "assets/images/kamar-tipe2.jpeg",
    deskripsi: "Kamar Tipe 2 memberikan privasi lebih dengan fasilitas kamar mandi pribadi di dalam kamar tanpa menggunakan AC. Ruangan didesain sejuk dan tertata rapi untuk mendukung aktivitas istirahat maupun belajar sehari-hari.",
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
  {
    id: 3,
    judul: "Tipe 3 AC, Kamar Mandi Luar",
    hargaPerBulan: 800000,
    status: "Penuh",
    tersedia: false,
    gambarUtama: "assets/images/kamar-tipe3.jpeg",
    deskripsi: "Kamar Tipe 3 dilengkapi pendingin ruangan (AC) untuk membantu menciptakan suasana istirahat yang nyaman. Tipe ini menggunakan fasilitas kamar mandi luar yang bersih dan berada dekat area kamar.",
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
  {
    id: 4,
    judul: "Tipe 4 AC, Kamar Mandi Dalam",
    hargaPerBulan: 950000,
    status: "Tersedia - sisa 3",
    tersedia: true,
    gambarUtama: "assets/images/kamar-tipe4.jpeg",
    deskripsi: "Kamar Tipe 4 merupakan pilihan dengan fasilitas lebih lengkap, dilengkapi pendingin ruangan (AC) dan kamar mandi privat di dalam kamar. Ruangan tertata rapi untuk mendukung istirahat, belajar, maupun bekerja.",
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
];

window.SIPENKOS.formatRupiah = function (nilai) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0
  }).format(Number(nilai) || 0);
};
