document.addEventListener("DOMContentLoaded", () => {
  const kamarList = window.SIPENKOS?.dataKamar || [];
  const params = new URLSearchParams(window.location.search);
  const rawId = params.get("id") || params.get("tipe") || "1";
  const kamar = kamarList.find((item) => String(item.id) === String(rawId));
  const formatRupiah = window.SIPENKOS?.formatRupiah || ((nilai) => `Rp ${Number(nilai || 0).toLocaleString("id-ID")}`);

  if (!kamar) {
    const judul = document.querySelector(".detail-header .tipe-kamar");
    if (judul) judul.textContent = "Kamar tidak ditemukan";
    const deskripsi = document.querySelector(".deskripsi-kamar");
    if (deskripsi) deskripsi.textContent = "Tipe kamar yang kamu cari tidak tersedia. Silakan kembali ke daftar kamar.";
    const tombol = document.querySelector(".btn-reservasi");
    if (tombol) {
      tombol.textContent = "Kembali ke Daftar Kamar";
      tombol.href = "daftar-kamar.html";
      tombol.classList.remove("btn-primary");
      tombol.classList.add("btn-outline");
    }
    return;
  }

  document.title = `Detail ${kamar.judul} - SIPENKOS`;

  const setText = (selector, value) => {
    const element = document.querySelector(selector);
    if (element) element.textContent = value;
  };

  setText(".detail-header .tipe-kamar", kamar.judul);
  setText(".nominal", formatRupiah(kamar.hargaPerBulan));
  setText(".deskripsi-box .deskripsi-kamar", kamar.deskripsi);

  const fotoUtama = document.querySelector(".foto-utama");
  const galeriTambahan = document.querySelector(".galeri-tambahan");
  const galeriLengkap = Array.isArray(kamar.galeri) ? kamar.galeri : [];

  if (fotoUtama) {
    fotoUtama.src = kamar.gambarUtama;
    fotoUtama.alt = `Foto utama ${kamar.judul}`;
  }

  if (galeriTambahan) {
    galeriTambahan.replaceChildren();

    galeriLengkap.forEach((item) => {
      const figure = document.createElement("figure");
      figure.className = "foto-pendukung";

      const image = document.createElement("img");
      image.src = item.src;
      image.alt = `${item.label} pada ${kamar.judul}`;
      image.loading = "lazy";

      const caption = document.createElement("figcaption");
      caption.textContent = item.label;

      figure.append(image, caption);
      galeriTambahan.appendChild(figure);
    });
  }

  const status = document.querySelector(".status-group span:last-child");
  if (status) {
    status.textContent = kamar.status;
    status.className = kamar.tersedia ? "tersedia" : "penuh";
  }

  const daftarFasilitas = document.querySelector(".deskripsi-box .fasilitas-wrapper:first-of-type .fasilitas");
  if (daftarFasilitas) {
    daftarFasilitas.replaceChildren();
    (kamar.fasilitas || []).forEach((item) => {
      const li = document.createElement("li");
      li.className = "item-fasilitas";
      const icon = document.createElement("span");
      icon.className = "icon";
      icon.textContent = item.icon;
      const label = document.createElement("span");
      label.textContent = item.teks;
      li.append(icon, label);
      daftarFasilitas.appendChild(li);
    });
  }

  const tombolReservasi = document.querySelector(".btn-reservasi");
  const tombolDurasi = [...document.querySelectorAll(".btn-durasi")];
  const durasiCaption = document.getElementById("durasiTerpilih");
  const totalHargaDurasi = document.getElementById("totalHargaDurasi");

  let pesananTersimpan = null;
  try {
    pesananTersimpan = JSON.parse(localStorage.getItem("kamarTerpilih") || "null");
  } catch (error) {
    pesananTersimpan = null;
  }

  let durasiTerpilih = String(pesananTersimpan?.id) === String(kamar.id) && [1, 6, 12].includes(Number(pesananTersimpan?.durasi))
    ? Number(pesananTersimpan.durasi)
    : null;

  if (!kamar.tersedia) durasiTerpilih = null;

  const boxTotal = document.querySelector(".total-durasi-box");

  const aturTombolReservasi = () => {
    if (!tombolReservasi) return;

    let teks = "Reservasi Sekarang";
    let bisaKlik = true;

    if (!kamar.tersedia) {
      teks = "Kamar Penuh (Tidak Dapat Direservasi)";
      bisaKlik = false;
    } else if (!durasiTerpilih) {
      teks = "Pilih Durasi Sewa Dulu";
      bisaKlik = false;
    }

    tombolReservasi.textContent = teks;
    tombolReservasi.classList.toggle("btn-primary", bisaKlik);
    tombolReservasi.classList.toggle("btn-disabled", !bisaKlik);
    tombolReservasi.setAttribute("aria-disabled", bisaKlik ? "false" : "true");
  };

  const perbaruiDurasi = (durasi) => {
    durasiTerpilih = durasi;
    if (boxTotal) boxTotal.hidden = !durasi;

    if (durasi) {
      if (durasiCaption) durasiCaption.textContent = String(durasi);
      if (totalHargaDurasi) totalHargaDurasi.textContent = formatRupiah(kamar.hargaPerBulan * durasi);
    }

    tombolDurasi.forEach((button) => {
      const aktif = Number(button.dataset.durasi) === durasi;
      button.classList.toggle("active", aktif);
      button.setAttribute("aria-pressed", aktif ? "true" : "false");
    });

    aturTombolReservasi();
  };

  if (!kamar.tersedia) {
    tombolDurasi.forEach((button) => { button.disabled = true; });
    if (tombolReservasi) tombolReservasi.removeAttribute("href");
  } else {
    tombolDurasi.forEach((button) => {
      button.addEventListener("click", () => {
        const durasi = Number(button.dataset.durasi);
        if (![1, 6, 12].includes(durasi)) return;
        perbaruiDurasi(durasi);
      });
    });
  }

  perbaruiDurasi(durasiTerpilih);

  if (tombolReservasi) {
    tombolReservasi.addEventListener("click", (event) => {
      event.preventDefault();
      if (!kamar.tersedia || !durasiTerpilih) return;

      const dataPesanan = {
        id: kamar.id,
        judul: kamar.judul,
        hargaPerBulan: kamar.hargaPerBulan,
        gambar: kamar.gambarUtama,
        fasilitas: kamar.fasilitas,
        durasi: durasiTerpilih
      };

      try {
        localStorage.setItem("kamarTerpilih", JSON.stringify(dataPesanan));
      } catch (error) {
        alert("Browser tidak mengizinkan penyimpanan data reservasi. Coba gunakan mode browser biasa.");
        return;
      }

      const sudahLogin = localStorage.getItem("isLoggedIn") === "true";
      window.location.href = sudahLogin ? "form-sewa.html" : "registrasi.html";
    });
  }
});
