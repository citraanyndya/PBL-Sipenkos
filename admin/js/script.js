/* ==========================================================================
   SIPENKOS · Admin UI – JavaScript murni
   Bagian:
   1. Utilitas & toast         5. Daftar terfilter (chip + cari + periode)
   2. Sidebar                  6. Status baris tabel
   3. Dropdown                 7. Modul per halaman
   4. Modal & konfirmasi
   ========================================================================== */
(function () {
    'use strict';

    /* 1. Utilitas ======================================================== */
    var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
    var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
    var DATA = window.APP_DATA || {};

    function esc(str) {
        return String(str == null ? '' : str).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }
    function rupiah(n) { return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); }
    function initials(name) {
        return String(name).trim().split(/\s+/).slice(0, 2).map(function (p) { return p.charAt(0).toUpperCase(); }).join('');
    }
    var BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    function tglIndo(iso) {
        var p = String(iso).split('-');
        if (p.length !== 3) { return iso; }
        return ('0' + Number(p[2])).slice(-2) + ' ' + BULAN[Number(p[1]) - 1] + ' ' + p[0];
    }
    function storage(key, value) {
        try {
            if (value === undefined) { return JSON.parse(localStorage.getItem('sipenkos:' + key)); }
            localStorage.setItem('sipenkos:' + key, JSON.stringify(value));
        } catch (err) { return null; }
        return null;
    }

    /** Ikon kecil inline (dipakai untuk elemen yang dirender lewat JS) */
    var ICONS = {
        check: '<path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/>',
        info: '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
        alert: '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
        image: '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
        idcard: '<rect width="20" height="14" x="2" y="5" rx="2"/><circle cx="8" cy="12" r="2"/><path d="M14 10h4"/><path d="M14 14h4"/>',
        search: '<path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>'
    };
    function svg(name, cls) {
        return '<svg class="icon ' + (cls || '') + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + (ICONS[name] || '') + '</svg>';
    }

    function toast(message, type) {
        var stack = $('#toast-stack');
        if (!stack) { return; }
        var el = document.createElement('div');
        el.className = 'toast' + (type ? ' toast-' + type : '');
        el.innerHTML = svg(type === 'danger' ? 'alert' : (type === 'info' ? 'info' : 'check')) + '<span>' + esc(message) + '</span>';
        stack.appendChild(el);
        setTimeout(function () {
            el.classList.add('is-leaving');
            setTimeout(function () { el.remove(); }, 220);
        }, 3200);
    }

    /** Tampilkan banner di dalam slot [data-banner-slot] */
    function showBanner(slot, tone, message, extraHtml) {
        if (!slot) { return; }
        var iconName = tone === 'success' ? 'check' : (tone === 'danger' ? 'alert' : 'info');
        slot.innerHTML = '<div class="banner banner-' + tone + ' is-entering" role="status"><div class="banner-body">' +
            svg(iconName) + '<span>' + esc(message) + '</span></div>' + (extraHtml || '') + '</div>';
    }

    /* 2. Sidebar (mobile) ================================================ */
    function initSidebar() {
        var body = document.body;
        $$('[data-sidebar-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () { body.classList.toggle('sidebar-open'); });
        });
        $$('[data-sidebar-close]').forEach(function (el) {
            el.addEventListener('click', function () { body.classList.remove('sidebar-open'); });
        });
    }

    /* 3. Dropdown ======================================================== */
    function closeDropdowns(except) {
        $$('[data-dropdown].is-open').forEach(function (d) {
            if (d !== except) {
                d.classList.remove('is-open');
                var t = $('[data-dropdown-toggle]', d);
                if (t) { t.setAttribute('aria-expanded', 'false'); }
            }
        });
    }
    function initDropdowns() {
        document.addEventListener('click', function (e) {
            var toggle = e.target.closest('[data-dropdown-toggle]');
            if (toggle) {
                var dd = toggle.closest('[data-dropdown]');
                var open = !dd.classList.contains('is-open');
                closeDropdowns(dd);
                dd.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                return;
            }
            var option = e.target.closest('[data-dropdown] [data-value]');
            if (option) {
                var wrap = option.closest('[data-dropdown]');
                $$('[data-value]', wrap).forEach(function (o) { o.classList.toggle('is-selected', o === option); });
                var label = $('[data-dropdown-label]', wrap);
                if (label) { label.textContent = option.getAttribute('data-label') || option.textContent.trim(); }
                wrap.setAttribute('data-current', option.getAttribute('data-value'));
                closeDropdowns();
                wrap.dispatchEvent(new CustomEvent('dropdown:change', { bubbles: true, detail: { value: option.getAttribute('data-value') } }));
                return;
            }
            if (!e.target.closest('[data-dropdown]')) { closeDropdowns(); }
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeDropdowns(); } });
    }

    /* 4. Modal =========================================================== */
    var lastFocus = null;
    var Modal = {
        open: function (id) {
            var m = typeof id === 'string' ? document.getElementById(id) : id;
            if (!m) { return; }
            lastFocus = document.activeElement;
            m.hidden = false;
            document.body.classList.add('modal-open');
            closeDropdowns();
            // Fokus ke dialog (bukan tombol) agar tidak muncul ring fokus, tetapi Tab tetap berada di dalam modal
            var dialog = $('.modal-dialog', m) || m;
            dialog.setAttribute('tabindex', '-1');
            setTimeout(function () { dialog.focus({ preventScroll: true }); }, 30);
            m.dispatchEvent(new CustomEvent('modal:open'));
        },
        close: function (id) {
            var m = typeof id === 'string' ? document.getElementById(id) : id;
            if (!m || m.hidden) { return; }
            m.hidden = true;
            if (!$$('.modal').some(function (x) { return !x.hidden; })) { document.body.classList.remove('modal-open'); }
            m.dispatchEvent(new CustomEvent('modal:close'));
            if (lastFocus && lastFocus.focus) { lastFocus.focus({ preventScroll: true }); }
        }
    };
    function initModals() {
        document.addEventListener('click', function (e) {
            var opener = e.target.closest('[data-modal-open]');
            if (opener) {
                e.preventDefault();
                Modal.open(opener.getAttribute('data-modal-open'));
                return;
            }
            var closer = e.target.closest('[data-modal-close]');
            if (closer) {
                Modal.close(closer.closest('.modal'));
                return;
            }
            if (e.target.classList && e.target.classList.contains('modal')) { Modal.close(e.target); }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') { return; }
            var open = $$('.modal').filter(function (m) { return !m.hidden; });
            if (open.length) { Modal.close(open[open.length - 1]); }
        });
        // Modal yang dibuka otomatis (state halaman sesuai desain Figma)
        $$('.modal[data-autoopen]').forEach(function (m) { Modal.open(m); });
    }

    /** Konfirmasi generik (mengembalikan Promise<boolean>) */
    function confirmDialog(opts) {
        return new Promise(function (resolve) {
            var wrap = document.createElement('div');
            wrap.className = 'modal';
            wrap.setAttribute('role', 'dialog');
            wrap.setAttribute('aria-modal', 'true');
            var tone = opts.tone || 'primary';
            wrap.innerHTML = '<div class="modal-dialog modal-sm modal-center">' +
                '<div class="result-icon' + (tone === 'danger' ? ' result-icon-danger' : '') + '">' + svg(tone === 'danger' ? 'alert' : 'check') + '</div>' +
                '<h2 class="result-title">' + esc(opts.title) + '</h2>' +
                '<p class="result-text">' + esc(opts.text || '') + '</p>' +
                '<div class="modal-actions modal-actions-split">' +
                '<button type="button" class="btn btn-secondary btn-lg" data-answer="0">' + esc(opts.cancelLabel || 'Batal') + '</button>' +
                '<button type="button" class="btn ' + (tone === 'danger' ? 'btn-danger' : 'btn-primary') + ' btn-lg" data-answer="1">' + esc(opts.okLabel || 'Ya, Lanjutkan') + '</button>' +
                '</div></div>';
            document.body.appendChild(wrap);
            Modal.open(wrap);
            var settled = false;
            function done(val) {
                if (settled) { return; }
                settled = true;
                resolve(val);
                Modal.close(wrap);
                wrap.remove();
            }
            wrap.addEventListener('click', function (e) {
                var b = e.target.closest('[data-answer]');
                if (b) { done(b.getAttribute('data-answer') === '1'); }
                else if (e.target === wrap) { done(false); }
            });
            wrap.addEventListener('modal:close', function () { if (!settled) { done(false); } });
        });
    }

    /** Info sederhana satu tombol */
    function resultDialog(opts) {
        var wrap = document.createElement('div');
        wrap.className = 'modal';
        wrap.setAttribute('role', 'dialog');
        wrap.setAttribute('aria-modal', 'true');
        wrap.innerHTML = '<div class="modal-dialog modal-sm modal-center">' +
            '<div class="result-icon">' + svg('check') + '</div>' +
            '<h2 class="result-title">' + esc(opts.title) + '</h2>' +
            '<p class="result-text">' + esc(opts.text || '') + '</p>' +
            '<div class="modal-actions"><button type="button" class="btn btn-primary btn-lg btn-block" data-modal-close>' + esc(opts.okLabel || 'Oke') + '</button></div></div>';
        document.body.appendChild(wrap);
        Modal.open(wrap);
        wrap.addEventListener('modal:close', function () { wrap.remove(); });
    }

    /** Lightbox gambar */
    function lightbox(src, alt) {
        var wrap = document.createElement('div');
        wrap.className = 'modal';
        wrap.innerHTML = '<button type="button" class="lightbox-close" data-modal-close aria-label="Tutup">' +
            '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>' +
            '<img class="lightbox-img" src="' + esc(src) + '" alt="' + esc(alt || '') + '">';
        document.body.appendChild(wrap);
        Modal.open(wrap);
        wrap.addEventListener('click', function (e) { if (e.target.tagName === 'IMG' || e.target === wrap) { Modal.close(wrap); } });
        wrap.addEventListener('modal:close', function () { wrap.remove(); });
    }
    document.addEventListener('click', function (e) {
        var z = e.target.closest('[data-zoom]');
        if (z) { lightbox(z.getAttribute('data-zoom'), z.getAttribute('data-zoom-alt')); }
    });

    /* 5. Daftar terfilter ================================================ */
    /*
       Struktur:
       [data-list]
         .chip[data-filter="all|status"][data-label="Menunggu"]
         input[data-search]
         [data-dropdown][data-period-select]  (opsional)
         [data-item][data-status][data-text][data-period]
         [data-empty]
    */
    function List(root) {
        this.root = root;
        this.filter = (($('.chip.is-active', root) || {}).getAttribute ? $('.chip.is-active', root).getAttribute('data-filter') : 'all') || 'all';
        this.search = $('[data-search]', root);
        this.period = $('[data-period-select]', root);
        this.hideEmptyChips = root.hasAttribute('data-hide-empty-chips');
        this.bind();
        this.update();
    }
    List.prototype.items = function () { return $$('[data-item]', this.root); };
    List.prototype.bind = function () {
        var self = this;
        $$('.chip[data-filter]', this.root).forEach(function (chip) {
            chip.addEventListener('click', function () { self.setFilter(chip.getAttribute('data-filter')); });
        });
        if (this.search) {
            var wrap = this.search.closest('.search');
            this.search.addEventListener('input', function () { self.update(); });
            var clear = wrap && $('.search-clear', wrap);
            if (clear) {
                clear.addEventListener('click', function () { self.search.value = ''; self.update(); self.search.focus(); });
            }
        }
        if (this.period) {
            this.period.addEventListener('dropdown:change', function () { self.update(); });
        }
    };
    List.prototype.setFilter = function (f) {
        this.filter = f;
        $$('.chip[data-filter]', this.root).forEach(function (c) {
            var on = c.getAttribute('data-filter') === f;
            c.classList.toggle('is-active', on);
            c.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        this.update();
    };
    List.prototype.getPeriod = function () { return this.period ? (this.period.getAttribute('data-current') || 'all') : 'all'; };
    List.prototype.update = function () {
        var q = this.search ? this.search.value.trim().toLowerCase() : '';
        var period = this.getPeriod();
        var filter = this.filter;
        var counts = { all: 0 };
        var visible = [];
        if (this.search) {
            var sw = this.search.closest('.search');
            if (sw) { sw.classList.toggle('is-filled', q.length > 0); }
        }
        this.items().forEach(function (item) {
            var text = (item.getAttribute('data-text') || item.textContent).toLowerCase();
            var okSearch = !q || text.indexOf(q) !== -1;
            var itemPeriod = item.getAttribute('data-period');
            var okPeriod = period === 'all' || !itemPeriod || itemPeriod === period;
            var tags = (item.getAttribute('data-status') || '').split(' ');
            if (okSearch && okPeriod) {
                counts.all++;
                tags.forEach(function (t) { if (t) { counts[t] = (counts[t] || 0) + 1; } });
            }
            var show = okSearch && okPeriod && (filter === 'all' || tags.indexOf(filter) !== -1);
            item.hidden = !show;
            if (show) { visible.push(item); }
        });
        var hideEmpty = this.hideEmptyChips;
        $$('.chip[data-filter]', this.root).forEach(function (chip) {
            var f = chip.getAttribute('data-filter');
            var label = chip.getAttribute('data-label');
            var n = counts[f] || 0;
            if (label && chip.hasAttribute('data-count')) { chip.textContent = label + ' (' + n + ')'; }
            if ((hideEmpty || chip.hasAttribute('data-hide-empty')) && f !== 'all') { chip.hidden = n === 0 && !chip.classList.contains('is-active'); }
        });
        var empty = $('[data-empty]', this.root);
        if (empty) { empty.hidden = visible.length > 0; }
        this.root.dispatchEvent(new CustomEvent('list:update', { detail: { visible: visible, counts: counts, query: q, period: period } }));
    };
    function initLists() {
        $$('[data-list]').forEach(function (root) { root._list = new List(root); });
    }

    /* 6. Status baris tabel ============================================== */
    var STATUS = {
        reservasi: { menunggu: ['Menunggu', 'warning'], diterima: ['Diterima', 'success'], ditolak: ['Ditolak', 'danger'] },
        penghuni: { aktif: ['Aktif', 'success'], calon: ['Calon', 'info'] },
        pembayaran: { menunggu: ['Menunggu', 'warning'], terverifikasi: ['Terverifikasi', 'success'], ditolak: ['Ditolak', 'danger'] },
        tagihan: { belum: ['Belum Bayar', 'danger'], lunas: ['Sudah Lunas', 'success'] },
        kerusakan: { belum: ['Belum Dikerjakan', 'danger'], diproses: ['Diproses', 'info'], selesai: ['Selesai', 'success'] }
    };
    function badgeHtml(group, status, labelOverride) {
        var s = (STATUS[group] || {})[status] || [status, 'neutral'];
        return '<span class="badge badge-' + s[1] + '"><span class="badge-dot"></span><span class="badge-label">' + esc(labelOverride || s[0]) + '</span></span>';
    }
    /** Ubah status satu baris: badge + aksi [data-when] + atribut */
    function setRowStatus(row, status) {
        var group = row.getAttribute('data-group');
        row.setAttribute('data-status', status);
        var cell = $('[data-badge]', row);
        if (cell) { cell.innerHTML = badgeHtml(group, status); }
        $$('[data-when]', row).forEach(function (el) {
            el.hidden = el.getAttribute('data-when').split(' ').indexOf(status) === -1;
        });
        row.classList.remove('is-highlight');
        void row.offsetWidth;
        row.classList.add('is-highlight');
        var list = row.closest('[data-list]');
        if (list && list._list) { list._list.update(); }
    }
    function syncRowActions() {
        $$('[data-item][data-status]').forEach(function (row) {
            var st = row.getAttribute('data-status');
            $$('[data-when]', row).forEach(function (el) { el.hidden = el.getAttribute('data-when').split(' ').indexOf(st) === -1; });
        });
    }

    function initProgress() {
        $$('[data-width]').forEach(function (el) {
            var w = el.getAttribute('data-width');
            requestAnimationFrame(function () { el.style.width = w + '%'; });
        });
    }

    /* Titik merah notifikasi di header mengikuti status baca */
    function syncNotifDot() {
        var dot = $('[data-notif-dot]');
        if (!dot) { return; }
        var allRead = storage('notif-all-read');
        dot.hidden = allRead === true;
    }

    /* Ekspor ke global untuk modul halaman */
    window.App = {
        $: $, $$: $$, esc: esc, rupiah: rupiah, initials: initials, tglIndo: tglIndo, svg: svg,
        toast: toast, showBanner: showBanner, Modal: Modal, confirm: confirmDialog, result: resultDialog,
        lightbox: lightbox, setRowStatus: setRowStatus, badgeHtml: badgeHtml, storage: storage, data: DATA
    };

    /* 7. Modul per halaman =============================================== */
    var Pages = {};

    /* --- 26 · Data Reservasi ------------------------------------------- */
    /** Kartu KTP contoh (dirender dari data reservasi) */
    function ktpCardHtml(r) {
        var k = r.ktp || {};
        return '<div class="idcard"><div class="idcard-head"><span>PROVINSI DKI JAKARTA</span><span>KOTA JAKARTA SELATAN</span></div>' +
            '<div class="idcard-nik">NIK : ' + esc(k.nik || '-') + '</div>' +
            '<div class="idcard-body"><dl>' +
            '<dt>Nama</dt><dd>' + esc(r.name.toUpperCase()) + '</dd>' +
            '<dt>Tempat/Tgl Lahir</dt><dd>' + esc((k.birth || '-').toUpperCase()) + '</dd>' +
            '<dt>Jenis Kelamin</dt><dd>' + esc(k.gender || '-') + '</dd>' +
            '<dt>Alamat</dt><dd>' + esc(k.address || '-') + '</dd>' +
            '<dt>Pekerjaan</dt><dd>' + esc(k.job || '-') + '</dd></dl>' +
            '<div class="idcard-photo"><span>' + esc(initials(r.name)) + '</span></div></div>' +
            '<span class="idcard-mark">CONTOH</span></div>';
    }

    Pages.reservasi = function () {
        var rows = DATA.reservations || [];
        var m = $('#modal-ktp');
        function find(id) { return rows.filter(function (r) { return r.id === id; })[0]; }
        function rowEl(id) { return $('[data-item][data-id="' + id + '"]'); }

        document.addEventListener('click', function (e) {
            var ktpBtn = e.target.closest('[data-res-ktp]');
            if (ktpBtn) { openKtp(find(ktpBtn.getAttribute('data-res-ktp'))); return; }
            var btn = e.target.closest('[data-res-action]');
            if (!btn) { return; }
            decide(find(btn.closest('[data-id]').getAttribute('data-id')), btn.getAttribute('data-res-action'));
        });

        /* Tombol Terima/Tolak ada di kolom Status. Setelah dipilih, data status
           diperbarui dan kedua tombol diganti label status (tidak muncul lagi). */
        function decide(r, action) {
            if (!r || r.status !== 'menunggu') { return; }
            var accept = action === 'terima';
            App.confirm({
                title: accept ? 'Terima Reservasi?' : 'Tolak Reservasi?',
                text: (accept ? 'Reservasi ' : 'Pengajuan ') + r.name + ' untuk ' + r.type + (accept ? ' akan diterima dan calon penghuni mendapat notifikasi.' : ' akan ditolak dan calon penghuni mendapat notifikasi.'),
                okLabel: accept ? 'Terima' : 'Tolak',
                tone: accept ? 'primary' : 'danger'
            }).then(function (ok) {
                if (!ok || r.status !== 'menunggu') { return; }
                r.status = accept ? 'diterima' : 'ditolak';
                setRowStatus(rowEl(r.id), r.status);
                toast(accept ? 'Reservasi ' + r.name + ' diterima' : 'Reservasi ' + r.name + ' ditolak', accept ? '' : 'danger');
            });
        }

        function openKtp(r) {
            $('[data-f="subtitle"]', m).textContent = r.name + ' · Diajukan ' + r.date;
            $('[data-f="name"]', m).textContent = r.name;
            $('[data-f="nik"]', m).textContent = (r.ktp && r.ktp.nik) || '-';
            $('[data-f="phone"]', m).textContent = r.phone;
            $('[data-f="type"]', m).textContent = r.type;
            $('[data-f="status"]', m).innerHTML = badgeHtml('reservasi', r.status);
            $('[data-f="media"]', m).innerHTML = ktpCardHtml(r);
            App.Modal.open(m);
        }
    };

    /* --- 27/28 · Data Penghuni ----------------------------------------- */
    Pages.penghuni = function () {
        var rows = DATA.residents || [];
        var m = $('#modal-ktp');
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-ktp]');
            if (!btn) { return; }
            var r = rows[Number(btn.getAttribute('data-ktp'))];
            fill(r);
            App.Modal.open(m);
        });
        function fill(r) {
            $('[data-f="subtitle"]', m).textContent = r.name + (r.room !== '–' ? ' · Kamar ' + r.room : ' · Calon penghuni');
            $('[data-f="name"]', m).textContent = r.name;
            $('[data-f="phone"]', m).textContent = r.phone;
            $('[data-f="room"]', m).textContent = r.room !== '–' ? r.room + ' · Tipe ' + r.typeId : 'Belum ditentukan';
            var media = $('[data-f="media"]', m);
            var dl = $('[data-f="download"]', m);
            if (r.ktp) {
                media.innerHTML = '<img src="../assets/images/' + esc(r.ktp) + '" alt="Foto KTP ' + esc(r.name) + '">';
                media.setAttribute('data-zoom', '../assets/images/' + r.ktp);
                media.classList.add('media-zoom');
                $('[data-f="status"]', m).innerHTML = '<span class="badge badge-success"><span class="badge-dot"></span>Terverifikasi</span>';
                dl.setAttribute('href', '../assets/images/' + r.ktp);
                dl.setAttribute('download', 'KTP-' + r.name.replace(/\s+/g, '-') + '.jpg');
                dl.classList.remove('is-disabled');
            } else {
                media.innerHTML = '<div class="media-placeholder">' + svg('idcard') + '<span>Dokumen belum diunggah</span></div>';
                media.removeAttribute('data-zoom');
                media.classList.remove('media-zoom');
                $('[data-f="status"]', m).innerHTML = '<span class="badge badge-warning"><span class="badge-dot"></span>Menunggu Unggahan</span>';
                dl.removeAttribute('href');
                dl.removeAttribute('download');
                dl.classList.add('is-disabled');
            }
        }
        if (m) {
            $('[data-f="download"]', m).addEventListener('click', function (e) {
                if (this.classList.contains('is-disabled')) {
                    e.preventDefault();
                    toast('Penghuni belum mengunggah dokumen identitas', 'info');
                } else {
                    toast('Dokumen sedang diunduh');
                }
            });
        }
    };

    /* --- 29/30 · Monitor Kamar ----------------------------------------- */
    Pages.kamar = function () {
        var types = DATA.roomTypes || [];
        var m = $('#modal-kamar');
        var select = $('#kelola-tipe', m);
        var total = $('#kelola-total', m);
        var filled = $('#kelola-terisi', m);
        var info = $('[data-f="info"]', m);

        function current() { return types[Number(select.value)]; }
        function clamp() {
            var t = Math.max(1, Math.min(99, parseInt(total.value, 10) || 1));
            var f = Math.max(0, Math.min(t, parseInt(filled.value, 10) || 0));
            total.value = t;
            filled.value = f;
            var empty = t - f;
            info.innerHTML = svg('info') + '<span>Kamar kosong otomatis dihitung: ' + empty + ' kamar · status ' + (empty > 0 ? 'Tersedia' : 'Penuh') + '</span>';
            $$('[data-step]', m).forEach(function (b) {
                var input = document.getElementById(b.getAttribute('data-target'));
                var v = parseInt(input.value, 10);
                var dir = Number(b.getAttribute('data-step'));
                var max = input === filled ? t : 99;
                var min = input === total ? Math.max(1, f) : 0;
                b.disabled = dir > 0 ? v >= max : v <= min;
            });
        }
        function load(i) {
            select.value = String(i);
            total.value = types[i].total;
            filled.value = types[i].filled;
            clamp();
        }
        select.addEventListener('change', function () { load(Number(select.value)); });
        [total, filled].forEach(function (inp) { inp.addEventListener('input', clamp); inp.addEventListener('blur', clamp); });
        $$('[data-step]', m).forEach(function (b) {
            b.addEventListener('click', function () {
                var input = document.getElementById(b.getAttribute('data-target'));
                input.value = (parseInt(input.value, 10) || 0) + Number(b.getAttribute('data-step'));
                if (input === total && Number(total.value) < Number(filled.value)) { total.value = filled.value; }
                clamp();
            });
        });
        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-kelola]');
            if (!b) { return; }
            load(Number(b.getAttribute('data-kelola')));
            App.Modal.open(m);
        });
        $('[data-f="save"]', m).addEventListener('click', function () {
            clamp();
            var i = Number(select.value);
            types[i].total = Number(total.value);
            types[i].filled = Number(filled.value);
            render(i);
            App.Modal.close(m);
            toast('Ketersediaan ' + types[i].name + ' berhasil diperbarui');
        });
        function render(changed) {
            types.forEach(function (t, i) {
                var row = $('[data-room-row="' + i + '"]');
                if (!row) { return; }
                var empty = t.total - t.filled;
                $('[data-c="total"]', row).textContent = t.total;
                $('[data-c="filled"]', row).textContent = t.filled;
                $('[data-c="empty"]', row).textContent = empty;
                $('[data-c="status"]', row).innerHTML = empty > 0 ?
                    '<span class="badge badge-success"><span class="badge-dot"></span>Tersedia · sisa ' + empty + '</span>' :
                    '<span class="badge badge-danger"><span class="badge-dot"></span>Penuh</span>';
                if (i === changed) { row.classList.remove('is-highlight'); void row.offsetWidth; row.classList.add('is-highlight'); }
            });
            var sumT = types.reduce(function (a, t) { return a + t.total; }, 0);
            var sumF = types.reduce(function (a, t) { return a + t.filled; }, 0);
            $('[data-stat="total"]').textContent = sumT;
            $('[data-stat="filled"]').textContent = sumF;
            $('[data-stat="empty"]').textContent = sumT - sumF;
        }
        m.addEventListener('modal:open', clamp);
        load(Number(m.getAttribute('data-initial') || 3));
    };

    /* --- Detail pembayaran (dipakai Verifikasi Pembayaran & Tagihan) ----- */
    function receiptHtml(p) {
        return '<div class="receipt"><div class="receipt-brand"><span>m-BCA</span></div>' +
            '<div class="receipt-status">TRANSFER BERHASIL</div>' +
            '<dl><dt>Tanggal &amp; Waktu</dt><dd>' + esc(String(p.time).replace(' · ', ' ').toUpperCase()) + ' WIB</dd>' +
            '<dt>Jenis Transaksi</dt><dd>TRANSFER ANTAR REKENING</dd>' +
            '<dt>Nama Pengirim</dt><dd>' + esc(p.name.toUpperCase()) + '</dd>' +
            '<dt>Rekening Tujuan / Penerima</dt><dd>PENGELOLA KOST - BU NINING</dd>' +
            '<dt>Jumlah / Nominal Transfer</dt><dd>' + esc(rupiah(p.amount)) + ',00</dd>' +
            '<dt>Nomor Referensi</dt><dd>' + esc(p.ref) + '</dd></dl></div>';
    }
    function payTypeHtml(pay) {
        return pay === 'dp' ? '<span class="pill pill-dp">Down Payment</span>' : '<span class="pill pill-full">Lunas (Full)</span>';
    }
    /** Isi & buka modal #modal-bukti dengan data pembayaran p */
    function openPaymentDetail(m, p) {
        $('[data-f="subtitle"]', m).textContent = p.name + ' · Kamar ' + p.room + ' · ' + p.period;
        $('[data-f="period"]', m).textContent = p.period;
        $('[data-f="bill"]', m).textContent = rupiah(p.bill || p.amount);
        $('[data-f="paid"]', m).textContent = rupiah(p.amount);
        $('[data-f="paytype"]', m).innerHTML = payTypeHtml(p.pay);
        $('[data-f="method"]', m).textContent = p.method;
        $('[data-f="time"]', m).textContent = p.time;
        var st = $('[data-f="status"]', m);
        if (p.status === 'menunggu') { st.innerHTML = badgeHtml('pembayaran', 'menunggu'); }
        else if (p.status === 'ditolak') { st.innerHTML = badgeHtml('pembayaran', 'ditolak'); }
        else { st.innerHTML = '<span class="badge badge-success"><span class="badge-dot"></span>Selesai</span>'; }
        var media = $('[data-f="media"]', m);
        if (p.proof) {
            media.innerHTML = '<img src="../assets/images/' + esc(p.proof) + '" alt="Bukti transfer ' + esc(p.name) + '">';
            media.setAttribute('data-zoom', '../assets/images/' + p.proof);
        } else {
            media.innerHTML = receiptHtml(p);
            media.removeAttribute('data-zoom');
        }
        // Alasan penolakan tampil kembali di detail bila pembayaran ditolak
        var rejected = p.status === 'ditolak';
        $('[data-f="match"]', m).hidden = rejected;
        $('[data-f="reason-box"]', m).hidden = !rejected;
        if (rejected) {
            $('[data-f="reason"]', m).textContent = p.reason || '-';
            $('[data-f="reason-meta"]', m).textContent = p.rejectedAt ? 'Ditolak ' + p.rejectedAt + ' · Alasan ini ditampilkan kepada penghuni' : 'Alasan ini ditampilkan kepada penghuni';
        }
        var actions = $('[data-f="actions"]', m);
        if (actions) { actions.hidden = p.status !== 'menunggu'; }
        m.setAttribute('data-current', p.id || '');
        App.Modal.open(m);
    }
    function nowLabel() {
        var d = new Date();
        return ('0' + d.getDate()).slice(-2) + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear() + ' · ' +
            ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2);
    }

    /* --- 31–33B · Verifikasi Pembayaran -------------------------------- */
    Pages.verifikasi = function () {
        var rows = DATA.payments || [];
        var m = $('#modal-bukti');
        var mt = $('#modal-tolak');
        var form = $('#form-tolak');
        var reason = $('#tolak-alasan');
        var err = $('#tolak-error');
        function find(id) { return rows.filter(function (r) { return r.id === id; })[0]; }
        function open(p) { openPaymentDetail(m, p); }

        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-proof]');
            if (b) { open(find(b.getAttribute('data-proof'))); }
        });

        $('[data-f="confirm"]', m).addEventListener('click', function () {
            var p = find(m.getAttribute('data-current'));
            p.status = 'terverifikasi';
            App.Modal.close(m);
            setRowStatus($('[data-item][data-id="' + p.id + '"]'), 'terverifikasi');
            showBanner($('[data-banner-slot]'), 'success', 'Pembayaran ' + p.name + ' untuk ' + p.period + ' berhasil dikonfirmasi');
            toast('Pembayaran terkonfirmasi');
        });

        /* Tolak => wajib isi alasan di modal "Alasan Penolakan Pembayaran" */
        function setError(show) {
            err.hidden = !show;
            reason.classList.toggle('is-invalid', show);
            reason.setAttribute('aria-invalid', show ? 'true' : 'false');
        }
        $('[data-f="reject"]', m).addEventListener('click', function () {
            var p = find(m.getAttribute('data-current'));
            mt.setAttribute('data-current', p.id);
            $('[data-f="subtitle"]', mt).textContent = p.name + ' · Kamar ' + p.room + ' · ' + p.period;
            reason.value = '';
            setError(false);
            $$('.reason-option', mt).forEach(function (o) { o.classList.remove('is-active'); });
            App.Modal.open(mt);
            setTimeout(function () { reason.focus(); }, 60);
        });
        $$('[data-reason]', mt).forEach(function (o) {
            o.addEventListener('click', function () {
                $$('.reason-option', mt).forEach(function (x) { x.classList.toggle('is-active', x === o); });
                reason.value = o.textContent.trim();
                setError(false);
                reason.focus();
            });
        });
        reason.addEventListener('input', function () {
            if (reason.value.trim()) { setError(false); }
            $$('.reason-option', mt).forEach(function (x) { x.classList.toggle('is-active', x.textContent.trim() === reason.value.trim()); });
        });
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var text = reason.value.trim();
            if (!text) {
                setError(true);
                reason.focus();
                return;
            }
            var p = find(mt.getAttribute('data-current'));
            p.status = 'ditolak';
            p.reason = text;
            p.rejectedAt = nowLabel();
            App.Modal.close(mt);
            App.Modal.close(m);
            setRowStatus($('[data-item][data-id="' + p.id + '"]'), 'ditolak');
            showBanner($('[data-banner-slot]'), 'danger', 'Pembayaran ' + p.name + ' untuk ' + p.period + ' ditolak. Alasan: ' + text);
            toast('Pembayaran ditolak, alasan dikirim ke penghuni', 'danger');
        });

        var auto = m.getAttribute('data-open-id');
        if (auto) { open(find(auto)); }
    };

    /* --- 34–36 · Tagihan & Riwayat ------------------------------------- */
    Pages.tagihan = function () {
        var list = $('[data-list]');
        var banner = $('[data-banner-slot]');
        list.addEventListener('list:update', function (e) {
            var d = e.detail;
            var names = {};
            d.visible.forEach(function (r) { names[r.getAttribute('data-name')] = r; });
            var keys = Object.keys(names);
            // Banner info muncul saat pencarian mengerucut pada satu penghuni
            var all = $$('[data-item]', list).filter(function (r) {
                var p = d.period;
                return (p === 'all' || r.getAttribute('data-period') === p) && r.getAttribute('data-text').indexOf(d.query) !== -1;
            });
            var people = {};
            all.forEach(function (r) { people[r.getAttribute('data-name')] = r; });
            var pk = Object.keys(people);
            if (d.query && pk.length === 1) {
                var r = people[pk[0]];
                banner.innerHTML = '<div class="banner banner-info banner-tight"><div class="banner-body">' + svg('info') +
                    '<span>Menampilkan ' + all.length + ' tagihan milik ' + esc(pk[0]) + ' · Kamar ' + esc(r.getAttribute('data-room')) + ' · ' + esc(r.getAttribute('data-type')) +
                    '</span></div><button type="button" class="btn btn-ghost btn-sm" data-reset-filter>Reset Filter</button></div>';
            } else {
                banner.innerHTML = '';
            }
            return keys;
        });
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-reset-filter]')) {
                var s = $('[data-search]', list);
                s.value = '';
                var pd = $('[data-period-select]', list);
                var opt = $('[data-value="2026-10"]', pd);
                if (opt) { opt.click(); }
                list._list.setFilter('all');
                return;
            }
            var tagih = e.target.closest('[data-tagih]');
            if (tagih) {
                var row = tagih.closest('[data-item]');
                App.result({
                    title: 'Pengingat Terkirim!',
                    text: 'Notifikasi pengingat tagihan ' + row.getAttribute('data-period-label') + ' sebesar ' + row.getAttribute('data-amount') + ' berhasil dikirim ke akun ' + row.getAttribute('data-name') + ' (Kamar ' + row.getAttribute('data-room') + ').'
                });
                return;
            }
            if (e.target.closest('[data-massal]')) {
                var unpaid = $$('[data-item][data-status="belum"]', list);
                var period = (list._list.getPeriod() === 'all') ? 'semua periode' : $('[data-dropdown-label]', list).textContent;
                if (!unpaid.length) {
                    toast('Tidak ada tagihan yang belum dibayar', 'info');
                    return;
                }
                App.confirm({
                    title: 'Kirim Pengingat Massal?',
                    text: 'Pengingat tagihan akan dikirim ke ' + unpaid.length + ' penghuni yang belum membayar (' + period + ').',
                    okLabel: 'Kirim'
                }).then(function (ok) {
                    if (!ok) { return; }
                    App.result({
                        title: 'Pengingat Terkirim!',
                        text: 'Notifikasi pengingat tagihan berhasil dikirim ke ' + unpaid.map(function (r) { return r.getAttribute('data-name') + ' (Kamar ' + r.getAttribute('data-room') + ')'; }).join(', ') + '.'
                    });
                });
                return;
            }
            var det = e.target.closest('[data-bill-detail]');
            if (det) {
                var rw = det.closest('[data-item]');
                openPaymentDetail($('#modal-bukti'), {
                    id: '',
                    name: rw.getAttribute('data-name'),
                    room: rw.getAttribute('data-room'),
                    period: rw.getAttribute('data-period-label'),
                    amount: Number(rw.getAttribute('data-amount-value')),
                    pay: rw.getAttribute('data-pay'),
                    method: rw.getAttribute('data-method') || 'Transfer Bank BCA',
                    time: rw.getAttribute('data-time'),
                    ref: rw.getAttribute('data-ref'),
                    proof: rw.getAttribute('data-proof-img') || '',
                    status: 'terverifikasi'
                });
            }
        });
        // Klik nama penghuni => tampilkan riwayat penghuni tsb (halaman 36)
        document.addEventListener('click', function (e) {
            var n = e.target.closest('[data-person-filter]');
            if (!n) { return; }
            var s = $('[data-search]', list);
            s.value = n.getAttribute('data-person-filter');
            var opt = $('[data-period-select] [data-value="all"]', list);
            if (opt) { opt.click(); } else { list._list.update(); }
        });
        list._list.update();
    };

    /* --- 37–39b · Laporan Kerusakan ------------------------------------ */
    Pages.kerusakan = function () {
        var rows = DATA.reports || [];
        var mEdit = $('#modal-kerusakan');
        var mView = $('#modal-kerusakan-view');
        var chosen = null;
        function find(id) { return rows.filter(function (r) { return r.id === id; })[0]; }
        function mediaHtml(r) {
            return r.photo ? '<img src="../assets/images/' + esc(r.photo) + '" alt="Foto ' + esc(r.title) + '">' :
                '<div class="media-placeholder">' + svg('image') + '<span>Penghuni tidak melampirkan foto</span></div>';
        }
        function fillCommon(m, r) {
            $('[data-f="subtitle"]', m).textContent = 'Kamar ' + r.room + ' · ' + r.name + ' · ' + r.date;
            $('[data-f="category"]', m).textContent = r.category;
            $('[data-f="desc"]', m).textContent = r.desc;
            var media = $('[data-f="media"]', m);
            media.innerHTML = mediaHtml(r);
            if (r.photo) { media.setAttribute('data-zoom', '../assets/images/' + r.photo); media.classList.add('media-zoom'); }
            else { media.removeAttribute('data-zoom'); media.classList.remove('media-zoom'); }
        }
        function setSegment(val) {
            chosen = val;
            $$('.segmented button', mEdit).forEach(function (b) {
                var on = b.getAttribute('data-value') === val;
                b.classList.toggle('is-active', on);
                b.setAttribute('aria-checked', on ? 'true' : 'false');
            });
        }
        function open(r, preset) {
            if (r.status === 'selesai') {
                fillCommon(mView, r);
                $('[data-f="status"]', mView).innerHTML = '<span class="badge badge-success"><span class="badge-dot"></span>Sudah Dikerjakan</span>';
                $('[data-f="note"]', mView).textContent = r.note || '-';
                App.Modal.open(mView);
                return;
            }
            fillCommon(mEdit, r);
            $('[data-f="status"]', mEdit).innerHTML = badgeHtml('kerusakan', r.status);
            setSegment(preset ? preset.status : r.status);
            $('[data-f="note"]', mEdit).value = preset ? preset.note : (r.note || '');
            mEdit.setAttribute('data-current', r.id);
            App.Modal.open(mEdit);
        }
        $$('.segmented button', mEdit).forEach(function (b) {
            b.addEventListener('click', function () { setSegment(b.getAttribute('data-value')); });
        });
        $('[data-f="save"]', mEdit).addEventListener('click', function () {
            var r = find(mEdit.getAttribute('data-current'));
            var before = r.status;
            r.status = chosen;
            r.note = $('[data-f="note"]', mEdit).value.trim();
            App.Modal.close(mEdit);
            setRowStatus($('[data-item][data-id="' + r.id + '"]'), r.status);
            var label = { belum: 'Belum Dikerjakan', diproses: 'Diproses', selesai: 'Selesai' }[r.status];
            if (before === r.status) {
                toast('Catatan untuk ' + r.name + ' disimpan');
            } else {
                showBanner($('[data-banner-slot]'), 'success', 'Status laporan "' + r.title + '" (Kamar ' + r.room + ') berhasil diubah menjadi ' + label);
            }
            refreshAlert();
        });
        function refreshAlert() {
            var alert = $('[data-alert-new]');
            var n = rows.filter(function (r) { return r.status === 'belum'; }).length;
            if (!alert) { return; }
            if (n === 0) { alert.hidden = true; return; }
            $('[data-alert-count]', alert).textContent = n + ' laporan baru belum dikerjakan';
        }
        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-report]');
            if (b) { open(find(b.getAttribute('data-report'))); return; }
            if (e.target.closest('[data-show-new]')) {
                var list = $('[data-list]');
                list._list.setFilter('belum');
                list.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
        var auto = document.body.getAttribute('data-open-report');
        if (auto) {
            var r = find(auto);
            var preset = auto && document.body.getAttribute('data-preset-status') ? { status: document.body.getAttribute('data-preset-status'), note: document.body.getAttribute('data-preset-note') || '' } : null;
            open(r, preset);
        }
    };

    /* --- 40–40C · Laporan Keuangan ------------------------------------- */
    Pages.keuangan = function () {
        var D = DATA.finance;
        var periodKey = '2026-10';
        var tbody = $('#expense-body');
        var form = $('#form-pengeluaran');
        var modal = $('#modal-pengeluaran');
        var CAT = { rutin: ['Operasional Rutin', 'neutral'], perbaikan: ['Perbaikan Fasilitas', 'amber'] };

        function cur() { return D.periods[periodKey]; }
        function pad(n) { return ('0' + n).slice(-2); }
        /** Waktu pencatatan sekarang (format lokal YYYY-MM-DDTHH:MM:SS agar bisa diurutkan) */
        function isoNow() {
            var d = new Date();
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
        }
        function createdLabel(c) {
            var parts = String(c || '').split('T');
            return tglIndo(parts[0]) + ', ' + (parts[1] || '').slice(0, 5);
        }
        function render() {
            var p = cur();
            // Urutkan berdasarkan waktu pencatatan: yang terakhir dimasukkan tampil paling atas
            p.expenses.sort(function (a, b) { return a.created < b.created ? 1 : (a.created > b.created ? -1 : 0); });
            var total = p.expenses.reduce(function (s, x) { return s + Number(x.amount); }, 0);
            tbody.innerHTML = p.expenses.length ? p.expenses.map(function (x) {
                var c = CAT[x.category] || CAT.rutin;
                return '<tr' + (x.isNew ? ' class="is-highlight"' : '') + '><td class="nowrap"><p class="cell-main">' + tglIndo(x.date) + '</p><p class="cell-sub">Dicatat ' + createdLabel(x.created) + '</p></td>' +
                    '<td><span class="badge badge-' + c[1] + '"><span class="badge-dot"></span>' + c[0] + '</span></td>' +
                    '<td>' + esc(x.desc) + '</td><td class="ta-right nowrap">' + rupiah(x.amount) + '</td></tr>';
            }).join('') : '<tr class="table-empty"><td colspan="4">Belum ada pengeluaran pada periode ini.</td></tr>';
            p.expenses.forEach(function (x) { delete x.isNew; });
            $('#expense-total').textContent = rupiah(total);
            $('[data-fin="gross"]').textContent = rupiah(p.gross);
            $('[data-fin="gross-note"]').textContent = 'Dari ' + p.filled + ' kamar terisi';
            $('[data-fin="expense"]').textContent = rupiah(total);
            $('[data-fin="expense-note"]').textContent = p.expenses.length + ' transaksi operasional';
            $('[data-fin="net"]').textContent = rupiah(p.gross - total);
        }

        $('[data-period-select]').addEventListener('dropdown:change', function (e) {
            periodKey = e.detail.value;
            render();
        });

        // Unduh laporan sebagai CSV
        $('[data-download-report]').addEventListener('click', function () {
            var p = cur();
            var total = p.expenses.reduce(function (s, x) { return s + Number(x.amount); }, 0);
            var lines = [['Laporan Keuangan Kos Bu Nining', p.label], [], ['Pendapatan Kotor', p.gross], ['Total Pengeluaran', total], ['Pendapatan Bersih', p.gross - total], [], ['Tanggal', 'Kategori', 'Keterangan', 'Nominal']];
            p.expenses.forEach(function (x) { lines.push([tglIndo(x.date), CAT[x.category][0], x.desc, x.amount]); });
            lines.push(['Total', '', '', total]);
            var csv = lines.map(function (l) { return l.map(function (v) { return '"' + String(v).replace(/"/g, '""') + '"'; }).join(';'); }).join('\r\n');
            var blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'laporan-keuangan-' + periodKey + '.csv';
            document.body.appendChild(a);
            a.click();
            setTimeout(function () { URL.revokeObjectURL(a.href); a.remove(); }, 500);
            toast('Laporan ' + p.label + ' berhasil diunduh');
        });

        // Format nominal saat mengetik
        var amount = $('#exp-amount', form);
        amount.addEventListener('input', function () {
            var digits = amount.value.replace(/\D/g, '').slice(0, 12);
            amount.value = digits ? Number(digits).toLocaleString('id-ID') : '';
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var ok = true;
            $$('[data-required]', form).forEach(function (f) {
                var bad = !f.value.trim() || (f === amount && !Number(amount.value.replace(/\D/g, '')));
                f.classList.toggle('is-invalid', bad);
                var err = f.closest('.field').querySelector('.field-error');
                if (err) { err.hidden = !bad; }
                if (bad) { ok = false; }
            });
            if (!ok) { return; }
            var date = $('#exp-date', form).value;
            var key = date.slice(0, 7);
            if (!D.periods[key]) {
                toast('Periode ' + tglIndo(date).slice(3) + ' belum tersedia di laporan', 'danger');
                return;
            }
            var item = { date: date, category: $('#exp-category', form).value, desc: $('#exp-desc', form).value.trim(), amount: Number(amount.value.replace(/\D/g, '')), created: isoNow(), isNew: true };
            D.periods[key].expenses.push(item);
            if (key !== periodKey) {
                var opt = $('[data-period-select] [data-value="' + key + '"]');
                if (opt) { opt.click(); }
            }
            render();
            App.Modal.close(modal);
            toast('Pengeluaran "' + item.desc + '" berhasil ditambahkan');
        });
        modal.addEventListener('modal:open', function () {
            $$('.is-invalid', form).forEach(function (f) { f.classList.remove('is-invalid'); });
            $$('.field-error', form).forEach(function (f) { f.hidden = true; });
        });
        modal.addEventListener('modal:close', function () {
            if (!modal.hasAttribute('data-keep')) { form.reset(); $('#exp-date', form).value = '2026-10-01'; }
            modal.removeAttribute('data-keep');
        });
        $('[data-new-expense]').addEventListener('click', function () {
            form.reset();
            $('#exp-date', form).value = periodKey === '2026-10' ? '2026-10-01' : periodKey + '-01';
            App.Modal.open(modal);
        });
        render();
    };

    /* --- 41 · Notifikasi ----------------------------------------------- */
    Pages.notifikasi = function () {
        var list = $('[data-list]');
        function refresh() {
            var unread = $$('.notif-item.is-unread', list).length;
            $$('[data-item]', list).forEach(function (it) {
                var tags = it.getAttribute('data-status').replace(/\s*unread/, '');
                it.setAttribute('data-status', tags + (it.classList.contains('is-unread') ? ' unread' : ''));
            });
            App.storage('notif-all-read', unread === 0);
            var dot = $('[data-notif-dot]');
            if (dot) { dot.hidden = unread === 0; }
            $('[data-mark-all]').disabled = unread === 0;
            list._list.update();
        }
        if (App.storage('notif-all-read') === true) {
            $$('.notif-item', list).forEach(function (it) { it.classList.remove('is-unread'); });
        }
        $('[data-mark-all]').addEventListener('click', function () {
            $$('.notif-item.is-unread', list).forEach(function (it) { it.classList.remove('is-unread'); });
            refresh();
            toast('Semua notifikasi ditandai sudah dibaca');
        });
        list.addEventListener('click', function (e) {
            var it = e.target.closest('.notif-item');
            if (!it) { return; }
            it.classList.remove('is-unread');
            refresh();
            window.location.href = it.getAttribute('data-href');
        });
        list.addEventListener('keydown', function (e) {
            if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('notif-item')) { e.preventDefault(); e.target.click(); }
        });
        refresh();
    };

    /* --- 42–44 · Kebijakan & Aturan ------------------------------------ */
    Pages.kebijakan = function () {
        var root = $('#rules');
        var viewList = $('#rules-view');
        var editList = $('#rules-edit');
        var banner = $('[data-banner-slot]');
        var rules = (DATA.rules || []).slice();
        var mode = root.getAttribute('data-mode');

        function bannerInfo() {
            banner.innerHTML = '<div class="banner banner-info banner-tight"><div class="banner-body">' + svg('info') +
                '<span>Aturan ini tampil di halaman Tentang dan halaman Kebijakan penghuni · Terakhir diperbarui ' + esc(DATA.rulesUpdated) + '</span></div></div>';
        }
        function bannerEdit() {
            banner.innerHTML = '<div class="banner banner-warning banner-tight"><div class="banner-body">' +
                '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>' +
                '<span>Mode edit aktif · perubahan baru berlaku setelah kamu klik Simpan Perubahan</span></div></div>';
        }
        function renderView() {
            viewList.innerHTML = rules.map(function (r, i) {
                return '<div class="rule"><span class="rule-num">' + (i + 1) + '</span><div class="rule-body"><p class="rule-title">' + esc(r.title) +
                    '</p><p class="rule-text">' + esc(r.text) + '</p></div></div>';
            }).join('');
            $('[data-rule-count]').textContent = rules.length + ' aturan';
        }
        function editRowHtml(r, i) {
            return '<div class="rule rule-edit" draggable="true" data-rule>' +
                '<span class="rule-grip" title="Geser untuk mengurutkan"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/></svg></span>' +
                '<span class="rule-num">' + (i + 1) + '</span>' +
                '<div class="rule-fields"><input class="input" type="text" value="' + esc(r.title) + '" placeholder="Judul aturan" aria-label="Judul aturan ' + (i + 1) + '" data-rule-title>' +
                '<textarea class="textarea" rows="1" placeholder="Deskripsi aturan" aria-label="Deskripsi aturan ' + (i + 1) + '" data-rule-text>' + esc(r.text) + '</textarea></div>' +
                '<button type="button" class="rule-delete" aria-label="Hapus aturan ' + (i + 1) + '" data-rule-delete><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></div>';
        }
        function renderEdit(list) {
            $('[data-rule-rows]', editList).innerHTML = list.map(editRowHtml).join('');
            renumber();
        }
        function collect() {
            return $$('[data-rule]', editList).map(function (row) {
                return { title: $('[data-rule-title]', row).value.trim(), text: $('[data-rule-text]', row).value.trim() };
            });
        }
        function renumber() {
            var rows = $$('[data-rule]', editList);
            rows.forEach(function (row, i) { $('.rule-num', row).textContent = i + 1; });
            $('[data-rule-count]').textContent = rows.length + ' aturan';
        }
        function setMode(m, opts) {
            mode = m;
            root.setAttribute('data-mode', m);
            viewList.hidden = m !== 'view';
            editList.hidden = m !== 'edit';
            $('[data-actions-view]').hidden = m !== 'view';
            $('[data-actions-edit]').hidden = m !== 'edit';
            if (m === 'edit') {
                renderEdit(opts && opts.draft ? opts.draft : rules);
                bannerEdit();
            } else {
                renderView();
                if (opts && opts.saved) {
                    showBanner(banner, 'success', 'Perubahan kebijakan berhasil disimpan dan sudah tampil untuk penghuni');
                } else {
                    bannerInfo();
                }
            }
        }
        $('[data-rules-edit]').addEventListener('click', function () { setMode('edit'); });
        $('[data-rules-cancel]').addEventListener('click', function () {
            App.confirm({ title: 'Batalkan Perubahan?', text: 'Perubahan yang belum disimpan akan hilang.', okLabel: 'Ya, Batalkan', tone: 'danger', cancelLabel: 'Lanjut Edit' })
                .then(function (ok) { if (ok) { setMode('view'); } });
        });
        $('[data-rules-save]').addEventListener('click', function () {
            var data = collect();
            var bad = false;
            $$('[data-rule]', editList).forEach(function (row) {
                var t = $('[data-rule-title]', row);
                var x = $('[data-rule-text]', row);
                t.classList.toggle('is-invalid', !t.value.trim());
                x.classList.toggle('is-invalid', !x.value.trim());
                if (!t.value.trim() || !x.value.trim()) { bad = true; }
            });
            if (bad) { toast('Lengkapi judul dan deskripsi setiap aturan', 'danger'); return; }
            if (!data.length) { toast('Minimal harus ada 1 aturan', 'danger'); return; }
            rules = data;
            var d = new Date();
            DATA.rulesUpdated = ('0' + d.getDate()).slice(-2) + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();
            setMode('view', { saved: true });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        editList.addEventListener('click', function (e) {
            var del = e.target.closest('[data-rule-delete]');
            if (del) {
                var row = del.closest('[data-rule]');
                var title = $('[data-rule-title]', row).value || 'aturan ini';
                App.confirm({ title: 'Hapus Aturan?', text: '"' + title + '" akan dihapus dari daftar tata tertib.', okLabel: 'Hapus', tone: 'danger' })
                    .then(function (ok) { if (ok) { row.remove(); renumber(); } });
                return;
            }
            if (e.target.closest('[data-rule-add]')) {
                var wrap = $('[data-rule-rows]', editList);
                var tmp = document.createElement('div');
                tmp.innerHTML = editRowHtml({ title: '', text: '' }, wrap.children.length);
                var row = tmp.firstChild;
                wrap.appendChild(row);
                renumber();
                $('[data-rule-title]', row).focus();
            }
        });
        // Drag & drop untuk mengurutkan
        var dragging = null;
        editList.addEventListener('dragstart', function (e) {
            var row = e.target.closest && e.target.closest('[data-rule]');
            if (!row || ['INPUT', 'TEXTAREA'].indexOf(document.activeElement.tagName) !== -1 && row.contains(document.activeElement)) { return; }
            dragging = row;
            row.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
            try { e.dataTransfer.setData('text/plain', ''); } catch (err) { /* abaikan */ }
        });
        editList.addEventListener('dragover', function (e) {
            if (!dragging) { return; }
            e.preventDefault();
            var over = e.target.closest('[data-rule]');
            $$('.is-drop-target', editList).forEach(function (x) { x.classList.remove('is-drop-target'); });
            if (over && over !== dragging) {
                over.classList.add('is-drop-target');
                var rect = over.getBoundingClientRect();
                var after = e.clientY > rect.top + rect.height / 2;
                over.parentNode.insertBefore(dragging, after ? over.nextSibling : over);
            }
        });
        editList.addEventListener('dragend', function () {
            if (dragging) { dragging.classList.remove('is-dragging'); }
            $$('.is-drop-target', editList).forEach(function (x) { x.classList.remove('is-drop-target'); });
            dragging = null;
            renumber();
        });

        if (mode === 'edit') {
            setMode('edit', { draft: rules.concat(DATA.ruleDraft ? [DATA.ruleDraft] : []) });
        } else if (mode === 'saved') {
            rules = rules.concat(DATA.ruleDraft ? [DATA.ruleDraft] : []);
            setMode('view', { saved: true });
        } else {
            setMode('view');
        }
    };

    /* Init ============================================================== */
    document.addEventListener('DOMContentLoaded', function () {
        initSidebar();
        initDropdowns();
        initLists();
        syncRowActions();
        initProgress();
        syncNotifDot();
        var mod = document.body.getAttribute('data-module');
        if (mod && Pages[mod]) { Pages[mod](); }
        initModals();
    });
})();
