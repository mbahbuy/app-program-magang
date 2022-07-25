<?php

if(  $data['paymentStatus']  == '1' )
{
?>
<div class="container mt-5">

    <form action="<?= BASEURL;?>fasilitas/payPayment" method="post" enctype="multipart/form-data">
        
        <div class="input-group mb-3">
                <label class="input-group-text" for="filePayment">Upload</label>
                <input type="file" class="form-control" id="filePayment" name="filePayment">
                <input type="hidden" id="paymentToken" name="paymentToken" value="<?= $data['paymentToken'];?>">
                <input type="hidden" name="produk_id" value="<?= $data['produk_id'];?>">
        </div>
        
        <button type="submit" class="btn btn-primary mt-3 col-sm-3">Upload Bukti Pembayaran</button>

    </form>

</div>
<?php
} elseif( $data['paymentStatus'] == '2' )
{
?>
<div class="container mt-5">

    <p>Token Payment sudah bisa dipakai</p>
    <a  href='<?= BASEURL;?>fasilitas/' class='btn btn-primary'>Kembali</a>

</div>
<?php
} else
{
?>
<div class="container mt-5">

    <p>Tindakan ilegal</p>
    <a  href='<?= BASEURL;?>fasilitas/' class='btn btn-primary'>Kembali</a>

</div>
<?php
}