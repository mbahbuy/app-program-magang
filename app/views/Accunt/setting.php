<div class="container mb-4 mt-3">

    <button type="button" onclick="location.href = '<?= BASEURL;?>accunt/passchanger';" class="btn btn-primary">Ganti Password</button>

</div>

<div class="container mb-4">

    <button type="button" onclick="location.href = '<?= BASEURL;?>accunt/logOut';" class="btn btn-primary">Log out</button>

</div>

<?php
if( password_verify( 1, $data['role'] ) )
{
    echo '<div class="container"><button type="button" onclick="location.href =\'' . BASEURL . 'accunt/inputproduk\';" class="btn btn-primary">Tambahkan Produk</button></div>';
}
?>