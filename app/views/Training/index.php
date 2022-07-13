<div class="container">
    <div id="dataSet"></div>
</div>

<script>

function dataSet( dataJson = null )
    {
        var tempatTampilan = document.getElementById( 'dataSet' );
        if( dataJson == null )
        {
            var xhttp = new XMLHttpRequest();
            xhttp.onload = function()
            {
                var JSONdata = JSON.parse( this.responseText );
                for( let i = 0; i < JSONdata.length; i++ )
                {
                    // console.log( JSONdata[i] );
                    let dataArray = document.createElement( 'div' );
                    dataArray.className = "card mt-4";
                    dataArray.innerHTML = "<div class='card-header'>" + JSONdata[i].list + "</div><div class='card-body'><h5 class='card-title'>" + JSONdata[i].name + "</h5><p class='card-text'>" + JSONdata[i].deskripsi + "</p><a  href='<?= BASEURL;?>training/book/" + JSONdata[i].id + "' class='btn btn-primary'>" + JSONdata[i].harga + "</a></div>";
                    tempatTampilan.appendChild( dataArray );

                };
            };
            xhttp.open( "GET", "<?= BASEURL;?>training/getDataTraining", true );
            xhttp.send();
        } else {
            var xhttp = new XMLHttpRequest();
            xhttp.onload = function()
            {
                var JSONdata = JSON.parse( this.responseText );
                for( let i = 0; i < JSONdata.length; i++ )
                {
                    // console.log( JSONdata[i] );
                    let dataArray = document.createElement( 'div' );
                    dataArray.className = "card mt-4";
                    dataArray.innerHTML = "<div class='card-header'>" + JSONdata[i].list + "</div><div class='card-body'><h5 class='card-title'>" + JSONdata[i].name + "</h5><p class='card-text'>" + JSONdata[i].deskripsi + "</p><a href='<?= BASEURL;?>training/book/" + JSONdata[i].id + "' class='btn btn-primary'>" + JSONdata[i].harga + "</a></div>";
                    tempatTampilan.appendChild( dataArray );

                };
            };
            xhttp.open( "GET", "<?= BASEURL;?>training/getDataTrainingByName/" + dataJson, true );
            xhttp.send();
        }
    }
dataSet();
</script>