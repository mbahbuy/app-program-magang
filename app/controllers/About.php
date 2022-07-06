<?php

class About extends Controller {
    public function index( $nama = 'Buyung', $pekerjaan = 'Pelajar', $umur = 23 ){
        $data['nama'] = $nama;
        $data['pekerjaan'] = $pekerjaan;
        $data['umur'] = $umur;
        $data['judul'] = 'About Me';
        $data['active'] = 'about';
        $this->view('templates/header', $data );
        $this->view('about/index', $data );
        $this->view('templates/footer');
    }
    public function page(){
        $data['judul'] = 'Pages';
        $data['active'] = 'about';
        $this->view('templates/header', $data );
        $this->view('about/page');
        $this->view('templates/footer');
    }
}