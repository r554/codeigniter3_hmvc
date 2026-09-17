<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
  <h1><i class="fa fa-bomb text-danger"></i> Module Destroyer</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Module Destroyer</li>
  </ol>
</section>

<section class="content">
  <div class="box box-danger">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-warning text-danger"></i> Daftar Modul yang Dapat Dihapus</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-default btn-sm" id="btn-refresh"><i class="fa fa-refresh"></i> Refresh</button>
        <a href="<?php echo site_url('crud-generator'); ?>" class="btn btn-primary btn-sm klik ajaxify">
          <i class="fa fa-magic"></i> Ke Generator
        </a>
      </div>
    </div>
    <div class="box-body">
      <div class="callout callout-danger">
        <i class="fa fa-exclamation-triangle"></i>
        <strong>Peringatan!</strong> Penghapusan file modul bersifat <strong>permanen dan tidak bisa di-undo</strong>.
        Modul core (Category, Dashboard, Default, Setting, CrudGenerator) tidak ditampilkan dan tidak bisa dihapus.
      </div>

      <div id="module-list-wrap">
        <div class="text-center" style="padding:30px;">
          <i class="fa fa-spinner fa-spin fa-2x"></i><br>Memuat daftar modul...
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Hapus -->
<div class="modal fade" id="modal-hapus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-bomb"></i> Hapus Modul: <strong id="modal-module-name"></strong></h4>
      </div>
      <div class="modal-body">
        <p>Pilih apa yang ingin dihapus:</p>
        <div class="checkbox">
          <label>
            <input type="checkbox" id="chk-del-files" checked>
            <strong>Hapus File</strong> — Folder <code id="modal-module-path"></code> beserta seluruh isinya
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" id="chk-del-route" checked>
            <strong>Hapus Route</strong> — Hapus entry di <code>routes.php</code>
          </label>
        </div>
        <div class="checkbox" id="row-del-menu">
          <label>
            <input type="checkbox" id="chk-del-menu" checked>
            <strong>Hapus Menu & Akses</strong> — Hapus dari <code>tbl_menu</code> dan <code>tbl_menu_akses</code>
          </label>
        </div>
        <div class="callout callout-warning" style="margin-top:10px;">
          <i class="fa fa-warning"></i> Pastikan tidak ada user yang sedang menggunakan modul ini.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btn-confirm-hapus">
          <i class="fa fa-trash"></i> Ya, Hapus Sekarang
        </button>
      </div>
    </div>
  </div>
</div>

<script>
var BASE_DESTROYER = '<?php echo site_url("CrudGenerator/CrudGenerator"); ?>';
var BASE_AKSES     = '<?php echo site_url("user-grup"); ?>';
var currentModule  = {};

