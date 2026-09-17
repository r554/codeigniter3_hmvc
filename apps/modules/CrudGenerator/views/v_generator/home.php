<?php $this->load->view('_heading/_headerContent') ?>

<section class="content">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-magic"></i> CRUD Generator</h3>
    <div class="box-tools pull-right">
      <span class="badge bg-blue" id="step-badge">Step 1 of 3</span>
    </div>
  </div>
  <div class="box-body">

    <!-- WIZARD STEPS NAV -->
    <div class="row" style="margin-bottom:20px;">
      <div class="col-md-12">
        <ol class="breadcrumb" style="background:none;padding:0;">
          <li class="active" id="nav-step1"><strong>1. Konfigurasi Tabel</strong></li>
          <li id="nav-step2" style="color:#aaa;">2. Konfigurasi Kolom</li>
          <li id="nav-step3" style="color:#aaa;">3. Preview &amp; Generate</li>
        </ol>
      </div>
    </div>

    <!-- ===================== STEP 1: Table & Module Config ===================== -->
    <div id="step1">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Pilih Tabel <span class="text-red">*</span></label>
            <select class="form-control" id="sel-table">
              <option value="">-- Loading tables... --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Primary Key <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inp-pk" placeholder="e.g. id_nama_tabel">
            <span class="help-block text-muted" id="pk-hint"></span>
          </div>
          <div class="form-group">
            <label>Nama Module (PascalCase) <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inp-module" placeholder="e.g. ProductCategory">
          </div>
          <div class="form-group">
            <label>Kode Menu <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inp-kode" placeholder="e.g. product-category">
          </div>
          <div class="form-group">
            <label>Judul Halaman <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inp-judul" placeholder="e.g. Product Category">
          </div>
        </div>
        <div class="col-md-6">
          <div class="callout callout-info">
            <h4><i class="fa fa-info-circle"></i> Petunjuk</h4>
            <ul>
              <li>Pilih tabel DB yang ingin dibuatkan CRUD-nya.</li>
              <li><strong>Nama Module</strong> akan jadi nama folder &amp; class (PascalCase).</li>
              <li><strong>Kode Menu</strong> dipakai untuk URL &amp; cek akses M_sidebar (kebab-case).</li>
              <li>File akan ditulis ke <code>apps/modules/{ModuleName}/</code>.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <button class="btn btn-primary btn-flat" id="btn-next1"><i class="fa fa-arrow-right"></i> Lanjut ke Step 2</button>
        </div>
      </div>
    </div>

    <!-- ===================== STEP 2: Column Config ===================== -->
    <div id="step2" style="display:none;">
      <div class="row">
        <div class="col-md-12">
          <div class="callout callout-warning">
            <b>Catatan:</b> Kolom <code>created_by</code>, <code>created_date</code>, <code>updated_by</code>, <code>updated_date</code> akan ditambahkan otomatis. Tidak perlu dicentang.
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <h4>Kolom untuk Tampil di List (DataTable)</h4>
          <table class="table table-bordered table-sm" id="tbl-list-cols">
            <thead><tr><th><input type="checkbox" id="chk-all-list"> Tampilkan</th><th>Kolom</th><th>Label Header</th></tr></thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="col-md-6">
          <h4>Kolom untuk Form (Tambah &amp; Edit)</h4>
          <table class="table table-bordered table-sm" id="tbl-form-cols">
            <thead><tr><th><input type="checkbox" id="chk-all-form"> Tampilkan</th><th>Kolom</th><th>Label</th><th>Tipe Input</th><th>Wajib</th></tr></thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="row" style="margin-top:10px;">
        <div class="col-md-12">
          <button class="btn btn-default btn-flat" id="btn-back1"><i class="fa fa-arrow-left"></i> Kembali</button>
          <button class="btn btn-primary btn-flat" id="btn-next2"><i class="fa fa-arrow-right"></i> Lanjut ke Step 3</button>
        </div>
      </div>
    </div>

    <!-- ===================== STEP 3: Preview & Generate ===================== -->
    <div id="step3" style="display:none;">
      <ul class="nav nav-tabs" id="preview-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-ctrl">Controller</a></li>
        <li><a data-toggle="tab" href="#tab-model">Model</a></li>
        <li><a data-toggle="tab" href="#tab-home">View: home</a></li>
        <li><a data-toggle="tab" href="#tab-tambah">View: tambah</a></li>
        <li><a data-toggle="tab" href="#tab-update">View: update</a></li>
      </ul>
      <div class="tab-content" style="border:1px solid #ddd;border-top:none;padding:10px;background:#272822;border-radius:0 0 4px 4px;">
        <div id="tab-ctrl"    class="tab-pane fade in active"><pre id="pre-ctrl"   class="preview-code"></pre></div>
        <div id="tab-model"   class="tab-pane fade"><pre id="pre-model"  class="preview-code"></pre></div>
        <div id="tab-home"    class="tab-pane fade"><pre id="pre-home"   class="preview-code"></pre></div>
        <div id="tab-tambah"  class="tab-pane fade"><pre id="pre-tambah" class="preview-code"></pre></div>
        <div id="tab-update"  class="tab-pane fade"><pre id="pre-update" class="preview-code"></pre></div>
      </div>
      <div class="row" style="margin-top:15px;">
        <div class="col-md-12">
          <div class="checkbox" style="display:inline-block;margin-right:15px;">
            <label>
              <input type="checkbox" id="chk-create-menu" checked> <strong>Buat Menu Otomatis</strong> (tambahkan ke Menu Master)
            </label>
          </div>
        </div>
      </div>
      <div class="row" style="margin-top:10px;">
        <div class="col-md-12">
          <button class="btn btn-default btn-flat" id="btn-back2"><i class="fa fa-arrow-left"></i> Kembali</button>
          <button class="btn btn-success btn-flat" id="btn-generate"><i class="fa fa-cogs"></i> Generate Files</button>
        </div>
      </div>
    </div>

    <!-- ===================== STEP 4: Result ===================== -->
    <div id="step4" style="display:none;">
      <div class="callout callout-success">
        <h4><i class="fa fa-check-circle"></i> Generate Berhasil!</h4>
        <p>File-file berikut berhasil dibuat:</p>
        <ul id="result-files"></ul>
        <div id="result-menu" style="margin-top:10px;display:none;">
          <hr>
          <p><i class="fa fa-info-circle text-blue"></i> <strong>Menu:</strong> <span id="menu-status"></span></p>
        </div>
        <hr>
        <a id="result-link" href="#" class="btn btn-primary klik ajaxify">
          <i class="fa fa-external-link"></i> Buka Module Baru
        </a>
        <a href="<?php echo site_url('menu'); ?>" class="btn btn-info klik ajaxify">
          <i class="fa fa-list"></i> Ke Menu Master
        </a>
        <button class="btn btn-default" id="btn-reset"><i class="fa fa-refresh"></i> Generate Lagi</button>
      </div>
    </div>

  </div><!-- /.box-body -->
