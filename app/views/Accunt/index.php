<div class="container mt-4">

    <div class="row">

        <div class="col">

            <h2 class="mb-3">Log In :</h2>    

            <div class="input-group mb-3">
                <input type="text" class="form-control" id="user-email-login" placeholder="Username/Email" required>
            </div>

            <div class="input-group mb-3">
                <input type="password" class="form-control" id="password-login" placeholder="Password" required>
            </div>

            <button type="button" class="btn btn-primary" id="login">Log In</button>

        </div>

        <div class="col">
            <h2 class="mb-4">Register :</h2>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">#</span>
                <input type="text" class="form-control" id="username" placeholder="Username" autocomplete="off" required>
            </div>
        
            <div class="input-group mb-3">
                <input type="email" class="form-control" id="email" placeholder="Email" autocomplete="off" required>
                <span class="input-group-text" id="basic-addon2">@example.com</span>
            </div>     
        
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">(+62)</span>
                <input type="text" class="form-control" id="no_hp" placeholder="No HP/WA" autocomplete="off" required>
            </div>
        
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Alamat :</span>
                <input type="text" class="form-control" id="alamat" placeholder="jl. pojok kota....." autocomplete="off" required>
            </div>
        
            <div class="input-group mb-3">
                <input type="password" class="form-control" id="pass1" placeholder="Password" required>
            </div>
        
            <div class="input-group mb-3">
                <input type="password" class="form-control" id="pass2" placeholder="Confirm Password" required>
            </div>
        
            <button type="button" class="btn btn-primary" id="daftar">Daftar</button>

        </div>

    </div>


</div>

<div id="notif"></div>

<script>
function notifLogin( data = null, alert = 'danger', texts = '' )
{
    if( data == null )
    {
        var notifPlace = document.getElementById('notif');
        var text = document.createElement('div');
        text.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        text.classList.add('container');
        notifPlace.appendChild(text);
    } else{
        location.href = '<?= BASEURL;?>';
    }
};
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
        text.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts + '</div>';
        text.classList.add('container');
        notifPlace.appendChild(text);
    }
};
document.getElementById( 'daftar' ).onclick = function()
{
    var pass1 = document.getElementById( 'pass1' ).value;
    var pass2 = document.getElementById( 'pass2' ).value;
    if( pass1 === pass2 )
    {
        var user_name = document.getElementById( 'username' ).value;
        var email = document.getElementById( 'email' ).value;
        var no_hp = document.getElementById( 'no_hp' ).value;
        var pass = pass1;
        var alamat = document.getElementById( 'alamat' ).value;
        var xhtml = new XMLHttpRequest();
        xhtml.onreadystatechange = function()
        {
            if( this.readyState == 4 && this.status == 200 )
            {
                var dataphp = JSON.parse(this.responseText);
                notif( dataphp.data, dataphp.alert, dataphp.text );
                // alert( this.responseText );
            };
        };
        xhtml.open( "POST", "<?= BASEURL;?>accunt/register", true );
        xhtml.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
        xhtml.send( "user_name=" + user_name +
        "&email=" + email +
        "&no_hp=" + no_hp +
        "&pass=" + pass +
        "&alamat=" + alamat 
        );
    } else {
        notif( null, 'danger', 'Password confirm tidak sama' );
    }
};

document.getElementById( 'login' ).onclick = function()
{
    var email_user = document.getElementById( 'user-email-login' ).value;
    var password_user = document.getElementById( 'password-login' ).value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if( this.readyState == 4 && this.status == 200 )
        {
            var dataphp = JSON.parse(this.responseText);
            notifLogin( dataphp.data, dataphp.alert, dataphp.text );
            location.href = '<?= BASEURL;?>accunt';
            // alert( this.responseText );
        };
    };
    xhttp.open( "POST", "<?= BASEURL;?>accunt/login", true );
    xhttp.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
    xhttp.send( "email_user=" + email_user + "&password=" + password_user );
};

</script>