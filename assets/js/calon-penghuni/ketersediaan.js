/* Ketersediaan kamar real-time: memuat ulang jumlah sisa kamar dari server tiap 15 detik (tanpa refresh halaman). */
(function () {
  "use strict";
  var wadah = document.querySelector("[data-api]");
  if (!wadah) return;
  var api = wadah.dataset.api;
  var toast = function (teks) {
    var t = document.createElement("div");
    t.setAttribute("role", "status");
    t.className = "toast-ketersediaan";
    t.textContent = teks;
    document.body.appendChild(t);
    setTimeout(function () { t.remove(); }, 6000);
  };
  var terapkan = function (el, sisa, detail) {
    var badge = el.querySelector(".tersedia, .penuh");
    if (badge) {
      var sebelum = badge.className;
      badge.className = sisa > 0 ? "tersedia" : "penuh";
      badge.textContent = sisa > 0 ? "Tersedia - sisa " + sisa : "Penuh";
      if (detail && sebelum === "tersedia" && sisa <= 0) toast("Kamar tipe ini baru saja diambil pengguna lain.");
    }
    var tombol = el.querySelector("#btnReservasi");
    if (tombol && sisa <= 0) {
      tombol.className = "btn btn-outline btn-full btn-reservasi btn-disabled";
      tombol.removeAttribute("href");
      tombol.textContent = "Kamar Penuh";
    }
  };
  var sedang = false;
  var segarkan = function () {
    if (sedang || document.hidden) return;
    sedang = true;
    fetch(api, { cache: "no-store", headers: { Accept: "application/json" } })
      .then(function (r) { if (!r.ok) throw new Error("HTTP " + r.status); return r.json(); })
      .then(function (data) {
        var peta = {};
        data.forEach(function (d) { peta[d.kelas] = d.sisa; });
        document.querySelectorAll("[data-kelas]").forEach(function (el) {
          if (el.dataset.kelas in peta) terapkan(el, peta[el.dataset.kelas], el.id === "detailKamar");
        });
      })
      .catch(function () { /* jaringan putus: biarkan tampilan terakhir */ })
      .then(function () { sedang = false; });
  };
  setInterval(segarkan, 15000);
  document.addEventListener("visibilitychange", function () { if (!document.hidden) segarkan(); });
})();
