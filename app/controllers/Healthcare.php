<?php

class Healthcare extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Healthcare';
        $data['active'] = 'healthcare';
        $this->view('templates/header', $data);
        $this->view('Healthcare/index');
        $this->view('templates/footer');
    }

    public function getDataHealthcare()
    {
        $array = $this->model( 'Healthcare_model' )->getAllDataHealthcare();
        foreach( $array as $x )
        {
            $data['name'] = $x['produk_name'];
            $data['list'] = $x['produk_child'];
            $data['deskripsi'] = $x['produk_deskripsi'];
            $data['harga'] = 'Rp ' . number_format( $x['produk_harga'], 0, ',', '.' );;
            $datafix[] = $data;
        }
        echo json_encode( $datafix );
        header( 'Content-type: application/json' );
    }

    public function getDataHealthcareByName( $name )
    {
        $data['data'] = $this->model( 'Healthcare_model' )->getSingleDataHealthcare( $name );
        
        echo json_encode( [ 'result' => $data ] );
        header( 'Content-type: application/json' );
    }
}