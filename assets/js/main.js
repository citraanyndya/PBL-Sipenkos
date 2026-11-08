document.addEventListener("DOMContentLoaded", () => {
  let isLoggedIn = false;
  let profile = null;

  try {
    isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    profile = JSON.parse(localStorage.getItem("sipenkosDemoUser") || "null");
  } catch (error) {
    isLoggedIn = false;
    profile = null;
  }

  const userName = (localStorage.getItem("userName") || profile?.nama || "Pengguna").trim();
  const guestActions = document.getElementById("guestActions");
  const userProfile = document.getElementById("userProfile");
  const headerName = document.getElementById("headerName");
  const headerAvatar = document.getElementById("headerAvatar");

  if (guestActions) guestActions.style.display = isLoggedIn ? "none" : "flex";
  if (userProfile) userProfile.style.display = isLoggedIn ? "flex" : "none";
  if (headerName && isLoggedIn) headerName.textContent = userName || "Pengguna";
  if (headerAvatar && isLoggedIn) headerAvatar.textContent = (userName || "P").charAt(0).toUpperCase();
  if (isLoggedIn && userProfile && !document.getElementById("btnKeluar")) {
    const btnKeluar = document.createElement("button");
    btnKeluar.type = "button";
    btnKeluar.id = "btnKeluar";
    btnKeluar.className = "btn btn-outline btn-sm";
    btnKeluar.textContent = "Keluar";
    btnKeluar.addEventListener("click", () => {
      ["isLoggedIn", "userName", "kamarTerpilih", "draftSewa"].forEach((kunci) => localStorage.removeItem(kunci));
      window.location.href = "beranda.html";
    });
    userProfile.appendChild(btnKeluar);
  }
});
