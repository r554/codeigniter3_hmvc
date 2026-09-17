<?php $this->load->view('_heading/_headerContent') ?>
	
	<section class="content">
	<div class="box">
	<div class="box-header">
    <div class="col-md-2">
    <a class="klik ajaxify" href="<?php echo site_url('add-grup'); ?>"><button class="btn btn-success" ><i class="glyphicon glyphicon-plus-sign"></i> Add Data</button></a>

	</div>
    
	</div>
	<!-- /.box-header -->
  
	<div class="box-body">
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
	<th>#</th>
    <th>Nama Grup</th>
	<th>Modul Kategori</th>
	<th>Menu Akses</th>
	<th>Deskripsi</th>
    <th style="width:200px;">Action</th>
    </tr>
    </thead>
    <tbody>
  <?php
  $no = 1;
  foreach ($dataWarna as $data) {
  $loop=$data->grup_id;
  $dataakses= $this->M_grup->akses_grup($loop);
    ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $data->nama_grup; ?></td>
	  <td><?php foreach ($dataakses as $data1) {  if ($data1->view == 1) {?><?php echo '' .$data1->menuparent.'<br>'; ?><?php } } ?></td>
	  <td><?php foreach ($dataakses as $data1) {  if ($data1->view == 1) {?><?php echo '' .$data1->nama_menu.'<br>'; ?><?php } } ?></td>
     <td><?php echo $data->deskripsi; ?></td>
	 <td>
        <a class="ajaxify" href="<?php echo base_url()?>edit-grup/<?php echo $data->grup_id; ?>"><button class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </button></a>
        <a class="ajaxify" href="<?php echo base_url()?>hak-akses/<?php echo $data->grup_id; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-cogs"></i> </button></a>
		<button class="btn btn-sm btn-danger hapus-grup" data-id="<?php echo $data->grup_id; ?>"><i class="glyphicon glyphicon-trash"></i></button>
      
	  </td>
    </tr>
    <?php
    $no++;
	}
	?>
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

    });

});


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

$(document).on("click",".hapus-grup",function(){
	var grup_id=$(this).attr("data-id");
	
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
			url: "<?php echo base_url('Setting/Grup/hapus'); ?>",
			data: "grup_id=" +grup_id,
			success: function(data){
				$("tr[data-id='"+grup_id+"']").fadeOut("fast",function(){
					$(this).remove();
				});
				hapus_berhasil();
				setTimeout(location.reload.bind(location), 350);
			}
		 });
	});
});

</script>
