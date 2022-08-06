<div class="container mt-5">

    <div class="form-floating mb-3">
        <select class="form-select" id="produkKategori" name="produkKategori">
            <option value="1" selected>Healthcare</option>
            <option value="2">Training</option>
            <option value="3">Fasilitas</option>
        </select>
        <label for="produkKategori">Pilih Kategori</label>
    </div>

    <button type="button" class="btn btn-primary mb-3 col-sm-3" id="update">Update Data Produk</button>
    <button type="button" class="btn btn-primary mb-3 col-sm-3" id="add">Tambah Data Produk</button>
    
    <div id="inputForm"></div>

</div>

<script>
const kategori = document.getElementById( 'produkKategori' );
const updateBtn = document.getElementById( 'update' );
const addBtn = document.getElementById( 'add' );
const inputForm = document.getElementById( 'inputForm' );

function notif( data = null, alert = 'danger', texts = '' )
{
    if( data == null )
    {
        inputForm.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } else{
        inputForm.innerHTML = '<div class="alert alert-'+ alert +' alert-dismissible fade show" role="alert">'+ texts + '</div>';
    }
};

function addOption()
{
    inputForm.innerHTML = '<div class="form-floating mb-3"><input type="text" class="form-control" id="produkName" placeholder="name" required><label for="produkName">Nama Produk</label></div><div class="form-floating mb-3"><input type="text" class="form-control" id="produkChild" placeholder="name" required><label for="produkChild">Jenis Produk</label></div><div class="form-floating mb-3"><textarea class="form-control" placeholder="Leave a comment here" id="produkDeskrip" style="height: 100px" required></textarea><label for="produkDeskrip">Deskripsi Produk</label></div><div class="input-group mb-3"><label class="input-group-text"  for="produkImg">Gambar/Foto Produk</label><input type="file" class="form-control produkImg" name="produkImg" id="produkImg"></div><div class="form-floating mb-3"><input type="number" class="form-control produkHarga" id="produkHarga" placeholder="harga" required><label for="produkHarga">Harga Produk</label></div><button type="button" class="btn btn-primary mb-3 col-sm-3 addProduk">Tambah</button>';
}

function updateOption( dataArray )
{
    var dataselect = '';
    for( let i = 0; i < dataArray.length; i++ )
    {
        dataselect += '<option value="' + dataArray[i].produk_id + '">' + dataArray[i].produk_name + '</option>';
    }
    inputForm.innerHTML = '<div class="form-floating mb-3"><select class="form-select produkName" id="produkName">' + dataselect + '</select><label for="produkName">Pilih Produk</label></div><div class="form-floating mb-3"><input type="text" class="form-control" id="produkChild" placeholder="name" value="' + dataArray[0].produk_child + '" required><label for="produkChild">Jenis Produk</label></div><div class="form-floating mb-3"><textarea class="form-control produkDeskrip" placeholder="Leave a comment here" id="produkDeskrip" style="height: 100px" required>' + dataArray[0].produk_deskripsi + '</textarea><label for="produkDeskrip">Deskripsi Produk</label></div><img class="img-thumbnail" src="' + dataArray[0].produk_img + '"><div class="input-group mb-3"><label class="input-group-text" for="produkImg">Gambar/Foto Produk</label><input type="file" class="form-control produkImg" name="produkImg" id="produkImg"></div><div class="form-floating mb-3"><input type="number" class="form-control produkHarga" id="produkHarga" placeholder="harga" value="' + dataArray[0].produk_harga + '" required><label for="produkHarga">Harga Produk</label></div><button type="button" class="btn btn-primary mb-3 col-sm-3 updateProduk">Update</button>';
}

kategori.onchange = function()
{
    inputForm.innerHTML = '';
}

updateBtn.onclick = function()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if( this.readyState == 4 && this.status == 200 )
        {
            var datajson = JSON.parse( this.responseText );
            updateOption( datajson );
        }
    };
    xhttp.open( 'POST', '<?= BASEURL;?>accunt/getAllDataProdukByKategori', true );
    xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
    xhttp.send(
        'target=' + kategori.value
    );
}

addBtn.onclick = function()
{
    addOption();
}

inputForm.addEventListener( 'click', function(btn)
{
    const updateProduk = btn.target.classList.contains( 'updateProduk' );
    const addProduk = btn.target.classList.contains( 'addProduk' );

    if( updateProduk )
    {
        var produkName = this.firstElementChild.firstElementChild.value;
        var produkChild = this.firstElementChild.nextElementSibling.firstElementChild.value;
        var produkDeskrip = this.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild.value;
        var produkImg = this.lastElementChild.previousElementSibling.previousElementSibling.lastElementChild;
        var produkHarga = this.lastElementChild.previousElementSibling.firstElementChild.value;

        var dataForm = new FormData();
        dataForm.append( "files", produkImg.files[0] );
        dataForm.append( "produkKategori", kategori.value );
        dataForm.append( "produkName", produkName );
        dataForm.append( "produkChild", produkChild );
        dataForm.append( "produkDeskrip", produkDeskrip );
        dataForm.append( "produkHarga", produkHarga );

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
            if( this.readyState == 4 && this.status == 200 )
            {
                var datajson = JSON.parse( this.responseText );
                notif( datajson.data, datajson.alert, datajson.text );
            }
        };
        xhttp.open( 'POST', '<?= BASEURL;?>accunt/updateDataProduk' );
        xhttp.send( dataForm );

    } else if( addProduk )
    {
        var produkName = this.firstElementChild.firstElementChild.value;
        var produkChild = this.firstElementChild.nextElementSibling.firstElementChild.value;
        var produkDeskrip = this.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild.value;
        var produkImg = this.lastElementChild.previousElementSibling.previousElementSibling.lastElementChild;
        var produkHarga = this.lastElementChild.previousElementSibling.firstElementChild.value;

        var dataForm = new FormData();
        dataForm.append( "files", produkImg.files[0] );
        dataForm.append( "produkKategori", kategori.value );
        dataForm.append( "produkName", produkName );
        dataForm.append( "produkChild", produkChild );
        dataForm.append( "produkDeskrip", produkDeskrip );
        dataForm.append( "produkHarga", produkHarga );

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
            if( this.readyState == 4 && this.status == 200 )
            {
                var datajson = JSON.parse( this.responseText );
                notif( datajson.data, datajson.alert, datajson.text );
            }
        };
        xhttp.open( 'POST', '<?= BASEURL;?>accunt/addDataProduk' );
        xhttp.send( dataForm );

    }
});

inputForm.addEventListener( 'change' , function(slt)
{
    if( slt.target.classList.contains( 'produkName' ) )
    {
        var produkId = this.firstElementChild.firstElementChild.value;
        var produkChild = this.firstElementChild.nextElementSibling.firstElementChild;
        var produkDeskrip = this.firstElementChild.nextElementSibling.nextElementSibling.firstElementChild;
        var produkImg = this.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling;
        var produkHarga = this.lastElementChild.previousElementSibling.firstElementChild;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
            if( this.readyState == 4 && this.status == 200 )
            {
                var datajson = JSON.parse( this.responseText );
                produkChild.value = datajson.child;
                produkDeskrip.value = datajson.deskripsi;
                produkHarga.value = datajson.harga;
                produkImg.src = datajson.img;
            }
        };
        xhttp.open( 'POST', '<?= BASEURL;?>accunt/getDataProdukById', true );
        xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
        xhttp.send(
            'target=' + produkId
        );

    }
});

</script>