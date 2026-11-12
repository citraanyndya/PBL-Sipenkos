document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".auth-form");
  if (!form) return;

  let message = form.querySelector(".auth-feedback");
  if (!message) {
    message = document.createElement("p");
    message.className = "auth-feedback";
    message.setAttribute("role", "status");
    message.setAttribute("aria-live", "polite");
    form.appendChild(message);
  }

  const showMessage = (text, isError = true) => {
    message.textContent = text;
    message.style.color = isError ? "#b91c1c" : "#166534";
  };

  const lanjutkan = () => {
    const adaPilihanKamar = Boolean(localStorage.getItem("kamarTerpilih"));
    window.location.href = adaPilihanKamar ? "form-sewa.php" : "beranda.php";
  };

  if (window.location.pathname.endsWith("registrasi.php")) {
    const password = document.getElementById("password");
    const konfirmasi = document.getElementById("konfirmasi_password");

    form.addEventListener("submit", (event) => {
      event.preventDefault();
      if (!form.reportValidity()) return;

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

      const profile = {
        nama: document.getElementById("nama").value.trim(),
        nohp: document.getElementById("nohp").value.trim(),
        email: document.getElementById("email").value.trim().toLowerCase()
      };

      try {
        localStorage.setItem("sipenkosDemoUser", JSON.stringify(profile));
        localStorage.setItem("userName", profile.nama);
        localStorage.setItem("isLoggedIn", "true");
      } catch (error) {
        showMessage("Akun kamu tidak dapat disimpan di browser ini.");
        return;
      }

      lanjutkan();
    });
  } else {
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      if (!form.reportValidity()) return;

      let profile = null;
      try {
        profile = JSON.parse(localStorage.getItem("sipenkosDemoUser") || "null");
      } catch (error) {
        profile = null;
      }

      const email = document.getElementById("email").value.trim().toLowerCase();
      if (!profile || profile.email !== email) {
        showMessage("Akun dengan email tersebut belum ditemukan. Silakan daftar terlebih dahulu.");
        return;
      }

      localStorage.setItem("userName", profile.nama);
      localStorage.setItem("isLoggedIn", "true");
      lanjutkan();
    });
  }
});
