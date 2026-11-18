// Tagihan = tagihan gabungan pada pemesanan (total, terbayar, sisa). Penghuni dapat mencicil/melunasi lewat unggah bukti.
// Status tagihan hanya berubah menjadi "terbayar/lunas" setelah pemilik kos memverifikasi bukti.
document.addEventListener("DOMContentLoaded", async () => {
  const S = window.SIPENKOS;
  const profil = await S.sesiPenghuni;
  if (!profil) return;
  const tbody = document.getElementById("tagihanList");
  const modal = document.getElementById("modalUpload");
  const form = document.getElementById("formUpload");
  const detail = document.getElementById("detailTagihan");
  const inputFile = document.getElementById("buktiPembayaran");
  const preview = document.getElementById("previewBukti");
  const nominal = document.getElementById("nominalBayar");
  let aktif = null;

  const labelLunas = { belum_bayar: "Belum bayar", sebagian: "Dibayar sebagian", lunas: "Lunas" };
  const kelasLunas = { belum_bayar: "status-belum", sebagian: "status-sebagian", lunas: "status-lunas" };

  const barisPesan = (teks) => {
    const tr = S.el("tr");
    const td = S.el("td", "tagihan-loading", teks);
    td.colSpan = 6;
    tr.appendChild(td);
    tbody.replaceChildren(tr);
  };

  // Pembayaran terakhir (data terbaru ada di indeks 0) — dipakai untuk menampilkan alasan penolakan.
  const pembayaranTerakhir = (p) => (Array.isArray(p.pembayaran) && p.pembayaran.length ? p.pembayaran[0] : null);

  async function muat() {
    let daftar;
    try { daftar = await S.api("/penghuni/pemesanan"); } catch (e) { barisPesan(S.pesanGalat(e)); return; }
    const rows = daftar.filter((p) => ["disetujui", "selesai"].includes(p.status_pesanan));
    if (!rows.length) { barisPesan("Belum ada tagihan."); return; }

    tbody.replaceChildren(...rows.map((p) => {
      const t = p.tagihan;
      const menunggu = !!p.ada_pembayaran_menunggu;
      const terakhir = pembayaranTerakhir(p);
      const ditolak = !menunggu && terakhir && terakhir.status_bayar === "ditolak";
      const tr = S.el("tr");

      // Kolom data biasa
      [S.formatTanggal(p.tgl_mulai) + " – " + S.formatTanggal(p.tgl_berakhir) + " (kamar " + p.kamar.nomor_kamar + ")",
       S.formatRupiah(t.total_tagihan), S.formatRupiah(t.total_terbayar), S.formatRupiah(t.sisa_tagihan)]
        .forEach((v) => tr.appendChild(S.el("td", "", v)));

      // Kolom status: bukti yang masih diperiksa TIDAK dianggap terbayar
      const tdStatus = S.el("td");
      const badge = S.el("span", "status-tagihan " + (menunggu ? "status-menunggu" : kelasLunas[t.status_pelunasan] || "status-belum"),
        menunggu ? "Menunggu verifikasi" : labelLunas[t.status_pelunasan] || "-");
      tdStatus.appendChild(badge);
      if (ditolak) {
        tdStatus.appendChild(S.el("span", "catatan-ditolak", "Bukti terakhir ditolak" + (terakhir.alasan_penolakan ? ": " + terakhir.alasan_penolakan : "") + ". Silakan kirim ulang."));
      }
      tr.appendChild(tdStatus);

      // Kolom aksi
      const aksi = S.el("td");
      if (menunggu) {
        aksi.appendChild(S.el("span", "aksi-menunggu", "Menunggu verifikasi pemilik kos"));
      } else if (t.sisa_tagihan > 0 && p.status_pesanan === "disetujui") {
        const b = S.el("button", "btn-bayar", ditolak ? "Kirim Ulang Bukti" : "Bayar / Cicil");
        b.type = "button";
        b.addEventListener("click", () => {
          aktif = p;
          detail.textContent = "Sisa tagihan " + S.formatRupiah(t.sisa_tagihan) + ". Minimal pembayaran sebagian 30% dari total (" + S.formatRupiah(Math.ceil(t.total_tagihan * 0.3)) + ") atau lunasi sisa. Pembayaran baru dihitung setelah diverifikasi pemilik kos.";
          modal.classList.add("active");
        });
        aksi.appendChild(b);
      } else if (t.status_pelunasan === "lunas") {
        aksi.appendChild(S.el("span", "aksi-selesai", "Selesai"));
      } else aksi.textContent = "-";
      tr.appendChild(aksi);
      return tr;
    }));
  }

  const tutup = () => { modal.classList.remove("active"); form.reset(); preview.replaceChildren(); };
  document.getElementById("tutupModal").addEventListener("click", tutup);
  modal.addEventListener("click", (e) => { if (e.target === modal) tutup(); });

  // Pratinjau berkas yang dipilih
  inputFile.addEventListener("change", () => {
    preview.replaceChildren();
    const f = inputFile.files[0];
    if (!f) return;
    if (f.type.startsWith("image/")) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(f);
      img.alt = "Pratinjau bukti pembayaran";
      preview.appendChild(img);
    } else preview.appendChild(S.el("small", "", "Berkas dipilih: " + f.name));
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const file = inputFile.files[0];
    if (!file || !aktif) return;
    if (file.size > 5 * 1024 * 1024) { alert("Ukuran file maksimal 5 MB."); return; }
    const fd = new FormData();
    fd.append("bukti_transfer", file);
    if (nominal.value) fd.append("jml_bayar", nominal.value);
    try {
      await S.api("/penghuni/pemesanan/" + aktif.id_pemesanan + "/bayar", { method: "POST", form: fd });
      tutup();
      alert("Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi pemilik kos. Tagihan baru dihitung terbayar setelah diverifikasi.");
      muat();
    } catch (err) { alert(S.pesanGalat(err)); }
  });
  muat();
});
