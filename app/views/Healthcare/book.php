<div class="container mt-5">

    <div class="input-group mb-3">
        <input type="text" class="form-control" id="pasien_nik" placeholder="KTP/No Induk Kesehatan Nasional" autocomplete="off" required>
    </div>
    
    <div class="col-md-auto mt-5 mb-5">
        <button type="submit" class="btn btn-primary" id="daftarAntrian">
            Ambil Antrian
        </button>
    </div>

</div>

<div id="notif"></div>

<script>

function notif( data = null, alert = 'danger', texts = '' )
{
    if( data == null )
    {
        var notifPlace = document.getElementById('notif');
        var text = document.createElement('div');
        text.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        text.classList.add('container');
        notifPlace.appendChild(text);
    } else{
        var notifPlace = document.getElementById('notif');
        var text = document.createElement('div');
        text.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts + '<br/>' + data + '</div>';
        text.classList.add('container');
        notifPlace.appendChild(text);
    }
};

document.getElementById( 'daftarAntrian' ).onclick = function()
{
    var pasien_nik = document.getElementById( 'pasien_nik' ).value;
    if( pasien_nik == '' )
    {
        notif( null, 'danger', 'Masukkan NIK terlebih dahulu' );
    } else
    {
        var pasien_need = "<?= $data['produk'];?>";// diambil dari data controller
        var pelanggan = "<?= $data['pengguna'];?>";// diambil dari cookie[token] bila sudah login
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if( this.readyState == 4 && this.status == 200 ){

                var dataphp = JSON.parse(this.responseText);
                notif( dataphp.data, dataphp.alert, dataphp.text );
                // alert( this.responseText );
            };
        };
        xhttp.open( "POST", "<?= BASEURL;?>healthcare/getNomorAntrian", true );
        xhttp.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
        xhttp.send( 
            "pasien_nik=" + pasien_nik +
            "&pasien_need=" + pasien_need +
            "&pasien_token=" + pelanggan
        );
    }
}

</script>