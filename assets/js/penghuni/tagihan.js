
document.addEventListener("DOMContentLoaded", () => {
    const tagihanList = document.getElementById("tagihanList");
    const modal = document.getElementById("modalUpload");
    const formUpload = document.getElementById("formUpload");
    const inputBukti = document.getElementById("buktiPembayaran");

    let indexTagihanAktif = null;
    let fileTerpilih = null;
    let previewURL = null;

    const formatRupiah = (angka) => {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            maximumFractionDigits: 0
        }).format(Number(angka) || 0);
    };

    const formatTanggal = (tanggal) => {
        if (!tanggal) return "-";

        const date = new Date(tanggal + "T00:00:00");

        if (isNaN(date.getTime())) return tanggal;

        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric"
        });
    };

    const ambilData = () => {
        try {
            const data = JSON.parse(
                localStorage.getItem("tagihanPembayaran") || "[]"
            );

            return Array.isArray(data) ? data : [];
        } catch (error) {
            return [];
        }
    };

    const simpanData = (data) => {
        localStorage.setItem(
            "tagihanPembayaran",
            JSON.stringify(data)
        );
    };

    const ambilNominal = (item) => {
        return Number(
            item.nominal ??
            item.jumlah ??
            item.nominalCicilan ??
            item.jumlahTagihan ??
            0
        );
    };

    const ambilStatus = (item) => {
        const status = String(item.status || "Belum Dibayar")
            .toLowerCase();

        if (status.includes("lunas")) return "Lunas";

        if (
            status.includes("menunggu") ||
            status.includes("verifikasi")
        ) {
            return "Menunggu Verifikasi";
        }

        if (status.includes("tolak")) return "Ditolak";

        return "Belum Dibayar";
    };

    const ambilTempo = (item) => {
        return (
            item.tanggalJatuhTempo ||
            item.jatuhTempo ||
            item.tanggalTempo ||
            ""
        );
    };

    const ambilID = (item, index) => {
        return String(
            item.id ||
            item.idCicilan ||
            `tagihan-${index + 1}`
        );
    };

    const ambilJSON = (key) => {
        try {
            return JSON.parse(localStorage.getItem(key) || "null");
        } catch (error) {
            return null;
        }
    };

    const tglISO = (tahun, bulan, hari) => {
        const d = new Date(tahun, bulan, hari);
        const dua = (n) => String(n).padStart(2, "0");
        return `${d.getFullYear()}-${dua(d.getMonth() + 1)}-${dua(d.getDate())}`;
    };

    // Data contoh untuk presentasi/testing: buka tagihan.php?demo=1
    const isiDataContoh = () => {
        const now = new Date();
        const y = now.getFullYear();
        const m = now.getMonth();

        simpanData([
            {
                id: "demo-1",
                periode: "Sewa Bulan Lalu",
                jenis: "Sewa Bulanan",
                nominal: 750000,
                tanggalJatuhTempo: tglISO(y, m - 1, 27),
                tanggalLunas: tglISO(y, m - 1, 25),
                status: "Lunas"
            },
            {
                id: "demo-2",
                periode: "Sewa Bulan Ini",
                jenis: "Sewa Bulanan",
                nominal: 750000,
                tanggalJatuhTempo: tglISO(y, m, 27),
                status: "Belum Dibayar"
            },
            {
                id: "demo-3",
                periode: "Sewa Bulan Depan",
                jenis: "Sewa Bulanan",
                nominal: 750000,
                tanggalJatuhTempo: tglISO(y, m + 1, 27),
                status: "Belum Dibayar"
            }
        ]);
    };

    // Membuat tagihan dari data reservasi (DP -> sisa pelunasan)
    const siapkanTagihan = () => {
        const params = new URLSearchParams(window.location.search);

        if (params.get("demo") === "1" && ambilData().length === 0) {
            isiDataContoh();
            return;
        }

        if (ambilData().length > 0) return;

        const pembayaran = ambilJSON("paymentData");
        if (!pembayaran) return;

        const sisa = Number(pembayaran.sisaPelunasan) || 0;
        if (pembayaran.tipePembayaran !== "dp" || sisa <= 0) return;

        const reservasi = pembayaran.reservasi || {};

        simpanData([{
            id: `pelunasan-${pembayaran.paymentId || "1"}`,
            periode: "Pelunasan Sewa",
            jenis: "Pelunasan",
            nominal: sisa,
            tanggalJatuhTempo: reservasi.tglMulai || "",
            status: "Belum Dibayar"
        }]);
    };

    // Kartu "Sisa Tagihan" di bagian atas
    const renderSisaTagihan = (data) => {
        const kartu = document.getElementById("sisaTagihanCard");
        const elNominal = document.getElementById("sisaTagihanNominal");
        const elInfo = document.getElementById("sisaTagihanInfo");

        if (!kartu || !elNominal || !elInfo) return;

        const belumBayar = data.filter((item) => {
            const status = ambilStatus(item);
            return status === "Belum Dibayar" || status === "Ditolak";
        });

        const menunggu = data.filter(
            (item) => ambilStatus(item) === "Menunggu Verifikasi"
        ).length;

        const total = belumBayar.reduce(
            (jumlah, item) => jumlah + ambilNominal(item),
            0
        );

        elNominal.textContent = formatRupiah(total);

        let info = belumBayar.length
            ? `${belumBayar.length} tagihan belum dibayar`
            : "Tidak ada tagihan yang harus dibayar";

        if (menunggu) info += ` · ${menunggu} menunggu verifikasi`;

        elInfo.textContent = info;
        kartu.classList.toggle("sisa-lunas", belumBayar.length === 0);
    };

    const renderTagihan = () => {
        const data = ambilData();
        renderSisaTagihan(data);

        if (data.length === 0) {
            tagihanList.innerHTML = `
                <tr>
                    <td colspan="6" class="tagihan-kosong">
                        Belum ada tagihan pembayaran.
                    </td>
                </tr>
            `;
            return;
        }

        const dataUrut = data
            .map((item, index) => ({
                ...item,
                indexAsli: index
            }))
            .sort((a, b) => {
                return ambilTempo(a).localeCompare(ambilTempo(b));
            });

        tagihanList.innerHTML = dataUrut.map((item) => {
            const index = item.indexAsli;
            const status = ambilStatus(item);
            const nominal = ambilNominal(item);

            const periode =
                item.periode ||
                item.bulan ||
                `Cicilan ke-${item.nomorCicilan || index + 1}`;

            const jenis = item.jenis || "Sewa";

            let kelasStatus = "status-belum";
            if (status === "Lunas") kelasStatus = "status-lunas";
            if (status === "Menunggu Verifikasi") kelasStatus = "status-menunggu";
            if (status === "Ditolak") kelasStatus = "status-ditolak";

            let aksi = "";
            if (status === "Lunas") {
                aksi = `<span class="aksi-selesai">Selesai</span>`;
            } else if (status === "Menunggu Verifikasi") {
                aksi = `<span class="aksi-menunggu">Bukti terkirim</span>`;
            } else {
                aksi = `
                    <button
                        class="btn-bayar"
                        type="button"
                        data-bayar="${index}">
                        ${status === "Ditolak" ? "Upload Ulang" : "Bayar"}
                    </button>
                `;
            }
            return `
                <tr>
                    <td>${periode}</td>
                    <td>${jenis}</td>
                    <td class="nominal-tagihan">${formatRupiah(nominal)}</td>
                    <td>${formatTanggal(ambilTempo(item))}</td>
                    <td>
                        <span class="status-tagihan ${kelasStatus}">
                            ${status}
                        </span>
                    </td>
                    <td>${aksi}</td>
                </tr>
            `;
        }).join("");
    };

    const bukaDatabase = () => {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(
                "SIPENKOS_BuktiPembayaran",
                1
            );

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                if (!db.objectStoreNames.contains("bukti")) {
                    db.createObjectStore("bukti", {
                        keyPath: "id"
                    });
                }
            };

            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    };

    const simpanBukti = async (id, file) => {
        const db = await bukaDatabase();

        return new Promise((resolve, reject) => {
            const transaksi = db.transaction("bukti", "readwrite");

            transaksi.objectStore("bukti").put({
                id,
                nama: file.name,
                tipe: file.type,
                ukuran: file.size,
                file,
                tanggalUpload: new Date().toISOString()
            });

            transaksi.oncomplete = () => {
                db.close();
                resolve();
            };

            transaksi.onerror = () => {
                db.close();
                reject(transaksi.error);
            };
        });
    };

    const tutupModal = () => {
        modal.classList.remove("active");
        formUpload.reset();
        fileTerpilih = null;

        if (previewURL) {
            URL.revokeObjectURL(previewURL);
            previewURL = null;
        }

        document.getElementById("previewBukti").innerHTML = "";
        indexTagihanAktif = null;
    };

    tagihanList.addEventListener("click", (event) => {
        const tombol = event.target.closest("[data-bayar]");

        if (!tombol) return;

        indexTagihanAktif = Number(tombol.dataset.bayar);

        const data = ambilData();
        const item = data[indexTagihanAktif];

        if (!item) return;

        const periode =
            item.periode ||
            item.bulan ||
            `Cicilan ke-${indexTagihanAktif + 1}`;

        document.getElementById("detailTagihan").textContent =
            `${periode} — ${formatRupiah(ambilNominal(item))}`;

        modal.classList.add("active");
    });

    inputBukti.addEventListener("change", () => {
        const file = inputBukti.files[0];

        fileTerpilih = null;

        if (previewURL) {
            URL.revokeObjectURL(previewURL);
            previewURL = null;
        }

        document.getElementById("previewBukti").innerHTML = "";

        if (!file) return;

        const formatValid = [
            "image/jpeg",
            "image/png",
            "application/pdf"
        ].includes(file.type);

        if (!formatValid) {
            alert("Format file harus JPG, PNG, atau PDF.");
            inputBukti.value = "";
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert("Ukuran file maksimal 5 MB.");
            inputBukti.value = "";
            return;
        }

        fileTerpilih = file;

        if (file.type.startsWith("image/")) {
            previewURL = URL.createObjectURL(file);

            document.getElementById("previewBukti").innerHTML = `
                <img src="${previewURL}" alt="Pratinjau bukti pembayaran">
            `;
        } else {
            document.getElementById("previewBukti").textContent =
                `File dipilih: ${file.name}`;
        }
    });

    formUpload.addEventListener("submit", async (event) => {
        event.preventDefault();

        if (indexTagihanAktif === null || !fileTerpilih) {
            alert("Silakan pilih bukti pembayaran terlebih dahulu.");
            return;
        }

        const data = ambilData();
        const item = data[indexTagihanAktif];

        if (!item) return;

        const tombolKirim = formUpload.querySelector(
            ".btn-kirim-bukti"
        );

        try {
            tombolKirim.disabled = true;
            tombolKirim.textContent = "Mengirim...";

            const idBukti = `bukti-${ambilID(
                item,
                indexTagihanAktif
            )}`;

            await simpanBukti(idBukti, fileTerpilih);

            item.status = "Menunggu Verifikasi";
            item.alasanPenolakan = "";

            item.buktiPembayaran = {
                id: idBukti,
                nama: fileTerpilih.name,
                tipe: fileTerpilih.type,
                ukuran: fileTerpilih.size,
                tanggalUpload: new Date().toISOString()
            };

            data[indexTagihanAktif] = item;
            simpanData(data);

            alert("Bukti pembayaran berhasil dikirim!");

            if (window.sipenkosSinkronNotif) window.sipenkosSinkronNotif();

            tutupModal();
            renderTagihan();

        } catch (error) {
            console.error(error);
            alert("Gagal menyimpan bukti pembayaran.");

        } finally {
            tombolKirim.disabled = false;
            tombolKirim.textContent = "Kirim Bukti Pembayaran";
        }
    });

    document.getElementById("tutupModal").addEventListener(
        "click",
        tutupModal
    );

    modal.addEventListener("click", (event) => {
        if (event.target === modal) tutupModal();
    });

    siapkanTagihan();
    renderTagihan();
});
