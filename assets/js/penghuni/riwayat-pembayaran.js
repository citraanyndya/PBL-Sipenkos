// Riwayat pembayaran penghuni: daftar transaksi, filter bulan, dan lihat bukti transfer.
document.addEventListener("DOMContentLoaded", async () => {
  const S = window.SIPENKOS;
  const profil = await S.sesiPenghuni;
  if (!profil) return;

  const tbody = document.getElementById("daftarRiwayat");
  const dlg = document.getElementById("dialogBukti");
  const isi = document.getElementById("isiBukti");
  const filter = document.getElementById("filterBulan");
  const reset = document.getElementById("resetFilter");
  const info = document.getElementById("infoFilter");
  let urlAktif = null; // URL blob yang sedang ditampilkan (dilepas saat dialog ditutup)

  const NAMA_BULAN = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  // ---------- util ----------
  const tanggalDari = (v) => { const d = new Date(String(v || "").replace(" ", "T")); return isNaN(d) ? null : d; };
  const kunciBulan = (v) => { const d = tanggalDari(v); return d ? d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0") : ""; };
  const labelBulan = (k) => { const [y, m] = k.split("-"); return NAMA_BULAN[Number(m) - 1] + " " + y; };

  const barisPesan = (teks, kelas) => {
    const tr = S.el("tr");
    const td = S.el("td", kelas || "riwayat-kosong", teks);
    td.colSpan = 7;
    tr.appendChild(td);
    tbody.replaceChildren(tr);
  };

  // Status ditulis jelas: bukti yang belum dicek tidak boleh terbaca seperti sudah dibayar.
  function infoStatus(b) {
    const s = String(b.status_bayar || "").toLowerCase();
    if (s.includes("tolak")) return { teks: "Ditolak", kelas: "status-ditolak" };
    if (s.includes("menunggu") || s.includes("pending") || s.includes("kirim")) return { teks: "Menunggu verifikasi", kelas: "status-menunggu" };
    if (s) return { teks: "Terverifikasi", kelas: "status-diterima" };
    return { teks: "-", kelas: "status-menunggu" };
  }

  // Alamat bukti dari server bisa relatif; pastikan diawali "/" agar tidak salah path.
  const alamatBukti = (b) => {
    const u = b.bukti_transfer_url || b.bukti_url || "";
    if (!u) return "";
    return /^(https?:)?\/\//.test(u) || u.startsWith("/") ? u : "/" + u;
  };

  function tutupDialog() {
    if (urlAktif) { URL.revokeObjectURL(urlAktif); urlAktif = null; }
    dlg.close();
  }
  document.getElementById("tutupBukti").addEventListener("click", tutupDialog);
  dlg.addEventListener("click", (e) => { if (e.target === dlg) tutupDialog(); });
  dlg.addEventListener("close", () => { if (urlAktif) { URL.revokeObjectURL(urlAktif); urlAktif = null; } });

  async function bukaBukti(b) {
    isi.replaceChildren(S.el("p", "bukti-tidak-tersedia", "Memuat bukti..."));
    if (!dlg.open) dlg.showModal();
    const url = alamatBukti(b);
    if (!url) { isi.replaceChildren(S.el("p", "bukti-tidak-tersedia", "Bukti pembayaran tidak tersedia untuk transaksi ini.")); return; }
    try {
      const f = await S.blobUrl(url);
      urlAktif = f.url;
      if (f.tipe.startsWith("image/")) {
        const img = document.createElement("img");
        img.src = f.url;
        img.alt = "Bukti pembayaran " + S.formatRupiah(b.jml_bayar);
        isi.replaceChildren(img);
      } else {
        const a = S.el("a", "btn-lihat-bukti", "Buka berkas bukti (PDF)");
        a.href = f.url; a.target = "_blank"; a.rel = "noopener";
        isi.replaceChildren(a);
      }
    } catch (e) {
      isi.replaceChildren(S.el("p", "bukti-tidak-tersedia", e.message || "Bukti pembayaran tidak dapat dibuka."));
    }
  }

  // ---------- data ----------
  let daftar = [];
  try { daftar = await S.api("/penghuni/pembayaran"); } catch (e) { barisPesan(S.pesanGalat(e), "loading-riwayat"); return; }
  if (!daftar.length) { barisPesan("Belum ada pembayaran."); return; }

  // Urut terbaru di atas
  daftar.sort((a, b) => (tanggalDari(b.tgl_bayar) || 0) - (tanggalDari(a.tgl_bayar) || 0));

  // Opsi filter hanya berisi bulan yang memang ada transaksinya
  const bulanAda = [...new Set(daftar.map((b) => kunciBulan(b.tgl_bayar)).filter(Boolean))].sort().reverse();
  bulanAda.forEach((k) => { const o = S.el("option", "", labelBulan(k)); o.value = k; filter.appendChild(o); });
  filter.disabled = false;

  function barisRiwayat(b) {
    const tr = S.el("tr");
    const st = infoStatus(b);
    const p = b.pemesanan || {};
    const jenis = b.jml_bayar >= p.total_tagihan ? "Pelunasan" : "DP / cicilan";

    [S.formatWaktu(b.tgl_bayar), "Kamar " + (b.kamar ? b.kamar.nomor_kamar : "-"), jenis, S.formatRupiah(b.jml_bayar), "Transfer"]
      .forEach((v, i) => tr.appendChild(S.el("td", i === 3 ? "jumlah-pembayaran" : "", v)));

    // Status + keterangan
    const tdStatus = S.el("td");
    tdStatus.appendChild(S.el("span", "status-badge " + st.kelas, st.teks));
    if (st.kelas === "status-ditolak" && b.alasan_penolakan) {
      tdStatus.appendChild(S.el("span", "alasan-tolak", "Alasan: " + b.alasan_penolakan));
    } else if (st.kelas === "status-diterima") {
      tdStatus.appendChild(S.el("span", "keterangan-bayar", p.status_pelunasan === "lunas" ? "Tagihan lunas" : "Sisa " + S.formatRupiah(p.sisa_tagihan)));
    } else if (st.kelas === "status-menunggu") {
      tdStatus.appendChild(S.el("span", "keterangan-bayar", "Menunggu dicek pemilik kos"));
    }
    tr.appendChild(tdStatus);

    // Bukti
    const td = S.el("td");
    if (alamatBukti(b)) {
      const tombol = S.el("button", "btn-lihat-bukti", "Lihat");
      tombol.type = "button";
      tombol.addEventListener("click", () => bukaBukti(b));
      td.appendChild(tombol);
    } else td.textContent = "-";
    tr.appendChild(td);
    return tr;
  }

  function tampilkan() {
    const k = filter.value;
    const hasil = k ? daftar.filter((b) => kunciBulan(b.tgl_bayar) === k) : daftar;
    reset.hidden = !k;
    info.textContent = k ? hasil.length + " transaksi pada " + labelBulan(k) : daftar.length + " transaksi";
    if (!hasil.length) { barisPesan("Tidak ada pembayaran pada bulan ini."); return; }
    tbody.replaceChildren(...hasil.map(barisRiwayat));
  }

  filter.addEventListener("change", tampilkan);
  reset.addEventListener("click", () => { filter.value = ""; tampilkan(); });
  tampilkan();
});
