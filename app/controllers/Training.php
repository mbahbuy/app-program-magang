<?php

class Training extends Controller
{
    // halaman training index
    public function index()
    {
        $data['judul'] = 'Halaman Training';
        $data['active'] = 'training';
        $this->view('templates/header', $data);
        $this->view('Training/index');
        $this->view('templates/footer');
    }

    // halaman training booking
    public function book( $produk )
    {
        $data['judul'] = 'Halaman Training->booking';
        $data['active'] = 'training';
        $data['produk'] = $produk;
        $this->view('templates/header', $data);
        $this->view('Training/book', $data);
        $this->view('templates/footer');
    }
    
    // halaman training payment
    public function payment( $payment )
    {
        $data['judul'] = 'Halaman Training->booking';
        $data['active'] = 'training';
        $data['paymentToken'] = $payment;
        $this->view('templates/header', $data);
        $this->view('Training/payment', $data);
        $this->view('templates/footer');
    }

    public function getDataTraining()
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getAllDataTraining();
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

    public function getDataTrainingByName( $name )
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getSingleDataTraining( $name );
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

    public function getTokenPayment()
    {
        $sumtimer = $_POST['trainingStart'] + $_POST['trainingTime'] - 01;
        if( $sumtimer <= 12 ){
            $timeEnd = $sumtimer;
            $options = 0;
        } else {
            $timeEnd = $sumtimer - 12;
            $options = 1;
        }

        if( $_POST['trainingStart'] < date('n')  ) {
            $option = 1;
        } else {
            $option = 0;
        }

        $datastart = date( '01-' . $_POST['trainingStart'] . '-20' . 'y' );
        $dataend = '30-' .
            $timeEnd
            . "-20" . (date( 'y' ) + $options + $option) 
        ;

        // Token pendaftaran
        $dataToken = hash( 'sha256', $_POST['pelanggan'] . md5( date( 'Y-m-d' ) ) );
        $check = $this->model( 'Book_model' )->checkDataTraining( $_POST, $datastart, $dataend );

        if( $check > 0 )
        {
            while( $x = $check )
            {
                $get_token = $x['book_token'];
                $get_start = $x['book_start'];
                $get_end = $x['book_end'];
            }
            $alert['alert'] = 'warning';
            $alert['text'] = 'Anda sudah terdaftar pada pelatihan tersebut, dengan token :<br/>' . $get_token . '<br/>Dari tanggal ' . $get_start . ', sampai tanggal ' . $get_end . '.' ;
            echo json_encode($alert);
        } else
        {
            if( $this->model( 'Book_model' )->insertDataTraining( $_POST, $datastart, $dataend, $dataToken ) > 0 )
            {
                $alert['data'] = BASEURL . 'training/payment/' . $dataToken;
                $alert['alert'] = 'success';
                $alert['text'] = 'Pendaftaran pelatihan berhasil. Silakan melengkapi Administrasi dibawah ini.';
                echo json_encode($alert);
            } else
            {
                $alert['alert'] = 'danger';
                $alert['text'] = 'Pendaftaran pelatihan gagal.<br/>Silakan melengkapi form pendaftaran dengan benar.';
                echo json_encode($alert);
            }
        }
    }
}