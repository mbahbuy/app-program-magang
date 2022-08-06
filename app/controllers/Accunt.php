<?php

use PHPMailer\PHPMailer\PHPMailer;

class Accunt extends Controller
{
    public function index()
    {
        // echo json_encode($_SESSION);die;
        if( !empty( $_SESSION['username'] ) && !empty( $_SESSION['token'] ) && !empty( $_SESSION['role'] ) )
        {
            if( $this->model( 'User_model' )->checkAccunt( $_SESSION['token'] ) > 0 )
            {
                $data['judul'] = 'Halaman Accunt';
                $data['active'] = 'accunt';
                $data['username'] = $_SESSION['username'];
                $data['token'] = $_SESSION['token'];
                $data['role'] = $_SESSION['role'];
                $this->view('templates/header', $data);
                $this->view('Accunt/setting', $data);
                $this->view('templates/footer');
            } else {
                echo 'Accunt ILEGAL.';
            }
        } else {
            $data['judul'] = 'Halaman Accunt';
            $data['active'] = 'accunt';
            $this->view('templates/header', $data);
            $this->view('Accunt/index');
            $this->view('templates/footer');
        }
    }

    public function verification( $token = null )
    {
        if( $token == null )
        {
            $data['judul'] = 'Halaman Accunt';
            $data['active'] = 'accunt';
            $this->view('templates/header', $data);
            echo "
            <div class='container'><div class='alert alert-danger alert-dismissible fade show' role='alert'>-----------------</div></div>
            ";
            $this->view( 'templates/footer' );
        } else {
            
            if( $this->model( 'User_model' )->activationAccunt( $token ) > 0 )
            {
                $data['judul'] = 'Halaman Accunt';
                $data['active'] = 'accunt';
                $this->view('templates/header', $data);
                echo "<div class='container'><div class='alert alert-success alert-dismissible fade show' role='alert'>Accunt Anda berhasil verifikasi. Silahkan <a href='" . BASEURL . "accunt'>LOG IN</a></div></div>
                ";
                $this->view( 'templates/footer' );
            } else {
                $data['judul'] = 'Halaman Accunt';
                $data['active'] = 'accunt';
                $this->view('templates/header', $data);
                echo "
                <div class='container'><div class='alert alert-danger alert-dismissible fade show' role='alert'>Link Verifikasi sudah tidak berlaku.</div></div>
                ";
                $this->view( 'templates/footer' );
            }
        }
    }

    public function register()
    {
        if( $this->model( 'User_model' )->tambahDataAccunt( $_POST ) > 0 )
        {
            // $alert['data'] = 1;
            // $alert['alert'] = 'success';
            // $alert['text'] = 'Accunt Anda berhasil ditambahkan. <br/> Silahkan cek email anda untuk verifikasi.';
            // echo json_encode($alert);
            $email = $_POST['email'];
            $token = hash( 'sha256', $_POST['user_name'] . date('Y-m-d')  );
            $kode_verif_email = BASEURL . 'accunt/verification/' . $token ;
            $mail = new PHPMailer();
            $mail->Host = 'namaindah.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mail@ptb.namaindah.com';
            $mail->Password = '1000%masukMail';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('mail@ptb.namaindah.com', 'Admin JAPIS');
            $mail->addAddress($email, 'Member JAPIS');
            $mail->addReplyTo('mail@ptb.namaindah.com', 'Admin JAPIS');
            $mail->isHTML(true);
            $mail->Subject = 'Kode Verifikasi dari JAPIS';
            // Email body content
            $mailContent = "<p>Selamat bergabung dengan JAPIS.</p><p>Berikut ini adalah kode verifikasi dari JAPIS</p><p>" . $kode_verif_email . "</p><p>Gunakan kode verifikasi ini untuk konfirmasi email Anda.</p><p>Terima kasih.</p>"; // isi email
            $mail->Body = $mailContent;
            $sendemail = $mail->send();

            if( $sendemail === true )
            {
                $alert['data'] = 1;
                $alert['alert'] = 'success';
                $alert['text'] = 'Accunt Anda berhasil ditambahkan. <br/> Silahkan cek email anda untuk vaerfikasi.';
                echo json_encode($alert);
            } else {
                $alert['alert'] = 'danger';
                $alert['text'] = 'Email verifikasi tidak terkirim';
                echo json_encode($alert);
            }
        } else {
            $alert['alert'] = 'danger';
            $alert['text'] = 'data tidak tersimpan, ada yang salah';
            echo json_encode($alert);
        }
    }

