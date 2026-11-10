document.addEventListener("DOMContentLoaded", () => {
  if (localStorage.getItem("isLoggedIn") !== "true") {
    window.location.href = "login.html";
    return;
  }

  const draft = JSON.parse(
    localStorage.getItem("draftSewa") || "null"
  );

  const paymentSummary = document.getElementById("paymentSummary");

  if (!draft) {
    paymentSummary.textContent =
      "Data reservasi tidak ditemukan. Silakan lengkapi formulir reservasi terlebih dahulu.";

    document.getElementById("paymentForm").style.display = "none";
    return;
  }

  const paymentAmount = document.getElementById("paymentAmount");
  const remainingAmount = document.getElementById("remainingAmount");
  const remainingPayment = document.getElementById("remainingPayment");
  const bankInfo = document.getElementById("bankInfo");
  const paymentNotice = document.getElementById("paymentNotice");
  const paymentProof = document.getElementById("paymentProof");
  const uploadArea = document.getElementById("uploadArea");
  const uploadTitle = document.getElementById("uploadTitle");
  const uploadDescription = document.getElementById("uploadDescription");
  const filePreview = document.getElementById("filePreview");
  const uploadEmpty = document.getElementById("uploadEmpty");
  const fileName = document.getElementById("fileName");
  const previewGambar = document.getElementById("previewGambar");
  const fileIcon = document.getElementById("fileIcon");
  const fileSize = document.getElementById("fileSize");
  const removeFile = document.getElementById("removeFile");
  const submitPayment = document.getElementById("submitPayment");
  const paymentForm = document.getElementById("paymentForm");

  function formatRupiah(angka) {
    return "Rp " + Number(angka || 0).toLocaleString("id-ID");
  }

  function ubahKeAngka(nilai) {
    if (typeof nilai === "number") return nilai;

    return Number(
      String(nilai || "").replace(/[^\d]/g, "")
    ) || 0;
  }

  const hargaPerBulan = ubahKeAngka(draft.hargaPerBulan);
  const durasi = Number(draft.durasi) || 0;
  const totalHarga = ubahKeAngka(draft.totalHarga) || hargaPerBulan * durasi;
  let pembayaranSekarang = 0;
  let sisaPelunasan = 0;

  const daftarRincian = [
    ["Kamar", draft.judulKamar || "-"],
    ["Durasi Sewa", durasi + " bulan"],
    ["Tanggal Mulai", draft.tglMulai || "-"]
  ];

  paymentSummary.replaceChildren();
  daftarRincian.forEach(([label, nilai]) => {
    const item = document.createElement("div");
    item.className = "rincian-item";
    const teks = document.createElement("span");
    teks.textContent = label;
    const hasil = document.createElement("span");
    hasil.textContent = nilai;
    item.append(teks, hasil);
    paymentSummary.appendChild(item);
  });

  function hitungPembayaran() {
    const tipe = document.querySelector(
      'input[name="tipePembayaran"]:checked'
    ).value;

    if (tipe === "full") {
      pembayaranSekarang = totalHarga;
      sisaPelunasan = 0;
    } else {
      pembayaranSekarang = Math.round(totalHarga * 0.5);
      sisaPelunasan = totalHarga - pembayaranSekarang;
    }

    paymentAmount.textContent = formatRupiah(pembayaranSekarang);
    remainingAmount.textContent = formatRupiah(sisaPelunasan);

    remainingPayment.style.display = sisaPelunasan > 0 ? "block" : "none";
  }

  document.querySelectorAll('input[name="tipePembayaran"]').forEach(input => {
    input.addEventListener("change", hitungPembayaran);
  });

  hitungPembayaran();

  function ubahMetodePembayaran() {
    const metode = document.querySelector('input[name="metodePembayaran"]:checked').value;
    if (metode === "tunai") {
      bankInfo.style.display = "none";
      paymentNotice.innerHTML = `
        <span>ⓘ</span>
        <p>
          Silakan lakukan pembayaran langsung kepada Bu Nining.
          Minta kwitansi sebagai bukti pembayaran, kemudian
          unggah foto kwitansi tersebut di sini.
        </p>
      `;

      uploadTitle.textContent = "Unggah Foto Kwitansi";
    } else {
      bankInfo.style.display = "block";
      paymentNotice.innerHTML = `
        <span>ⓘ</span>
        <p>
          Setelah melakukan transfer, unggah bukti transfer
          untuk diperiksa oleh pemilik kos.
        </p>
      `;

      uploadTitle.textContent = "Unggah Bukti Transfer";
    }

    uploadDescription.textContent = "Format JPG, PNG, atau PDF (maks. 5 MB)";
  }

  document.querySelectorAll('input[name="metodePembayaran"]').forEach(input => {
    input.addEventListener("change", ubahMetodePembayaran);
  });

  ubahMetodePembayaran();

  document.getElementById("copyAccount")
    .addEventListener("click", async () => {
      try {
        await navigator.clipboard.writeText("1234567890");
        const tombol = document.getElementById("copyAccount");
        tombol.textContent = "Tersalin ✓";
      } catch (error) {
        alert("Gagal menyalin nomor rekening.");
      }
    });

  let fileTerpilih = null;
  let urlPratinjau = null;
  const ukuranMaksimal = 5 * 1024 * 1024;
  function validasiFile(file) {
    const formatDiizinkan = [
      "image/jpeg",
      "image/png",
      "application/pdf"
    ];

    if (!formatDiizinkan.includes(file.type)) {
      alert("Format file harus JPG, PNG, atau PDF.");
      return false;
    }

    if (file.size > ukuranMaksimal) {
      alert("Ukuran file maksimal 5 MB.");
      return false;
    }
    return true;
  }

  function tampilkanFile(file) {
    if (!validasiFile(file)) return;
    fileTerpilih = file;
    fileName.textContent = file.name;
    fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + " MB";
    filePreview.hidden = false;
    uploadEmpty.hidden = true;
    submitPayment.disabled = false;

    if (urlPratinjau) URL.revokeObjectURL(urlPratinjau);
    urlPratinjau = null;

    if (file.type.startsWith("image/")) {
      urlPratinjau = URL.createObjectURL(file);
      previewGambar.src = urlPratinjau;
      previewGambar.hidden = false;
      fileIcon.hidden = true;
    } else {
      previewGambar.hidden = true;
      previewGambar.removeAttribute("src");
      fileIcon.hidden = false;
    }
  }

  paymentProof.addEventListener("change", () => {
    const file = paymentProof.files[0];
    if (file) {
      tampilkanFile(file);
    }
  });

  removeFile.addEventListener("click", () => {
    fileTerpilih = null;
    paymentProof.value = "";
    filePreview.hidden = true;
    uploadEmpty.hidden = false;
    
    if (urlPratinjau) URL.revokeObjectURL(urlPratinjau);
    urlPratinjau = null;
    previewGambar.hidden = true;
    previewGambar.removeAttribute("src");
    fileIcon.hidden = false;
    uploadTitle.textContent =
    document.querySelector(
        'input[name="metodePembayaran"]:checked'
      ).value === "tunai"
        ? "Unggah Foto Kwitansi"
        : "Unggah Bukti Transfer";

    uploadDescription.textContent =
      "Format JPG, PNG, atau PDF (maks. 5 MB)";
    submitPayment.disabled = true;

  });

  uploadArea.addEventListener("dragover", event => {
    event.preventDefault();
    uploadArea.classList.add("dragover");
  });

  uploadArea.addEventListener("dragleave", () => {
    uploadArea.classList.remove("dragover");
  });

  uploadArea.addEventListener("drop", event => {
    event.preventDefault();
    uploadArea.classList.remove("dragover");
    const file = event.dataTransfer.files[0];

    if (file && validasiFile(file)) {
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      paymentProof.files = dataTransfer.files;
      tampilkanFile(file);
    }
  });

  paymentForm.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!fileTerpilih) {
      alert("Silakan unggah bukti pembayaran terlebih dahulu.");
      return;
    }

    const tipe = document.querySelector('input[name="tipePembayaran"]:checked').value;
    const metode = document.querySelector('input[name="metodePembayaran"]:checked').value;

    const dataPembayaran = {
      paymentId: "PAY-" + Date.now(),
      reservasi: draft,
      tipePembayaran: tipe,
      metodePembayaran: metode,
      totalHarga: totalHarga,
      pembayaranSekarang: pembayaranSekarang,
      sisaPelunasan: sisaPelunasan,
      namaFile: fileTerpilih.name,
      tipeFile: fileTerpilih.type,
      ukuranFile: fileTerpilih.size,
      statusPembayaran: "menunggu_verifikasi",
      statusReservasi: "menunggu_verifikasi",
      tanggalUpload: new Date().toISOString()
    };

    try {
      localStorage.setItem("paymentData", JSON.stringify(dataPembayaran));
      localStorage.setItem("reservasiStatus", "menunggu_verifikasi");
      localStorage.setItem(
        "statusReservasiData",
        JSON.stringify({ ...dataPembayaran, status: "Menunggu Verifikasi" })
      );
    } catch (error) {
      alert("Data pembayaran gagal disimpan di browser. Silakan coba lagi.");
      return;
    }

    submitPayment.disabled = true;
    submitPayment.textContent = "Mengirim bukti pembayaran...";
    window.location.href = "status-reservasi.html";
  });
});