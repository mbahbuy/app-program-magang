<?php

class Get_Data_Produk_model
{
    private $table = 'produk';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllDataHealthcare()
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) ');
        $this->db->bind( 'kategori', 1 );
        $this->db->bind( 'active', 1 );

        return $this->db->resultSet();
    }
    
    public function getSingleDataHealthcare( $name )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) AND (produk_name LIKE :name OR produk_child LIKE :name)');
        $this->db->bind( 'name', '%' . $name . '%' );
        $this->db->bind( 'kategori', 1 );
        $this->db->bind( 'active', 1 );
        
        return $this->db->single();
    }
    
    public function getAllDataTraining()
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) ');
        $this->db->bind( 'kategori', 2 );
        $this->db->bind( 'active', 1 );
        
        return $this->db->resultSet();
    }
    
    public function getSingleDataTraining( $name )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) AND (produk_name LIKE :name OR produk_child LIKE :name)');
        $this->db->bind( 'name', '%' . $name . '%' );
        $this->db->bind( 'kategori', 2 );
        $this->db->bind( 'active', 1 );
        
        return $this->db->single();
    }
    
    public function getAllDataFasilitas()
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) ');
        $this->db->bind( 'kategori', 3 );
        $this->db->bind( 'active', 1 );
        
        return $this->db->resultSet();
    }
    
    public function getSingleDataFasilitas( $name )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) AND (produk_name LIKE :name OR produk_child LIKE :name)');
        $this->db->bind( 'name', '%' . $name . '%' );
        $this->db->bind( 'kategori', 3 );
        $this->db->bind( 'active', 1 );
        
        return $this->db->single();
    }

    public function getNameFasilitasProduk( $id )
    {
        $this->db->query( 'SELECT produk_name FROM ' . $this->table .
            ' WHERE produk_id = :id'
        );

        $this->db->bind( 'id', $id );
        return $this->db->single();
    }
}