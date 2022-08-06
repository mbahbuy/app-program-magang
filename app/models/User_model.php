<?php

class User_model {
    private $table = 'user';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllAccunt()
    {
        $this->db->query( 'SELECT user_name FROM ' . $this->table );
        return $this->db->resultSet();
    }

    // fungsi ambil password value
    public function getPasswordAccunt( $data )
    {
        $sql = 'SELECT pass FROM  ' . $this->table . ' WHERE (user_name=:email_user OR email=:email_user) AND active=:active';

        $this->db->query( $sql );
        $this->db->bind( 'email_user', $data['email_user'] );
        $this->db->bind( 'active', 1 );

        return $this->db->single();
    }

    // fungsi check accunt is exist
    public function checkAccunt( $param )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE token=:token AND active=:active' );
        $this->db->bind( 'token', $param );
        $this->db->bind( 'active', 1 );

        return $this->db->single();
    }

    // fungsi ambil data buat cookie
    public function getDataCookies( $param )
    {
        $this->db->query( 'SELECT user_name, token, role FROM ' . $this->table . ' WHERE pass=:pass' );
        $this->db->bind( "pass", $param );

        return $this->db->single();
    }

    // fungsi tambah/daftar accunt
    public function tambahDataAccunt( $data )
    {
        $query = "INSERT INTO `user`( `user_name`, `email`, `no_hp`, `pass`, `alamat`, `token` )
                    VALUES
                    ( :user, :email, :no_hp, :pass, :alamat, :token )
        ";

        $this->db->query( $query );
        $this->db->bind( 'user', $data['user_name'] );
        $this->db->bind( 'email', $data['email'] );
        $this->db->bind( 'no_hp', $data['no_hp'] );
        $this->db->bind( 'pass', password_hash( $data['pass'], PASSWORD_DEFAULT));
        $this->db->bind( 'alamat', $data['alamat'] );
        $this->db->bind( 'token', hash( 'sha256', $data['user_name'] . date('Y-m-d')  ) );

        $this->db->execute();

        return $this->db->rowCount();
    }

    // fungsi verifikasi email dan aktifasi accunt
    public function activationAccunt( $paramtoken )
    {
        if( empty( $paramtoken ) )
        {
            return null;
        } else {
            $this->db->query( "UPDATE " . $this->table . " SET active = :active WHERE token = :token" );
            $this->db->bind( 'active', 1 );
            $this->db->bind( 'token', $paramtoken );
            $this->db->execute();
            return $this->db->rowCount();
        }
    }

    // fungsi setcookie
    // public function setCookie( $cookiesName, $cookiesValue ){
    //     $arr_cookie_options = array (
    //         'Expires' => time() + 60*60*24*30,
    //         'Path' => '/',
    //         // 'Domain' => BASEURL, // leading dot for compatibility or use subdomain
    //         'Secure' => 'true',     // or false
    //         'HttpOnly' => 'true',    // or false
    //         'SameSite' => 'None' // None || Lax  || Strict
    //         );
    //     return setcookie( $cookiesName, $cookiesValue, $arr_cookie_options);
    // }
    
    // fungsi setsession
    public function setSession( $data )
    {
        $_SESSION['username'] = $data['username'];
        $_SESSION['token'] = $data['token'];
        $_SESSION['role'] = $data['role'];
    }

    public function getDataRole( $token )
    {
        $this->db->query( 'SELECT  role FROM ' . $this->table . ' WHERE token = :token' );
        $this->db->bind( "token", $token );

        return $this->db->single();
    }

    // fungsi read session
    public function getSession()
    {
        $data['username'] = $_SESSION['username'];
        $data['token'] = $_SESSION['token'];
        $datarole = $this->getDataCookies( $data['token'] );
        $data['role'] = base64_encode(hash_hmac('sha256', $datarole['role'], $_SESSION['role'], true));

        $result[] = $data;
        return $result;
    }

    // fungsi delete sission
    public function removeSession()
    {
        session_destroy();
    }

    // fungsi ganti password
    public function updatePass( $data )
    {
        $this->db->query( 'UPDATE ' . $this->table . 
            ' SET pass = :pass' . 
            ' WHERE token = :token'
        );

        $this->db->bind( 'pass', password_hash( $data['pass'], PASSWORD_DEFAULT));
        $this->db->bind( 'token', $data['token'] );

        $this->db->execute();
        return $this->db->rowCount();
    }
}