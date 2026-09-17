 <?php $this->load->view('_heading/_headerContent') ?>

<style>
#osas {
color:red;
font-weight:bold;
margin-left:0px;
}

</style>
		
		<section class="content">
		<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
		
		<div class="box">
        <div class="row">
        <div class="col-md-9" id="newContain">
       
        <div class="box-header with-border">
        <h3 class="box-title">Tambah Data</h3>
         </div>
			
            <!-- /.box-header -->
            <!-- form start -->
			
			<form class="form-horizontal" id="form-tambah" method="POST">
			<input type="hidden" name="created_by" value="<?php echo $userdata->nama; ?>">
			
			<div class="box-body">
            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Cabang </label>
                  <div class="col-sm-6">
                  <input type="text" class="form-control" name="nama_cabang" id="nama_cabang" aria-describedby="sizing-addon2">
                  </div>
                </div>
				
              </div>
              <div class="box-footer">
              <button name="simpan" id="simpan" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Simpan</button>
             
               <a class="klik ajaxify" href="<?php echo site_url('cabang'); ?>"><button class="btn btn-primary btn-flat" ><i class="fa fa-arrow-left"></i> Kembali</button></a>
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
        var error = 0;
        var message = "";

        var data = $(this).serialize();

        var nama_cabang = $("#nama_cabang").val();
        var nama_cabang = nama_cabang.trim();
        
        if (error == 0) {
            if (nama_cabang.length == 0) {
                error++;
                message = "Nama Cabang wajib di isi.";
            }
        }
      
        if (error == 0) {
            $.ajax({
                method: 'POST',
                beforeSend: function () {
                    $(".loading2").show();
                    $(".loading2").modal('show');
                },
                url: '<?php echo site_url('Category/Cabang/prosesAdd'); ?>',
                data: data,
            }).done(function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'berhasil') {
                    document.getElementById("form-tambah").reset();
                    $(".loading2").hide();
                    $(".loading2").modal('hide');
                    save_berhasil();
                    setTimeout(location.reload.bind(location), 500);
                } else {
                    $(".loading2").hide();
                    $(".loading2").modal('hide');
                    gagal();
                    
                }
            })
            e.preventDefault();
        } else {
            swal("Peringatan", message, "warning");
            return false;
        }
    });

</script>
	
		
		

		
		