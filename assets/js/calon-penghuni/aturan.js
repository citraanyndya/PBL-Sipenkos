document.addEventListener("DOMContentLoaded", () => {
  const KUNCI_STORAGE = "sipenkosAturan";

  const aturanDefault = [
    {
      id: 1,
      judul: "Jam Bertamu",
      deskripsi: "Bertamu maksimal hingga pukul 22.00 WIB di area ruang tamu bersama."
    },
    {
      id: 2,
      judul: "Keamanan & Kunci",
      deskripsi: "Pintu pagar utama wajib dikunci kembali setelah pukul 23.00 WIB."
    },
    {
      id: 3,
      judul: "Pembayaran Sewa",
      deskripsi: "Pembayaran sewa dilakukan paling lambat tanggal 27 setiap bulannya."
    },
    {
      id: 4,
      judul: "Kebersihan & Ketertiban",
      deskripsi: "Dilarang membuat kegaduhan di atas pukul 22.00 WIB dan wajib menjaga kebersihan area bersama."
    },
    {
      id: 5,
      judul: "Penggunaan Fasilitas",
      deskripsi: "Penggunaan fasilitas umum (dapur/mesin cuci) harap dirapikan kembali setelah digunakan."
    }
  ];

  const aturanList = document.getElementById("aturanList");
  const jumlahAturan = document.getElementById("jumlahAturan");
  if (!aturanList) return;

  function ambilAturan() {
    let data = null;

    try {
      data = JSON.parse(localStorage.getItem(KUNCI_STORAGE) || "null");
    } catch (error) {
      data = null;
    }

    if (!Array.isArray(data)) {
      data = aturanDefault;
      try {
        localStorage.setItem(KUNCI_STORAGE, JSON.stringify(data));
      } catch (error) {
        console.error("Aturan tidak bisa disimpan di browser:", error);
      }
    }

    return data;
  }

  function tampilkanAturan() {
    const dataAturan = ambilAturan();

    if (jumlahAturan) jumlahAturan.textContent = `${dataAturan.length} aturan`;
    aturanList.replaceChildren();

    if (dataAturan.length === 0) {
      const kosong = document.createElement("p");
      kosong.className = "pesan-memuat";
      kosong.textContent = "Belum ada aturan kos yang tersedia.";
      aturanList.appendChild(kosong);
      return;
    }

    dataAturan.forEach((aturan, index) => {
      const item = document.createElement("div");
      item.className = "aturan-item";

      const nomor = document.createElement("span");
      nomor.className = "nomor";
      nomor.textContent = index + 1;

      const isi = document.createElement("div");
      isi.className = "aturan-content";

      const judul = document.createElement("h3");
      judul.textContent = aturan.judul;

      const deskripsi = document.createElement("p");
      deskripsi.textContent = aturan.deskripsi;

      isi.append(judul, deskripsi);
      item.append(nomor, isi);
      aturanList.appendChild(item);
    });
  }

  window.addEventListener("storage", (event) => {
    if (event.key === KUNCI_STORAGE) tampilkanAturan();
  });

  tampilkanAturan();
});