
$(document).ready(function() {

    // $(".cd-popup-trigger").trigger('click');
    var loginoptions = {
            dataType:  'json',  
            beforeSubmit: function(data) { 
                
                var loading = "<img src='"+basedomain+"assets/images/loading.gif' width='40%'/>";
                 // loading += "<p>Please Wait ...</p>";
                // $('#imgupload').css('height','100%');
                $('.popuptext').html(loading);
                $(".cd-popup-trigger").trigger('click');
                
            },
            success : function(data) {

                if(data.status==true){
                    if (data.flag==true){
                        redirect(basedomain+"account/pelaporan");
                    }else{
                        redirect(basedomain+"account");
                    }
                              
                } else {
                    $('.popuptext').html("Username atau Password salah");
                    
                }
                         
            }
        };  

    // $("#loginForm").ajaxForm(loginoptions);

    var resetoptions = {
            dataType:  'json',  
            beforeSubmit: function(data) { 
                
                var loading = "<img src='"+basedomain+"assets/images/loading.gif' width='50%'/>";
                 loading += "<p>Please Wait ...</p>";
                // $('#imgupload').css('height','100%');
                $('.popuptext').html(loading);
                $(".cd-popup-trigger").trigger('click');
                
            },
            success : function(data) {

                if(data.status==true){
                    $('.popuptext').html("Silahkan verifikasi email anda untuk melanjutkan");            
                } else {
                    $('.popuptext').html("Email tidak terdaftar");
                    
                }
                         
            }
        };  

    // $("#resetakun").ajaxForm(resetoptions);

});

function submit_confirm(txt)
{
    var txt;
    if (txt) txt = txt;
    else txt = "Simpan data ?";
    var r = confirm(txt);
    if (r == true) {
        // do something
    } else {
        return false;
    }
}

function clog(data)
{
    console.log(data);
}

function redirect(data)
{
    window.location.href=data;
}

function readURLpose(input, target) {
    console.log(input);
    if (input.files && input.files[0]) {

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+target).attr('src', e.target.result);
            $('#'+target).attr('width', '100px');
            // $('#'+target).attr('height', '200px');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/* account pelaporan kemasan */

$(document).on('change', '#lokasipabrik', function(){


      var id = $(this).val();

      $.post(basedomain+'account/ajaxPabrik',{kode_wilayah:id}, function(data){

        var html = "";

        if (data.status==true){
          
          var hasil = data.res;
          $('.noNPPBKC').val(hasil.pabrik.noNPPBKC);
          $('.namaJalan').val(hasil.pabrik.namaJalan);
          $('.kecamatan').val(hasil.pabrik.kecamatan);
          $('.noFax').val(hasil.ind.noFax);
          $('.namaPimpinan').val(hasil.ind.namaPimpinan);
          $('.industriID').val(hasil.ind.id);
          $('.pabrikID').val(hasil.pabrik.id);
          
        }else{
          $('.noNPPBKC').val('');
          $('.namaJalan').val('');
          $('.kecamatan').val('');
          $('.noFax').val('');
          $('.namaPimpinan').val('');
          $('.industriID').val('');
          $('.pabrikID').val('');
        } 
        
      }, "JSON")  

    })

    $(document).on('click', '.tambah_data_kemasan', function(){
      
        $('#info_produsen').css('display','block');
        $('#info_produk').css('display','block');
        
    }) 
    $(document).on('click', '.cancel_kemasan', function(){
        $('#info_produsen').css('display','none');
          $('#info_produk').css('display','none');
    }) 

$(document).ready(function(){
  $("#search-box").keyup(function(){
    $.ajax({
    type: "POST",
    url: basedomain+"account/ajax_getMerek",
    data:'keyword='+$(this).val(),
    beforeSend: function(){
      $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
    },
    success: function(data){
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data);
      $("#search-box").css("background","#FFF");
    }
    });
  });
});



$(document).on('change', '#fotoDepan', function(){
    readURLpose(this, "previewDepan");
});

$(document).on('dblclick', '.passwordAdmin', function(){
    
    var html =""
    html += "<input type='text'  class='form-control passAdmin' placeholder='Password harus diisi dengan 6-8 digit alphanumeric ' name='pass' >";
    $(this).html(html);
});

$(document).on('blur', '.passAdmin', function(){
    var html =""
    html += "<label>***** (Klik 2x untuk edit)</label>";
    $(".passwordAdmin").html(html);
});