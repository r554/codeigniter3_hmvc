
<!-- style loading -->
<div class="loading2"></div>
<!-- -->

 
        <div class="content-body"><div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
			
                  <div class="caution" style=" margin-top:50px; text-align:center;"><img src="<?php echo base_url().'assets/' ?>tambahan/gambar/caution.gif" width="150px"></div>
                    <center><h1 class="page_error_code text-primary"><b>Oopppsss!!!</b></h1></center>
                     <center><h2 class="page_error_info">Maaf Anda tidak punya akses ke halaman <font color="red"><?php echo @$judul; ?></font></h2></center>
                    <div class="col-md-6 col-sm-6 col-xs-8 col-md-offset-3 col-sm-offset-3 col-xs-offset-2">                        
                    </div>
                </div>
            </div>
        </div>
  
<script type="text/javascript">	
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