<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
  <h1><i class="fa fa-database"></i> Backup & Restore</h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Backup & Restore</li>
  </ol>
</section>

<section class="content">

  <div class="row">

    <!-- ===== KOLOM KIRI: Buat Backup ===== -->
    <div class="col-md-6">

      <!-- Backup Database -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-database"></i> Backup Database</h3>
        </div>
        <div class="box-body">
          <p class="text-muted">Export seluruh struktur dan data database ke file <code>.sql</code>.</p>
          <button class="btn btn-primary btn-flat" id="btn-backup-db">
            <i class="fa fa-download"></i> Backup Database
          </button>
          <div id="result-backup-db" style="margin-top:10px;"></div>
        </div>
      </div>

      <!-- Backup Files -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-folder-open"></i> Backup Files</h3>
        </div>
        <div class="box-body">
          <p class="text-muted">Zip seluruh isi folder <code><?= htmlspecialchars(env('STORAGE_LOCAL_PATH','uploads')) ?>/</code> ke file <code>.zip</code>.</p>
          <button class="btn btn-success btn-flat" id="btn-backup-files">
            <i class="fa fa-download"></i> Backup Files
          </button>
          <div id="result-backup-files" style="margin-top:10px;"></div>
        </div>
      </div>

      <!-- Backup Full -->
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-archive"></i> Backup Full (DB + Files)</h3>
        </div>
        <div class="box-body">
          <p class="text-muted">Backup database dan files sekaligus dalam satu file <code>.zip</code>.</p>
          <button class="btn btn-warning btn-flat" id="btn-backup-full">
            <i class="fa fa-download"></i> Backup Full
          </button>
          <div id="result-backup-full" style="margin-top:10px;"></div>
        </div>
      </div>

    </div><!-- /.col-md-6 kiri -->

    <!-- ===== KOLOM KANAN: Restore ===== -->
    <div class="col-md-6">

      <!-- Restore Database -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-upload"></i> Restore Database</h3>
        </div>
        <div class="box-body">
          <div class="callout callout-danger" style="margin-bottom:15px;">
            <p><i class="fa fa-exclamation-triangle"></i> <strong>Peringatan:</strong> Restore akan menimpa data yang ada. Pastikan sudah backup terlebih dahulu.</p>
          </div>
          <form id="form-restore-db" enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
              <label>Upload File <code>.sql</code></label>
              <input type="file" name="sql_file" id="sql_file" accept=".sql" class="form-control">
            </div>
            <button type="submit" class="btn btn-danger btn-flat">
              <i class="fa fa-upload"></i> Restore Database
            </button>
            <div id="result-restore-db" style="margin-top:10px;"></div>
          </form>
        </div>
      </div>

      <!-- Restore Files -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-upload"></i> Restore Files</h3>
        </div>
        <div class="box-body">
          <div class="callout callout-warning" style="margin-bottom:15px;">
            <p><i class="fa fa-info-circle"></i> File ZIP akan diekstrak ke folder <code><?= htmlspecialchars(env('STORAGE_LOCAL_PATH','uploads')) ?>/</code>.</p>
          </div>
          <form id="form-restore-files" enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
              <label>Upload File <code>.zip</code></label>
              <input type="file" name="zip_file" id="zip_file" accept=".zip" class="form-control">
            </div>
            <button type="submit" class="btn btn-danger btn-flat">
              <i class="fa fa-upload"></i> Restore Files
            </button>
            <div id="result-restore-files" style="margin-top:10px;"></div>
          </form>
        </div>
      </div>

    </div><!-- /.col-md-6 kanan -->

  </div><!-- /.row -->

  <!-- ===== RIWAYAT BACKUP ===== -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-history"></i> Riwayat Backup</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm btn-flat" onclick="location.reload()">
              <i class="fa fa-refresh"></i> Refresh
            </button>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          <?php if (empty($files)): ?>
            <div class="text-center" style="padding:30px;">
              <i class="fa fa-inbox fa-3x text-muted"></i>
              <p class="text-muted" style="margin-top:10px;">Belum ada file backup tersedia.</p>
            </div>
          <?php else: ?>
          <table class="table table-hover table-bordered" id="table-backup">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama File</th>
                <th>Tipe</th>
                <th>Ukuran</th>
                <th>Tanggal</th>
                <th style="width:130px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($files as $i => $f): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><code><?= htmlspecialchars($f['name']) ?></code></td>
                <td>
                  <?php if ($f['type'] === 'database'): ?>
                    <span class="label label-primary"><i class="fa fa-database"></i> Database</span>
                  <?php elseif ($f['type'] === 'files'): ?>
                    <span class="label label-success"><i class="fa fa-folder"></i> Files</span>
                  <?php else: ?>
                    <span class="label label-warning"><i class="fa fa-archive"></i> Full</span>
                  <?php endif; ?>
                </td>
                <td><?= $f['size'] ?></td>
                <td><?= $f['date'] ?></td>
                <td>
                  <a href="<?= site_url('download-backup/' . $f['name']) ?>" class="btn btn-xs btn-info btn-flat" title="Download">
                    <i class="fa fa-download"></i>
                  </a>
                  <button class="btn btn-xs btn-danger btn-flat btn-hapus-backup"
                          data-filename="<?= htmlspecialchars($f['name']) ?>"
                          title="Hapus">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
        <div class="box-footer text-muted">
          <small><i class="fa fa-lock"></i> File backup disimpan di folder <code>backups/</code> dan dilindungi dari akses publik.</small>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== JADWAL BACKUP (SCHEDULED) ===== -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-clock-o"></i> Jadwal Backup Otomatis</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <?php
            $sched_enabled   = env('BACKUP_SCHEDULE_ENABLED', 'false') === 'true';
            $sched_type      = env('BACKUP_SCHEDULE_TYPE', 'full');
            $sched_frequency = env('BACKUP_SCHEDULE_FREQUENCY', 'daily');
            $sched_time      = env('BACKUP_SCHEDULE_TIME', '02:00');
            $sched_last_run  = env('BACKUP_SCHEDULE_LAST_RUN', '');
            $cron_secret     = env('CRON_SECRET', '');
          ?>

          <?php if ($sched_enabled && $sched_last_run): ?>
          <div class="callout callout-info" style="margin-bottom:15px;">
            <p><i class="fa fa-history"></i> Backup terakhir dijalankan: <strong><?= htmlspecialchars($sched_last_run) ?></strong></p>
          </div>
          <?php endif; ?>

          <form id="form-schedule-settings">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label><i class="fa fa-power-off"></i> Status</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="schedule_enabled" value="true"  <?= $sched_enabled  ? 'checked' : '' ?>> Aktif
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="schedule_enabled" value="false" <?= !$sched_enabled ? 'checked' : '' ?>> Nonaktif
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><i class="fa fa-database"></i> Tipe Backup</label>
                  <select name="schedule_type" class="form-control">
                    <option value="full"  <?= $sched_type === 'full'  ? 'selected' : '' ?>>Full (DB + Files)</option>
                    <option value="db"    <?= $sched_type === 'db'    ? 'selected' : '' ?>>Database saja</option>
                    <option value="files" <?= $sched_type === 'files' ? 'selected' : '' ?>>Files saja</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><i class="fa fa-repeat"></i> Frekuensi</label>
                  <select name="schedule_frequency" class="form-control">
                    <option value="daily"   <?= $sched_frequency === 'daily'   ? 'selected' : '' ?>>Harian</option>
                    <option value="weekly"  <?= $sched_frequency === 'weekly'  ? 'selected' : '' ?>>Mingguan</option>
                    <option value="monthly" <?= $sched_frequency === 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><i class="fa fa-clock-o"></i> Jam Backup</label>
                  <input type="time" name="schedule_time" class="form-control" value="<?= htmlspecialchars($sched_time) ?>">
                  <span class="help-block">Format 24 jam (HH:MM)</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fa fa-key"></i> Cron Secret Token</label>
                  <input type="text" name="cron_secret" class="form-control"
                         placeholder="Biarkan kosong untuk tidak mengubah"
                         autocomplete="off">
                  <span class="help-block">Token digunakan untuk mengamankan endpoint cron job eksternal.</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fa fa-terminal"></i> Endpoint Cron Job (Server)</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="cron-url" readonly
                           value="<?= site_url('cron/run-backup') ?>?token=<?= htmlspecialchars($cron_secret ?: 'CRON_SECRET') ?>">
                    <span class="input-group-btn">
                      <button class="btn btn-default btn-flat" type="button" id="btn-copy-cron-url" title="Salin URL">
                        <i class="fa fa-copy"></i>
                      </button>
                    </span>
                  </div>
                  <span class="help-block">Gunakan URL ini di cron job server (misal cPanel Cron Jobs).</span>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-success btn-flat">
              <i class="fa fa-save"></i> Simpan Pengaturan Jadwal
            </button>
            <span id="result-schedule-settings" style="margin-left:10px;"></span>
          </form>
        </div>
        <div class="box-footer text-muted">
          <small>
            <i class="fa fa-info-circle"></i>
            Pseudo-cron berjalan otomatis saat ada request ke aplikasi (~1% peluang per request).
            Untuk jadwal yang akurat, gunakan endpoint cron job di server Anda.
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== CLOUD STORAGE SETTINGS ===== -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-cloud-upload"></i> Cloud Storage — Pengaturan Backup Otomatis</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <?php
            $cloud_active  = env('BACKUP_UPLOAD_TO_CLOUD', 'false') === 'true';
            $delete_local  = env('BACKUP_CLOUD_DELETE_LOCAL', 'false') === 'true';
            $storage_driver = env('STORAGE_DRIVER', 'local');
          ?>

          <?php if ($storage_driver === 'local'): ?>
          <div class="callout callout-warning">
            <p><i class="fa fa-exclamation-triangle"></i>
              Storage driver saat ini adalah <strong>local</strong>. Aktifkan driver <strong>s3</strong> di
              <a href="<?= site_url('web-config') ?>#storage">Konfigurasi &rsaquo; Storage</a> sebelum mengaktifkan upload cloud.
            </p>
          </div>
          <?php endif; ?>

          <form id="form-cloud-settings">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fa fa-toggle-on"></i> Upload Otomatis ke Cloud</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="upload_to_cloud" value="true"  <?= $cloud_active  ? 'checked' : '' ?>> Aktif
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="upload_to_cloud" value="false" <?= !$cloud_active ? 'checked' : '' ?>> Nonaktif
                    </label>
                  </div>
                  <span class="help-block">Jika aktif, setiap backup akan diunggah otomatis ke cloud storage.</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><i class="fa fa-trash-o"></i> Hapus File Lokal Setelah Upload</label>
                  <div>
                    <label class="radio-inline">
                      <input type="radio" name="delete_local" value="true"  <?= $delete_local  ? 'checked' : '' ?>> Ya
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="delete_local" value="false" <?= !$delete_local ? 'checked' : '' ?>> Tidak
                    </label>
                  </div>
                  <span class="help-block">File lokal di folder <code>backups/</code> akan dihapus setelah berhasil diunggah.</span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-info btn-flat">
              <i class="fa fa-save"></i> Simpan Pengaturan Cloud
            </button>
            <span id="result-cloud-settings" style="margin-left:10px;"></span>
          </form>
        </div>
        <div class="box-footer text-muted">
          <small><i class="fa fa-info-circle"></i> Konfigurasi endpoint S3/R2/MinIO diatur di <a href="<?= site_url('web-config') ?>#storage">Konfigurasi &rsaquo; Storage</a>.</small>
        </div>
      </div>
    </div>
  </div>

