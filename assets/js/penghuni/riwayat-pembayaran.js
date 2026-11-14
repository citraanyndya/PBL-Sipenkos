
document.addEventListener("DOMContentLoaded", () => {

    if (localStorage.getItem("isLoggedIn") !== "true") {
        window.location.href = "../calon-penghuni/login.php";
        return;
    }

    const daftarRiwayat = document.getElementById("daftarRiwayat");
    const dialogBukti = document.getElementById("dialogBukti");
    const isiBukti = document.getElementById("isiBukti");
    const tutupBukti = document.getElementById("tutupBukti");

    function ambilJSON(key, fallback) {
        try {
            return JSON.parse(localStorage.getItem(key)) || fallback;
        } catch (error) {
            return fallback;
        }
    }

    function formatRupiah(angka) {
        return "Rp " + Number(angka || 0).toLocaleString("id-ID");
    }

    function formatTanggal(nilai) {
        if (!nilai) return "-";

        const tanggal = new Date(nilai);

        if (isNaN(tanggal)) return "-";

        return tanggal.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric"
        });
    }

    function buatPeriode(tglMulai, durasi) {
        if (!tglMulai || !durasi) return "-";

        const mulai = new Date(tglMulai + "T00:00:00");

        if (isNaN(mulai)) return "-";

        const akhir = new Date(mulai);
        akhir.setMonth(akhir.getMonth() + Number(durasi) - 1);

        const format = (tanggal) => {
            return tanggal.toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "long",
                year: "numeric"
            });
        };

        return `${format(mulai)} - ${format(akhir)}`;
    }

    // Ambil riwayat yang sudah tersimpan
    let riwayat = ambilJSON("riwayatPembayaran", []);

    if (!Array.isArray(riwayat)) {
        riwayat = [];
    }

    // Sinkronisasi pembayaran DP dari halaman reservasi
    const pembayaran = ambilJSON("paymentData", null);

    if (
        pembayaran &&
        pembayaran.statusPembayaran === "diterima"
    ) {
        const sudahAda = riwayat.some(
            item => item.paymentId === pembayaran.paymentId
        );

        if (!sudahAda) {
            const reservasi = pembayaran.reservasi || {};

            const transaksiDP = {
                paymentId: pembayaran.paymentId,
                tanggalBayar:
                    pembayaran.tanggalVerifikasi ||
                    pembayaran.tanggalUpload,

                periode: buatPeriode(
                    reservasi.tglMulai,
                    reservasi.durasi
                ),

                jenis:
                    pembayaran.tipePembayaran === "dp"
                        ? "DP 50%"
                        : "Pembayaran Penuh",

                jumlah: Number(pembayaran.pembayaranSekarang) || 0,

                metode:
                    pembayaran.metodePembayaran === "tunai"
                        ? "Tunai"
                        : "Transfer",

                status: "lunas",

                namaFile: pembayaran.namaFile || "-",

                bukti: localStorage.getItem("buktiPratinjau"),

                tipeFile: pembayaran.tipeFile || ""
            };

            riwayat.push(transaksiDP);

            localStorage.setItem(
                "riwayatPembayaran",
                JSON.stringify(riwayat)
            );
        }
    }

    // Gabungkan tagihan yang sudah lunas (dari halaman Tagihan)
    const daftarTagihan = ambilJSON("tagihanPembayaran", []);

    if (Array.isArray(daftarTagihan)) {
        daftarTagihan.forEach((t, i) => {
            if (!String(t.status || "").toLowerCase().includes("lunas")) return;

            const idTrx = `tagihan-${t.id || i}`;
            if (riwayat.some((r) => r.paymentId === idTrx)) return;

            const bukti = t.buktiPembayaran || {};

            riwayat.push({
                paymentId: idTrx,
                tanggalBayar: t.tanggalLunas || bukti.tanggalUpload || t.tanggalJatuhTempo,
                periode: t.periode || t.bulan || "-",
                jenis: t.jenis || "Sewa",
                jumlah: Number(t.nominal ?? t.jumlah) || 0,
                metode: "Transfer",
                status: "lunas",
                namaFile: bukti.nama || "-",
                tipeFile: bukti.tipe || "",
                idBukti: bukti.id || ""
            });
        });
    }

    // Urutkan transaksi terbaru di atas
    riwayat.sort((a, b) => {
        return new Date(b.tanggalBayar) -
               new Date(a.tanggalBayar);
    });

    function tampilkanRiwayat() {

        daftarRiwayat.replaceChildren();

        if (riwayat.length === 0) {
            const baris = document.createElement("tr");
            const kolom = document.createElement("td");

            kolom.colSpan = 7;
            kolom.className = "riwayat-kosong";
            kolom.textContent =
                "Belum ada pembayaran yang berhasil diverifikasi.";

            baris.appendChild(kolom);
            daftarRiwayat.appendChild(baris);

            return;
        }

        riwayat.forEach((transaksi, index) => {

            const baris = document.createElement("tr");

            const data = [
                formatTanggal(transaksi.tanggalBayar),
                transaksi.periode || "-",
                transaksi.jenis || "-",
                formatRupiah(transaksi.jumlah),
                transaksi.metode || "-",
            ];

            data.forEach((nilai, i) => {
                const kolom = document.createElement("td");
                kolom.textContent = nilai;

                if (i === 1) {
                    kolom.className = "periode-text";
                }

                if (i === 3) {
                    kolom.className = "jumlah-pembayaran";
                }

                baris.appendChild(kolom);
            });

            // Status
            const kolomStatus = document.createElement("td");
            const badge = document.createElement("span");

            badge.className = "status-lunas";
            badge.textContent = "● Lunas";

            kolomStatus.appendChild(badge);
            baris.appendChild(kolomStatus);

            // Tombol bukti
            const kolomBukti = document.createElement("td");
            const tombol = document.createElement("button");

            tombol.type = "button";
            tombol.className = "btn-lihat-bukti";
            tombol.textContent = "◎ Lihat";

            tombol.addEventListener("click", () => {
                bukaBukti(transaksi);
            });

            kolomBukti.appendChild(tombol);
            baris.appendChild(kolomBukti);

            daftarRiwayat.appendChild(baris);
        });
    }

    // Ambil file bukti yang disimpan di IndexedDB (diupload dari halaman Tagihan)
    function ambilBuktiDB(id) {
        return new Promise((resolve) => {
            if (!id || !window.indexedDB) return resolve(null);

            const request = indexedDB.open("SIPENKOS_BuktiPembayaran", 1);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains("bukti")) {
                    db.createObjectStore("bukti", { keyPath: "id" });
                }
            };

            request.onerror = () => resolve(null);

            request.onsuccess = () => {
                const db = request.result;
                const get = db.transaction("bukti", "readonly")
                    .objectStore("bukti")
                    .get(id);

                get.onsuccess = () => {
                    db.close();
                    resolve(get.result || null);
                };

                get.onerror = () => {
                    db.close();
                    resolve(null);
                };
            };
        });
    }

    async function bukaBukti(transaksi) {

        isiBukti.replaceChildren();

        let sumber = transaksi.bukti || "";
        let tipe = transaksi.tipeFile || "";

        if (!sumber && transaksi.idBukti) {
            const rekaman = await ambilBuktiDB(transaksi.idBukti);

            if (rekaman && rekaman.file) {
                sumber = URL.createObjectURL(rekaman.file);
                tipe = rekaman.tipe || tipe;
            }
        }

        if (sumber && tipe.startsWith("image/")) {
            const gambar = document.createElement("img");

            gambar.src = sumber;
            gambar.alt = "Bukti pembayaran";

            isiBukti.appendChild(gambar);

        } else if (sumber && tipe === "application/pdf") {
            const tautan = document.createElement("a");

            tautan.href = sumber;
            tautan.target = "_blank";
            tautan.rel = "noopener";
            tautan.textContent = `Buka file PDF: ${transaksi.namaFile || "bukti"}`;

            isiBukti.appendChild(tautan);

        } else {
            const pesan = document.createElement("p");

            pesan.className = "bukti-tidak-tersedia";
            pesan.textContent =
                `File: ${transaksi.namaFile || "-"}. Pratinjau file belum tersedia.`;

            isiBukti.appendChild(pesan);
        }

        dialogBukti.showModal();
    }

    tutupBukti.addEventListener("click", () => {
        dialogBukti.close();
    });

    dialogBukti.addEventListener("click", (event) => {
        if (event.target === dialogBukti) {
            dialogBukti.close();
        }
    });

    tampilkanRiwayat();

});
