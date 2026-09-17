<?php $this->load->view('_heading/_headerContent') ?>

	<section class="content">
 
		<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
		
		<div class="box">
		<form class="form-horizontal" id="form-update" method="POST">
        <input type="hidden" name="id_menu" value="<?php echo $dataMenu->id_menu; ?>">
		<input type="hidden" name="last_update_by" value="<?php echo $userdata->nama; ?>">
        <div class="row">
        <div class="col-md-9">

        <div class="box-header with-border">
        <h3 class="box-title">Edit Menu</h3>
        </div>
			
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Nama Menu</label>
		<div class="col-sm-5">
        <input type="text" class="form-control" placeholder="nama warna" name="nama_menu" aria-describedby="sizing-addon2" value="<?php echo $dataMenu->nama_menu; ?>">
        </div>
		</div>	
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Icon</label>
        <div class="col-sm-5">
        <input type="text" class="form-control" placeholder="icon menu" name="icon" aria-describedby="sizing-addon2" value="<?php echo $dataMenu->icon; ?>">
         <p style='color: red; font-size: 14px;'> *example fa fa-home</p>
		</div>	  
		<div class="col-md-1">
		<a href="<?php echo base_url()?>icon.html" target="_blank" class="btn default btn-primary "><i class='fa fa-edit'></i> Icon List </a>
		</div>
		</div>
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Link Menu</label>
		<div class="col-sm-5">
        <input type="text" class="form-control" placeholder="link menu" name="link" aria-describedby="sizing-addon2" value="<?php echo $dataMenu->link; ?>">
        </div>
		</div>	
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">kode Menu</label>
		<div class="col-sm-5">
        <input type="text" class="form-control" placeholder="kode menu" name="kode_menu" aria-describedby="sizing-addon2" value="<?php echo $dataMenu->kode_menu; ?>">
        </div>
		</div>	
		
		<div class="form-group">
        <label class="col-sm-2 control-label">Parent Menu</label>
        <div class="col-sm-5">
        <select name="parent" id="parent" class="form-control select2" style="width:100%;">
			<option value="">-- Menu Utama (Tanpa Parent) --</option>
			<?php foreach ($dataMenu2 as $data): ?>
			<option value="<?= $data->id_menu ?>" <?= ($data->id_menu == $dataMenu->parent) ? 'selected' : '' ?>>
				<?= htmlspecialchars($data->nama_menu) ?>
			</option>
			<?php endforeach; ?>
		</select>
		<p class="text-muted" style="font-size:12px;">Kosongkan jika ini adalah Menu Utama</p>
        </div>
        </div>
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Urutan</label>
		<div class="col-sm-3">
        <input type="text" class="form-control" placeholder="urutan" name="urutan" aria-describedby="sizing-addon2" value="<?php echo $dataMenu->urutan; ?>">
        </div>
		</div>
		
		<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">Menu Available</label>
		<div class="col-xs-3">
		<?php $menu = explode(",", $dataMenu->menu_file); ?>
		<input type='checkbox' name='menu_file[]' value="view" <?php foreach($menu as $value){ echo ('view'==$value)?'checked':''; } ?> /> &nbsp;View<br>
		<input type='checkbox' name='menu_file[]' value="add" <?php foreach($menu  as $value){ echo ('add'==$value)?'checked':''; } ?> /> &nbsp;Add<br>
		<input type='checkbox' name='menu_file[]' value="edit" <?php foreach($menu as $value){ echo ('edit'==$value)?'checked':''; } ?> /> &nbsp;Edit<br>
		<input type='checkbox' name='menu_file[]' value="del" <?php foreach($menu  as $value){ echo ('del'==$value)?'checked':''; } ?> /> &nbsp;Delete<br>										
		</div>
		</div>
		
			
        </div>
        <!-- /.box-body -->
		<div class="box-footer">
        <button name="simpan" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Update</button>
        <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-retweet"></i> Cancel</button>
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
		
		$('#form-update').submit(function(e) {
		var data = $(this).serialize();
		$.ajax({
		method: 'POST',
        beforeSend: function (){
        $(".loading2").show();
		$(".loading2").modal('show');		
        },
		url: '<?php echo site_url('Setting/Menu/prosesUpdate'); ?>',
		data: data,	
		})
		.done(function(data) {
		 var result = jQuery.parseJSON(data);
		 if (result.status == 'berhasil')	
			{
				$(".loading2").hide();
				$(".loading2").modal('hide');
				setTimeout(location.reload.bind(location), 500);				
				update_berhasil();
				
			} else 
			
			{
				document.getElementById("form-update").reset();
				$(".loading2").hide();
				$(".loading2").modal('hide');	
				gagal();
			}
		})
		
		e.preventDefault();
	});
	
	// untuk select2 original
		$(function () 
		{
		$(".select2").select2({
        });
		});	
		
</script>	