/* Ringkasan perpanjangan: kamar tujuan, masa sewa baru, dan total (harga dari tipe terpilih; server menghitung ulang). */
(function () {
  "use strict";
  var form = document.getElementById("formPerpanjangan");
  if (!form) return;
  var mulai = new Date(form.dataset.mulai + "T00:00:00");
  var bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
  var fmt = function (d) { return ("0" + d.getDate()).slice(-2) + " " + bulan[d.getMonth()] + " " + d.getFullYear(); };
  var hitung = function () {
    var n = parseInt(form.querySelector("input[name=durasi_bulan]:checked").value, 10);
    var tipe = form.querySelector("input[name=kelas]:checked");
    var akhir = new Date(mulai.getFullYear(), mulai.getMonth() + n, mulai.getDate());
    document.getElementById("ringKamar").textContent = tipe.dataset.nama;
    document.getElementById("ringMasa").textContent = fmt(mulai) + " – " + fmt(akhir);
    document.getElementById("ringTotal").textContent = "Rp " + (parseInt(tipe.dataset.harga, 10) * n).toLocaleString("id-ID");
    form.querySelectorAll(".opsi-bayar").forEach(function (l) { l.classList.toggle("aktif", l.querySelector("input").checked); });
  };
  form.addEventListener("change", hitung);
  hitung();
})();
