<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
  <h1><i class="fa fa-history"></i> Log Aktivitas</h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">Log Aktivitas</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Username</label>
                <input type="text" id="filter_user" class="form-control input-sm" placeholder="Semua user">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Aksi</label>
                <select id="filter_action" class="form-control input-sm">
                  <option value="">Semua aksi</option>
                  <option value="login">login</option>
                  <option value="logout">logout</option>
                  <option value="backup_db">backup_db</option>
                  <option value="backup_files">backup_files</option>
                  <option value="backup_full">backup_full</option>
                  <option value="restore_db">restore_db</option>
                  <option value="restore_files">restore_files</option>
                  <option value="delete_backup">delete_backup</option>
                  <option value="save_config">save_config</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanggal</label>
                <input type="date" id="filter_date" class="form-control input-sm">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>&nbsp;</label><br>
                <button class="btn btn-primary btn-sm btn-flat" id="btn-filter"><i class="fa fa-search"></i> Filter</button>
                <button class="btn btn-default btn-sm btn-flat" id="btn-reset"><i class="fa fa-times"></i> Reset</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list"></i> Riwayat Aktivitas</h3>
          <div class="box-tools pull-right">
            <a href="<?= site_url('activity-log/export') ?>" class="btn btn-sm btn-success btn-flat">
              <i class="fa fa-file-excel-o"></i> Export CSV
            </a>
            <button class="btn btn-sm btn-danger btn-flat" id="btn-clear-all">
              <i class="fa fa-trash"></i> Hapus Semua
            </button>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="table-activity" class="table table-bordered table-hover table-striped" style="width:100%">
            <thead>
              <tr>
                <th width="40">#</th>
                <th width="150">Tanggal</th>
                <th width="120">Username</th>
                <th width="100">Modul</th>
                <th width="120">Aksi</th>
                <th>Deskripsi</th>
                <th width="120">IP Address</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';

var table = $('#table-activity').DataTable({
  processing: true,
  serverSide: true,
  ajax: {
    url: '<?= site_url('activity-log/data') ?>',
    type: 'POST',
    data: function(d) {
      d[CSRF_NAME]     = CSRF_HASH;
      d.filter_user    = $('#filter_user').val();
      d.filter_action  = $('#filter_action').val();
      d.filter_date    = $('#filter_date').val();
    }
  },
  columns: [
    { data: 'no',          orderable: false },
    { data: 'created_at'  },
    { data: 'username'    },
    { data: 'module'      },
    { data: 'action',     orderable: false },
    { data: 'description' },
    { data: 'ip_address'  },
  ],
  order: [[1, 'desc']],
  language: {
    processing:  '<i class="fa fa-spinner fa-spin"></i> Memuat...',
    emptyTable:  'Tidak ada data log.',
    zeroRecords: 'Data tidak ditemukan.',
    info:        'Menampilkan _START_ - _END_ dari _TOTAL_ data',
    infoEmpty:   'Menampilkan 0 data',
    search:      'Cari:',
    lengthMenu:  'Tampilkan _MENU_ data',
    paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
  },
  pageLength: 25,
});

$('#btn-filter').on('click', function() { table.ajax.reload(); });
$('#btn-reset').on('click', function() {
  $('#filter_user').val('');
  $('#filter_action').val('');
  $('#filter_date').val('');
  table.ajax.reload();
});

$('#btn-clear-all').on('click', function() {
  if (!confirm('Hapus SEMUA log aktivitas? Tindakan ini tidak dapat dibatalkan.')) return;
  var data = {};
  data[CSRF_NAME] = CSRF_HASH;
  $.post('<?= site_url('activity-log/clear') ?>', data, function(res) {
    var r = JSON.parse(res);
    if (r.status === 'ok') {
      table.ajax.reload();
      toastr ? toastr.success(r.message) : alert(r.message);
    }
  });
});
</script>