    public function login()
    {
        $datapass = $this->model( 'User_model' )->getPasswordAccunt( $_POST );
        if( password_verify( $_POST['password'], $datapass['pass'] ) )
        {
            $session = $this->model( 'User_model' )->getDataCookies( $datapass['pass'] );
            $data['username'] = $session['user_name'];
            $data['token'] = $session['token'];
            $data['role'] = password_hash( $session['role'], PASSWORD_DEFAULT );
            $this->model( 'User_model' )->setSession( $data );
            $alert['data'] = 'sudah login';
            $alert['alert'] = 'success';
            $alert['text'] = 'anda sudah login';
            echo json_encode( $alert );
        } else {
            $alert['alert'] = 'danger';
            $alert['text'] = 'email/sandi yang anda masukkan salah.';
            echo json_encode($alert);
        }
    }

    // fungsi halaman ganti password
    public function passchanger()
    {
        if( isset( $_SESSION['username'] ) )
        {
            $data['judul'] = 'Halaman Accunt';
            $data['active'] = 'accunt';
            $data['username'] = $_SESSION['username'];
            $data['token'] = $_SESSION['token'];
            $data['role'] = $_SESSION['role'];
            $this->view('templates/header', $data);
            $this->view('Accunt/gantipass', $data);
            $this->view('templates/footer');
        } else
        {
            header( 'location: ' . BASEURL . 'accunt' );
        }
    }

    public function checkPass()
    {
        if( isset( $_SESSION['username'] ) )
        {
            $get_pass = $this->model( 'User_model' )->getPasswordAccunt( $_POST );
            if( password_verify( $_POST['key'], $get_pass['pass'] ) )
            {
                $alert['data'] = 1;
                $alert['alert'] = '';
                $alert['text'] = '';
                echo json_encode( $alert );
            } else
            {
                $alert['alert'] = 'danger';
                $alert['text'] = 'Password tidak sesuai';
                echo json_encode($alert);
            }
        } else
        {
            
        }
    }

    public function passwordCHanger()
    {
        if( isset( $_SESSION['username'] ) )
        {
            $input = $this->model( 'User_model' )->updatePass( $_POST );
            if( $input > 0 )
            {
                $alert['data'] = 1;
                echo json_encode( $alert );
            }
        }
    }

    public function logOut()
    {
        $this->model( 'User_model' )->removeSession();
        header( 'location: ' . BASEURL . 'accunt' );
    }

    // fungsi halaman admin input produk
    public function inputProduk()
    {
        if( isset( $_SESSION['username'] ) )
        {
            if( password_verify( 1, $_SESSION['role'] ) )
            {
                $data['judul'] = 'Halaman Accunt';
                $data['active'] = 'accunt';
                $this->view('templates/header', $data);
                $this->view('Accunt/InputProduk');
                $this->view('templates/footer');
            } else {
                header( 'location: ' . BASEURL . 'accunt' );
            }
        } else
        {
            header( 'location: ' . BASEURL . 'accunt' );
        }
    }

    public function getAllDataProdukByKategori()
    {
        if( isset( $_POST ) )
        {
            $hasil = $this->model( 'Get_Data_Produk_model' )->getDataNameProdukBykategori( $_POST['target'] );
            echo json_encode( $hasil );
        } else {
            echo json_encode( ['result' => 'Tindakan Ilegal Br00!!!'] );
        }
    }

    public function getDataProdukById()
    {
        if( isset( $_POST ) )
        {
            $hasil = $this->model( 'Get_Data_Produk_model' )->getSingleDataProdukById( $_POST['target'] );
            $data['child'] = $hasil['produk_child'];
            $data['deskripsi'] = $hasil['produk_deskripsi'];
            $data['img'] = 'public/img/' . $hasil['produk_img'];
            $data['harga'] = $hasil['produk_harga'];
            echo json_encode( $data );
        } else {
            echo json_encode( ['result' => 'Tindakan Ilegal Br00!!!'] );
        }
    }

