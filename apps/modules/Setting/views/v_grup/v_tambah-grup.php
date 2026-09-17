<?php $this->load->view('_heading/_headerContent') ?>

	<section class="content">
		<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
		
		<div class="box">
        <div class="row">
        <div class="col-md-9">
       
        <div class="box-header with-border">
        <h3 class="box-title">Add user grup</h3>
        </div>
			
            <!-- form start -->
             <form class="form-horizontal" id="form-tambah" method="POST">
			 <input type="hidden" name="created_by" value="<?php echo $userdata->nama; ?>">
			
              <div class="box-body">
			  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Grup</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" placeholder="nama grup" name="nama_grup" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
				<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" placeholder="deskripsi singkat" name="deskripsi" aria-describedby="sizing-addon2">
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
		url: '<?php echo site_url('Setting/Grup/prosesTambah'); ?>',
		data: data,	
		})
		.done(function(data) {
			var result = jQuery.parseJSON(data);
			if (result.status == 'berhasil')
				
			{
				document.getElementById("form-tambah").reset();
				$(".loading2").hide();
				$(".loading2").modal('hide');	
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
		
	
	
	
	
		//klik loading ajax
	
	$(document).ready(function(){
    $('.klik').click(function() {
    var url = $(this).attr('href');
	$("#loading2").show().html("<img src='http://localhost/custom-admin/assets/tambahan/gambar/loader-ok.gif' height='18'> ");
	$("#loading2").modal('show');		
	$.ajax({
	complete: function(){
	$("#loading2").hide();
	$("#loading2").modal('hide');
	}
	});	
	return true;
    });
    });
		
		
</script>	


		