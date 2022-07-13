<?php

class Fasilitas extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Fasilitas';
        $data['active'] = 'fasilitas';
        $this->view('templates/header', $data);
        $this->view('Fasilitas/index');
        $this->view('templates/footer');
    }

    public function getDataFasilitas()
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getAllDataFasilitas();
        foreach( $array as $x )
        {
            $data['name'] = $x['produk_name'];
            $data['list'] = $x['produk_child'];
            $data['deskripsi'] = $x['produk_deskripsi'];
            $data['harga'] = 'Rp' . number_format( $x['produk_harga'], 0, ',', '.' );
            $data['img'] = $x['produk_img'];
            $data['id'] = $x['produk_id'];
            $datafix[] = $data;
        }
        echo json_encode( $datafix );
        header( 'Content-type: application/json' );
    }

    public function getDataFasilitasByName( $name )
    {
        $array = $this->model( 'Get_Data_Produk_model' )->getSingleDataFasilitas( $name );
        foreach( $array as $x )
        {
            $data['name'] = $x['produk_name'];
            $data['list'] = $x['produk_child'];
            $data['deskripsi'] = $x['produk_deskripsi'];
            $data['harga'] = 'Rp' . number_format( $x['produk_harga'], 0, ',', '.' );
            $data['img'] = $x['produk_img'];
            $data['id'] = $x['produk_id'];
            $datafix[] = $data;
        }
        echo json_encode( $datafix );
        header( 'Content-type: application/json' );
    }
}