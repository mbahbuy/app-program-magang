<?php

class Fasilitas extends Controller
{
    // halaman fasilitas index
    public function index()
    {
        $data['judul'] = 'Halaman Fasilitas';
        $data['active'] = 'fasilitas';
        $this->view('templates/header', $data);
        $this->view('Fasilitas/index');
        $this->view('templates/footer');
    }

    // halaman fasilitas booking
    public function book( $produk = null )
    {
        if( $produk == null )
        {
            header( 'location: ' . BASEURL . 'fasilitas' );
        } else
        {
            $data['judul'] = 'Halaman Fasilitas';
            $data['active'] = 'fasilitas';
            $data['produk'] = $produk;
            $this->view('templates/header', $data);
            $this->view('Fasilitas/book', $data);
            $this->view('templates/footer');
        }
    }

    // halaman fasilitas payment
    public function payment( $token = null )
    {
        if( $token == null )
        {
            header( 'location: ' . BASEURL . 'fasilitas' );
        } else
        {
            $data['judul'] = 'Halaman Fasilitas';
            $data['active'] = 'fasilitas';
            $data['paymentToken'] = $token;
            $status = $this->model( 'Book_model' )->statusPayment( $token );
            $data['paymentStatus'] = $status['book_payment'];
            $data['produk_id'] = $status['produk_id'];
            $this->view( 'templates/header', $data );
            $this->view( 'fasilitas/payment', $data );
            $this->view( 'templates/footer' );
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
                        <a href="' . BASEURL . '/Fasilitas">Kembali Ke Fasilitas book</a>
                    </div>
    
                </div>
                ';
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["filePayment"]["tmp_name"], $target_file)) {
                    $this->model( 'Book_model' )->payingPayment( $_POST['paymentToken'] );
                    $nameProduk = $this->model( 'Get_Data_Produk_model' )->getNameFasilitasProduk($_POST['produk_id']);
                    $namaProduk = $nameProduk['produk_name'];
                    echo '
                        <div class="container">
            
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Booking ' . $namaProduk . ' selesai di bayar. Silahkan datang ke tempat kami untuk mengunakan fasilitas yang telah di booking pada waktu yang telah anda tentukan.
                                <a href="' . BASEURL . '/Fasilitas">Kembali Ke Fasilitas book</a>
                            </div>
            
                        </div>
                    ';
                } else {
                    echo '
                        <div class="container">
            
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Tempat uplaod file tidak ada.
                            <a href="' . BASEURL . '/Fasilitas">Kembali Ke Fasilitas book</a>
                            </div>
            
                        </div>
                    ';
                }
            }
        }

    // fungsi mengambil data fasilitas dari DB
    public function getDataFasilitas()
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getAllDataFasilitas();
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

    public function getDataFasilitasByName( $name )
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getSingleDataFasilitas( $name );
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

    // funsi check date booking
    public function getDataFasilitasBooked( $produk, $target )
    {
        $waktu = date( 'Y-m-d' );
        if( $target < $waktu )
        {
            $data['satu'] = 'disabled';
            $data['dua'] = 'disabled';
            $data['tiga'] = 'disabled';
            $dataJson[] = $data;
            echo json_encode( $dataJson );
        } else
        {
            $jamArray = [
                '01',
                '02',
                '03'
            ];
            foreach( $jamArray as $jam )
            {
                $check = ( $this->model( 'Book_model' )->getFasilitasBooked( $produk, $target, $jam ) == true ) ? 'disabled' : '';
                $x = [ $jam => $check ];
                $dataJson[] = $x;
            }
            echo json_encode( $dataJson );
        }
    }

    // fungsi ambil token booking
    public function getTokenPayment()
    {
        if( empty( $_POST ) )
        {
            header( 'location: ' . BASEURL . 'fasilitas' );
        } else
        {
            if( !empty( $_POST['user_token'] ))
            {
                if( !empty( $_POST['produk_id'] ))
                {                    
                    if( !empty( $_POST['book_start'] ))
                    {
                        
                        if( !empty( $_POST['book_timer'] ))
                        {
                            if( $this->model( 'Book_model' )->checkFasilitasDoubleData( $_POST['produk_id'], $_POST['book_start'], $_POST['book_timer'] ) > 0 )
                            {
                                $alert['alert'] = 'danger';
                                $alert['text'] = 'Jangan Aneh2' ;
                                echo json_encode( $alert );
                            } else
                            {
                                $token = hash( 'sha256', $_POST['user_token'] . $_POST['produk_id'] . $_POST['book_timer'] . md5( date( 'Y-m-d' ) ) );
                                if( $this->model( 'Book_model' )->insertBookFasilitas( $_POST, $token ) > 0 )
                                {
                                    $nameProduk = $this->model( 'Get_Data_Produk_model' )->getNameFasilitasProduk($_POST['produk_id']);
                                    $namaProduk = $nameProduk['produk_name'];
                                    $alert['data'] = BASEURL . 'fasilitas/payment/' . $token;
                                    $alert['alert'] = 'success';
                                    $alert['text'] = 'Penyewaan ' . $namaProduk . ', pada tanggal ' . $_POST['book_start'] . ' ( ' . waktu( $_POST['book_timer'] ) . ' ) telah di terima.' ;
                                    echo json_encode( $alert );
                                } else
                                {
                                    $alert['alert'] = 'danger';
                                    $alert['text'] = 'error Book_model' ;
                                    echo json_encode( $alert );
                                }
                            }
                        } else
                        {
                            $alert['alert'] = 'danger';
                            $alert['text'] = 'input waktu booking kosong' ;
                            echo json_encode( $alert );
                        }
                    } else
                    {
                        $alert['alert'] = 'danger';
                        $alert['text'] = 'input tanggal booking kosong' ;
                        echo json_encode( $alert );
                    }
                } else
                {
                    $alert['alert'] = 'danger';
                    $alert['text'] = 'produk id kosong' ;
                    echo json_encode( $alert );
                }
            } else
            {
                $alert['alert'] = 'danger';
                $alert['text'] = 'belum login' ;
                echo json_encode( $alert );
            }
        }
    }

}

function waktu( $ppp )// fungsi untuk notifikasi waktu yang dipilih
{
    $time = [
        "01" => "08:00 - 11:30",
        "02" => "12:30 - 16:00",
        "03" => "17:00 - 21:30"
    ];
    return $time[ $ppp ];
}
