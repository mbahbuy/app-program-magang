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
        $this->db->query( 'SELECT * FROM ' . $this->table . ' WHERE (produk_kategori=:kategori and active=:active) AND (produk_name LIKE :name)');
        $this->db->bind( 'name', '%' . $name . '%' );
        $this->db->bind( 'kategori', 3 );
        $this->db->bind( 'active', 1 );
        
        return $this->db->single();
    }

    public function getSingleDataProdukById( $id )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table .
            ' WHERE produk_id = :id'
        );

        $this->db->bind( 'id', $id );
        return $this->db->single();
    }

    public function getSingleDataProdukByNameKategori( $name, $kategori )
    {
        $this->db->query( 'SELECT * FROM ' . $this->table .
            ' WHERE produk_name = :name' . 
            ' AND produk_kategori = :kategori'
        );

        $this->db->bind( 'name', $name );
        $this->db->bind( 'kategori', $kategori );
        return $this->db->single();
    }

    public function getDataNameProdukBykategori( $kategori )
    {
        switch( $kategori )
        {
            case "1" :
                return $this->getAllDataHealthcare();
                break;
            case "2" :
                return $this->getAllDataTraining();
                break;
            case "3" :
                return $this->getAllDataFasilitas();
                break;
            default :
                $this->db->query( 'SELECT * from ' . $this->table . 
                    ' WHERE active = :active'
                );
                $this->db->bind( 'active', 1 );
                
                return $this->db->resultSet();
        }
    }

    // fungsi masukkan data produk
    public function getRowsCountProduk( $data )
    {
        $this->db->query( 'Insert Into ' . $this->table . 
        '( produk_kategori, produk_changer, produk_name, produk_child, produk_deskripsi, produk_img, produk_harga )' . 
        ' VALUE( :kategori, :changer, :name, :child, :deskripsi, :img, :harga )'
        );

        $this->db->bind( 'kategori', $data['kategori'] );
        $this->db->bind( 'changer', $data['changer'] );
        $this->db->bind( 'name', $data['name'] );
        $this->db->bind( 'child', $data['child'] );
        $this->db->bind( 'deskripsi', $data['deskripsi'] );
        $this->db->bind( 'img', $data['gambar'] );
        $this->db->bind( 'harga', $data['harga'] );

        $this->db->execute();
        return $this->db->rowCount();
    }

    // fungsi nonactifkan produk
    public function changeProdukActivited( $id )
    {
        $this->db->query( 'UPDATE ' . $this->table . 
            ' SET active = :active ' . 
            ' WHERE produk_id = :id'
        );

        $this->db->bind( 'active', 0 );
        $this->db->bind( 'id', $id );

        $this->db->execute();
        return $this->db->rowCount();
    }
}