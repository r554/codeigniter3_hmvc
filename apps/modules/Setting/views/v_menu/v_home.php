<?php $this->load->view('_heading/_headerContent') ?>
	<section class="content">
	<div class="box">
	<div class="box-header">
    <div class="col-md-2">
    <a class="klik ajaxify" href="<?php echo site_url('add-menu'); ?>"><button class="btn btn-success" ><i class="glyphicon glyphicon-plus-sign"></i> Add Data</button></a>

	</div>
    
	</div>
	<!-- /.box-header -->
  
	<div class="box-body">
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
		<th width="4%">#</th>
          <th>Nama Menu</th>
		  <th>Parent Menu</th>
		  <th>Icon</th>
		  <th>Menu Link</th>
		  <th>Available</th>
          <th style="width:125px;">Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
		</div>

	</section>



	<script type="text/javascript">

 //untuk load data table ajax	
	

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "order": [], //Initial no order.
		 oLanguage: {
        sProcessing: "<img src='<?php base_url();?>assets/tambahan/gambar/loading.gif' width='30px'>" 
          },

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Setting/Menu/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 2, -1 ], // kolom parent & action tidak sortable
            "orderable": false,
        },
        ],

    });

});


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

$(document).on("click",".hapus-menu",function(){
	var id_menu=$(this).attr("data-id");
	
	swal({
		title:"Hapus Data?",
		text:"Yakin anda akan menghapus data ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Hapus",
		confirmButtonColor: '#dc1227',
		customClass: ".sweet-alert button",
		closeOnConfirm: true,
		 html: true
	},
		function(){
		 $.ajax({
			method: "POST",
			url: "<?php echo base_url('Setting/Menu/hapus'); ?>",
			data: "id_menu=" +id_menu,
			success: function(data){
				$("tr[data-id='"+id_menu+"']").fadeOut("fast",function(){
					$(this).remove();
				});
				hapus_berhasil();
				reload_table();
			}
		 });
	});
});
</script>
