document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formSewa");
  if (!form) return;

  if (localStorage.getItem("isLoggedIn") !== "true") {
    window.location.href = "login.php";
    return;
  }

  let pesanan = null;
  try {
    pesanan = JSON.parse(localStorage.getItem("kamarTerpilih") || "null");
  } catch (error) {
    pesanan = null;
  }

  let draftLama = null;
  try {
    draftLama = JSON.parse(localStorage.getItem("draftSewa") || "null");
  } catch (error) {
    draftLama = null;
  }

  const kamar = (window.SIPENKOS?.dataKamar || []).find((item) => String(item.id) === String(pesanan?.id));
  if (!pesanan || !kamar || !kamar.tersedia) {
    alert("Pilihan kamar tidak ditemukan atau sudah tidak tersedia. Silakan pilih kamar kembali.");
    window.location.href = "daftar-kamar.php";
    return;
  }

  const elGambar = document.getElementById("kamarGambar");
  const elTipe = document.getElementById("tipeKamar");
  const elHarga = document.getElementById("hargaKamar");
  const elFasilitas = document.getElementById("fasilitasKamar");
  const durasiInput = document.getElementById("durasiSewa");
  const rincianBiayaList = document.getElementById("rincianBiayaList");
  const tglMulai = document.getElementById("tglMulai");
  const fotoKtp = document.getElementById("fotoKtp");
  const btnKirim = document.getElementById("btnKirimPengajuan");
  const pesanPenolakan = document.getElementById("pesanPenolakan");
  const format = window.SIPENKOS?.formatRupiah || ((nilai) => `Rp ${Number(nilai || 0).toLocaleString("id-ID")}`);
  const durasi = [1, 6, 12].includes(Number(pesanan.durasi)) ? Number(pesanan.durasi) : 6;

  if (elGambar) {
    elGambar.src = kamar.gambarUtama;
    elGambar.alt = `Foto ${kamar.judul}`;
  }
  if (elTipe) elTipe.textContent = kamar.judul;
  if (elHarga) elHarga.textContent = format(kamar.hargaPerBulan);
  if (durasiInput) durasiInput.value = `${durasi} Bulan`;

  if (elFasilitas) {
    elFasilitas.replaceChildren();
    (kamar.fasilitas || []).forEach((item) => {
      const li = document.createElement("li");
      li.className = "item-fasilitas";
      li.textContent = `${item.icon} ${item.teks}`;
      elFasilitas.appendChild(li);
    });
  }

  if (draftLama?.statusVerifikasi === "ditolak") {
    document.getElementById("tglMulai").value = draftLama.tglMulai || ""
    document.getElementById("namaKontak").value = draftLama.namaKontak || ""
    document.getElementById("hubungan").value = draftLama.hubungan || ""
    document.getElementById("noKontak").value = draftLama.noKontak || ""
    if (pesanPenolakan) {
      const alasan = Array.isArray(draftLama.alasanPenolakan) ? draftLama.alasanPenolakan : [
        draftLama.alasanPenolakan || "Silakan periksa kembali data yang kamu kirim."
      ]
      pesanPenolakan.hidden = false
      const judul = document.createElement("strong");
      judul.textContent = "Pengajuan perlu diperbaiki"
      const deskripsi = document.createElement("p");
      deskripsi.textContent = "Admin menolak pengajuan kamu dengan alasan berikut:"
      const daftar = document.createElement("ul")
      alasan.filter(Boolean).forEach((item) => {
          const li = document.createElement("li");
          li.textContent = item;
          daftar.appendChild(li);
      })
      pesanPenolakan.replaceChildren(judul, deskripsi, daftar);
    }
      if (btnKirim) {
          btnKirim.textContent = "Kirim Ulang Pengajuan";
      }
    }

  const hariIni = new Date();
  const todayLocal = [hariIni.getFullYear(), String(hariIni.getMonth() + 1).padStart(2, "0"), String(hariIni.getDate()).padStart(2, "0")].join("-");
  if (tglMulai) tglMulai.min = todayLocal;
  if (fotoKtp) fotoKtp.accept = "image/jpeg,image/png,image/webp";

  const nilaiRadio = (name, fallback) => form.querySelector(`input[name="${name}"]:checked`)?.value || fallback;

  function hitungRincianBiaya() {
    const totalHarga = kamar.hargaPerBulan * durasi;

    if (rincianBiayaList) {
      rincianBiayaList.replaceChildren();
      const rows = [
        ["Harga sewa per bulan", format(kamar.hargaPerBulan), ""],
        ["Durasi sewa", `${durasi} bulan`, ""],
        ["Total harga sewa", format(totalHarga), "bold-item"]
      ];
      rows.forEach(([label, value, className]) => {
        const row = document.createElement("div");
        row.className = `rincian-item ${className}`.trim();
        const left = document.createElement("span");
        left.textContent = label;
        const right = document.createElement("span");
        right.textContent = value;
        row.append(left, right);
        rincianBiayaList.appendChild(row);
      });
    }

    return {durasi, totalHarga};
  }

  hitungRincianBiaya();

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    if (btnKirim?.disabled) return;

    if (tglMulai?.value && tglMulai.value < todayLocal) {
      tglMulai.setCustomValidity("Tanggal mulai kos tidak boleh sebelum hari ini.");
      tglMulai.reportValidity();
      tglMulai.setCustomValidity("");
      return;
    }

    const file = fotoKtp?.files?.[0];
    if (!file) {
      alert("Silakan pilih ulang foto KTP.");
      fotoKtp?.focus();
      return;
    }
    
    const tipeDiizinkan = ["image/jpeg", "image/png", "image/webp"];
    if (!tipeDiizinkan.includes(file.type)) {
      alert("Format KTP harus JPG, PNG, atau WEBP.");
      fotoKtp.value = "";
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      alert("Ukuran file KTP maksimal 5 MB.");
      fotoKtp.value = "";
      return;
    }

    const biaya = hitungRincianBiaya();
    const draft = {
      idKamar: kamar.id,
      judulKamar: kamar.judul,
      hargaPerBulan: kamar.hargaPerBulan,
      gambar: kamar.gambarUtama,
      tglMulai: tglMulai.value,
      durasi: biaya.durasi,
      namaKontak: document.getElementById("namaKontak").value.trim(),
      hubungan: document.getElementById("hubungan").value.trim(),
      noKontak: document.getElementById("noKontak").value.trim(),
      namaFileKtp: file.name,
      totalHarga: biaya.totalHarga,
      statusVerifikasi: "menunggu_verifikasi",
      alasanPenolakan: [],
    };

    try {
      localStorage.setItem("draftSewa", JSON.stringify(draft));
      localStorage.setItem("kamarTerpilih", JSON.stringify({ ...pesanan, durasi: biaya.durasi }));
    } catch (error) {
      alert("Data reservasi tidak dapat disimpan di browser. Coba gunakan browser biasa atau kosongkan ruang penyimpanan.");
      return;
    }

    if (btnKirim) {
      btnKirim.disabled = true;
      btnKirim.textContent = "Mengirim...";
    }

    window.location.href = "pembayaran.php";
  });

  const backLink = document.querySelector(".back-link");
  if (backLink) backLink.href = `detail-kamar.php?tipe=${kamar.id}`;
});
