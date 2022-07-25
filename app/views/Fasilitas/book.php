
<div class="container">

    <div class="input-group mb-3">
        <label class="input-group-text" for="dateBook">Tanggal Booking</label>
        <input type="date" name="dateBook" id="dateBook">
    </div>

    <div id="tempatTime"></div>

</div>

<div id="notif"></div>

<script>

const tempat = document.getElementById( 'tempatTime' );
const timeBooking = document.getElementById( 'dateBook' );
const user_token = 'iguiewf7ewfiuewhf98hiu4h8n4n984f4f894jf94';
const produk = '<?= $data['produk'];?>';

function autoTime( satu = null, dua = null, tiga = null )
{
    tempat.innerHTML = '<div class="form-floating mb-3"><select class="form-select" id="fasilitasTime"><option value="01" ' + satu + '>08.00 - 11.30</option><option value="02" ' + dua + '>12.30 - 16.00</option><option value="03" ' + tiga + '>17.00 - 21.30</option></select><label for="fasilitasTime">Booking pada jam:</label></div><button type="button" class="btn btn-primary mt-3 col-sm-3 bookNow">Book</button>'
}

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

timeBooking.onchange = function()
{
    var target = this.value;
    if( target == '' )
    {
        tempat.innerHTML = '';
    } else 
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onload = function()
        {
            var dataJSON = JSON.parse( this.responseText );
            autoTime( dataJSON[0]['01'], dataJSON[1]['02'], dataJSON[2]['03'] );
        };
        xhttp.open( 'GET', '<?= BASEURL;?>fasilitas/getDataFasilitasBooked/' + produk + '/' + target, true );
        xhttp.send();
    }

}

tempat.addEventListener( 'click', function(p)
{
    if( p.target.classList.contains( 'bookNow' ) )
    {
        var bookingTime = timeBooking.value;
        var times = this.firstElementChild.firstElementChild;
        var bookingTimes = times.value;
        
        if( bookingTimes == ''  )
        {
            timeBooking.value = '';
        } else
        {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function()
            {
                if( this.readyState == 4 && this.status == 200 )
                {
                    var dataphp = JSON.parse(this.responseText);
                    notif( dataphp.data, dataphp.alert, dataphp.text );
                }
            };
            xhttp.open( 'POST', '<?= BASEURL;?>fasilitas/getTokenPayment', true );
            xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
            xhttp.send(
                'user_token=' + user_token +
                '&produk_id=' + produk +
                '&book_start=' + bookingTime +
                '&book_timer=' + bookingTimes
    
            );
        }
    }
} );

</script>