</section>

<script>
var CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
var CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

function showResult(el, status, message, extra) {
  var icon  = status === 'ok' ? 'check' : 'times';
  var cls   = status === 'ok' ? 'text-green' : 'text-red';
  var html  = '<span class="' + cls + '"><i class="fa fa-' + icon + '"></i> ' + message + '</span>';
  if (extra) html += extra;
  $(el).html(html);
}

function spinnerResult(el) {
  $(el).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
}

function buildExtra(r) {
  var extra = '';
  if (r.status === 'ok') {
    if (r.url) {
      extra += ' &nbsp;<a href="' + r.url + '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-download"></i> Download (' + r.size + ')</a>';
    }
    if (r.cloud_url) {
      extra += ' &nbsp;<a href="' + r.cloud_url + '" target="_blank" class="btn btn-xs btn-default btn-flat"><i class="fa fa-cloud"></i> Cloud</a>';
    }
  }
  return extra;
}

// ---- Backup Database ----
$('#btn-backup-db').on('click', function() {
  spinnerResult('#result-backup-db');
  var data = {};
  data[CSRF_NAME] = CSRF_HASH;
  $.post('<?= site_url('backup-database') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    showResult('#result-backup-db', r.status, r.message, buildExtra(r));
  });
});

// ---- Backup Files ----
$('#btn-backup-files').on('click', function() {
  spinnerResult('#result-backup-files');
  var data = {};
  data[CSRF_NAME] = CSRF_HASH;
  $.post('<?= site_url('backup-files') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    showResult('#result-backup-files', r.status, r.message, buildExtra(r));
  });
});