</div><!-- /.box -->
</section>

<style>
.preview-code {
  color: #f8f8f2;
  background: transparent;
  border: none;
  font-size: 12px;
  max-height: 400px;
  overflow-y: auto;
  white-space: pre-wrap;
  word-break: break-all;
  margin: 0;
}
#tbl-list-cols td, #tbl-form-cols td { vertical-align: middle; }
.step-active { font-weight: bold; color: #3c8dbc !important; }
.step-done   { color: #00a65a !important; }
</style>

<script>
var allColumns  = [];
var genConfig   = {};

var BASE        = '<?php echo site_url("CrudGenerator/CrudGenerator"); ?>';

// ---- helpers ----
function showStep(n) {
  ['step1','step2','step3','step4'].forEach(function(s,i){
    $('#'+s).toggle(i+1 === n);
  });
  $('#step-badge').text('Step ' + Math.min(n,3) + ' of 3');
  // nav highlight
  ['nav-step1','nav-step2','nav-step3'].forEach(function(s,i){
    var el = $('#'+s);
    el.removeClass('active step-done step-active');
    if (i+1 < n)  el.addClass('step-done').find('strong,span').css('color','#00a65a');
    if (i+1 === n) el.addClass('active step-active');
    if (i+1 > n)  el.css('color','#aaa');
  });
}

// ---- Step 1: load tables ----
$(document).ready(function(){
  $.post(BASE + '/get_tables', {}, function(res){
    var r = $.parseJSON(typeof res === 'string' ? res : JSON.stringify(res));
    var sel = $('#sel-table').empty().append('<option value="">-- Pilih Tabel --</option>');
    if (r.status === 'ok') {
      $.each(r.data, function(i,t){ sel.append('<option>'+t+'</option>'); });
    }
  });

  // Auto-suggest PK from table name: tbl_foo => id_foo
  $('#sel-table').on('change', function(){
    var t = $(this).val();
    if (!t) return;
    var guess = t.replace(/^tbl_/, 'id_');
    $('#inp-pk').val(guess).trigger('input');
    $('#pk-hint').text('Otomatis: ' + guess);

    // Load columns
    $.post(BASE + '/get_columns', { table: t }, function(res){
      var r = $.parseJSON(typeof res==='string'?res:JSON.stringify(res));
      if (r.status !== 'ok') return;
      allColumns = r.data;
    });
  });

  // Auto-fill module & kode from table
  $('#sel-table').on('change', function(){
    var t = $(this).val().replace(/^tbl_/, '');
    var pascal = t.split('_').map(function(w){ return w.charAt(0).toUpperCase()+w.slice(1); }).join('');
    var kebab  = t.split('_').join('-');
    var title  = t.split('_').map(function(w){ return w.charAt(0).toUpperCase()+w.slice(1); }).join(' ');
    $('#inp-module').val(pascal);
    $('#inp-kode').val(kebab);
    $('#inp-judul').val(title);
  });

  // ---- Step 1 → 2 ----
  $('#btn-next1').on('click', function(){
    var table  = $('#sel-table').val();
    var pk     = $('#inp-pk').val().trim();
    var mod    = $('#inp-module').val().trim();
    var kode   = $('#inp-kode').val().trim();
    var judul  = $('#inp-judul').val().trim();
    if (!table||!pk||!mod||!kode||!judul) {
      swal('Peringatan','Semua field wajib diisi.','warning'); return;
    }
    genConfig = { table_name: table, primary_key: pk, module_name: mod, kode_menu: kode, judul: judul };

    // Populate column tables
    var skip = ['created_by','created_date','updated_by','updated_date', pk];
    var $listBody = $('#tbl-list-cols tbody').empty();
    var $formBody = $('#tbl-form-cols tbody').empty();
    var inputTypes = ['text','textarea','number','date','select','file'];

    if (allColumns.length === 0) {
      // fetch if not yet loaded
      $.post(BASE + '/get_columns', { table: table }, function(res){
        var r = $.parseJSON(typeof res==='string'?res:JSON.stringify(res));
        if (r.status === 'ok') { allColumns = r.data; buildColTables(skip, inputTypes, $listBody, $formBody); }
      });
    } else {
      buildColTables(skip, inputTypes, $listBody, $formBody);
    }

    showStep(2);
  });

  function buildColTables(skip, inputTypes, $listBody, $formBody) {
    $.each(allColumns, function(i, col){
      if ($.inArray(col.field, skip) !== -1) return;
      var lbl = col.field.replace(/_/g,' ').replace(/\b\w/g,function(c){return c.toUpperCase();});

      // List table row
      $listBody.append(
        '<tr data-field="'+col.field+'">' +
        '<td class="text-center"><input type="checkbox" class="chk-list" checked></td>' +
        '<td><code>'+col.field+'</code></td>' +
        '<td><input type="text" class="form-control input-sm list-label" value="'+lbl+'"></td>' +
        '</tr>'
      );

      // Guess input type from DB type
      var dbtype = (col.type||'').toLowerCase();
      var selType = 'text';
      if (dbtype.indexOf('text')!==-1 && dbtype.indexOf('varchar')===-1) selType='textarea';
      if (dbtype.indexOf('int')!==-1 || dbtype.indexOf('decimal')!==-1 || dbtype.indexOf('float')!==-1) selType='number';
      if (dbtype.indexOf('date')!==-1 || dbtype.indexOf('time')!==-1) selType='date';

      var opts = inputTypes.map(function(t){ return '<option value="'+t+'"'+(t===selType?' selected':'')+'>'+t+'</option>'; }).join('');

      $formBody.append(
        '<tr data-field="'+col.field+'">' +
        '<td class="text-center"><input type="checkbox" class="chk-form" checked></td>' +
        '<td><code>'+col.field+'</code></td>' +
        '<td><input type="text" class="form-control input-sm form-label" value="'+lbl+'"></td>' +
        '<td><select class="form-control input-sm form-type">'+opts+'</select></td>' +
        '<td class="text-center"><input type="checkbox" class="chk-req" checked></td>' +
        '</tr>'
      );
    });
  }

  // Check-all list cols
  $('#chk-all-list').on('change', function(){ $('.chk-list').prop('checked', $(this).is(':checked')); });
  $('#chk-all-form').on('change', function(){ $('.chk-form').prop('checked', $(this).is(':checked')); });

  // ---- Step 2 → 1 ----
  $('#btn-back1').on('click', function(){ showStep(1); });

  // ---- Step 2 → 3 (Preview) ----
  $('#btn-next2').on('click', function(){
    // Collect list cols
    var listCols = [];
    $('#tbl-list-cols tbody tr').each(function(){
      if ($(this).find('.chk-list').is(':checked')) {
        listCols.push({ field: $(this).data('field'), label: $(this).find('.list-label').val() });
      }
    });
    // Collect form fields
    var formFields = [];
    $('#tbl-form-cols tbody tr').each(function(){
      if ($(this).find('.chk-form').is(':checked')) {
        formFields.push({
          field:    $(this).data('field'),
          label:    $(this).find('.form-label').val(),
          type:     $(this).find('.form-type').val(),
          required: $(this).find('.chk-req').is(':checked')
        });
      }
    });
    if (listCols.length === 0 || formFields.length === 0) {
      swal('Peringatan','Pilih minimal 1 kolom list dan 1 kolom form.','warning'); return;
    }
    genConfig.list_columns = listCols;
    genConfig.form_fields  = formFields;

    // Call preview
    $.ajax({
      method: 'POST',
      url: BASE + '/preview',
      data: { config: JSON.stringify(genConfig) },
      beforeSend: function(){ $('#btn-next2').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Loading...'); }
    }).done(function(res){
      $('#btn-next2').prop('disabled',false).html('<i class="fa fa-arrow-right"></i> Lanjut ke Step 3');
      var r = $.parseJSON(typeof res==='string'?res:JSON.stringify(res));
      if (r.status !== 'ok') { swal('Error', r.message||'Preview gagal','error'); return; }
      $('#pre-ctrl').text(r.data.controller);
      $('#pre-model').text(r.data.model);
      $('#pre-home').text(r.data.view_home);
      $('#pre-tambah').text(r.data.view_tambah);
      $('#pre-update').text(r.data.view_update);
      showStep(3);
    });
  });

  // ---- Step 3 → 2 ----
  $('#btn-back2').on('click', function(){ showStep(2); });

  // ---- Step 3: Generate ----
  $('#btn-generate').on('click', function(){
    var createMenu = $('#chk-create-menu').is(':checked') ? '1' : '0';
    $.ajax({
      method: 'POST',
      url: BASE + '/generate',
      data: { config: JSON.stringify(genConfig), create_menu: createMenu },
      beforeSend: function(){ $('#btn-generate').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Generating...'); }
    }).done(function(res){
      $('#btn-generate').prop('disabled',false).html('<i class="fa fa-cogs"></i> Generate Files');
      var r = $.parseJSON(typeof res==='string'?res:JSON.stringify(res));
      if (r.status !== 'berhasil') { swal('Error', r.message||'Generate gagal','error'); return; }
      var $ul = $('#result-files').empty();
      $.each(r.files, function(i,f){ $ul.append('<li><code>'+f+'</code></li>'); });
      $('#result-link').attr('href', r.url);
      // Show menu result
      if (r.menu) {
        $('#result-menu').show();
        var menuText = r.menu.status === 'created' 
          ? 'Menu berhasil dibuat (ID: ' + r.menu.menu_id + ')' 
          : 'Menu sudah ada (ID: ' + r.menu.menu_id + ')';
        $('#menu-status').text(menuText);
      } else {
        $('#result-menu').hide();
      }
      showStep(4);
    });
  });

  // ---- Step 4: Reset ----
  $('#btn-reset').on('click', function(){
    genConfig   = {};
    allColumns  = [];
    $('#inp-pk,#inp-module,#inp-kode,#inp-judul').val('');
    $('#sel-table').val('');
    showStep(1);
  });

  showStep(1);
});
</script>
