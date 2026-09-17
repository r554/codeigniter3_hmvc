
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
        <div class="row">
        <div class="col-md-7">
       
        <div class="box-header with-border">
        <h3 class="box-title">Add User</h3>
        </div>
			
            <!-- form start -->
             <form class="form-horizontal" id="form-tambah" method="POST">
			 <input type="hidden" name="created_by" value="<?php echo $userdata->nama; ?>">
			
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama User</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" placeholder="nama user" name="nama" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-7">
                  <input type="email" class="form-control" placeholder="email user" name="email" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" placeholder="username" name="username" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
				<div class="form-group">
       			 <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
        		 <div class="col-sm-7">
                 <input type="password" class="form-control" id="password-field" placeholder="password"  name="password" aria-describedby="sizing-addon2"><span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password">
                 	
                 </span>
                 </div>
                 </div>
				
				<div class="form-group">
                <label class="col-sm-2 control-label">Grup</label>
                <div class="col-sm-4">
                <select name="grup_id" class="form-control select2" aria-describedby="sizing-addon2">
				<?php
				foreach ($datagrup as $data) {
				?>
				<option value="<?php echo $data->grup_id; ?>">
				<?php echo $data->nama_grup; ?>
				</option>
				<?php
				}
				?>
				</select>
                </div>
                </div>
				
				
				
				  <input type="hidden" name="status" value="4">

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
        beforeSend: function (){
        $(".loading2").show();
		$(".loading2").modal('show');	
        },
		url:'<?php echo base_url();?>Setting/User/prosesTambah',
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
				document.getElementById("form-tambah").reset();
				$(".loading2").hide();
				$(".loading2").modal('hide');	
				save_berhasil();
				$("#profile").fadeOut("slow");
				setTimeout(location.reload.bind(location), 350);
				
			} else 
			
			{
				document.getElementById("form-tambah").reset();
				$(".loading2").hide();
				$(".loading2").modal('hide');	
				gagal();
				$("#profile").fadeOut("slow");
				setTimeout(location.reload.bind(location), 350);
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


		