/* SIPENKOS - JS pendukung (tanpa data tiruan/localStorage: semua data dari server). */
(function () {
  "use strict";
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var rupiah = function (n) { return "Rp " + Number(n || 0).toLocaleString("id-ID"); };

  /* 1. Sidebar penghuni di layar kecil */
  var sidebar = $("#sidebar"), overlay = $("#sidebarOverlay"), navbar = $(".main-area .navbar-container");
  if (sidebar && navbar) {
    var tombol = document.createElement("button");
    tombol.type = "button"; tombol.className = "menu-toggle"; tombol.setAttribute("aria-label", "Buka menu"); tombol.textContent = "☰";
    navbar.insertBefore(tombol, navbar.firstChild);
    var atur = function (buka) {
      sidebar.classList.toggle("open", buka);
      if (overlay) overlay.classList.toggle("show", buka);
    };
    tombol.addEventListener("click", function () { atur(!sidebar.classList.contains("open")); });
    if (overlay) overlay.addEventListener("click", function () { atur(false); });
    document.addEventListener("keydown", function (e) { if (e.key === "Escape") atur(false); });
  }

  /* 2. Konfirmasi aksi berisiko */
  $$("[data-konfirmasi]").forEach(function (el) {
    el.addEventListener("click", function (e) { if (!window.confirm(el.dataset.konfirmasi)) e.preventDefault(); });
  });

  /* 3. Batas 5 MB + nama berkas pada input file */
  $$("input[type=file]").forEach(function (f) {
    f.addEventListener("change", function () {
      var galat = f.parentNode.querySelector(".form-error-text");
      if (galat) galat.remove();
      if (f.files[0] && f.files[0].size > 5 * 1024 * 1024) {
        var s = document.createElement("span");
        s.className = "form-error-text"; s.textContent = "Ukuran berkas maksimal 5 MB.";
        f.parentNode.appendChild(s); f.value = "";
      }
    });
  });

  /* 4. Pilihan durasi sewa (detail kamar & perpanjangan): ubah total + tautan/ field tersembunyi */
  var grup = $(".durasi-group");
  if (grup) {
    var harga = parseInt(grup.dataset.harga || "0", 10);
    var kotak = $(".total-durasi-box"), outDurasi = $("#durasiTerpilih"), outTotal = $("#totalHargaDurasi");
    var aksi = $(".btn-reservasi"), input = $("#inputDurasi");
    var pilih = function (n) {
      $$(".btn-durasi", grup).forEach(function (b) {
        var on = parseInt(b.dataset.durasi, 10) === n;
        b.classList.toggle("active", on); b.setAttribute("aria-pressed", on ? "true" : "false");
      });
      if (kotak) { kotak.hidden = false; }
      if (outDurasi) outDurasi.textContent = n;
      if (outTotal) outTotal.textContent = rupiah(harga * n);
      if (input) input.value = n;
      if (aksi && aksi.tagName === "A" && aksi.dataset.dasar) aksi.href = aksi.dataset.dasar + "&durasi=" + n;
    };
    $$(".btn-durasi", grup).forEach(function (b) { b.addEventListener("click", function () { pilih(parseInt(b.dataset.durasi, 10)); }); });
    pilih(parseInt(grup.dataset.awal || "6", 10));
  }


  /* 6. Dialog konfirmasi keluar (satu tombol Keluar: sidebar kiri bawah di area penghuni, navbar di halaman calon penghuni) */
  var tombolKeluar = $$("#logoutButton, [data-logout]");
  if (tombolKeluar.length) {
    var dialog = document.createElement("dialog");
    dialog.className = "dialog-keluar";
    dialog.setAttribute("aria-labelledby", "judulKeluar");
    dialog.innerHTML =
      '<div class="dialog-keluar-ikon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg></div>' +
      '<h2 id="judulKeluar">Keluar dari akun?</h2><p>Kamu perlu masuk kembali untuk mengakses akunmu.</p>' +
      '<div class="dialog-keluar-aksi"><button type="button" class="btn-batal" data-batal>Batal</button>' +
      '<a class="btn-keluar" data-keluar href="#">Keluar</a></div>';
    document.body.appendChild(dialog);
    var tujuan = "../auth/logout.php";
    dialog.querySelector("[data-batal]").addEventListener("click", function () { dialog.close(); });
    dialog.addEventListener("click", function (e) { if (e.target === dialog) dialog.close(); });
    tombolKeluar.forEach(function (el) {
      el.addEventListener("click", function (e) {
        tujuan = el.getAttribute("href") || tujuan;
        if (typeof dialog.showModal !== "function") return;   // tanpa dukungan <dialog>: ikuti tautan langsung
        e.preventDefault();
        dialog.querySelector("[data-keluar]").setAttribute("href", tujuan);
        dialog.showModal();
      });
    });
  }

  /* 5. Total biaya di form reservasi tidak perlu JS (durasi tetap dari halaman sebelumnya). */
})();
