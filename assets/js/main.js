document.addEventListener("DOMContentLoaded", () => {
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
  const userName = localStorage.getItem("userName") || "Rina";

  const guestActions = document.getElementById("guestActions");
  const userProfile = document.getElementById("userProfile");
  const headerName = document.getElementById("headerName");
  const headerAvatar = document.getElementById("headerAvatar");

  if (isLoggedIn) {
    if (guestActions) guestActions.style.display = "none";
    if (userProfile) userProfile.style.display = "flex";
    if (headerName) headerName.textContent = userName;
    if (headerAvatar) headerAvatar.textContent = userName.charAt(0).toUpperCase();
  } else {
    if (guestActions) guestActions.style.display = "flex";
    if (userProfile) userProfile.style.display = "none";
  }
});