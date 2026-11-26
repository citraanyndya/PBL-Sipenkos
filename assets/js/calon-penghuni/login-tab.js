/* Tab Penghuni / Pengelola pada halaman login (hanya mengatur tampilan; autentikasi dilakukan di server). */
document.addEventListener("DOMContentLoaded", function () {
  "use strict";
  var tab = {
    penghuni: { tombol: document.getElementById("tab-penghuni"), panel: document.getElementById("panel-penghuni") },
    pengelola: { tombol: document.getElementById("tab-pengelola"), panel: document.getElementById("panel-pengelola") }
  };
  var urutan = ["penghuni", "pengelola"];
  function pilih(nama, fokus) {
    urutan.forEach(function (k) {
      var aktif = k === nama;
      tab[k].tombol.classList.toggle("is-active", aktif);
      tab[k].tombol.setAttribute("aria-selected", aktif ? "true" : "false");
      tab[k].tombol.tabIndex = aktif ? 0 : -1;
      tab[k].panel.hidden = !aktif;
    });
    if (fokus) tab[nama].tombol.focus();
  }
  urutan.forEach(function (k, i) {
    tab[k].tombol.addEventListener("click", function () { pilih(k); });
    tab[k].tombol.addEventListener("keydown", function (e) {
      if (e.key === "ArrowRight" || e.key === "ArrowLeft") pilih(urutan[(i + 1) % urutan.length], true);
    });
  });
  if (window.location.hash === "#pengelola") pilih("pengelola");
});
