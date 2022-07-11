<?php

class Healthcare_model
{
    private $table = 'produk';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllDataHealthcare()
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and produk_active=:active) ');
        $this->db->bind( 'kategori', 1 );
        $this->db->bind( 'active', 1 );

        return $this->db->resultSet();
    }

    public function getSingleDataHealthcare( $name )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and produk_active=:active) AND (produk_name LIKE :name OR produk_child LIKE :name)');
        $this->db->bind( 'name', '%' . $name . '%' );
        $this->db->bind( 'kategori', 1 );
        $this->db->bind( 'active', 1 );

        return $this->db->single();
    }

}