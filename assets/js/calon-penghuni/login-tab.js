/*
 * Login satu pintu: tab Penghuni | Pengelola (versi C).
 * - Tab hanya memilih FORM yang tampil. Hak akses tetap ditentukan akunnya
 *   (di backend: endpoint /auth/login vs /auth/admin/login + role pada token).
 * - Form penghuni ditangani auth.js; form pengelola ditangani di sini (DEMO).
 */
document.addEventListener("DOMContentLoaded", function () {
  var tab = {
    penghuni: { tombol: document.getElementById("tab-penghuni"), panel: document.getElementById("panel-penghuni") },
    pengelola: { tombol: document.getElementById("tab-pengelola"), panel: document.getElementById("panel-pengelola") }
  };
  var urutan = ["penghuni", "pengelola"];

  function pilih(nama, fokus) {
    urutan.forEach(function (k) {
      var aktif = k === nama;
      tab[k].tombol.classList.toggle("is-active", aktif);
      tab[k].tombol.setAttribute("aria-selected", String(aktif));
      tab[k].tombol.tabIndex = aktif ? 0 : -1;
      tab[k].panel.hidden = !aktif;
    });
    if (fokus) tab[nama].tombol.focus();
    history.replaceState(null, "", nama === "pengelola" ? "#pengelola" : window.location.pathname + window.location.search);
  }

  urutan.forEach(function (k, i) {
    tab[k].tombol.addEventListener("click", function () { pilih(k); });
    tab[k].tombol.addEventListener("keydown", function (e) {
      if (e.key !== "ArrowRight" && e.key !== "ArrowLeft") return;
      e.preventDefault();
      pilih(urutan[(i + (e.key === "ArrowRight" ? 1 : urutan.length - 1)) % urutan.length], true);
    });
  });

  // Tautan langsung: login-tab.php#pengelola
  if (window.location.hash === "#pengelola") pilih("pengelola");

  // ----- Form pengelola (DEMO) -----
  var form = document.getElementById("form-pengelola");
  var galat = document.getElementById("pengelola-galat");
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var user = document.getElementById("pengelola-username");
    var sandi = document.getElementById("pengelola-sandi");
    galat.hidden = true;

    if (!user.value.trim() || !sandi.value) {
      galat.textContent = "Username dan kata sandi wajib diisi.";
      galat.hidden = false;
      (user.value.trim() ? sandi : user).focus();
      return;
    }
    var akun = window.SIPENKOS_SESI && window.SIPENKOS_SESI.masukDemo("pengelola", user.value, sandi.value);
    if (!akun) {
      galat.textContent = "Username atau kata sandi salah.";
      galat.hidden = false;
      sandi.focus();
      return;
    }
    window.location.href = "../admin/dashboard.php";
  });
});
