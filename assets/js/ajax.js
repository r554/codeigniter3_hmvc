

//untuk notifikasi berhasil
		
		function save_berhasil()
		{
		swal({
		position: 'top',
		type: 'success',
		title: 'Data Berhasil Disimpan',
		showConfirmButton: false,
		timer: 1500
		});
		} 
		
		//untuk notifikasi hapus berhasil
		
		function hapus_berhasil()
		{
		swal({
		position: 'top',
		type: 'success',
		title: 'Data Berhasil Dihapus',
		showConfirmButton: false,
		timer: 1500
		});
		}
		
		//untuk notifikasi gagal
		
		function gagal()
		{
		swal({
		position: 'top',
		type: 'error',
		title: 'Data gagal di simpan',
		showConfirmButton: false,
		timer: 1000
		});
		} 
		
		//untuk notifikasi peringatan
		
		function peringatan()
		{
		swal({
		position: 'top',
		type: 'warning',
		title: 'Peringatan',
		text: 'No Telp / Username Sudah digunakan Employe lain',
		showConfirmButton: false,
		timer: 2100
		});
		} 

		//untuk notifikasi peringatan
		
		function format()
		{
		swal({
		position: 'top',
		title: 'Peringatan',
		text: 'File tidak sesuai dengan format !!!',
		showConfirmButton: false,
		timer: 1500
		});
		} 
		
		//untuk notifikasi update berhasil
		
		function update_berhasil()
		{
		swal({
		position: 'top',
		type: 'success',
		title: 'Data Berhasil Diupdate',
		showConfirmButton: false,
		timer: 1500
		});
		} 
		
		
		//untuk notifikasi upload berhasil
		
		function upload_berhasil()
		{
		swal({
		position: 'top',
		type: 'success',
		title: 'Data Berhasil Diupload',
		showConfirmButton: false,
		timer: 1500
		});
		} 
		
		
		//untuk live gambar ajax slider	
		
	function fileValidation(){
    var fileInput = document.getElementById('gambar');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('maaf masukan gambar dengan format .jpeg/.jpg/.png/.gif only.');
        fileInput.value = '';
        return false;
    }else{
        //Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('slider').innerHTML = '<img src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
			}
		}
	}
	
	
	
	//untuk live gambar ajax profil
		
	function fileFoto(){
    var fileInput = document.getElementById('foto');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('maaf masukan gambar dengan format .jpeg/.jpg/.png/.gif only.');
        fileInput.value = '';
        return false;
    }else{
        //Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile').innerHTML = '<img src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
			}
		}
	}
	
	
	
	//untuk live gambar ajax produk
		
	function fileProduk(){
    var fileInput = document.getElementById('gambar');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('maaf masukan gambar dengan format .jpeg/.jpg/.png/.gif only.');
        fileInput.value = '';
        return false;
    }else{
        //Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('produk').innerHTML = '<img src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
			}
		}
	}
	
	
	//untuk live gambar detail_produk
		
	function detailproduk(){
    var fileInput = document.getElementById('gambar');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('maaf masukan gambar dengan format .jpeg/.jpg/.png/.gif only.');
        fileInput.value = '';
        return false;
    }else{
        //Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('detail-produk').innerHTML = '<img src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
			}
		}
	}
	
