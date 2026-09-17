	<script type="text/javascript">
	
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
	
	
	
	// Animasi angka bergerak dashboard
	
	$('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 1000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
		});
	});
	
	
		// Ajax loading 1

			  $(document).ready(function(){
                $('.klik').click(function() {
                    var url = $(this).attr('href');	
					$("#loading2").modal('show');				
					$.ajax({
					complete: function(){
					$("#loading2").FadeOut();
					}
					});	
					return true;
                });
            });
			
			
			// Ajax loading 2
			 $(document).ready(function(){
                $('.klik').click(function() {
                    var url = $(this).attr('href');
					$(".loading2").show();					
					$.ajax({
					complete: function(){
					$(".loading2").hide();
					}
					});	
					return true;
                });
            });	
			
			
			// Ajax loading spesial
			 $(document).ready(function(){
                $('.klik-custom').click(function() {
                    var url = $(this).attr('href');
					save_berhasil();					
					$.ajax({
					complete: function(){
					}
					});	
					return true;
                });
            });	
			
			
	// text editor summernote
		
		$(document).ready(function() {
        $('#summernote').summernote({
            height: 200
        });
		});
	
		$(document).ready(function() {
        $('#summernote2').summernote({
            height: 200
		});
		});
	
		$(document).ready(function() {
        $('#summernote3').summernote({
            height: 200
        });
		});
		
		$(document).ready(function() {
        $('#summernote4').summernote({
            height: 200
        });
		});
		
		$(document).ready(function() {
        $('#summernote5').summernote({
            height: 200
        });
		});
		
		
		
		// untuk select2 original
		$(function () 
		{
		$(".select2").select2({
        });
		});
		
		
		
	// untuk select2 ajak pilih kategori
		$(function () 
		{
		$(".select2-cat").select2({
        placeholder: " -- Pilih Kategori Motor -- ",
		allowClear: true
        });
		});
		
		$(function () 
		{
		$(".select2-warna").select2({
        placeholder: " -- Cari warna / Nama motor -- ",
		allowClear: true
        });
		});
		
		
		// untuk select2 ajak pilih menu
		$(function () 
		{
		$(".select-menu").select2({
        placeholder: " -- Pilih Jenis Menu -- "
        });
		});
		
		// untuk select2 ajak provinsi
		$(function () 
		{
		$(".select-provinsi").select2({
        placeholder: " -- Pilih Provinsi -- "
        });
		});
		
		// untuk select2 ajak kabupaten
		$(function () 
		{
		$(".select-kota").select2({
        placeholder: " -- Pilih Kota/Kabupaten -- "
        });
		});
		
		// untuk select2 ajak kecamatan
		$(function () 
		{
		$(".select-kecamatan").select2({
        placeholder: " -- Pilih Kecamatan -- "
        });
		});
		
		// untuk select2 ajak desa
		$(function () 
		{
		$(".select-desa").select2({
        placeholder: " -- Pilih Kelurahan/Desa -- "
        });
		});
		
		
	
	
	
	
	
	// ----------------- ajak Kategori------------- //	
		
	$(document).ready(function(){
    $('.select-kategori').select2({
    placeholder: 'Pilih Kategori Motor',
    allowClear: true,
    ajax:{
    url: "<?php echo base_url('Kategori/Cat_produk/kategori_ajak') ?>",
    dataType: "json",
    delay: 250,
    data: function(params){
    return {
         cari: params.term
     };
     },
     processResults: function(data){
     var results = [];

     $.each(data, function(index, item){
     results.push({
     id: item.id_cat_produk,
     text: item.nama_cat_produk
     });
     });
     return{
      results: results
      };
      }
     }
    });
  });
  
  
  	$(document).ready(function(){
    $('.select-kategori-multi').select2({
    placeholder: 'Pilih Kategori Motor',
	multiple: true,
    ajax:{
    url: "<?php echo base_url('Kategori/Cat_produk/kategori_ajak') ?>",
    dataType: "json",
    delay: 250,
    data: function(params){
    return {
         cari: params.term
     };
     },
     processResults: function(data){
     var results = [];

     $.each(data, function(index, item){
     results.push({
     id: item.id_cat_produk,
     text: item.nama_cat_produk
     });
     });
     return{
      results: results
      };
      }
     }
    });
  });


	
// ----------------- ajak menu ------------- //	
		
	
	$(document).ready(function(){
    $('.select2-menu').select2({
    placeholder: 'Pilih Sub Menu',
    allowClear: true,
    ajax:{
    url: "<?php echo base_url('Setting/Menu/menu_ajak') ?>",
    dataType: "json",
    delay: 250,
    data: function(params){
    return {
         cari: params.term
     };
     },
     processResults: function(data){
     var results = [];

     $.each(data, function(index, item){
     results.push({
     id: item.id_menu,
     text: item.nama_menu
     });
     });
     return{
      results: results
      };
      }
     }
    });
  });	
		
		
		
 // ----------------- select 2 remote data ajak overview ------------- //
		
	
	// untuk select2 ajak produk overview(matic)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-matic-overview").select2({   
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_matic') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak edit produk overview(matic)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-matic-overview").select2({   
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_matic') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	



