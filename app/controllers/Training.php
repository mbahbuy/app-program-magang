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
    public function book( $produk = null )
    {
        if( $produk == null )
        {
            header( 'location: ' . BASEURL . 'training' );
        } else
        {
            $data['judul'] = 'Halaman Training->booking';
            $data['active'] = 'training';
            $data['produk'] = $produk;
            $this->view('templates/header', $data);
            $this->view('Training/book', $data);
            $this->view('templates/footer');
        }
    }
    
    // halaman training payment
    public function payment( $payment = null )
    {
        if( $payment == null )
        {
            header( 'location: ' . BASEURL . 'training' );
        } else 
        {
            $data['judul'] = 'Halaman Training->booking';
            $data['active'] = 'training';
            $data['paymentToken'] = $payment;
            $status = $this->model( 'Book_model' )->statusPayment( $payment );
            $data['paymentStatus'] = $status['book_payment'];
            $data['produk_id'] = $status['produk_id'];
            $this->view('templates/header', $data);
            $this->view('Training/payment', $data);
            $this->view('templates/footer');
        }
    }

    // function upload bukti payment
    public function payPayment()
    {
        $target_dir = 'public/img/';
        $target_file = $target_dir . basename($_FILES["filePayment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image

        $check = getimagesize($_FILES["filePayment"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
        $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo '
            <div class="container">

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Foto bukti corupt/bukan gambar.
                    <a href="' . BASEURL . '/training">Kembali Ke Training book</a>
                </div>

            </div>
            ';
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["filePayment"]["tmp_name"], $target_file)) {
                $this->model( 'Book_model' )->payingPayment( $_POST['paymentToken'] );
                echo '
                    <div class="container">
        
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Pendaftaran pelatihan berhasil. Silakan belajar dengan giat.
                            <a href="' . BASEURL . '/training">Kembali Ke Training book</a>
                        </div>
        
                    </div>
                ';
            } else {
                echo '
                    <div class="container">
        
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Tempat uplaod file tidak ada.
                        <a href="' . BASEURL . '/training">Kembali Ke Training book</a>
                        </div>
        
                    </div>
                ';
            }
        }
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
        $dataToken = hash( 'sha256', $_POST['pelanggan'] . $_POST['training'] . md5( date( 'Y-m-d' ) ) );
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