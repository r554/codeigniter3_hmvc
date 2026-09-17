<?php $this->load->view('_heading/_headerContent') ?>

<section class="content">

<div class="box">
  <div class="box-header">
    
  </div>
  <!-- /.box-header -->
  <div class="box-body">
  
   <form action="<?= base_url() ?>Setting/Akses/update_hak_akses/<?= $grup_id; ?>" method="post">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
         <th width='5%'><center>#</center></th>
		 <th width='40%'>Nama Menu</th>
		 <th width='7%'><center>View</center></th>
		 <th width='7%'><center>Add</center></th>
		 <th width='7%'><center>Edit</center></th>
		 <th width='7%'><center>Delete</center></th>
        </tr>
      </thead>
       
	   <tbody>
		<?php
		$no = 1;
		$current_parent = null;
		foreach ($privilege as $row):
			// Tentukan parent label: jika parent kosong = ini main menu, jika ada = ini sub menu
			$parent_label = !empty($row->parent) ? $row->menuparent : $row->nama_menu;

			// Tampilkan header section saat parent berubah
			if ($parent_label !== $current_parent):
				$current_parent = $parent_label;
		?>
			<tr style="background-color: #f4f4f4;">
				<td colspan="6" style="padding: 8px 10px;">
					<strong><i class="fa fa-folder-open-o"></i> <?= htmlspecialchars($parent_label) ?></strong>
				</td>
			</tr>
		<?php endif; ?>
		<?php
			// Hanya tampilkan sub menu (yang punya parent), bukan main menu itu sendiri sebagai baris
			// Kecuali main menu yang tidak punya child (standalone)
			$view_pos  = strpos($row->menu_file, 'iew');
			$view      = ($row->view == 1 && $view_pos !== false) ? 'checked' : '';
			$viewavail = ($view_pos !== false) ? '' : 'disabled';

			$add_pos   = strpos($row->menu_file, 'dd');
			$add       = ($row->add == 1 && $add_pos !== false) ? 'checked' : '';
			$addavail  = ($add_pos !== false) ? '' : 'disabled';

			$edit_pos  = strpos($row->menu_file, 'dit');
			$edit      = ($row->edit == 1 && $edit_pos !== false) ? 'checked' : '';
			$editavail = ($edit_pos !== false) ? '' : 'disabled';

			$del_pos   = strpos($row->menu_file, 'el');
			$del       = ($row->del == 1 && $del_pos !== false) ? 'checked' : '';
			$delavail  = ($del_pos !== false) ? '' : 'disabled';

			$indent    = !empty($row->parent) ? 'padding-left: 25px;' : 'font-weight: bold;';
		?>
		<tr class="odd gradeX">
			<input type="hidden" name="id_menu[]" value="<?= $row->menus ?>" />
			<input type="hidden" name="grup_id" />
			<td><center><?= $no ?></center></td>
			<td style="<?= $indent ?>">
				<?php if (!empty($row->parent)): ?>
					<i class="fa fa-level-up fa-rotate-90 text-muted"></i>&nbsp;
				<?php endif; ?>
				<?= htmlspecialchars($row->nama_menu) ?>
			</td>
			<td class="center"><center><input type="checkbox" class="flat-red" value="1" name="view[<?= $row->menus ?>]" <?= $view ?> <?= $viewavail ?> /></center></td>
			<td class="center"><center><input type="checkbox" class="flat-red" value="1" name="add[<?= $row->menus ?>]" <?= $add ?> <?= $addavail ?> /></center></td>
			<td class="center"><center><input type="checkbox" class="flat-red" value="1" name="edit[<?= $row->menus ?>]" <?= $edit ?> <?= $editavail ?> /></center></td>
			<td class="center"><center><input type="checkbox" class="flat-red" value="1" name="del[<?= $row->menus ?>]" <?= $del ?> <?= $delavail ?> /></center></td>
		</tr>
		<?php $no++; endforeach; ?>
		</tbody>
	   
       </table>
	    <div class="box-footer">
        <button type="submit" class="btn btn-sm btn-primary klik-custom"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-retweet"></i> Cancel</button>
        </div>
	</form>
  </div>
</div>

</section>

  <script>
  $(document).ready(function(){
  $('.klik-custom').click(function() {
  var url = $(this).attr('href');			
  $.ajax({
  complete: function(){
  save_berhasil();
  }
  });	
  return true;
  });
  });		
  </script>

  <script>
  //Flat red color scheme for iCheck
  $(function() {
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
	});
		
	 $(function() {
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    });
		});	
		
		
	</script>







