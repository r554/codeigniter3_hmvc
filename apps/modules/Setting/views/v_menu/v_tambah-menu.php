<?php $this->load->view('_heading/_headerContent') ?>

<style>.select-width {width:310px;}</style>


	<section class="content">
		<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
		
		<div class="box">
        <div class="row">
        <div class="col-md-9">
       
        <div class="box-header with-border">
        <h3 class="box-title">Add Menu</h3>
        </div>
			
            <!-- form start -->
             <form class="form-horizontal" id="form-tambah" method="POST">
			<input type="hidden" name="created_by" value="<?php echo $userdata->nama; ?>">
              <div class="box-body">
			  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Menu</label>
                  <div class="col-sm-5">
                  <input type="text" class="form-control" placeholder="nama menu" name="nama_menu" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Icon</label>
                  <div class="col-sm-5">
                  <input type="text" class="form-control" placeholder="icon menu" name="icon" aria-describedby="sizing-addon2">
                  <p style='color: red; font-size: 14px;'> *example fa fa-home</p>
				  </div>
				  
				 <div class="col-md-1">
				 <a href='icon.html' target="_blank" class="btn default btn-primary "><i class='fa fa-edit'></i> Icon List </a>
				 </div>
				 
				 </div>
				
				<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Link Menu</label>
                  <div class="col-sm-5">
                  <input type="text" class="form-control" placeholder="link menu" name="link" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
				<div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Kode Menu</label>
                <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="kode menu" name="kode_menu" aria-describedby="sizing-addon2">
                </div>
                </div>
				
				
				<div class="form-group">
                <label class="col-sm-2 control-label">Parent Menu</label>
                <div class="col-sm-5">
				<select name="parent" id="parent" class="form-control select2" style="width:100%;">
					<option value="">-- Menu Utama (Tanpa Parent) --</option>
					<?php foreach($dataMenu as $m): ?>
					<option value="<?= $m->id_menu ?>"><?= htmlspecialchars($m->nama_menu) ?></option>
					<?php endforeach; ?>
				</select>
				<p class="text-muted" style="font-size:12px;">Kosongkan jika ini adalah Menu Utama</p>
                </div>
                </div>
				
				
				<div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Urutan</label>
                <div class="col-sm-3">
                <input type="text" class="form-control" placeholder="urutan" name="urutan" aria-describedby="sizing-addon2">
                </div>
                </div>
			
			
				<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Menu Available</label>
				<div class="col-xs-3">
				<input type='checkbox' name='menu_file[]' value="view" />&nbsp; View<br>
				<input type='checkbox' name='menu_file[]' value="add" />&nbsp; Add<br>
				<input type='checkbox' name='menu_file[]' value="edit" />&nbsp; Edit<br>
				<input type='checkbox' name='menu_file[]' value="del" />&nbsp; Delete<br>										
				</div>
				</div>
				
              </div>
              <div class="box-footer">
              <button name="simpan" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
              <button type="reset" class="btn btn-sm btn-warning "><i class="fa fa-retweet"></i> Cancel</button>
              </div>
             </form>
			
          </div>
          <!-- /.box -->
       
          </div>
          <!-- /.row -->
		</div>
		
		</section>

		
		<script type="text/javascript">	
	
		//Proses Controller logic ajax
	
		$('#form-tambah').submit(function(e) {
		var data = $(this).serialize();
		$.ajax({
		method: 'POST',
        beforeSend: function (){
        $(".loading2").show();
		$(".loading2").modal('show');		
        },
		url: '<?php echo site_url('Setting/Menu/prosesTambah'); ?>',
		data: data,	
		})
		.done(function(data) {
			var result = jQuery.parseJSON(data);
			if (result.status == 'berhasil')
				
			{
				document.getElementById("form-tambah").reset();
				$(".loading2").hide();
				$(".loading2").modal('hide');
				setTimeout(location.reload.bind(location), 500);
				save_berhasil();
				
			} else 
			
			{
				document.getElementById("form-tambah").reset();
				$(".loading2").hide();
				$(".loading2").modal('hide');	
				gagal();
			}
		})
		
		e.preventDefault();
	});
		

	$(function() {
		$('#parent').select2({
			placeholder: '-- Menu Utama (Tanpa Parent) --',
			allowClear: true
		});
	});
			
</script>	
		
		