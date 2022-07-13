<?php

if( !empty( $data['paymentToken'] ))
{
?>
<div class="container mt-5">

    <div class="input-group mb-3">
        <label class="input-group-text" for="filePayment">Upload</label>
        <input type="file" class="form-control" id="filePayment" name="filePayment">
        <input type="hidden" name="paymentToken" value="<?= $data['paymentToken'];?>">
    </div>

    <button type="button" class="btn btn-primary mt-3 col-sm-3" >Upload Bukti Pembayaran</button>

</div>
<?php
} else
{
?>
<div class="container mt-5">

    <p>Tindakan Ilegal.</p>
    <a  href='<?= BASEURL;?>training/' class='btn btn-primary'>Kembali</a>

</div>
<?php
}