// untuk select2 ajak produk overview (maxi)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-maxi-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_maxi') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	

	
// untuk select2 ajak produk edit overview (maxi)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-maxi-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_maxi') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk overview (naked)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-naked-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_naked') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit overview (naked)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-naked-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_naked') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


	
// untuk select2 ajak produk overview (sport)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
	
	
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-sport-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_sport') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	



// untuk select2 ajak produk edit overview (sport)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
	
	
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-sport-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_sport') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	



// untuk select2 ajak produk overview (moviestar)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-moviestar-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_moviestar') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit overview (moviestar)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-moviestar-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_moviestar') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk overview (moped)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-moped-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_moped') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit overview (moped)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-moped-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_moped') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	



// untuk select2 ajak produk overview (cbu)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-cbu-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_matic') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit overview (cbu)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' +
	'<div class="col-sm-9">' + data.judul_motor + '</div>' +
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select-edit-cbu-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_matic') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Overview ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak edit overview all
	
	$(document).ready(function(){
	
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul + '</b></div>' + 
	'<div class="col-sm-9"><font size="2px"><b>' + data.judul_motor + '</b></font></div>' + 
	'<div class="col-sm-9">' + data.content + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.judul || data.text;
	}	
	
	$(".select2-edit-overview").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Overview/cari_ajak_all') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_overview; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " -- Pilih Overview -- ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// ----------------- select 2 remote data ajak kategori warna ------------- //

// untuk select2 ajak produk warna (matic)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-matic").select2({   
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_matic') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (matic)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-matic").select2({   
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_matic') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	





// untuk select2 ajak produk warna (maxi)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-maxi").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_maxi') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (maxi)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-maxi").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_maxi') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk warna (naked)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-naked").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_naked') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (naked)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-naked").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_naked') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


	
// untuk select2 ajak produk warna (sport)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
	
	
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-sport").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_sport') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (sport)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
	
	
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-sport").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_sport') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	



// untuk select2 ajak produk warna (moviestar)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-moviestar").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_moviestar') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (moviestar)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-moviestar").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_moviestar') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk warna (moped)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-moped").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_moped') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (moped)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-moped").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_moped') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	



// untuk select2 ajak produk warna (cbu)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-cbu").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_cbu') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


// untuk select2 ajak produk edit warna (cbu)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select-edit-warna-cbu").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_cbu') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	


 // untuk select2 ajak edit produk warna (all)
	
	$(document).ready(function(){
	function carigambar (data) {
	if (data.loading) return data.text; 
    var template = '<div class="clearfix">' +
	'<div class="col-sm-3">' +
    '<img src="../../jajal/' + data.gambar + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-9"><b>' + data.judul_kategori + '</b></div>' +
	'<div class="col-sm-9">' + data.nama_warna + '</div>' +
    '</div>';
    template += '</div></div>';
    return template;
	}
	function seleksigambar (data) {
    return data.nama_warna || data.text;
	}	
	
	$(".select2-edit-warna").select2({
	ajax: {
    url: "<?php echo base_url('Kategori/Cat_warna/cari_ajak_all') ?>",
    dataType: 'json',
    delay: 250,
	type: 'GET',
    data: function (params) {
          return {
                q: params.term,
				page: params.page
            };
        },
        processResults: function (data,params) {
		data.forEach(function (d, i) {
        //definisi id untuk data ajax
        d.id = d.id_warna; })
        return {results: data };
		},
    cache: true
    },
	placeholder: " Pilih Warna ",
	multiple: true,
	escapeMarkup: function (template) { return template; }, // let our custom formatter work
	templateResult: carigambar,
	templateSelection: seleksigambar
	 
});
});	

// ----------------- select 2 remote data ajak ------------- //	
	
	
	//ajak select chain kota dan provinsi
		
		
		$(function(){
		$("#loadingImg").hide();
		$("#loadingImg-kota").hide();
		$("#loadingImg-kecamatan").hide();

		$.ajaxSetup({
		type:"POST",
		url: "<?php echo base_url('Support/Member/ambil_data') ?>",
		cache: false,
		});

		$("#provinsi").change(function(){

		var value=$(this).val();
		if(value>0){
		$.ajax({
		beforeSend: function (){
		$("#loadingImg").fadeIn();
        },
		data:{modul:'kabupaten',id:value},
		success: function(respond){
		$("#kabupaten-kota").html(respond);
		$("#loadingImg").fadeOut();	
		}
		})
		}
		});
		
		
		$("#kabupaten-kota").change(function(){
		var value=$(this).val();
		if(value>0){
		$.ajax({
		beforeSend: function (){
		$("#loadingImg-kota").fadeIn();
        },
		data:{modul:'kecamatan',id:value},
		success: function(respond){
		$("#kecamatan").html(respond);
		$("#loadingImg-kota").fadeOut();	
		}
		})
		}
		});
		
		
		$("#kecamatan").change(function(){
		var value=$(this).val();
		if(value>0){
		$.ajax({
		beforeSend: function (){
		$("#loadingImg-kecamatan").fadeIn();
        },
		data:{modul:'kelurahan',id:value},
		success: function(respond){
		$("#kelurahan-desa").html(respond);
		$("#loadingImg-kecamatan").fadeOut();
		}
		})
		} 
		});
		
		
		})
		
		
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
	
	
</script>