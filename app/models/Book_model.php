<?php

class Book_model
{
    private $table = 'book';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // fungsi pengechekan data antrian yang sudah ada
    public function checkNomorAntrian( $data )
    {
        $this->db->query( "SELECT * FROM " . $this->table . " WHERE ( tanggal LIKE :tanggal ) AND ( book_payment=:nik  AND produk_id=:need ) ");

        $this->db->bind( 'tanggal', '%' . date( 'Y-m-d' ) . '%' );
        $this->db->bind( 'nik', $data['pasien_nik'] );
        $this->db->bind( 'need', $data['pasien_need'] );

        return $this->db->single();
    }

    // fungsi pengambilan terakhir tersimpan
    public function getAntrianTerakhir( $data )
    {
        $this->db->query( "SELECT book_timer FROM " . $this->table . " WHERE ( tanggal LIKE :tanggal AND produk_id=:need ) ORDER BY book_timer DESC" );

        $this->db->bind( 'tanggal', '%' . date( 'Y-m-d' ) . '%' );
        $this->db->bind( 'need', $data['pasien_need'] );

        return $this->db->single();
    }

    public function insertNomorAntrian( $data )
    {
        $this->db->query( "INSERT INTO " . $this->table . "( user_token, produk_id, book_timer, book_payment ) VALUES ( :token , :need, :antrian, :nik)" );

        $this->db->bind( 'token', $data['pasien_token'] );
        $this->db->bind( 'need', $data['pasien_need'] );
        $this->db->bind( 'antrian', $data['book_timer'] );
        $this->db->bind( 'nik', $data['pasien_nik'] );

        $this->db->execute();
    
        return $this->db->rowCount();
    }

    public function checkDataTraining( $data, $datastart, $dataend )
    {
        $this->db->query("SELECT *
        FROM " . $this->table . " 
        WHERE (
            user_token = :pelanggan
            AND
            produk_id = :training
            AND
            book_payment = 2
            )
            AND
            (
                ( 
                    book_start
                    BETWEEN
                    :datastart
                    AND
                    :dataend
                )
                OR
                (
                    book_end
                    BETWEEN
                    :datastart
                    AND
                    :dataend
                )
            )
        ");

        $this->db->bind( 'pelanggan', $data['pelanggan'] );
        $this->db->bind( 'training', $data['training'] );
        $this->db->bind( 'datastart', $datastart );
        $this->db->bind( 'dataend', $dataend );

        return $this->db->single();
    }

    public function insertDataTraining( $data, $datastart, $dataend, $dataToken )
    {
        $this->db->query( "INSERT INTO " . $this->table . " 
        ( user_token, produk_id, book_timer, book_start, book_end, book_token )
            VALUES (
                :pelanggan,
                :training,
                :trainingTime,
                :datastart,
                :dataend,
                :dataToken
            )
        " );

        $this->db->bind( 'pelanggan', $data['pelanggan'] );
        $this->db->bind( 'training', $data['training'] );
        $this->db->bind( 'trainingTime', $data['trainingTime'] );
        $this->db->bind( 'datastart', $datastart );
        $this->db->bind( 'dataend', $dataend );
        $this->db->bind( 'dataToken', $dataToken );

        $this->db->execute();

        return $this->db->rowCount();
    }

}