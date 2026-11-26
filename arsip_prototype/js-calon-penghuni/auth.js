document.addEventListener("DOMContentLoaded", function () {
  var form = document.querySelector(".auth-form");
  if (!form) return;

  var message = form.querySelector(".auth-feedback");
  if (!message) {
    message = document.createElement("p");
    message.className = "auth-feedback";
    message.setAttribute("role", "status");
    message.setAttribute("aria-live", "polite");
    form.appendChild(message);
  }

  function showMessage(text, isError) {
    message.textContent = text;
    message.style.color = isError === false ? "#166534" : "#b91c1c";
  }

  function lanjutkan() {
    var adaPilihanKamar = Boolean(localStorage.getItem("kamarTerpilih"));
    window.location.href = adaPilihanKamar ? "form-sewa.php" : "beranda.php";
  }

  var isRegistrasi = window.location.pathname.endsWith("registrasi.php");
  if (isRegistrasi) {
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!form.reportValidity()) return;

      var password = document.getElementById("password");
      var konfirmasi = document.getElementById("konfirmasi_password");
      if (password.value.length < 8) {
        showMessage("Kata sandi minimal 8 karakter.");
        password.focus();
        return;
      }
      if (password.value !== konfirmasi.value) {
        showMessage("Konfirmasi kata sandi belum sama.");
        konfirmasi.focus();
        return;
      }

      var hasil = window.SIPENKOS_SESI && window.SIPENKOS_SESI.daftarPenghuni({
        nama: document.getElementById("nama").value,
        nohp: document.getElementById("nohp").value,
        username: document.getElementById("username").value,
        email: document.getElementById("email").value,
        password: password.value
      });

      if (!hasil || !hasil.ok) {
        showMessage(hasil ? hasil.pesan : "Modul penyimpanan belum siap. Muat ulang halaman.");
        return;
      }
      window.SIPENKOS_SESI.masuk("penghuni", hasil.akun.username, password.value);
      lanjutkan();
    });
  } else {
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!form.reportValidity()) return;

      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;
      var sesi = window.SIPENKOS_SESI && window.SIPENKOS_SESI.masuk("penghuni", username, password);
      if (!sesi) {
        showMessage("Username atau kata sandi salah. Periksa kembali atau daftar akun terlebih dahulu.");
        return;
      }
      window.location.href = "../penghuni/dashboard.php";
    });
  }
});
