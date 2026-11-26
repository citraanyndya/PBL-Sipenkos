/* Formulir pembayaran: jenis (penuh/DP/pelunasan), metode (transfer/tunai), pratinjau berkas, salin rekening. */
(function () {
  "use strict";
  var form = document.getElementById("paymentForm");
  if (!form) return;
  var rp = function (n) { return "Rp " + Number(n).toLocaleString("id-ID"); };
  var file = document.getElementById("paymentProof"), kirim = document.getElementById("submitPayment");
  var label = document.getElementById("labelBukti"), catatan = document.getElementById("catatanBukti"), prev = document.getElementById("previewBukti");

  function tandaiAktif(nama) {
    form.querySelectorAll("input[name=" + nama + "]").forEach(function (r) { r.closest(".opsi-bayar").classList.toggle("aktif", r.checked); });
  }
  function metode() { return form.querySelector("input[name=metode]:checked").value; }
  function terapkanMetode() {
    var tunai = metode() === "tunai";
    form.querySelectorAll("[data-metode]").forEach(function (el) { el.hidden = el.dataset.metode !== metode(); });
    label.textContent = tunai ? "Foto kwitansi" : "Bukti transfer";
    catatan.textContent = tunai ? "Foto kwitansi harus jelas terbaca. Format JPG/PNG, maks. 5 MB." : "Format JPG/PNG, maks. 5 MB.";
    kirim.textContent = tunai ? "Kirim Foto Kwitansi" : "Kirim Bukti Transfer";
    tandaiAktif("metode");
  }
  function terapkanJenis() {
    var j = form.querySelector("input[name=jenis]:checked");
    document.getElementById("nominalBayar").textContent = rp(j.dataset.nominal);
    tandaiAktif("jenis");
  }
  form.querySelectorAll("input[name=metode]").forEach(function (r) { r.addEventListener("change", terapkanMetode); });
  form.querySelectorAll("input[name=jenis]").forEach(function (r) { r.addEventListener("change", terapkanJenis); });

  file.addEventListener("change", function () {
    var f = file.files[0];
    prev.hidden = true; kirim.disabled = true;
    if (!f) return;
    if (!/^image\/(jpeg|png)$/.test(f.type)) { catatan.textContent = "Format harus JPG atau PNG."; file.value = ""; return; }
    if (f.size > 5 * 1024 * 1024) { catatan.textContent = "Ukuran berkas maksimal 5 MB."; file.value = ""; return; }
    prev.src = URL.createObjectURL(f); prev.hidden = false; kirim.disabled = false;
  });
  var salin = document.getElementById("copyAccount");
  if (salin) salin.addEventListener("click", function () {
    var t = document.getElementById("nomorRekening").textContent.replace(/\s/g, "");
    if (navigator.clipboard) navigator.clipboard.writeText(t).then(function () { salin.textContent = "Tersalin"; }); else window.prompt("Salin nomor rekening:", t);
  });
  terapkanMetode(); terapkanJenis();
})();
