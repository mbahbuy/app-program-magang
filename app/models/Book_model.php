<?php

use LDAP\Result;

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

    // fungsi check training payment status
    public function statusPayment( $dataToken )
    {
        $this->db->query( 'SELECT book_payment, produk_id FROM ' . $this->table .
            ' WHERE book_token=:token '
        );

        $this->db->bind( 'token', "$dataToken" );

        return $this->db->single();
    }

    // fungsi training active
    public function payingPayment( $dataToken )
    {
        $this->db->query( 'UPDATE ' . $this->table .
            ' SET book_payment = :payment ' .
            ' WHERE book_token = :token'
        );

        $this->db->bind( 'payment', 2 );
        $this->db->bind( 'token', $dataToken );

        $this->db->execute();
        return $this->db->rowCount();
    }

    //fungsi fasilitas booked check
    public function getFasilitasBooked( $produk, $waktu, $jam )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table .
            ' WHERE produk_id = :produk ' .
            ' AND book_start LIKE :time ' .
            ' AND book_timer = :jam' .
            ' AND ( NOT book_payment = :satu OR NOT book_payment = :dua )' 
        );

        $this->db->bind( 'produk', $produk );
        $this->db->bind( 'time', "%$waktu%" );
        $this->db->bind( 'jam', "$jam" );
        $this->db->bind( 'satu', "1" );
        $this->db->bind( 'dua', "2" );

        return $this->db->single();
    }

    // fungsi check data doble
    public function checkFasilitasDoubleData( $produk, $waktu, $jam )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table .
            ' WHERE produk_id = :produk ' .
            ' AND book_start = :time ' .
            ' AND book_timer = :jam ' 
        );

        $this->db->bind( 'produk', $produk );
        $this->db->bind( 'time', $waktu );
        $this->db->bind( 'jam', $jam );

        $this->db->execute();
        return $this->db->rowCount();
    }

    // fungsi fasilitas insert data to DB
    public function insertBookFasilitas( $data, $token )
    {
        $this->db->query( 'INSERT INTO ' . $this->table . '( user_token, produk_id, book_timer, book_start, book_token, book_payment )' . 
            ' VALUES ( 
                :userToken,
                :produkID,
                :book_timer,
                :book_start,
                :book_token,
                :book_payment
            )'
        );

        $this->db->bind( 'userToken', $data['user_token'] );
        $this->db->bind( 'produkID', $data['produk_id'] );
        $this->db->bind( 'book_timer', $data['book_timer'] );
        $this->db->bind( 'book_start', $data['book_start'] );
        $this->db->bind( 'book_token', $token );
        $this->db->bind( 'book_payment', 1 );

        $this->db->execute();
        return $this->db->rowCount();

    }

}