// ---- Backup Full ----
$('#btn-backup-full').on('click', function() {
  spinnerResult('#result-backup-full');
  var data = {};
  data[CSRF_NAME] = CSRF_HASH;
  $.post('<?= site_url('backup-full') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    showResult('#result-backup-full', r.status, r.message, buildExtra(r));
  });
});

// ---- Cloud Settings ----
$('#form-cloud-settings').on('submit', function(e) {
  e.preventDefault();
  var upload = $('input[name="upload_to_cloud"]:checked').val();
  var del    = $('input[name="delete_local"]:checked').val();
  var data   = {};
  data[CSRF_NAME] = CSRF_HASH;
  data['upload_to_cloud']  = upload;
  data['delete_local']     = del;
  $('#result-cloud-settings').html('<i class="fa fa-spinner fa-spin"></i>');
  $.post('<?= site_url('save-cloud-settings') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    var cls  = r.status === 'ok' ? 'text-green' : 'text-red';
    var icon = r.status === 'ok' ? 'check' : 'times';
    $('#result-cloud-settings').html('<span class="' + cls + '"><i class="fa fa-' + icon + '"></i> ' + r.message + '</span>');
  });
});

// ---- Restore Database ----
$('#form-restore-db').on('submit', function(e) {
  e.preventDefault();
  if (!$('#sql_file')[0].files.length) {
    showResult('#result-restore-db', 'error', 'Pilih file .sql terlebih dahulu.');
    return;
  }
  if (!confirm('Yakin ingin merestore database? Data yang ada akan ditimpa!')) return;
  spinnerResult('#result-restore-db');
  var formData = new FormData(this);
  $.ajax({
    url: '<?= site_url('restore-database') ?>',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(res) {
      var r = jQuery.parseJSON(res);
      showResult('#result-restore-db', r.status, r.message);
    }
  });
});

