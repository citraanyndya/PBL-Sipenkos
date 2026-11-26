/* Dialog bukti pembayaran (gambar dari server lewat berkas/tampil.php). */
(function () {
  "use strict";
  var dialog = document.getElementById("dialogBukti");
  var buka = document.getElementById("btnLihatBukti");
  var tutup = document.getElementById("tutupBukti");
  if (!dialog) return;
  if (buka) buka.addEventListener("click", function () { if (dialog.showModal) dialog.showModal(); else dialog.setAttribute("open", ""); });
  if (tutup) tutup.addEventListener("click", function () { dialog.close ? dialog.close() : dialog.removeAttribute("open"); });
  dialog.addEventListener("click", function (e) { if (e.target === dialog) dialog.close(); });
})();
