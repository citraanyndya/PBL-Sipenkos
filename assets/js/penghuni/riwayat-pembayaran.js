/* Riwayat pembayaran: filter bulan (kirim form GET) dan dialog bukti (gambar dari server). */
(function () {
  "use strict";
  var pilih = document.getElementById("filterBulan");
  if (pilih) pilih.addEventListener("change", function () { document.getElementById("formFilter").submit(); });
  var dialog = document.getElementById("dialogBukti");
  var gambar = document.getElementById("gambarBukti");
  if (!dialog) return;
  document.querySelectorAll(".btn-lihat-bukti").forEach(function (b) {
    b.addEventListener("click", function () {
      gambar.src = b.dataset.bukti;
      if (dialog.showModal) dialog.showModal(); else dialog.setAttribute("open", "");
    });
  });
  var tutup = document.getElementById("tutupBukti");
  if (tutup) tutup.addEventListener("click", function () { dialog.close(); });
  dialog.addEventListener("click", function (e) { if (e.target === dialog) dialog.close(); });
})();
