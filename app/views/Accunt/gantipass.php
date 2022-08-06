<div class="container mt-4">

    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="pass1" placeholder="password" required>
        <label for="pass1">Masukkan password lama</label>
    </div>

    <div id="inputPass"></div>

</div>

<script>

const pass1 = document.getElementById( 'pass1' );
const inputPlace = document.getElementById( 'inputPass' );
const token = '<?= $data['token'];?>';

function inputPass( data = null, alert = 'danger', texts = '' )
{
    if( data == null )
    {
        inputPlace.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts +'</div><button type="button" class="btn btn-primary" onclick="location.reload();">Masukkan Ulang</button>';
    } else
    {
        inputPlace.innerHTML = '<div class="form-floating mb-3"><input type="password" class="form-control" id="pass2" placeholder="password" required><label for="pass2">Masukkan password baru</label></div><div class="form-floating mb-3"><input type="password" class="form-control" id="pass3" placeholder="password" required><label for="pass3">Konfirmasi password baru</label></div><button type="button" class="btn btn-primary gantiPass">Rubah password</button>';
    }
};

pass1.onkeyup = function()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if( this.readyState == 4 && this.status == 200 )
        {
            var data = JSON.parse( this.responseText );
            inputPass( data.data, data.alert, data.text );
        }
    };
    xhttp.open( 'POST', '<?= BASEURL;?>accunt/checkPass', true );
    xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
    xhttp.send( 
        'email_user=' + '<?= $data['username'];?>' +
        '&key=' + this.value
    );
}

inputPlace.addEventListener( 'click', function(x) 
{
    if( x.target.classList.contains( 'gantiPass' ) )
    {
        var pass2 = this.firstElementChild.firstElementChild.value;
        var pass3 = this.firstElementChild.nextElementSibling.firstElementChild.value;
        if( pass2 == pass3 )
        {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function()
            {
                if( this.readyState == 4 && this.status == 200 )
                {
                    var data = JSON.parse( this.responseText );
                    if( data.data == null )
                    {
                        inputPass( null, 'danger', 'ada yang salah' );
                    } else {
                        location.href = '<?= BASEURL?>Accunt';
                    }
                }
            };
            xhttp.open( 'POST', '<?= BASEURL;?>accunt/passwordCHanger', true );
            xhttp.setRequestHeader( 'Content-Type' , 'application/x-www-form-urlencoded' );
            xhttp.send(
                'token=' + token +
                '&pass=' + pass2
            );
        } else {
            inputPass( null, 'danger', 'Password konfirmasi tidak sama' );
        }
    }
});

</script>