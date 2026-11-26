/* Penghubung admin tim <-> backend: dialog konfirmasi untuk form berisiko + cegah kirim ganda. CSP: tanpa inline script. */
(function () {
    'use strict';
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.dataset.dikonfirmasi === '1') { return; }
        var hapus = form.classList.contains('form-hapus');
        var umum = form.classList.contains('form-konfirmasi');
        if (!hapus && !umum) { return; }
        e.preventDefault();
        var nama = 'data ini';
        if (hapus) {
            var sel = form.closest('tr') ? form.closest('tr').querySelector('td') : null;
            if (sel) { nama = sel.textContent.trim().replace(/\s+/g, ' '); }
        }
        var opsi = hapus
            ? { title: 'Hapus data?', text: 'Yakin ingin menghapus "' + nama + '"? Tindakan ini tidak dapat dibatalkan.', tone: 'danger', okLabel: 'Ya, Hapus' }
            : { title: 'Konfirmasi', text: form.dataset.konfirmasi || 'Yakin melanjutkan?', tone: form.dataset.tone || 'primary', okLabel: form.dataset.ok || 'Ya, Lanjutkan' };
        if (window.App && window.App.confirm) {
            window.App.confirm(opsi).then(function (ya) {
                if (!ya) { return; }
                form.dataset.dikonfirmasi = '1';
                form.submit();
            });
        } else if (window.confirm(opsi.text)) {
            form.dataset.dikonfirmasi = '1';
            form.submit();
        }
    });

    // Cegah klik ganda pada form biasa
    document.querySelectorAll('form').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            if (ev.defaultPrevented) { return; }
            window.setTimeout(function () {
                f.querySelectorAll('button[type="submit"]').forEach(function (b) { b.disabled = true; });
            }, 0);
        });
    });
})();
