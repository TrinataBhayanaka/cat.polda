
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
	}

 $(document).ready(function() {
	$('#add-kategori').on('click', function(){
		bootbox.dialog({
			title: "Tambah Kategori Ruang Lingkup",
			message:'<div class="row"> ' + '<div class="col-md-12"> ' +
					'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
					'<label class="col-md-4 control-label" for="name">Kategori Ruang Lingkup</label> ' +
					'<div class="col-md-4"> ' +
					'<input id="kategori" name="kategori" type="text" placeholder="" class="form-control input-md" required="required"> ' +
					'</div> ' +
					'</div> ' + '<div class="form-group"> ' +
					'</div> </div>' + '</form> </div> </div><script></script>',
			buttons: {
				success: {
					label: "Simpan",
					className: "btn-info",
					callback: function() {
						var kategori = $('#kategori').val();
						//get values
						// alert(namagrup);
						// alert(answer);
						if(kategori != ''){
						$.post( basedomain+"pengaturan_admin/ajax_insert_kategori", { kategori: kategori} );
						
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : "Kategori Ruang Lingkup : " + kategori + ".<br> Berhasil Disimpan",
							container : 'floating',
							timer : 2000
						});
						setTimeout(
						  function() 
						  {
							location.reload();
						  }, 2000);
						
					}else{
					
						//alert( "isi Data" );
						bootbox.alert("Data Harus Diisi!", function(){
							//EMPTY
						});
					}
					}
				}
			}
		});

		$(".demo-modal-radio").niftyCheck();
	});
	
	//edit ajax kategori lingkup
	$('.kategori-edit').on('click', function(){
		var idKategori = $(this).attr("value");
		// alert(idGrup_kursus);
		$.post(basedomain+"pengaturan_admin/ajax_edit_kategori", {idKategori:idKategori}, function(data){
							// alert(data[0].namagrup);
							// console.log (data);
							$("#kategori").val(data[0].ruang_lingkup);
							$("#idKategori").val(idKategori);
					   },"JSON");
		bootbox.dialog({
			title: "Rubah Kategori Ruang Lingkup",
			message:'<div class="row"> ' + '<div class="col-md-12"> ' +
					'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
					'<label class="col-md-4 control-label" for="name">Kategori Ruang Lingkup</label> ' +
					'<div class="col-md-4"> ' +
					'<input id="kategori" name="kategori" type="text" placeholder="" class="form-control input-md" required="required"> ' +
					'<input id="idKategori" name="idKategori" type="hidden" placeholder="" class="form-control input-md" > ' +
					'</div> ' +
					'</div> ' + '<div class="form-group"> ' +
					'</div> </div>' + '</form> </div> </div><script></script>',
			buttons: {
				success: {
					label: "Update",
					className: "btn-info",
					callback: function() {
						var kategori = $('#kategori').val();
						var id = $("#idKategori").val();
						//get values
						// alert(namagrup);
						// alert(answer);
						if(kategori != '' && id != ''){
						$.post( basedomain+"pengaturan_admin/ajax_update_kategori", { kategori: kategori,id : id } );
						
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : "Kategori Ruang Lingkup : " + kategori + ".<br> Berhasil DiRubah",
							container : 'floating',
							timer : 2000
						});
						setTimeout(
						  function() 
						  {
							location.reload();
						  }, 2000);
						
					}else{
					
						//alert( "isi Data" );
						bootbox.alert("Data Harus Diisi!", function(){
							//EMPTY
						});
					}
					}
				}
			}
		});

		$(".demo-modal-radio").niftyCheck();
	});
	
	$('.delete-kategori').click( function () {
			var idKategori = $(this).attr("value");
			$.post(basedomain+"pengaturan_admin/count_sub", {idKategori:idKategori}, function(data){
							$("#kategori").val(data.jml);
					   },"JSON");
			// alert(countSub);
			//some modification
			// bootbox.confirm("Anda Yakin Untuk Menghapus Data?", function(result) {
			bootbox.dialog({
			//title: "Rubah Kategori Ruang Lingkup",
			message:'<div class="row"> ' + 
					'<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-5 control-label" for="name">Anda Yakin Untuk Menghapus Data?</label> ' +
						'</div> <div class="form-group"> ' +
						'<div class="col-md-6"> ' +
							'<input id="kategori" name="kategori" type="hidden" placeholder="" class="form-control input-md"  readonly> ' +
						'</div> ' +
					'</form> </div> </div><script></script>',
				buttons: {
				danger: {
					label: "Cancel",
					className: "",
					callback: function() {
						$.niftyNoty({
						type: 'danger',
						icon : 'fa fa-minus',
						message : 'Hapus Data Gagal!',
						container : 'floating',
						timer : 2000
						});
					}
				},
				success: {
					label: "Ok",
					className: "btn-info",
					callback: function() {
						var kategori = $("#kategori").val();
						//get values
						// alert(namagrup);
						// alert(answer);
						if(kategori != 0){
							//alert( "isi Data" );
						bootbox.alert("Hapus Data Kategori Sub Ruang Lingkup Terlebih Dahulu !!", function(){
							//EMPTY
						});
						
					}else{
						var Newkategori = idKategori;
						$.post( basedomain+"pengaturan_admin/ajax_update_status", { Newkategori: Newkategori} );
						
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : "Hapus Data Berhasil",
							container : 'floating',
							timer : 2000
						});
						setTimeout(
						  function() 
						  {
							location.reload();
						  }, 2000);
						
					}
				  }
				}
			}


			});
			
		
	});	
	
	
	$('#glosarium-delete-btn').click( function () {
		//rowDeletion.row('.selected').remove().draw( false );
		//var id = rowDeletion.cell('.selected', 2).data();
		//alert(id);
		 //console.log = id; 
	
		//solusi alternatif
		var vals = [];  // variable vals initialization as array
		//get all the checkboxes that are checked in one variable
		$('input:checkbox[name="check[]"]').each(function() {
			if (this.checked) {
				// push the element into array
				vals.push(this.value);
			}
		});
		var str = vals.join(",");
		//console.log = vals[0];
		 //alert(vals[0]);
		 //alert(str);
		if (!str) {
			//alert('Checked First!');
			bootbox.alert("Checked First!", function(){
				//EMPTY
			});
			return str;
		}else{
			
			//some modification
			bootbox.confirm("Are you sure want to remove data?", function(result) {
				if (result) {
					$.post( basedomain+"home/ajax_delete", { id: str} );
					$.niftyNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : 'Remove Data Successfully.',
						container : 'floating',
						timer : 2000
					});
					setTimeout(
				   function() 
				   {
					location.reload();
				   }, 2000);
				}else{
					$.niftyNoty({
						type: 'danger',
						icon : 'fa fa-minus',
						message : 'Remove Data Failed!',
						container : 'floating',
						timer : 2000
					});
				};


			});
			
		}
	});
	
	// BOOTBOX - CUSTOM HTML FORM
	// =================================================================
	// Require Bootbox
	// http://bootboxjs.com/
	// =================================================================

	$('#add-sub-kategori').on('click', function(){
		var idKategori = $(this).attr("value");
		$.post(basedomain+'pengaturan_admin/ajax_select_list',{idKategori:idKategori},function(data){
				// alert(data);
				// console.log(data);
				$("#idkategori").val(data[0].idKategori);
				$("#kategori").val(data[0].ruang_lingkup);
				
			},"JSON")
		bootbox.dialog({
			title: "Tambah Kategori Sub Ruang Lingkup",
			message:'<div class="row"> ' + 
					'<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-5 control-label" for="name">Kategori Ruang Lingkup</label> ' +
						'<div class="col-md-6"> ' +
							'<input id="kategori" name="kategori" type="text" placeholder="" class="form-control input-md"  readonly> ' +
							'<input id="idkategori" name="idkategori" type="hidden" placeholder="" class="form-control input-md"> ' +
						'</div> ' +
						'</div> <div class="form-group"> ' +
						'<label class="col-md-5 control-label" for="name">Kategori Sub Ruang Lingkup</label> ' +
						'<div class="col-md-6"> ' +
							'<input id="subkategori" name="subkategori" type="text" placeholder="" class="form-control input-md" required="required"> ' +
						'</div> </div> ' +
					'</form> </div> </div><script></script>',
			buttons: {
				success: {
					label: "Simpan",
					className: "btn-info",
					callback: function() {
						var kategori = $('#idkategori').val();
						var subkategori = $("#subkategori").val();
						//get values
						// alert(namagrup);
						// alert(answer);
						if(idkategori != '' && subkategori != '' && kategori != ''){
						$.post( basedomain+"pengaturan_admin/ajax_insert_sub_kategori", { kategori: kategori,subkategori : subkategori } );
						
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : "Kategori Sub Ruang Lingkup : " + subkategori + ".<br> Berhasil DiSimpan",
							container : 'floating',
							timer : 2000
						});
						setTimeout(
						  function() 
						  {
							location.reload();
						  }, 2000);
						
					}else{
					
						//alert( "isi Data" );
						bootbox.alert("Data Harus Diisi!", function(){
							//EMPTY
						});
					}
					}
				}
			}
		});
		
		$(".demo-modal-radio").niftyCheck();
		// $("#selectkategori").selectpicker('refresh');	
	});
	
	//edit ajax kategori lingkup
	$('.kategori-sub-edit').on('click', function(){
		var idKategori = $(this).attr("value");
		// alert(idGrup_kursus);
		$.post(basedomain+"pengaturan_admin/ajax_edit_kategori_sub", {idKategori:idKategori}, function(data){
							$("#kategori").val(data.kategori.ruang_lingkup);
							$("#subkategori").val(data.subkategori.ruang_lingkup);
							$("#idsubkategori").val(data.subkategori.idKategori);
							
					   },"JSON");
		bootbox.dialog({
			title: "Rubah Kategori Sub Ruang Lingkup",
			message:'<div class="row"> ' + 
					'<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-5 control-label" for="name">Kategori Ruang Lingkup</label> ' +
						'<div class="col-md-6"> ' +
							'<input id="kategori" name="kategori" type="text" placeholder="" class="form-control input-md"  readonly> ' +
						'</div> ' +
						'</div> <div class="form-group"> ' +
						'<label class="col-md-5 control-label" for="name">Kategori Sub Ruang Lingkup</label> ' +
						'<div class="col-md-6"> ' +
							'<input id="subkategori" name="subkategori" type="text" placeholder="" class="form-control input-md"> ' +
								'<input id="idsubkategori" name="idsubkategori" type="hidden" placeholder="" class="form-control input-md" required="required"> ' +
						
						'</div> </div> ' +
					'</form> </div> </div><script></script>',
			buttons: {
				success: {
					label: "Update",
					className: "btn-info",
					callback: function() {
						var subkategori = $('#subkategori').val();
						var idsubkategori = $("#idsubkategori").val();
						//get values
						// alert(namagrup);
						// alert(answer);
						if(subkategori != '' && idsubkategori != ''){
						$.post( basedomain+"pengaturan_admin/ajax_update_kategori_sub", {subkategori : subkategori, idsubkategori : idsubkategori} );
						
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : "Kategori Sub Ruang Lingkup : " + subkategori + ".<br> Berhasil DiRubah",
							container : 'floating',
							timer : 2000
						});
						setTimeout(
						  function() 
						  {
							location.reload();
						  }, 2000);
						
					}else{
					
						//alert( "isi Data" );
						bootbox.alert("Data Harus Diisi!", function(){
							//EMPTY
						});
					}
					}
				}
			}
		});

		$(".demo-modal-radio").niftyCheck();
	});
		
	$('.delete-sub').click( function () {
			var idSubKategori = $(this).attr("value");
			//some modification
			bootbox.confirm("Anda Yakin Untuk Menghapus Data?", function(result) {
				if (result) {
					$.post( basedomain+"pengaturan_admin/ajax_delete_sub", { idSubKategori: idSubKategori} );
					$.niftyNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : 'Hapus Data Berhasil.',
						container : 'floating',
						timer : 2000
					});
					setTimeout(
				   function() 
				   {
					location.reload();
				   }, 2000);
				}else{
					$.niftyNoty({
						type: 'danger',
						icon : 'fa fa-minus',
						message : 'Hapus Data Gagal!',
						container : 'floating',
						timer : 2000
					});
				};


			});
			
		
	});	
	
	
	
 })
 

