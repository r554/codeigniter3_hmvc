	<?php $this->load->view('_heading/_headerContent') ?>
	
	
	<section class="content">
		<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
		
		<div class="box">
		<form id="form-update" class="form-horizontal" method="POST">
        <input type="hidden" name="id" value="<?php echo $brand->id; ?>">
		<input type="hidden" name="updated_by" value="<?php echo $userdata->nama; ?>">
        <div class="row">
        <div class="col-md-9">

        <div class="box-header with-border">
        <h3 class="box-title">Update Data</h3>
        </div>
			
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
	
         <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Nama Cabang </label>
         <div class="col-sm-6">
         <input type="text" class="form-control" name="nama_cabang" id="nama_cabang" value="<?php echo $brand->nama_cabang; ?>">
         </div>
         </div>
					
        </div>
        <!-- /.box-body -->
		
		<div class="box-footer">
        <button name="simpan" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Update</button>
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
    $('#form-update').submit(function(e) {
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
                url: '<?php echo site_url('Category/Cabang/prosesUpdate'); ?>',
                data: data,
            }).done(function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'berhasil') {
                  
                    $(".loading2").hide();
                    $(".loading2").modal('hide');
                    save_berhasil();
                    
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
	
	
