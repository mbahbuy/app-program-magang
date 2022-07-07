<?php

class Accunt extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Accunt';
        $data['active'] = 'accunt';
        $this->view('templates/header', $data);
        $this->view('Accunt/index');
        $this->view('templates/footer');
    }

    public function activation()
    {
        $data['judul'] = 'Halaman Activation Accunt';
        $data['active'] = 'accunt';
        $this->view('templates/header', $data);
        $this->view('Accunt/activation');
        $this->view('templates/footer');
    }

    public function register()
    {
        $json = file_get_contents( 'php://input' );
        $data = json_decode( $json );
        var_dump($data);die;
        if( $this->model( 'User_model' )->tambahDataAccunt( $data ) > 0 )
        {
            $token = hash( 'sha256', $data['user_name'] . date('Y-m-d')  );
            $to = $data['email'];
            $subject = "Activation Email.";
            
            $message = "<b>Pengaktifan Akun di</b>";
            $message .= "<h1>WARKOP MBAH BUY.</h1>";
            $message .= "Selamat, anda berhasil bergabung. Untuk meng-aktifkan akun anda silahkan klik link dibawah ini.";
            $message .= " <a href='" . BASEURL . "accunt/verification/".$token."'>" . BASEURL . "accunt/verification/".$token."</a> ";
            
            $header = "From:mbahbagonku@gmail.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
            $sendemail = mail($to,$subject,$message,$header);

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
}