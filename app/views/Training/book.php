<div class="container mt-5">

    <div class="form-floating mt-3">
        <select class="form-select" id="trainingTime">
            <option value="01" selected>Satu Bulan</option>
            <option value="02">Dua Bulan</option>
            <option value="03">Tiga Bulan</option>
            <option value="04">Empat Bulan</option>
            <option value="05">Lima Bulan</option>
            <option value="06">Satu Semester</option>
            <option value="07">Tujuh Bulan</option>
            <option value="08">Delapan Bulan</option>
            <option value="09">Sembilan Bulan</option>
            <option value="10">Sepuluh Bulan</option>
            <option value="11">Sebelas Bulan</option>
            <option value="12">Dua Semester</option>
        </select>
        <label for="trainingTime">Waktu Training:</label>
    </div>

    <div class="form-floating mt-3">
        <select class="form-select" id="trainingStart">
            <option value="01" selected>Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">Nopember</option>
            <option value="12">Desember</option>
        </select>
        <label for="trainingStart">Training dimulai pada bulan:</label>
    </div>

    <button type="button" class="btn btn-primary mt-3 col-sm-3" id="book">Book</button>
        
</div>

<div class="mt-4" id="notif"></div>

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
        text.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts + '<br/> <a href="' + data + '">' + data + '</a></div>';
        text.classList.add('container');
        notifPlace.appendChild(text);
    }
};

document.getElementById( 'book' ).onclick = function()
{

    var trainingStart = document.getElementById( 'trainingStart' ).value;
    var trainingTime = document.getElementById( 'trainingTime' ).value;
    var training = "<?= $data['produk'];?>";// diambil dari data controller
    var pelanggan = "<?= $data['pengguna'];?>";// diambil dari cookie[token] bila sudah login
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if( this.readyState == 4 && this.status == 200 ){

            var dataphp = JSON.parse(this.responseText);
            notif( dataphp.data, dataphp.alert, dataphp.text );
            // alert( this.responseText );
        };
    };
    xhttp.open( "POST", "<?= BASEURL;?>training/getTokenPayment", true );
    xhttp.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
    xhttp.send( 
        "trainingStart=" + trainingStart +
        "&trainingTime=" + trainingTime +
        "&training=" + training +
        "&pelanggan=" + pelanggan
    );
};

</script>