// ---- Restore Files ----
$('#form-restore-files').on('submit', function(e) {
  e.preventDefault();
  if (!$('#zip_file')[0].files.length) {
    showResult('#result-restore-files', 'error', 'Pilih file .zip terlebih dahulu.');
    return;
  }
  if (!confirm('Yakin ingin merestore files? File yang ada mungkin akan ditimpa!')) return;
  spinnerResult('#result-restore-files');
  var formData = new FormData(this);
  $.ajax({
    url: '<?= site_url('restore-files') ?>',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(res) {
      var r = jQuery.parseJSON(res);
      showResult('#result-restore-files', r.status, r.message);
    }
  });
});

// ---- Schedule Settings ----
$('#form-schedule-settings').on('submit', function(e) {
  e.preventDefault();
  var data = {};
  data[CSRF_NAME]             = CSRF_HASH;
  data['schedule_enabled']    = $('input[name="schedule_enabled"]:checked').val();
  data['schedule_type']       = $('select[name="schedule_type"]').val();
  data['schedule_frequency']  = $('select[name="schedule_frequency"]').val();
  data['schedule_time']       = $('input[name="schedule_time"]').val();
  data['cron_secret']         = $('input[name="cron_secret"]').val();
  $('#result-schedule-settings').html('<i class="fa fa-spinner fa-spin"></i>');
  $.post('<?= site_url('save-schedule-settings') ?>', data, function(res) {
    var r   = jQuery.parseJSON(res);
    var cls = r.status === 'ok' ? 'text-green' : 'text-red';
    var ico = r.status === 'ok' ? 'check' : 'times';
    $('#result-schedule-settings').html('<span class="' + cls + '"><i class="fa fa-' + ico + '"></i> ' + r.message + '</span>');
  });
});

// ---- Copy Cron URL ----
$('#btn-copy-cron-url').on('click', function() {
  var url = $('#cron-url').val();
  if (navigator.clipboard) {
    navigator.clipboard.writeText(url).then(function() {
      $('#btn-copy-cron-url').html('<i class="fa fa-check"></i>');
      setTimeout(function() { $('#btn-copy-cron-url').html('<i class="fa fa-copy"></i>'); }, 2000);
    });
  } else {
    $('#cron-url').select();
    document.execCommand('copy');
  }
});

// ---- Hapus Backup ----
$(document).on('click', '.btn-hapus-backup', function() {
  var fname = $(this).data('filename');
  var row   = $(this).closest('tr');
  if (!confirm('Hapus file backup: ' + fname + '?')) return;
  var data = { filename: fname };
  data[CSRF_NAME] = CSRF_HASH;
  $.post('<?= site_url('delete-backup') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    if (r.status === 'ok') {
      row.fadeOut(400, function() { $(this).remove(); });
    } else {
      alert('Gagal: ' + r.message);
    }
  });
});
</script>
