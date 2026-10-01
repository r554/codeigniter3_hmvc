<?php $this->load->view('_heading/_headerContent') ?>

<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
<section class="content">
<div class="row">
  <div class="col-md-3">
    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
        <?php
          $foto_src = (!empty($userdata->foto) && file_exists(FCPATH . 'upload/user/' . $userdata->foto))
            ? base_url() . 'upload/user/' . $userdata->foto
            : base_url() . 'assets/admin-lte/dist/img/default-50x50.gif';
        ?>
        <img class="profile-user-img img-responsive img-circle" src="<?php echo $foto_src; ?>" alt="User profile picture">
        <h3 class="profile-username text-center"><?php echo $userdata->nama; ?></h3>
		 <?php foreach ($datagrup as $data) { ?>
		 <?php if($data->grup_id ==  $userdata->grup_id){ ?>
		 <p class="text-muted text-center"><?php echo $data->nama_grup; ?></p>
         <?php 
	     }} ?>
	    
      </div>
    </div>
  </div>

    <div class="col-md-9">
    <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
    <li class="active"><a href="#settings" data-toggle="tab"><i class="fa fa-user"></i> Settings</a></li>
    <li><a href="#password" data-toggle="tab"><i class="fa fa-lock"></i> Ubah Password</a></li>
    <li><a href="#security" data-toggle="tab"><i class="fa fa-shield"></i> Keamanan</a></li>
    </ul>
      
	 <div class="tab-content">
     <div class="active tab-pane" id="settings">
		
     <form class="form-horizontal" id="form-update" method="POST">
	 <input type="hidden" name="last_update_by" value="<?php echo $userdata->nama; ?>">
     <div class="form-group">
     <label for="inputUsername" class="col-sm-2 control-label">Username</label>
     <div class="col-sm-6">
     <input type="text" class="form-control" id= placeholder="Username" name="username" value="<?php echo $userdata->username; ?>">
     </div>
     </div>
            
	 <div class="form-group">
     <label for="inputNama" class="col-sm-2 control-label">Name</label>
     <div class="col-sm-6">
     <input type="text" class="form-control" placeholder="Name" name="nama" value="<?php echo $userdata->nama; ?>">
     </div>
     </div>
			
	  <div class="form-group">
      <label for="inputNama" class="col-sm-2 control-label">Level</label>
      <div class="col-sm-6">
			  
      <?php
	  foreach ($datagrup as $data) {
	  ?>
	  <?php if($data->grup_id ==  $userdata->grup_id){ ?>
      <input type="text" class="form-control" value="<?php echo $data->nama_grup; ?>" readonly>
      <?php
	   }} ?>  
	   </div>
       </div>
			  
			  <div class="form-group">
              <label for="inputNama" class="col-sm-2 control-label">Status</label>
              <div class="col-sm-6">
			  
			  <?php
		      foreach ($dataStatus as $data) {
		      ?>
		      <?php if($data->id_status == $userdata->status){ ?>
              <p class="form-control-static"><?php echo $data->nama; ?></p>
              <?php
			  }} ?>
			  </div>
              </div>
		
            <div class="form-group">
              <label for="inputFoto" class="col-sm-2 control-label">Foto</label>
              <div class="col-sm-5">
                <input type="file" class="form-control" placeholder="Foto" name="foto">
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-danger">Submit</button>
              </div>
            </div>
          </form>
        </div>
		
		<!---- ini buat update password -->
		
        <div class="tab-pane" id="password">
           <form class="form-horizontal" id="form-update-pass" method="POST">
		   <input type="hidden" name="last_update_by" value="<?php echo $userdata->nama; ?>">
            <div class="form-group">
              <label for="passLama" class="col-sm-2 control-label">Password Lama</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" placeholder="Password Lama" name="passLama">
              </div>
            </div>
            <div class="form-group">
              <label for="passBaru" class="col-sm-2 control-label">Password Baru</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" placeholder="Password Baru" name="passBaru">
              </div>
            </div>
            <div class="form-group">
              <label for="passKonf" class="col-sm-2 control-label">Konfirmasi Password</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" placeholder="Konfirmasi Password" name="passKonf">
              </div>
            </div>
            
            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-danger">Submit</button>
            </div>
            </div>
          </form>
        </div>

        <!-- Security Tab -->
        <div class="tab-pane" id="security">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-shield"></i> Two-Factor Authentication (2FA)</h3>
            </div>
            <div class="box-body">
              <p class="text-muted">
                Two-Factor Authentication menambahkan lapisan keamanan ekstra pada akun Anda. 
                Setelah mengaktifkan 2FA, Anda akan diminta memasukkan kode 6 digit dari aplikasi authenticator setiap kali login.
              </p>

              <?php if (!empty($userdata->two_factor_enabled)): ?>
                <!-- 2FA Aktif -->
                <div class="alert alert-success">
                  <h4><i class="fa fa-check-circle"></i> 2FA Aktif</h4>
                  <p>Akun Anda dilindungi dengan Two-Factor Authentication.</p>
                </div>

                <div class="form-group">
                  <label>Status:</label>
                  <p class="form-control-static">
                    <span class="label label-success"><i class="fa fa-lock"></i> Aktif</span>
                  </p>
                </div>

                <div class="form-group">
                  <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#disableModal">
                    <i class="fa fa-times-circle"></i> Nonaktifkan 2FA
                  </button>
                  <button type="button" class="btn btn-default btn-flat" id="viewBackupCodes">
                    <i class="fa fa-list"></i> Lihat Backup Codes
                  </button>
                </div>

                <div class="callout callout-info" style="margin-top: 20px;">
                  <h4><i class="fa fa-info-circle"></i> Tips Keamanan</h4>
                  <ul style="margin-bottom: 0;">
                    <li>Simpan backup codes di tempat yang aman</li>
                    <li>Jangan bagikan kode 2FA kepada siapa pun</li>
                    <li>Jika kehilangan akses authenticator, gunakan backup code untuk login</li>
                  </ul>
                </div>

              <?php else: ?>
                <!-- 2FA Nonaktif -->
                <div class="alert alert-warning">
                  <h4><i class="fa fa-exclamation-triangle"></i> 2FA Tidak Aktif</h4>
                  <p>Akun Anda belum dilindungi dengan Two-Factor Authentication. Aktifkan sekarang untuk keamanan lebih baik.</p>
                </div>

                <div class="form-group">
                  <label>Status:</label>
                  <p class="form-control-static">
                    <span class="label label-default"><i class="fa fa-unlock"></i> Tidak Aktif</span>
                  </p>
                </div>

                <div class="form-group">
                  <button type="button" class="btn btn-primary btn-flat" id="enableTwoFactor">
                    <i class="fa fa-shield"></i> Aktifkan 2FA
                  </button>
                </div>

                <div class="callout callout-info" style="margin-top: 20px;">
                  <h4><i class="fa fa-info-circle"></i> Cara Mengaktifkan 2FA</h4>
                  <ol style="margin-bottom: 0;">
                    <li>Install aplikasi Google Authenticator atau Authy di smartphone Anda</li>
                    <li>Klik tombol "Aktifkan 2FA" di atas</li>
                    <li>Scan QR code yang ditampilkan menggunakan aplikasi authenticator</li>
                    <li>Masukkan kode 6 digit untuk verifikasi</li>
                    <li>Simpan backup codes yang diberikan</li>
                  </ol>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

	</section>

