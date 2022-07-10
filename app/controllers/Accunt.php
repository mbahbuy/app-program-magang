<?php

// use PHPMailer\PHPMailer\PHPMailer;

class Accunt extends Controller
{
    public function index()
    {
        if( isset( $_COOKIE['username'] ) && isset( $_COOKIE['token'] ) && isset( $_COOKIE['role'] ) )
        {
            if( $this->model( 'User_model' )->checkAccunt( $_COOKIE['token'] ) > 0 )
            {
                $data['judul'] = 'Halaman Accunt';
                $data['active'] = 'accunt';
                $data['username'] = $_COOKIE['accunt']['username'];
                $data['token'] = $_COOKIE['accunt']['token'];
                $data['role'] = $_COOKIE['accunt']['role'];
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
            $alert['data'] = 1;
            $alert['alert'] = 'success';
            $alert['text'] = 'Accunt Anda berhasil ditambahkan. <br/> Silahkan cek email anda untuk verifikasi.';
            echo json_encode($alert);
            // $email = $_POST['email'];
            // $token = hash( 'sha256', $_POST['user_name'] . date('Y-m-d')  );
            // $kode_verif_email = BASEURL . 'accunt/verification/' . $token ;
            // $mail = new PHPMailer();
            // $mail->Host = 'namaindah.com';
            // $mail->SMTPAuth = true;
            // $mail->Username = 'mail@ptb.namaindah.com';
            // $mail->Password = '1000%masukMail';
            // $mail->SMTPSecure = 'ssl';
            // $mail->Port = 465;
            // $mail->setFrom('mail@ptb.namaindah.com', 'Admin JAPIS');
            // $mail->addAddress($email, 'Member JAPIS');
            // $mail->addReplyTo('mail@ptb.namaindah.com', 'Admin JAPIS');
            // $mail->isHTML(true);
            // $mail->Subject = 'Kode Verifikasi dari JAPIS';
            // // Email body content
            // $mailContent = "<p>Selamat bergabung dengan JAPIS.</p><p>Berikut ini adalah kode verifikasi dari JAPIS</p><p>" . $kode_verif_email . "</p><p>Gunakan kode verifikasi ini untuk konfirmasi email Anda.</p><p>Terima kasih.</p>"; // isi email
            // $mail->Body = $mailContent;
            // $sendemail = $mail->send();

            // if( $sendemail === true )
            // {
            //     $alert['data'] = 1;
            //     $alert['alert'] = 'success';
            //     $alert['text'] = 'Accunt Anda berhasil ditambahkan. <br/> Silahkan cek email anda untuk vaerfikasi.';
            //     echo json_encode($alert);
            // } else {
            //     $alert['alert'] = 'danger';
            //     $alert['text'] = 'Email verifikasi tidak terkirim';
            //     echo json_encode($alert);
            // }
        } else {
            $alert['alert'] = 'danger';
            $alert['text'] = 'data tidak tersimpan, ada yang salah';
            echo json_encode($alert);
        }
    }

    public function login()
    {
        $data = $this->model( 'User_model' )->loginAccunt( $_POST );
        echo json_encode( [ 'result' => $data] );
        // if( $data > 0 )
        // {
        //     $token = $this->model( 'User_model' )->checkAccunt( $data['token'] );
        //     // setcookie( 'username', $data['user_name'], time() + (86400 * 30 * 365) );
        //     // setcookie( 'token', $data['token'], time() + (86400 * 30 * 365) );
        //     // setcookie( 'role', $data['role'], time() + (86400 * 30 * 365) );
        //     // $alert['data'] = 'sudah login';
        //     echo json_encode( $token );
        // } else {
        //     $alert['alert'] = 'danger';
        //     $alert['text'] = 'email/sandi yang anda masukkan salah.';
        //     echo json_encode($alert);
        // }
    }

}