/*
 * SIPENKOS - penyimpanan akun dan sesi versi demonstrasi.
 * Data browser disimpan sebagai JSON pada localStorage.
 * Untuk produksi, pindahkan autentikasi dan penyimpanan ke PHP + PostgreSQL,
 * gunakan password_hash()/password_verify(), dan jangan menyimpan password di browser.
 */
(function () {
  "use strict";

  var KEY_DB = "sipenkosDatabase";
  var KEY_SESSION = "sipenkosDemoSession";

  function bacaDatabase() {
    try {
      var db = JSON.parse(localStorage.getItem(KEY_DB) || "null");
      if (!db || !Array.isArray(db.pengguna) || !Array.isArray(db.pengelola)) {
        db = { versi: 1, pengguna: [], pengelola: [] };
      }
      return db;
    } catch (e) {
      return { versi: 1, pengguna: [], pengelola: [] };
    }
  }

  function simpanDatabase(db) {
    try {
      db.diperbaruiPada = new Date().toISOString();
      localStorage.setItem(KEY_DB, JSON.stringify(db));
      return true;
    } catch (e) {
      return false;
    }
  }

  function siapkanAkunDemo() {
    var db = bacaDatabase();
    var demo = [
      { username: "admin", password: "admin12345", nama: "Admin Kos Bu Nining", role: "pengelola" },
      { username: "penghuni", password: "penghuni123", nama: "Penghuni Demo", role: "penghuni" }
    ];
    demo.forEach(function (akun) {
      var daftar = akun.role === "pengelola" ? db.pengelola : db.pengguna;
      if (!daftar.some(function (x) { return x.username === akun.username; })) {
        daftar.push({
          id: akun.role + "-demo",
          username: akun.username,
          password: akun.password,
          nama: akun.nama,
          email: akun.role === "pengelola" ? "admin@sipenkos.local" : "penghuni@sipenkos.local",
          role: akun.role,
          status: "aktif",
          dibuatPada: new Date().toISOString(),
          akunDemo: true
        });
      }
    });
    simpanDatabase(db);
  }

  function normalisasiUsername(value) {
    return String(value || "").trim().toLowerCase();
  }

  function daftarPenghuni(data) {
    var db = bacaDatabase();
    var username = normalisasiUsername(data.username);
    if (username.length < 3) return { ok: false, pesan: "Username minimal 3 karakter." };
    if (db.pengguna.some(function (x) { return normalisasiUsername(x.username) === username; }) ||
        db.pengelola.some(function (x) { return normalisasiUsername(x.username) === username; })) {
      return { ok: false, pesan: "Username sudah digunakan. Silakan pilih username lain." };
    }
    var akun = {
      id: "penghuni-" + Date.now(),
      username: username,
      password: data.password,
      nama: String(data.nama || "").trim(),
      nohp: String(data.nohp || "").trim(),
      email: String(data.email || "").trim().toLowerCase(),
      role: "penghuni",
      status: "aktif",
      dibuatPada: new Date().toISOString()
    };
    db.pengguna.push(akun);
    if (!simpanDatabase(db)) return { ok: false, pesan: "Data akun gagal disimpan di browser." };
    return { ok: true, akun: akun };
  }

  function buatSesi(akun) {
    var sesi = {
      id: akun.id,
      role: akun.role,
      username: akun.username,
      nama: akun.nama,
      email: akun.email || "",
      masukPada: new Date().toISOString()
    };
    try {
      localStorage.setItem(KEY_SESSION, JSON.stringify(sesi));
      localStorage.setItem("isLoggedIn", "true");
      localStorage.setItem("sipenkosRole", akun.role === "pengelola" ? "pengelola" : "calon_penghuni");
      localStorage.setItem("userName", akun.nama);
      localStorage.setItem("sipenkosUsername", akun.username);
      return sesi;
    } catch (e) {
      return false;
    }
  }

  function masuk(role, username, password) {
    var db = bacaDatabase();
    var daftar = role === "pengelola" ? db.pengelola : db.pengguna;
    var akun = daftar.find(function (x) {
      return normalisasiUsername(x.username) === normalisasiUsername(username) &&
        x.password === password && x.status !== "nonaktif";
    });
    if (!akun) return false;
    return buatSesi(akun);
  }

  function keluar() {
    [KEY_SESSION, "isLoggedIn", "sipenkosRole", "userName", "sipenkosUsername"].forEach(function (key) {
      localStorage.removeItem(key);
    });
  }

  function bacaSesi() {
    try { return JSON.parse(localStorage.getItem(KEY_SESSION) || "null"); }
    catch (e) { return null; }
  }

  function bacaData(role, kategori) {
    var db = bacaDatabase();
    var kunci = role === "pengelola" ? "dataPengelola" : "dataPenghuni";
    if (!db[kunci] || typeof db[kunci] !== "object") db[kunci] = {};
    if (!Array.isArray(db[kunci][kategori])) db[kunci][kategori] = [];
    return db[kunci][kategori];
  }

  function simpanData(role, kategori, daftar) {
    if (!Array.isArray(daftar)) return false;
    var db = bacaDatabase();
    var kunci = role === "pengelola" ? "dataPengelola" : "dataPenghuni";
    if (!db[kunci] || typeof db[kunci] !== "object") db[kunci] = {};
    db[kunci][kategori] = daftar;
    return simpanDatabase(db);
  }

  function eksporJSON() {
    return JSON.stringify(bacaDatabase(), null, 2);
  }

  siapkanAkunDemo();
  window.SIPENKOS_SESI = {
    daftarPenghuni: daftarPenghuni,
    masuk: masuk,
    masukDemo: masuk,
    baca: bacaSesi,
    keluar: keluar,
    bacaDatabase: bacaDatabase,
    simpanDatabase: simpanDatabase,
    bacaData: bacaData,
    simpanData: simpanData,
    eksporJSON: eksporJSON
  };

  document.addEventListener("DOMContentLoaded", function () {
    var path = window.location.pathname;
    var diHalamanAdmin = /\/admin\//i.test(path) && !/\/admin\/index\.php$/i.test(path);
    var diHalamanPenghuni = /\/penghuni\//i.test(path);
    var sesi = bacaSesi();

    if (diHalamanAdmin && (!sesi || sesi.role !== "pengelola")) {
      window.location.replace("../calon-penghuni/login.php#pengelola");
      return;
    }
    if (diHalamanPenghuni && (!sesi || sesi.role !== "penghuni")) {
      window.location.replace("../calon-penghuni/login.php");
      return;
    }
    document.querySelectorAll('a[href*="index.php?keluar=1"]').forEach(function (tautan) {
      tautan.addEventListener("click", keluar);
    });
  });
})();