<!-- Modal: Setup 2FA -->
<div class="modal fade" id="setupModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-shield"></i> Setup Two-Factor Authentication</h4>
      </div>
      <div class="modal-body">
        <div id="setupLoading" class="text-center" style="padding: 20px;">
          <i class="fa fa-spinner fa-spin fa-3x"></i>
          <p>Generating QR Code...</p>
        </div>
        <div id="setupContent" style="display:none;">
          <div class="text-center" style="margin-bottom: 20px;">
            <h4>1. Scan QR Code</h4>
            <p class="text-muted">Gunakan Google Authenticator atau Authy</p>
            <div id="qrCodeContainer" style="margin: 20px 0;"></div>
          </div>
          <div class="form-group">
            <label>2. Atau masukkan kode manual:</label>
            <input type="text" class="form-control" id="manualSecret" readonly>
          </div>
          <div class="form-group">
            <label>3. Masukkan kode verifikasi 6 digit:</label>
            <input type="text" class="form-control" id="verifyCode" maxlength="6" placeholder="000000">
            <span class="help-block" id="verifyError" style="color:red;display:none;"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="verifyButton" style="display:none;">
          <i class="fa fa-check"></i> Verifikasi & Aktifkan
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Backup Codes -->
<div class="modal fade" id="backupCodesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-list"></i> Backup Codes</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <i class="fa fa-exclamation-triangle"></i> <strong>Penting!</strong> Simpan kode-kode ini di tempat yang aman. Setiap kode hanya bisa digunakan sekali.
        </div>
        <div id="backupCodesGrid" class="row"></div>
        <div class="text-center" style="margin-top: 20px;">
          <button type="button" class="btn btn-default" id="downloadCodes">
            <i class="fa fa-download"></i> Download
          </button>
          <button type="button" class="btn btn-default" id="printCodes">
            <i class="fa fa-print"></i> Print
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="location.reload();">Selesai</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Disable Confirmation -->
<div class="modal fade" id="disableModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Konfirmasi</h4>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menonaktifkan 2FA? Akun Anda akan kurang aman.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="confirmDisable">
          <i class="fa fa-times-circle"></i> Ya, Nonaktifkan
        </button>
      </div>
    </div>
  </div>
