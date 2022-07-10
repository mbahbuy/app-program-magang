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

    // fungsi login
    public function loginAccunt( $data )
    {
        $sql = 'SELECT token FROM  ' . $this->table . ' WHERE (user_name=:email_user OR email=:email_user) AND pass=:password AND active=:active';

        $this->db->query( $sql );
        $this->db->bind( 'email_user', $data['email_user'] );
        $this->db->bind( 'password', password_hash( $data['password'], PASSWORD_DEFAULT) );
        $this->db->bind( 'active', 1 );

        return password_hash( $data['password'], PASSWORD_DEFAULT);
    }

    // fungsi check accunt already exist and get data
    public function checkAccunt( $param )
    {
        $this->db->query( 'SELECT user_name, token, role FROM ' . $this->table . ' WHERE (token=:token AND active=:active)' );
        $this->db->bind( "token", $param );
        $this->db->bind( "active", 1 );

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

}