function loadModules() {
  $('#module-list-wrap').html('<div class="text-center" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Memuat...</div>');
  $.post(BASE_DESTROYER + '/ajax_module_list', {}, function(res) {
    var r = typeof res === 'string' ? $.parseJSON(res) : res;
    if (r.status !== 'ok' || r.data.length === 0) {
      $('#module-list-wrap').html('<div class="callout callout-info"><i class="fa fa-info-circle"></i> Tidak ada modul yang bisa dihapus.</div>');
      return;
    }
    var html = '<table class="table table-bordered table-hover">';
    html += '<thead><tr><th>#</th><th>Nama Modul</th><th>Kode Menu</th><th>Ada Route?</th><th>Ada Menu?</th><th>Akses Grup</th><th>Aksi</th></tr></thead><tbody>';
    $.each(r.data, function(i, m) {
      var routeBadge = m.has_route
        ? '<span class="label label-success"><i class="fa fa-check"></i> Ya</span>'
        : '<span class="label label-default"><i class="fa fa-times"></i> Tidak</span>';
      var menuBadge = m.has_menu
        ? '<span class="label label-success"><i class="fa fa-check"></i> Ya (ID: ' + m.menu_id + ')</span>'
        : '<span class="label label-default"><i class="fa fa-times"></i> Tidak</span>';
      html += '<tr>';
      html += '<td>' + (i+1) + '</td>';
      html += '<td><strong>' + m.module + '</strong></td>';
      html += '<td><code>' + m.kode_menu + '</code></td>';
      html += '<td>' + routeBadge + '</td>';
      html += '<td>' + menuBadge + '</td>';
      var aksesBtn = m.has_menu
        ? '<a href="' + BASE_AKSES + '" class="btn btn-xs btn-info klik ajaxify"><i class="fa fa-key"></i> Atur Akses</a>'
        : '<span class="text-muted">-</span>';
      html += '<td>' + aksesBtn + '</td>';
      html += '<td><button class="btn btn-sm btn-danger btn-hapus" '
            + 'data-module="' + m.module + '" '
            + 'data-kode="' + m.kode_menu + '" '
            + 'data-has-menu="' + (m.has_menu ? 1 : 0) + '" '
            + 'data-path="apps/modules/' + m.module + '">'
            + '<i class="fa fa-trash"></i> Hapus</button></td>';
      html += '</tr>';
    });
    html += '</tbody></table>';
    $('#module-list-wrap').html(html);
  });
}

$(document).ready(function() {
  loadModules();

  $('#btn-refresh').on('click', loadModules);

  // Open modal
  $(document).on('click', '.btn-hapus', function() {
    currentModule = {
      module   : $(this).data('module'),
      kode_menu: $(this).data('kode'),
      has_menu : $(this).data('has-menu'),
      path     : $(this).data('path'),
    };
    $('#modal-module-name').text(currentModule.module);
    $('#modal-module-path').text(currentModule.path);
    // Hide menu checkbox if no menu
    if (currentModule.has_menu == 0) {
      $('#row-del-menu').hide();
      $('#chk-del-menu').prop('checked', false);
    } else {
      $('#row-del-menu').show();
      $('#chk-del-menu').prop('checked', true);
    }
    $('#modal-hapus').modal('show');
  });

  // Confirm delete
  $('#btn-confirm-hapus').on('click', function() {
    var delFiles = $('#chk-del-files').is(':checked') ? '1' : '0';
    var delRoute = $('#chk-del-route').is(':checked') ? '1' : '0';
    var delMenu  = $('#chk-del-menu').is(':checked')  ? '1' : '0';

    if (delFiles == '0' && delRoute == '0' && delMenu == '0') {
      swal('Peringatan', 'Pilih minimal satu opsi penghapusan', 'warning');
      return;
    }

    $('#btn-confirm-hapus').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');

    $.ajax({
      method: 'POST',
      url   : BASE_DESTROYER + '/hapus_module',
      data  : {
        module   : currentModule.module,
        kode_menu: currentModule.kode_menu,
        del_files: delFiles,
        del_route: delRoute,
        del_menu : delMenu,
      }
    }).done(function(res) {
      $('#btn-confirm-hapus').prop('disabled', false).html('<i class="fa fa-trash"></i> Ya, Hapus Sekarang');
      var r = typeof res === 'string' ? $.parseJSON(res) : res;
      if (r.status !== 'berhasil') {
        swal('Gagal', r.message || 'Penghapusan gagal', 'error');
        return;
      }
      $('#modal-hapus').modal('hide');
      var detail = r.detail;
      var msg = 'Modul <strong>' + currentModule.module + '</strong> berhasil dihapus.<br>';
      if (detail.files)  msg += '✔ File: ' + detail.files + '<br>';
      if (detail.route)  msg += '✔ Route: ' + detail.route + '<br>';
      if (detail.menu)   msg += '✔ Menu: ' + detail.menu + '<br>';
      swal({ title: 'Berhasil!', text: msg, type: 'success', html: true });
      loadModules();
    });
  });
});
</script>