    public function addDataProduk()
    {
        if( empty( $_POST ) ){ echo 'Tindakan Ilegal'; die; };
        $count = 0;
        if( isset( $_FILES['files'] ) )
        {
            // Upload directory
            $upload_location = "public/img/";
    
    
            // File name
            $filename = $_FILES['files']['name'];
    
            // File path
            $path = $upload_location.$filename;
    
            // file extension
            $file_extension = pathinfo($path, PATHINFO_EXTENSION);
            $file_extension = strtolower($file_extension);
    
            // Valid file extensions
            $valid_ext = array("pdf","doc","docx","jpg","png","jpeg");
    
            // Check extension
            if(in_array($file_extension,$valid_ext)){
    
                // Upload file
                if(move_uploaded_file($_FILES['files']['tmp_name'],$path)){
                    $count += 1;
                } 
            }
        }


        $data['kategori'] = $_POST['produkKategori'];
        $data['changer'] = 1;
        $data['name'] = $_POST['produkName'];
        $data['child'] = $_POST['produkChild'];
        $data['deskripsi'] = $_POST['produkDeskrip'];
        $data['gambar'] = ( $count == 0 ) ? '' : $filename;
        $data['harga'] = $_POST['produkHarga'];

        if( $this->model( 'Get_Data_Produk_model' )->getSingleDataFasilitas( $data['name'], $data['kategori'] ) > 0 )
        {
            $alert['alert'] = 'danger';
            $alert['text'] = 'Data produk yang akan anda masukkan telah ada di dalam database. Silahkan update bila mau merubah isinya.';
            echo json_encode( $alert );
        } else {
            if( $this->model( 'Get_Data_Produk_model' )->getRowsCountProduk( $data ) > 0 )
            {
                $alert['data'] = 'sip';
                $alert['alert'] = 'success';
                $alert['text'] = 'Data produk "' . $data['name'] . '" telah berasih disimpan.' ;
                echo json_encode( $alert );
            } else {
                $alert['alert'] = 'danger';
                $alert['text'] = 'server error';
                echo json_encode( $alert );
            }
        }

    }

    public function updateDataProduk()
    {
        if( empty( $_POST ) ){ echo 'Tindakan Ilegal'; die; };
        $count = 0;
        if( isset( $_FILES['files'] ) )
        {
            // Upload directory
            $upload_location = "public/img/";
    
    
            // File name
            $filename = $_FILES['files']['name'];
    
            // File path
            $path = $upload_location.$filename;
    
            // file extension
            $file_extension = pathinfo($path, PATHINFO_EXTENSION);
            $file_extension = strtolower($file_extension);
    
            // Valid file extensions
            $valid_ext = array("pdf","doc","docx","jpg","png","jpeg");
    
            // Check extension
            if(in_array($file_extension,$valid_ext)){
    
                // Upload file
                if(move_uploaded_file($_FILES['files']['tmp_name'],$path)){
                    $count += 1;
                } 
            }
        }

        $data['id'] = $_POST['produkName'];
        $get_data = $this->model( 'Get_Data_Produk_model' )->getSingleDataProdukById( $data['id'] );

        $data['kategori'] = $_POST['produkKategori'];
        $data['changer'] = $get_data['produk_changer'] + 1;
        $data['name'] = $get_data['produk_name'];
        $data['child'] = $_POST['produkChild'];
        $data['deskripsi'] = $_POST['produkDeskrip'];
        $data['gambar'] = ( $count == 0 ) ? '' : $filename;
        $data['harga'] = $_POST['produkHarga'];

        if( $this->model( 'Get_Data_Produk_model' )->changeProdukActivited( $data['id'] ) > 0 )
        {
            $this->model( 'Get_Data_Produk_model' )->getRowsCountProduk( $data );
            $alert['data'] = 'sip';
            $alert['alert'] = 'success';
            $alert['text'] = 'Data produk "' . $data['name'] . '" telah berasih diupdate.' ;
            echo json_encode( $alert );
        } else {
            $alert['alert'] = 'danger';
            $alert['text'] = 'server error';
            echo json_encode( $alert );
        }

    }
}