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

    // public function getMahasiswaById($id) {// error
    //     $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE id=:id' );
    //     $this->db->bind( 'id', $id );
    //     return $this->db->single();
    // }

    public function tambahDataAccunt( $data )
    {
        $query = "INSERT INTO `user`( `user_name`, `email`, `no_hp`, `pass`, `alamat`, `token` )
                    VALUES
                    ( :user_name, :email, :no_hp, :pass, :alamat, :token )
        ";

        $this->db->query( $query );
        $this->db->bind( 'user_name', $data['user_name'] );
        $this->db->bind( 'email', $data['email'] );
        $this->db->bind( 'no_hp', $data['no_hp'] );
        $this->db->bind( 'pass', password_hash( $data['pass'] , PASSWORD_DEFAULT ));
        $this->db->bind( 'alamat', $data['alamat'] );
        $this->db->bind( 'token', hash( 'sha256', $data['user_name'] . date('Y-m-d')  ) );

        $this->db->execute();

        return $this->db->rowCount();
    }

    // public function hapusDataMahasiswa( $id ) {
    //     $query = "DELETE FROM mahasiswa WHERE id = :id";
    //     $this->db->query( $query );
    //     $this->db->bind( 'id', $id );

    //     $this->db->execute();

    //     return $this->db->rowCount();
    // }

    // public function ubahDataMahasiswa( $data ) {
    //     $query = "UPDATE mahasiswa SET
    //                 nama=:nama, nrp=:nrp, email=:email, jurusan=:jurusan
    //             WHERE id=:id
    //     ";

    //     $this->db->query( $query );
    //     $this->db->bind( 'nama', $data['nama'] );
    //     $this->db->bind( 'nrp', $data['nrp'] );
    //     $this->db->bind( 'email', $data['email'] );
    //     $this->db->bind( 'jurusan', $data['jurusan'] );
    //     $this->db->bind( 'id', $data['id'] );

    //     $this->db->execute();

    //     return $this->db->rowCount();

    // }

    // public function cariDataMahasiswa() {
    //     $keyword = $_POST['keyword'];
    //     $query = "SELECT * FROM mahasiswa WHERE nama LIKE :keyword";
    //     $this->db->query( $query );
    //     $this->db->bind( 'keyword', "%$keyword%" );
    //     return $this->db->resultSet();
    // }
}