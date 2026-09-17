<?php $this->load->view('_heading/_headerContent') ?>

<!-- style loading -->
		<div class="loading2"></div>
		<!-- -->
<section class="content">
<div class="row">
  <div class="col-md-3">
    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
        <?php
          $foto_src = (!empty($userdata->foto) && file_exists(FCPATH . 'upload/user/' . $userdata->foto))
            ? base_url() . 'upload/user/' . $userdata->foto
            : base_url() . 'assets/admin-lte/dist/img/default-50x50.gif';
        ?>
        <img class="profile-user-img img-responsive img-circle" src="<?php echo $foto_src; ?>" alt="User profile picture">
        <h3 class="profile-username text-center"><?php echo $userdata->nama; ?></h3>
		 <?php foreach ($datagrup as $data) { ?>
		 <?php if($data->grup_id ==  $userdata->grup_id){ ?>
		 <p class="text-muted text-center"><?php echo $data->nama_grup; ?></p>
         <?php 
	     }} ?>
	    
      </div>
    </div>
  </div>

    <div class="col-md-9">
    <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
    <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
    <li><a href="#password" data-toggle="tab">Ubah Password</a></li>
    </ul>
      
	 <div class="tab-content">
     <div class="active tab-pane" id="settings">
		
     <form class="form-horizontal" id="form-update" method="POST">
	 <input type="hidden" name="last_update_by" value="<?php echo $userdata->nama; ?>">
     <div class="form-group">
     <label for="inputUsername" class="col-sm-2 control-label">Username</label>
     <div class="col-sm-6">
     <input type="text" class="form-control" id= placeholder="Username" name="username" value="<?php echo $userdata->username; ?>">
     </div>
     </div>
            
	 <div class="form-group">
     <label for="inputNama" class="col-sm-2 control-label">Name</label>
     <div class="col-sm-6">
     <input type="text" class="form-control" placeholder="Name" name="nama" value="<?php echo $userdata->nama; ?>">
     </div>
     </div>
			
	  <div class="form-group">
      <label for="inputNama" class="col-sm-2 control-label">Level</label>
      <div class="col-sm-6">
			  
      <?php
	  foreach ($datagrup as $data) {
	  ?>
	  <?php if($data->grup_id ==  $userdata->grup_id){ ?>
      <input type="text" class="form-control" value="<?php echo $data->nama_grup; ?>" readonly>
      <?php
	   }} ?>  
	   </div>
       </div>
			  
			  <div class="form-group">
              <label for="inputNama" class="col-sm-2 control-label">Status</label>
              <div class="col-sm-6">
			  
			  <?php
		      foreach ($dataStatus as $data) {
		      ?>
		      <?php if($data->id_status == $userdata->status){ ?>
              <p class="form-control-static"><?php echo $data->nama; ?></p>
              <?php
			  }} ?>
			  </div>
              </div>
		
            <div class="form-group">
              <label for="inputFoto" class="col-sm-2 control-label">Foto</label>
              <div class="col-sm-5">
                <input type="file" class="form-control" placeholder="Foto" name="foto">
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-danger">Submit</button>
              </div>
            </div>
          </form>
        </div>
		
		<!---- ini buat update password -->
		
        <div class="tab-pane" id="password">
           <form class="form-horizontal" id="form-update-pass" method="POST">
		   <input type="hidden" name="last_update_by" value="<?php echo $userdata->nama; ?>">
            <div class="form-group">
              <label for="passLama" class="col-sm-2 control-label">Password Lama</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" placeholder="Password Lama" name="passLama">
              </div>
            </div>
            <div class="form-group">
              <label for="passBaru" class="col-sm-2 control-label">Password Baru</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" placeholder="Password Baru" name="passBaru">
              </div>
            </div>
            <div class="form-group">
              <label for="passKonf" class="col-sm-2 control-label">Konfirmasi Password</label>
              <div class="col-sm-6">
                <input type="password" class="form-control" placeholder="Konfirmasi Password" name="passKonf">
              </div>
            </div>
            
            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-danger">Submit</button>
            </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

	</section>

    <script type="text/javascript">
	
	//Proses update ajax
		
		$(document).ready(function(){
		$('#form-update').submit(function(e){
		 e.preventDefault(); 
		 $.ajax({
		 url:'<?php echo base_url();?>Setting/Profile/update',
		 type:"post",
		 data:new FormData(this),
		 contentType: false,
		 cache: false,
		 processData:false,
		             
		 beforeSend: function (){
		 $(".loading2").show();
		 $(".loading2").modal('show');	
		 },
		 success: function(data){
		 $(".loading2").hide();
		 $(".loading2").modal('hide');
		 setTimeout(location.reload.bind(location), 500);		 
		 update_berhasil();
		 }
		 });
		 });
	});
	
	
	//Proses update password ajax
		
		$(document).ready(function(){
		$('#form-update-pass').submit(function(e){
		 e.preventDefault(); 
		 $.ajax({
		 url:'<?php echo base_url();?>Setting/Profile/update',
		 type:"post",
		 data:new FormData(this),
		 contentType: false,
		 cache: false,
		 processData:false,
		             
		 beforeSend: function (){
		 $(".loading2").show();
		 $(".loading2").modal('show');	
		 },
		 success: function(data){
		 $(".loading2").hide();
		 $(".loading2").modal('hide');	
		 update_berhasil();
		 }
		 });
		 });
	});
	
	
	

</script>