</div>

    <script type="text/javascript">
	
	//Proses update ajax
		
		$(document).ready(function(){
		$('#form-update').submit(function(e){
		 e.preventDefault(); 
		 $.ajax({
		 url:'<?php echo base_url();?>Setting/Profile/update',
		 type:"post",
		 data:new FormData(this),
		 contentType: false,
		 cache: false,
		 processData:false,
		             
		 beforeSend: function (){
		 $(".loading2").show();
		 $(".loading2").modal('show');	
		 },
		 success: function(data){
		 $(".loading2").hide();
		 $(".loading2").modal('hide');
		 setTimeout(location.reload.bind(location), 500);		 
		 update_berhasil();
		 }
		 });
		 });
	});
	
	
	//Proses update password ajax
		
		$(document).ready(function(){
		$('#form-update-pass').submit(function(e){
		 e.preventDefault(); 
		 $.ajax({
		 url:'<?php echo base_url();?>Setting/Profile/update',
		 type:"post",
		 data:new FormData(this),
		 contentType: false,
		 cache: false,
		 processData:false,
		             
		 beforeSend: function (){
		 $(".loading2").show();
		 $(".loading2").modal('show');	
		 },
		 success: function(data){
		 $(".loading2").hide();
		 $(".loading2").modal('hide');	
		 update_berhasil();
		 }
		 });
		 });
	});
	
	
	
	// ========== 2FA Modal Handlers ==========
	
	// Enable 2FA - Show modal and generate QR code
	$('#enableTwoFactor').click(function() {
		$('#setupModal').modal('show');
		$('#setupLoading').show();
		$('#setupContent').hide();
		$('#verifyButton').hide();
		
		$.ajax({
			url: '<?= base_url("Setting/Profile/generate_2fa_secret") ?>',
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.status === 'success') {
					$('#qrCodeContainer').html('<img src="' + response.qr_url + '" alt="QR Code" style="max-width: 250px;">');
					$('#manualSecret').val(response.secret);
					$('#setupLoading').hide();
					$('#setupContent').show();
					$('#verifyButton').show();
				} else {
					alert('Gagal generate QR code. Silakan coba lagi.');
					$('#setupModal').modal('hide');
				}
			},
			error: function() {
				alert('Terjadi kesalahan. Silakan coba lagi.');
				$('#setupModal').modal('hide');
			}
		});
	});
	
	// Verify and enable 2FA
	$('#verifyButton').click(function() {
		var code = $('#verifyCode').val().trim();
		$('#verifyError').hide();
		
		if (code.length !== 6) {
			$('#verifyError').text('Kode harus 6 digit').show();
			return;
		}
		
		$(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memverifikasi...');
		
		$.ajax({
			url: '<?= base_url("Setting/Profile/verify_and_enable_2fa") ?>',
			type: 'POST',
			data: { code: code },
			dataType: 'json',
			success: function(response) {
				if (response.status === 'success') {
					$('#setupModal').modal('hide');
					showBackupCodes(response.backup_codes);
					
					// Reload page after user closes backup codes modal to reflect new 2FA status
					$('#backupCodesModal').on('hidden.bs.modal', function() {
						location.reload();
					});
				} else {
					$('#verifyError').text(response.message).show();
					$('#verifyButton').prop('disabled', false).html('<i class="fa fa-check"></i> Verifikasi & Aktifkan');
				}
			},
			error: function() {
				$('#verifyError').text('Terjadi kesalahan. Silakan coba lagi.').show();
				$('#verifyButton').prop('disabled', false).html('<i class="fa fa-check"></i> Verifikasi & Aktifkan');
			}
		});
	});
	
	// View backup codes
	$('#viewBackupCodes').click(function() {
		$.ajax({
			url: '<?= base_url("Setting/Profile/get_backup_codes") ?>',
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.status === 'success') {
					showBackupCodes(response.codes);
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alert('Gagal mengambil backup codes.');
			}
		});
	});
	
	// Display backup codes in modal
	function showBackupCodes(codes) {
		var html = '';
		codes = Array.isArray(codes) ? codes : [];
		for (var i = 0; i < codes.length; i++) {
			// Backend may return plain strings or objects: {code: '...', used: false}
			var item = codes[i];
			var code = typeof item === 'object' && item !== null ? item.code : item;
			var used = typeof item === 'object' && item !== null ? !!item.used : false;
			if (!code) {
				continue;
			}
			var status = used ? '<span class="label label-default">Terpakai</span>' : '<span class="label label-success">Tersedia</span>';
			html += '<div class="col-md-6" style="margin-bottom: 10px;">';
			html += '<div class="well well-sm" style="margin-bottom: 0;">';
			html += '<strong>' + $('<div>').text(code).html() + '</strong> ' + status;
			html += '</div>';
			html += '</div>';
		}
		$('#backupCodesGrid').html(html || '<div class="col-md-12">Tidak ada backup code.</div>');
		$('#backupCodesModal').modal('show');
	}
	
	// Download backup codes
	$('#downloadCodes').click(function() {
		var text = 'CoatesApp - Backup Codes\n\n';
		$('#backupCodesGrid .well').each(function() {
			var code = $(this).find('strong').text();
			text += code + '\n';
		});
		
		var blob = new Blob([text], { type: 'text/plain' });
		var url = window.URL.createObjectURL(blob);
		var a = document.createElement('a');
		a.href = url;
		a.download = 'backup-codes.txt';
		a.click();
		window.URL.revokeObjectURL(url);
	});
	
	// Print backup codes
	$('#printCodes').click(function() {
		var printContent = '<h2>CoatesApp - Backup Codes</h2><ul>';
		$('#backupCodesGrid .well').each(function() {
			var code = $(this).find('strong').text();
			printContent += '<li>' + code + '</li>';
		});
		printContent += '</ul>';
		
		var printWindow = window.open('', '_blank');
		printWindow.document.write('<html><head><title>Backup Codes</title></head><body>');
		printWindow.document.write(printContent);
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.print();
	});
	
	// Disable 2FA
	$('#confirmDisable').click(function() {
		$(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		
		$.ajax({
			url: '<?= base_url("Setting/Profile/disable_2fa_profile") ?>',
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.status === 'success') {
					$('#disableModal').modal('hide');
					alert('2FA berhasil dinonaktifkan.');
					location.reload();
				} else {
					alert(response.message);
					$('#confirmDisable').prop('disabled', false).html('<i class="fa fa-times-circle"></i> Ya, Nonaktifkan');
				}
			},
			error: function() {
				alert('Terjadi kesalahan. Silakan coba lagi.');
				$('#confirmDisable').prop('disabled', false).html('<i class="fa fa-times-circle"></i> Ya, Nonaktifkan');
			}
		});
	});

</script>