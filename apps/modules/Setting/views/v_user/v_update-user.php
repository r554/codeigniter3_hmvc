 <style>
  .field-icon {
  float: left;
  margin-left: 93%;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

 </style>


<?php $this->load->view('_heading/_headerContent') ?>
	<section class="content">
 
		<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
		
		<div class="box">
		<form class="form-horizontal" id="form-update" method="POST">
        <input type="hidden" name="id" value="<?php echo $dataUser->id; ?>">
		<input type="hidden" name="last_update_by" value="<?php echo $userdata->nama; ?>">
        <div class="row">
        <div class="col-md-7">

        <div class="box-header with-border">
        <h3 class="box-title">Edit User</h3>
        </div>
			
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Nama User</label>
		<div class="col-sm-7">
        <input type="text" class="form-control" placeholder="nama" name="nama" aria-describedby="sizing-addon2" value="<?php echo $dataUser->nama; ?>">
        </div>
		</div>	
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email User</label>
		<div class="col-sm-7">
        <input type="text" class="form-control" placeholder="email" name="email" aria-describedby="sizing-addon2" value="<?php echo $dataUser->email; ?>">
        </div>
		</div>	
		
		
		<div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Status</label>
        <div class="col-sm-3">
        <select name="status" class="form-control select2"  aria-describedby="sizing-addon2">
		<?php
		foreach ($dataStatus as $status) {
		?>
		<option value="<?php echo $status->id_status; ?>" <?php if($status->id_status == $dataUser->status){echo "selected='selected'";} ?>><?php echo $status->nama; ?></option>
		<?php
		}
		?>
		</select>
        </div>
        </div>
		
		
		<div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-7">
        <input type="text" class="form-control" placeholder="username" name="username" aria-describedby="sizing-addon2" value="<?php echo $dataUser->username; ?>">
        </div>
		</div>	
		
		<div class="form-group">
       	<label for="inputEmail3" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-7">
        <input type="password" class="form-control" id="password-field" placeholder="password"  name="password" aria-describedby="sizing-addon2"><span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password">
        </span>
        <p style='color: red; font-size: 14px;'><b> *isi password jika mau di update</b></p>
        </div>
        </div> 
		
	    <div class="form-group">
        <label class="col-sm-2 control-label">Grup</label>
        <div class="col-sm-4">
        <select name="grup_id" class="form-control select2" aria-describedby="sizing-addon2">
		<?php
		foreach ($datagrup as $data) {
		?>
		<option value="<?php echo $data->grup_id; ?>" <?php if($data->grup_id ==  $dataUser->grup_id){echo "selected='selected'";} ?>><?php echo $data->nama_grup; ?></option>
		<?php
		}
		?>
		</select>
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
        beforeSend: function (){
        $(".loading2").show();
		$(".loading2").modal('show');	
        },
		url:'<?php echo base_url();?>Setting/User/prosesUpdate',
		type:"post",
		data:new FormData(this),
		processData:false,
		contentType:false,
		cache:false,	
		})
		.done(function(data) {
			var result = jQuery.parseJSON(data);
			if (result.status == 'berhasil')
			{
				$(".loading2").hide();
				$(".loading2").modal('hide');
				setTimeout(location.reload.bind(location), 500);			
				save_berhasil();
			} else 
			
			{
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


			// untuk show hide password
	$(".toggle-password").click(function() {

	$(this).toggleClass("fa-eye fa-eye-slash");
	var input = $($(this).attr("toggle"));
	if (input.attr("type") == "password") {
    input.attr("type", "text");
	} else {
    input.attr("type", "password");
	}
	});
		
</script>	
	