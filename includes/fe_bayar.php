<?php
function fe_form_bayar(array $p, float $terbayar, string $kembali, array $app): void
{
    $total = (float) $p['total_tagihan'];
    $sisa = max($total - $terbayar, 0);
    $dp = round($total * PERSEN_DP / 100);
    $pelunasan = $terbayar > 0;
    ?>
    <form id="paymentForm" class="form-bayar" action="../reservasi/proses_bayar.php" method="post" enctype="multipart/form-data" data-total="<?= (int) $total ?>" data-dp="<?= (int) $dp ?>" data-sisa="<?= (int) $sisa ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id_pemesanan" value="<?= (int) $p['id_pemesanan'] ?>">
      <input type="hidden" name="kembali" value="<?= e($kembali) ?>">
      <fieldset class="grup-bayar"><legend>Jenis pembayaran</legend>
        <?php if ($pelunasan): ?>
          <label class="opsi-bayar aktif"><input type="radio" name="jenis" value="pelunasan" checked data-nominal="<?= (int) $sisa ?>"><span><strong>Pelunasan</strong><small>Sisa tagihan <?= e(rupiah($sisa)) ?> (sudah dibayar <?= e(rupiah($terbayar)) ?>)</small></span></label>
        <?php else: ?>
          <label class="opsi-bayar aktif"><input type="radio" name="jenis" value="penuh" checked data-nominal="<?= (int) $total ?>"><span><strong>Bayar Penuh</strong><small><?= e(rupiah($total)) ?> — langsung lunas</small></span></label>
          <label class="opsi-bayar"><input type="radio" name="jenis" value="dp" data-nominal="<?= (int) $dp ?>"><span><strong>DP <?= PERSEN_DP ?>%</strong><small><?= e(rupiah($dp)) ?> sekarang, sisa <?= e(rupiah($total - $dp)) ?> dibayar kemudian lewat menu Tagihan</small></span></label>
        <?php endif; ?>
      </fieldset>
      <fieldset class="grup-bayar"><legend>Metode pembayaran</legend>
        <label class="opsi-bayar aktif"><input type="radio" name="metode" value="transfer" checked><span><strong>Transfer Bank</strong><small>Unggah bukti transfer</small></span></label>
        <label class="opsi-bayar"><input type="radio" name="metode" value="tunai"><span><strong>Tunai</strong><small>Bayar langsung ke pemilik, unggah foto kwitansi</small></span></label>
      </fieldset>
      <div class="nominal-bayar">Yang dibayar sekarang: <strong id="nominalBayar"><?= e(rupiah($pelunasan ? $sisa : $total)) ?></strong></div>
      <div class="rekening-box" data-metode="transfer">
        <div class="rekening-header"><strong>Rekening Pembayaran</strong><span>Kos Bu Nining</span></div>
        <div class="rekening-detail"><div class="rekening-logo"><?= e($app['bank']) ?></div>
          <div class="rekening-nomor"><strong id="nomorRekening"><?= e($app['no_rekening']) ?></strong><small>a.n. <?= e($app['atas_nama']) ?></small></div>
          <button type="button" class="btn btn-outline btn-sm" id="copyAccount">Salin</button></div>
        <p class="catatan">Transfer sesuai nominal di atas ke rekening a.n. <strong><?= e($app['atas_nama']) ?></strong>.</p>
      </div>
      <div class="info-tunai" data-metode="tunai" hidden>
        <p><strong>Pembayaran tunai:</strong> serahkan uang langsung kepada <?= e($app['atas_nama']) ?> di kos, minta <b>kwitansi</b>, lalu foto kwitansi tersebut dan unggah di bawah. Pemilik akan mencocokkannya sebelum menyetujui.</p>
      </div>
      <div class="form-group">
        <label class="form-label" for="paymentProof" id="labelBukti">Bukti transfer</label>
        <input type="file" id="paymentProof" name="bukti_transfer" accept=".jpg,.jpeg,.png" required>
        <small class="catatan" id="catatanBukti">Format JPG/PNG, maks. 5 MB.</small>
        <img id="previewBukti" class="preview-bukti" alt="Pratinjau" hidden>
      </div>
      <button type="submit" class="btn btn-primary btn-full btn-lg" id="submitPayment" disabled>Kirim Bukti Transfer</button>
    </form>
    <?php
}
