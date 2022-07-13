<?php

class Healthcare extends Controller
{
    // tampilan halaman healthcare index
    public function index()
    {
        $data['judul'] = 'Halaman Healthcare';
        $data['active'] = 'healthcare';
        $this->view('templates/header', $data);
        $this->view('Healthcare/index');
        $this->view('templates/footer');
    }
    
    // tampilan halaman healthcare booking
    public function book( $produk )
    {
        $data['judul'] = 'Halaman Healthcare->booking';
        $data['active'] = 'healthcare';
        $data['produk'] = $produk;
        $this->view('templates/header', $data);
        $this->view('Healthcare/book', $data);
        $this->view('templates/footer');
    }

    public function getDataHealthcare()
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getAllDataHealthcare();
        foreach( $array as $x )
        {
            $data['name'] = $x['produk_name'];
            $data['list'] = $x['produk_child'];
            $data['deskripsi'] = $x['produk_deskripsi'];
            $data['harga'] = 'Rp' . number_format( $x['produk_harga'], 0, ',', '.' );
            $data['img'] = $x['produk_img'];
            $data['id'] = $x['produk_id'];
            $datafix[] = $data;
        }
        echo json_encode( $datafix );
        header( 'Content-type: application/json' );
    }

    public function getDataHealthcareByName( $name )
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getSingleDataHealthcare( $name );
        foreach( $array as $x )
        {
            $data['name'] = $x['produk_name'];
            $data['list'] = $x['produk_child'];
            $data['deskripsi'] = $x['produk_deskripsi'];
            $data['harga'] = 'Rp' . number_format( $x['produk_harga'], 0, ',', '.' );
            $data['img'] = $x['produk_img'];
            $data['id'] = $x['produk_id'];
            $datafix[] = $data;
        }
        echo json_encode( $datafix );
        header( 'Content-type: application/json' );
    }

    // fungsi controller mendaftar untuk mengambil antrian
    public function getNomorAntrian()
    {
        if( date( 'H:i:s' ) > "15:00:00")
        {
            $alert['alert'] = 'danger';
            $alert['text'] = 'Maaf, Data tidak diterima.<br/>Pendaftaran Pasien telah habis.<br/>Daftar lagi besok!!!';
            echo json_encode($alert);
        } elseif ( date( 'H:i:s' ) < "04:00:00" )
        {
            $alert['alert'] = 'danger';
            $alert['text'] = 'Maaf, Data tidak diterima.<br/>Pendaftaran Pasien belum dibuka!!!';
            echo json_encode($alert);
        } else 
        {
            if( $this->model( 'Book_model' )->checkNomorAntrian( $_POST ) > 0 )
            {
                $alert['alert'] = 'danger';
                $alert['text'] = 'Maaf, Data tidak diterima.<br/>Anda telah mendapatkan nomor antrian hari ini.<br/>Daftar lagi besok, bila mau mendaftar ke menu yang sama!!!';
                echo json_encode($alert);
            } else 
            {
                $getdata = $this->model( 'Book_model' )->getAntrianTerakhir( $_POST );
                $inputan['pasien_token'] = $_POST['pasien_token'];
                $inputan['pasien_nik'] = $_POST['pasien_nik'];
                $inputan['pasien_need'] = $_POST['pasien_need'];
                $inputan['book_timer'] = ( $getdata > 0 ) ? $getdata['book_timer'] + 1 : 1;
                $this->model( 'Book_model' )->insertNomorAntrian( $inputan );
                $nomorAntrian = ( $getdata > 0 ) ? $getdata['book_timer'] + 1 : 1 ;

                $alert['data'] = poli( $nomorAntrian ) . " - " . $nomorAntrian ;
                $alert['alert'] = 'success';
                $alert['text'] = 'Data diterima.';
                echo json_encode($alert);

            }
        }
    }
}

function poli( $tuyul )
{
    $pemikat = [
        "01" => "Rapit Test",
        "02" => "Vaksin",
        "03" => "Poli Umum",
        "04" => "Poli Gigi",
        "05" => "Poli Penyakit Kulit",
        "06" => "Poli Ibu dan Anak",
        "07" => "Poli Kangker",
        "08" => "Poli Psychology",
    ];
    return $pemikat[ $tuyul ];
}